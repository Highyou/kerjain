<?php
// ============================================================
// app/Controllers/HomeController.php
// Mengelola halaman publik + integrasi rekomendasi TF-IDF
// ============================================================
namespace App\Controllers;

use App\Models\ServiceModel;
use App\Models\CategoryModel;
use App\Models\ReviewModel;

class Home extends BaseController
{
    protected $serviceModel;
    protected $categoryModel;
    protected $reviewModel;

    public function __construct()
    {
        $this->serviceModel  = new ServiceModel();
        $this->categoryModel = new CategoryModel();
        $this->reviewModel   = new ReviewModel();
    }

    // ──────────────────────────────────────────────
    // Halaman beranda
    // ──────────────────────────────────────────────
    public function index()
    {
        return view('home/index', [
            'title'           => 'Beranda',
            'categories'      => $this->categoryModel->where('is_active', 1)->findAll(),
            'popularServices' => $this->serviceModel->getActiveWithDetails(8),
        ]);
    }

    // ──────────────────────────────────────────────
    // Browse semua jasa
    // ──────────────────────────────────────────────
    public function browse()
    {
        $categorySlug = $this->request->getGet('kategori');
        $sort         = $this->request->getGet('sort') ?? 'popular';
        $minPrice     = $this->request->getGet('min_price');
        $maxPrice     = $this->request->getGet('max_price');

        $builder = $this->serviceModel
            ->select('services.*, users.name as seller_name, categories.name as category_name, categories.slug as cat_slug')
            ->join('sellers', 'sellers.id = services.seller_id')
            ->join('users', 'users.id = sellers.user_id')
            ->join('categories', 'categories.id = services.category_id')
            ->where('services.status', 'active');

        if ($categorySlug) {
            $cat = $this->categoryModel->where('slug', $categorySlug)->first();
            if ($cat) $builder->where('services.category_id', $cat['id']);
        }

        if ($minPrice) $builder->where('services.price >=', (int)$minPrice);
        if ($maxPrice) $builder->where('services.price <=', (int)$maxPrice);

        match ($sort) {
            'newest'   => $builder->orderBy('services.created_at', 'DESC'),
            'cheapest' => $builder->orderBy('services.price', 'ASC'),
            'rating'   => $builder->orderBy('services.rating_avg', 'DESC'),
            default    => $builder->orderBy('services.total_orders', 'DESC'),
        };

        return view('home/browse', [
            'title'      => 'Jelajahi Jasa',
            'services'   => $builder->paginate(12),
            'pager'      => $this->serviceModel->pager,
            'categories' => $this->categoryModel->where('is_active', 1)->findAll(),
        ]);
    }

    // ──────────────────────────────────────────────
    // Detail jasa + REKOMENDASI TF-IDF
    // ──────────────────────────────────────────────
    public function detail(string $slug)
    {
        $service = $this->serviceModel->getBySlug($slug);

        if (!$service) {
            return redirect()->to('/jasa')->with('error', 'Jasa tidak ditemukan.');
        }

        $reviews        = $this->reviewModel->getByService($service['id']);
        $recommendations = $this->getRecommendations($service['id']);

        return view('home/detail', [
            'title'           => $service['title'],
            'service'         => $service,
            'reviews'         => $reviews,
            'recommendations' => $recommendations,
        ]);
    }

    // ──────────────────────────────────────────────
    // Filter by kategori
    // ──────────────────────────────────────────────
    public function byCategory(string $slug)
    {
        return redirect()->to("/jasa?kategori={$slug}");
    }

    // ──────────────────────────────────────────────
    // Pencarian
    // ──────────────────────────────────────────────
    public function search()
    {
        $q = $this->request->getGet('q');

        $services = $this->serviceModel
            ->select('services.*, users.name as seller_name, categories.name as category_name')
            ->join('sellers', 'sellers.id = services.seller_id')
            ->join('users', 'users.id = sellers.user_id')
            ->join('categories', 'categories.id = services.category_id')
            ->where('services.status', 'active')
            ->groupStart()
                ->like('services.title', $q)
                ->orLike('services.description', $q)
                ->orLike('services.tags', $q)
            ->groupEnd()
            ->paginate(12);

        return view('home/browse', [
            'title'      => "Hasil Cari: {$q}",
            'services'   => $services,
            'pager'      => $this->serviceModel->pager,
            'categories' => $this->categoryModel->where('is_active', 1)->findAll(),
            'search_q'   => $q,
        ]);
    }

    // ──────────────────────────────────────────────
    // Panggil Python script via shell_exec
    // ──────────────────────────────────────────────
    private function getRecommendations(int $serviceId): array
    {
        $scriptPath = ROOTPATH . 'recommender/recommend.py';

        // Pastikan Python3 tersedia
        $pythonCmd = PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';

        $output = shell_exec("{$pythonCmd} {$scriptPath} {$serviceId} 6 2>/dev/null");

        if (!$output) return [];

        $decoded = json_decode($output, true);
        return is_array($decoded) ? $decoded : [];
    }
}