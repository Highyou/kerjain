-- ============================================================
-- KERJAIN - MARKETPLACE JASA MAHASISWA
-- Database: MySQL | Framework: CodeIgniter 4
-- File: 01_DATABASE.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS kerjain_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE kerjain_db;

-- ============================================================
-- 1. TABEL USERS (Akun semua role)
-- ============================================================
CREATE TABLE users (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    role        ENUM('buyer','seller','admin') NOT NULL DEFAULT 'buyer',
    avatar      VARCHAR(255) DEFAULT NULL,
    phone       VARCHAR(20) DEFAULT NULL,
    is_active   TINYINT(1) NOT NULL DEFAULT 1,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- 2. TABEL SELLERS (Profil khusus seller)
-- ============================================================
CREATE TABLE sellers (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id         INT UNSIGNED NOT NULL,
    university      VARCHAR(150) DEFAULT NULL,
    major           VARCHAR(150) DEFAULT NULL,
    bio             TEXT DEFAULT NULL,
    portfolio_url   VARCHAR(255) DEFAULT NULL,
    rating_avg      DECIMAL(3,2) DEFAULT 0.00,
    total_orders    INT DEFAULT 0,
    is_verified     TINYINT(1) DEFAULT 0,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- 3. TABEL BUYERS (Profil khusus buyer)
-- ============================================================
CREATE TABLE buyers (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED NOT NULL,
    address     TEXT DEFAULT NULL,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- 4. TABEL CATEGORIES (Kategori jasa)
-- ============================================================
CREATE TABLE categories (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    slug        VARCHAR(110) NOT NULL UNIQUE,
    icon        VARCHAR(50) DEFAULT 'bi-briefcase',
    description TEXT DEFAULT NULL,
    is_active   TINYINT(1) DEFAULT 1,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Seed kategori awal
INSERT INTO categories (name, slug, icon, description) VALUES
('Desain Grafis',    'desain-grafis',    'bi-palette',        'Logo, poster, banner, UI/UX design'),
('Pemrograman',      'pemrograman',      'bi-code-slash',     'Web, mobile, desktop app'),
('Penulisan',        'penulisan',        'bi-pencil',         'Artikel, esai, copywriting'),
('Terjemahan',       'terjemahan',       'bi-translate',      'Inggris, Mandarin, Arab, dll'),
('Video & Editing',  'video-editing',    'bi-camera-video',   'Editing video, motion graphic'),
('Musik & Audio',    'musik-audio',      'bi-music-note',     'Jingle, podcast editing'),
('Fotografi',        'fotografi',        'bi-camera',         'Foto produk, portrait, event'),
('Les & Tutor',      'les-tutor',        'bi-book',           'Bimbel, privat, konsultasi');

-- ============================================================
-- 5. TABEL SERVICES (Daftar jasa)
-- ============================================================
CREATE TABLE services (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    seller_id       INT UNSIGNED NOT NULL,
    category_id     INT UNSIGNED NOT NULL,
    title           VARCHAR(200) NOT NULL,
    slug            VARCHAR(220) NOT NULL UNIQUE,
    description     TEXT NOT NULL,
    price           DECIMAL(12,2) NOT NULL,
    delivery_days   INT NOT NULL DEFAULT 3,
    revision_count  INT NOT NULL DEFAULT 1,
    thumbnail       VARCHAR(255) DEFAULT NULL,
    status          ENUM('active','inactive','pending') DEFAULT 'pending',
    rating_avg      DECIMAL(3,2) DEFAULT 0.00,
    total_reviews   INT DEFAULT 0,
    total_orders    INT DEFAULT 0,
    tags            VARCHAR(255) DEFAULT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id)   REFERENCES sellers(id)    ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    INDEX idx_category (category_id),
    INDEX idx_status   (status),
    INDEX idx_price    (price)
) ENGINE=InnoDB;

-- ============================================================
-- 6. TABEL ORDERS (Header transaksi)
-- ============================================================
CREATE TABLE orders (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_code      VARCHAR(30) NOT NULL UNIQUE,
    buyer_id        INT UNSIGNED NOT NULL,
    total_amount    DECIMAL(14,2) NOT NULL DEFAULT 0,
    status          ENUM('pending','confirmed','in_progress','completed','cancelled','disputed') DEFAULT 'pending',
    payment_method  VARCHAR(50) DEFAULT NULL,
    payment_status  ENUM('unpaid','paid','refunded') DEFAULT 'unpaid',
    note            TEXT DEFAULT NULL,
    paid_at         DATETIME DEFAULT NULL,
    completed_at    DATETIME DEFAULT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_buyer  (buyer_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ============================================================
-- 7. TABEL ORDER_ITEMS (Detail item per transaksi)
-- ============================================================
CREATE TABLE order_items (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id        INT UNSIGNED NOT NULL,
    service_id      INT UNSIGNED NOT NULL,
    seller_id       INT UNSIGNED NOT NULL,
    price           DECIMAL(12,2) NOT NULL,
    quantity        INT NOT NULL DEFAULT 1,
    subtotal        DECIMAL(14,2) NOT NULL,
    custom_request  TEXT DEFAULT NULL,
    status          ENUM('pending','confirmed','in_progress','delivered','completed','cancelled') DEFAULT 'pending',
    delivery_file   VARCHAR(255) DEFAULT NULL,
    note_seller     TEXT DEFAULT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id)   REFERENCES orders(id)   ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE RESTRICT,
    FOREIGN KEY (seller_id)  REFERENCES sellers(id)  ON DELETE RESTRICT,
    INDEX idx_order  (order_id),
    INDEX idx_seller (seller_id)
) ENGINE=InnoDB;

-- ============================================================
-- 8. TABEL REVIEWS (Ulasan buyer ke jasa)
-- ============================================================
CREATE TABLE reviews (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_item_id   INT UNSIGNED NOT NULL,
    service_id      INT UNSIGNED NOT NULL,
    buyer_id        INT UNSIGNED NOT NULL,
    seller_id       INT UNSIGNED NOT NULL,
    rating          TINYINT(1) NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment         TEXT DEFAULT NULL,
    is_published    TINYINT(1) DEFAULT 1,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_item_id) REFERENCES order_items(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id)    REFERENCES services(id)    ON DELETE CASCADE,
    FOREIGN KEY (buyer_id)      REFERENCES users(id)       ON DELETE CASCADE,
    FOREIGN KEY (seller_id)     REFERENCES sellers(id)     ON DELETE CASCADE,
    UNIQUE KEY unique_review (order_item_id),
    INDEX idx_service (service_id)
) ENGINE=InnoDB;

-- ============================================================
-- 9. TABEL CART (Keranjang sementara)
-- ============================================================
CREATE TABLE carts (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    buyer_id        INT UNSIGNED NOT NULL,
    service_id      INT UNSIGNED NOT NULL,
    quantity        INT NOT NULL DEFAULT 1,
    custom_request  TEXT DEFAULT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id)  REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cart (buyer_id, service_id)
) ENGINE=InnoDB;

-- ============================================================
-- SEED: Admin default
-- password: admin123 (bcrypt hash)
-- ============================================================
INSERT INTO users (name, email, password, role) VALUES
('Admin KerjaIn', 'admin@kerjain.id',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
