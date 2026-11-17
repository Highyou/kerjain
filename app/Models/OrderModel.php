<?php
namespace App\Models;
use CodeIgniter\Model;
 
class OrderModel extends Model
{
    protected $table         = 'orders';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'order_code','buyer_id','total_amount','status',
        'payment_method','payment_status','note','paid_at','completed_at'
    ];
    protected $useTimestamps = true;
 
    public function getByBuyer(int $buyerId): array
    {
        return $this->where('buyer_id', $buyerId)->orderBy('created_at', 'DESC')->findAll();
    }
 
    public function getRecentByBuyer(int $buyerId, int $limit = 5): array
    {
        return $this->where('buyer_id', $buyerId)->orderBy('created_at', 'DESC')->findAll($limit);
    }
 
    public function countByBuyer(int $buyerId): int
    {
        return $this->where('buyer_id', $buyerId)->countAllResults();
    }
 
    public function countActiveByBuyer(int $buyerId): int
    {
        return $this->where('buyer_id', $buyerId)
                    ->whereIn('status', ['pending','confirmed','in_progress'])
                    ->countAllResults();
    }
}