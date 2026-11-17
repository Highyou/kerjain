"""
============================================================
KERJAIN — Sistem Rekomendasi TF-IDF + Cosine Similarity
File  : recommender/recommend.py
Bahasa: Python 3 (dipanggil oleh CI4 via shell_exec)
============================================================

CARA KERJA:
1. CI4 memanggil script ini via shell_exec() dengan service_id
2. Script mengambil data jasa dari MySQL
3. Hitung TF-IDF dari title + description + tags
4. Hitung cosine similarity antara jasa target dengan semua jasa
5. Return JSON top-N jasa paling mirip
"""

import sys
import json
import mysql.connector
import re
from math import log, sqrt

# ──────────────────────────────────────────────────────────
# KONFIGURASI DATABASE
# ──────────────────────────────────────────────────────────
DB_CONFIG = {
    "host":     "localhost",
    "user":     "root",
    "password": "",            # sesuaikan dengan Laragon
    "database": "kerjain_db"
}

# ──────────────────────────────────────────────────────────
# 1. AMBIL DATA DARI DATABASE
# ──────────────────────────────────────────────────────────
def fetch_services():
    conn = mysql.connector.connect(**DB_CONFIG)
    cursor = conn.cursor(dictionary=True)
    cursor.execute("""
        SELECT id, title, description, tags, category_id,
               thumbnail, price, slug, rating_avg, total_orders
        FROM services
        WHERE status = 'active'
    """)
    rows = cursor.fetchall()
    conn.close()
    return rows

# ──────────────────────────────────────────────────────────
# 2. PREPROCESSING TEKS
# ──────────────────────────────────────────────────────────
STOP_WORDS = {
    'dan','di','ke','dari','yang','untuk','adalah','ini','itu',
    'dengan','tidak','atau','juga','sudah','saya','anda','kamu',
    'akan','bisa','ada','jasa','harga','produk','order','pesanan'
}

def tokenize(text: str) -> list[str]:
    """Bersihkan dan tokenisasi teks"""
    text = str(text).lower()
    text = re.sub(r'[^a-zA-Z0-9\s]', ' ', text)
    tokens = text.split()
    tokens = [t for t in tokens if len(t) > 2 and t not in STOP_WORDS]
    return tokens

def build_corpus(services: list) -> list[str]:
    """Gabungkan title + description + tags menjadi 1 dokumen per jasa"""
    corpus = []
    for svc in services:
        doc = f"{svc['title']} {svc['title']} "        # title diberi bobot 2x
        doc += f"{svc['description']} "
        doc += f"{svc['tags'] or ''} {svc['tags'] or ''}"  # tags 2x
        corpus.append(doc)
    return corpus

# ──────────────────────────────────────────────────────────
# 3. TF-IDF DARI SCRATCH
# ──────────────────────────────────────────────────────────
def compute_tf(doc_tokens: list) -> dict:
    tf = {}
    total = len(doc_tokens)
    for word in doc_tokens:
        tf[word] = tf.get(word, 0) + 1
    return {w: c/total for w, c in tf.items()}

def compute_idf(all_token_lists: list) -> dict:
    N = len(all_token_lists)
    idf = {}
    all_words = set(w for tokens in all_token_lists for w in tokens)
    for word in all_words:
        df = sum(1 for tokens in all_token_lists if word in tokens)
        idf[word] = log(N / (1 + df)) + 1   # smoothed IDF
    return idf

def compute_tfidf_vectors(corpus: list) -> tuple[list, dict]:
    all_tokens = [tokenize(doc) for doc in corpus]
    idf        = compute_idf(all_tokens)
    vectors    = []
    for tokens in all_tokens:
        tf  = compute_tf(tokens)
        vec = {w: tf[w] * idf.get(w, 0) for w in tf}
        vectors.append(vec)
    return vectors, idf

# ──────────────────────────────────────────────────────────
# 4. COSINE SIMILARITY
# ──────────────────────────────────────────────────────────
def cosine_similarity(vec_a: dict, vec_b: dict) -> float:
    common_words = set(vec_a.keys()) & set(vec_b.keys())
    if not common_words:
        return 0.0

    dot_product = sum(vec_a[w] * vec_b[w] for w in common_words)

    norm_a = sqrt(sum(v ** 2 for v in vec_a.values()))
    norm_b = sqrt(sum(v ** 2 for v in vec_b.values()))

    if norm_a == 0 or norm_b == 0:
        return 0.0

    return dot_product / (norm_a * norm_b)

# ──────────────────────────────────────────────────────────
# 5. FUNGSI UTAMA REKOMENDASI
# ──────────────────────────────────────────────────────────
def get_recommendations(target_service_id: int, top_n: int = 6) -> list:
    services = fetch_services()

    if not services:
        return []

    # Buat indeks id → posisi
    id_to_idx = {svc['id']: i for i, svc in enumerate(services)}
    target_idx = id_to_idx.get(target_service_id)

    if target_idx is None:
        return []

    corpus  = build_corpus(services)
    vectors, _ = compute_tfidf_vectors(corpus)

    target_vec = vectors[target_idx]

    # Hitung similarity ke semua jasa
    scores = []
    for i, vec in enumerate(vectors):
        if i == target_idx:
            continue    # skip diri sendiri
        sim = cosine_similarity(target_vec, vec)
        scores.append((sim, i))

    # Sort descending
    scores.sort(key=lambda x: x[0], reverse=True)

    # Ambil top-N
    results = []
    for sim, idx in scores[:top_n]:
        svc = services[idx]
        results.append({
            "id":           svc['id'],
            "title":        svc['title'],
            "slug":         svc['slug'],
            "price":        float(svc['price']),
            "rating_avg":   float(svc['rating_avg']),
            "total_orders": svc['total_orders'],
            "thumbnail":    svc['thumbnail'],
            "similarity":   round(sim, 4)
        })

    return results


# ──────────────────────────────────────────────────────────
# ENTRY POINT — dipanggil dari CLI oleh CI4
# ──────────────────────────────────────────────────────────
if __name__ == "__main__":
    if len(sys.argv) < 2:
        print(json.dumps({"error": "service_id diperlukan"}))
        sys.exit(1)

    try:
        service_id = int(sys.argv[1])
        top_n      = int(sys.argv[2]) if len(sys.argv) > 2 else 6
        recs       = get_recommendations(service_id, top_n)
        print(json.dumps(recs, ensure_ascii=False))
    except Exception as e:
        print(json.dumps({"error": str(e)}))
        sys.exit(1)
