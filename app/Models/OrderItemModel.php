<?php
namespace App\Models;
use CodeIgniter\Model;
 
class OrderItemModel extends Model
{
    protected $table         = 'order_items';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'order_id','service_id','seller_id','price','quantity',
        'subtotal','custom_request','status','delivery_file','note_seller'
    ];
    protected $useTimestamps = true;
 
    public function getItemsByOrder(int $orderId): array
    {
        return $this->select('order_items.*, services.title, services.thumbnail,
                              users.name as seller_name, sellers.id as seller_id_ref')
                    ->join('services', 'services.id = order_items.service_id')
                    ->join('sellers', 'sellers.id = order_items.seller_id')
                    ->join('users', 'users.id = sellers.user_id')
                    ->where('order_items.order_id', $orderId)
                    ->findAll();
    }
 
    public function getBySeller(int $sellerId, string $status = ''): array
    {
        $q = $this->select('order_items.*, services.title, orders.order_code, orders.created_at as order_date, users.name as buyer_name')
                  ->join('services', 'services.id = order_items.service_id')
                  ->join('orders', 'orders.id = order_items.order_id')
                  ->join('users', 'users.id = orders.buyer_id')
                  ->where('order_items.seller_id', $sellerId)
                  ->orderBy('order_items.created_at', 'DESC');
 
        if ($status) $q = $q->where('order_items.status', $status);
 
        return $q->findAll();
    }
 
    public function getDetailBySeller(int $id, int $sellerId): ?array
    {
        return $this->select('order_items.*, services.title, services.thumbnail, orders.order_code,
                              orders.buyer_id, users.name as buyer_name, users.phone as buyer_phone')
                    ->join('services', 'services.id = order_items.service_id')
                    ->join('orders', 'orders.id = order_items.order_id')
                    ->join('users', 'users.id = orders.buyer_id')
                    ->where('order_items.id', $id)
                    ->where('order_items.seller_id', $sellerId)
                    ->first();
    }
 
    public function getRecentBySeller(int $sellerId, int $limit = 5): array
    {
        return $this->getBySeller($sellerId);
    }
 
    public function getTotalRevenue(int $sellerId): float
    {
        $db  = \Config\Database::connect();
        $row = $db->query("SELECT SUM(oi.subtotal) as total
                           FROM order_items oi
                           JOIN orders o ON o.id = oi.order_id
                           WHERE oi.seller_id = $sellerId
                             AND o.payment_status = 'paid'")->getRow();
        return (float)($row->total ?? 0);
    }
 
    public function getMonthlyRevenue(int $sellerId): array
    {
        $db = \Config\Database::connect();
        return $db->query("SELECT DATE_FORMAT(o.created_at, '%Y-%m') as bulan,
                                  SUM(oi.subtotal) as total
                           FROM order_items oi
                           JOIN orders o ON o.id = oi.order_id
                           WHERE oi.seller_id = $sellerId
                             AND o.payment_status = 'paid'
                             AND o.created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                           GROUP BY bulan ORDER BY bulan ASC")->getResultArray();
    }
}