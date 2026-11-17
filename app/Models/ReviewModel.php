<?php
namespace App\Models;
use CodeIgniter\Model;
 
class ReviewModel extends Model
{
    protected $table         = 'reviews';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['order_item_id','service_id','buyer_id','seller_id','rating','comment','is_published'];
    protected $useTimestamps  = false;
 
    public function getByService(int $serviceId): array
    {
        return $this->select('reviews.*, users.name as buyer_name, users.avatar as buyer_avatar')
                    ->join('users', 'users.id = reviews.buyer_id')
                    ->where('service_id', $serviceId)
                    ->where('is_published', 1)
                    ->orderBy('reviews.created_at', 'DESC')
                    ->findAll();
    }
}