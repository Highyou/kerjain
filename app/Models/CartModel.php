<?php
namespace App\Models;
use CodeIgniter\Model;
 
class CartModel extends Model
{
    protected $table         = 'carts';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['buyer_id','service_id','quantity','custom_request'];
    protected $useTimestamps  = false;
 
    public function getCartWithDetails(int $buyerId): array
    {
        return $this->select('carts.*, services.title, services.price, services.thumbnail,
                              services.delivery_days, sellers.id as seller_id,
                              users.name as seller_name, categories.name as category_name')
                    ->join('services', 'services.id = carts.service_id')
                    ->join('sellers', 'sellers.id = services.seller_id')
                    ->join('users', 'users.id = sellers.user_id')
                    ->join('categories', 'categories.id = services.category_id')
                    ->where('carts.buyer_id', $buyerId)
                    ->findAll();
    }
 
    public function countByBuyer(int $buyerId): int
    {
        return $this->where('buyer_id', $buyerId)->countAllResults();
    }
}