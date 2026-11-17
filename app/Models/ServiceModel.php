<?php
namespace App\Models;
use CodeIgniter\Model;
 
class ServiceModel extends Model
{
    protected $table         = 'services';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'seller_id','category_id','title','slug','description',
        'price','delivery_days','revision_count','thumbnail',
        'status','rating_avg','total_reviews','total_orders','tags'
    ];
    protected $useTimestamps = true;
 
    /**
     * Ambil jasa dengan data seller & kategori untuk halaman publik
     */
    public function getActiveWithDetails(int $limit = 12, int $offset = 0): array
    {
        return $this->select('services.*, users.name as seller_name, sellers.university,
                              categories.name as category_name, categories.slug as cat_slug')
                    ->join('sellers', 'sellers.id = services.seller_id')
                    ->join('users', 'users.id = sellers.user_id')
                    ->join('categories', 'categories.id = services.category_id')
                    ->where('services.status', 'active')
                    ->orderBy('services.total_orders', 'DESC')
                    ->findAll($limit, $offset);
    }
 
    public function getBySlug(string $slug): ?array
    {
        return $this->select('services.*, users.name as seller_name, sellers.university,
                              sellers.bio as seller_bio, sellers.rating_avg as seller_rating,
                              categories.name as category_name')
                    ->join('sellers', 'sellers.id = services.seller_id')
                    ->join('users', 'users.id = sellers.user_id')
                    ->join('categories', 'categories.id = services.category_id')
                    ->where('services.slug', $slug)
                    ->where('services.status', 'active')
                    ->first();
    }
 
    public function incrementOrder(int $serviceId): void
    {
        $this->set('total_orders', 'total_orders + 1', false)->where('id', $serviceId)->update();
    }
 
    public function recalcRating(int $serviceId): void
    {
        $db  = \Config\Database::connect();
        $row = $db->query("SELECT AVG(rating) as avg_r, COUNT(*) as total
                           FROM reviews WHERE service_id = $serviceId AND is_published = 1")->getRow();
        $this->update($serviceId, [
            'rating_avg'    => round($row->avg_r ?? 0, 2),
            'total_reviews' => $row->total ?? 0,
        ]);
    }
 
    /**
     * TF-IDF Rekomendasi: ambil semua jasa aktif lain sebagai kandidat
     */
    public function getAllForRecommendation(): array
    {
        return $this->select('id, title, description, tags, category_id, thumbnail, price, slug, rating_avg, total_orders')
                    ->where('status', 'active')
                    ->findAll();
    }
}