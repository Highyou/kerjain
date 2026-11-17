<?php
// app/Controllers/AdminController.php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ServiceModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\CategoryModel;
use App\Models\SellerModel;
use App\Models\ReviewModel;

class AdminController extends BaseController
{
    protected $userModel;
    protected $serviceModel;
    protected $orderModel;
    protected $orderItemModel;
    protected $categoryModel;
    protected $sellerModel;
    protected $reviewModel;

    public function __construct()
    {
        $this->userModel      = new UserModel();
        $this->serviceModel   = new ServiceModel();
        $this->orderModel     = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->categoryModel  = new CategoryModel();
        $this->sellerModel    = new SellerModel();
        $this->reviewModel    = new ReviewModel();
    }

    // ──────────────────────────────────────────────
    // Dashboard Admin
    // ──────────────────────────────────────────────
    public function dashboard()
    {
        $data = [
            'title'           => 'Admin Dashboard',
            'total_users'     => $this->userModel->countAllResults(),
            'total_sellers'   => $this->userModel->where('role', 'seller')->countAllResults(),
            'total_buyers'    => $this->userModel->where('role', 'buyer')->countAllResults(),
            'total_services'  => $this->serviceModel->countAllResults(),
            'pending_services'=> $this->serviceModel->where('status', 'pending')->countAllResults(),
            'total_orders'    => $this->orderModel->countAllResults(),
            'total_revenue'   => $this->orderModel->where('payment_status', 'paid')->selectSum('total_amount')->first()['total_amount'] ?? 0,
            'recent_orders'   => $this->orderModel->orderBy('created_at', 'DESC')->findAll(5),
            'top_services'    => $this->serviceModel->orderBy('total_orders', 'DESC')->findAll(5),
            'top_sellers'     => $this->sellerModel->orderBy('total_orders', 'DESC')->findAll(5),
        ];

        return view('admin/dashboard', $data);
    }

    // ──────────────────────────────────────────────
    // KELOLA USER
    // ──────────────────────────────────────────────
    public function users()
    {
        $role   = $this->request->getGet('role') ?? '';
        $search = $this->request->getGet('search') ?? '';

        $builder = $this->userModel;
        if ($role)   $builder = $builder->where('role', $role);
        if ($search) $builder = $builder->like('name', $search)->orLike('email', $search);

        return view('admin/users', [
            'title' => 'Kelola User',
            'users' => $builder->paginate(20),
            'pager' => $this->userModel->pager,
        ]);
    }

    public function userDetail($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');

        return view('admin/user_detail', ['title' => 'Detail User', 'user' => $user]);
    }

    public function userToggle($id)
    {
        $user = $this->userModel->find($id);
        $this->userModel->update($id, ['is_active' => $user['is_active'] ? 0 : 1]);
        $status = $user['is_active'] ? 'dinonaktifkan' : 'diaktifkan';
        return redirect()->back()->with('success', "User berhasil {$status}.");
    }

    public function userDelete($id)
    {
        $this->userModel->delete($id);
        return redirect()->to('/admin/users')->with('success', 'User berhasil dihapus.');
    }

    // ──────────────────────────────────────────────
    // KELOLA JASA
    // ──────────────────────────────────────────────
    public function jasa()
    {
        $status  = $this->request->getGet('status') ?? '';
        $builder = $this->serviceModel->select('services.*, users.name as seller_name, categories.name as category_name')
                                      ->join('sellers', 'sellers.id = services.seller_id')
                                      ->join('users',   'users.id = sellers.user_id')
                                      ->join('categories', 'categories.id = services.category_id');

        if ($status) $builder = $builder->where('services.status', $status);

        return view('admin/jasa', [
            'title'    => 'Kelola Jasa',
            'services' => $builder->paginate(20),
            'pager'    => $this->serviceModel->pager,
        ]);
    }

    public function jasaApprove($id)
    {
        $this->serviceModel->update($id, ['status' => 'active']);
        return redirect()->back()->with('success', 'Jasa berhasil disetujui.');
    }

    public function jasaReject($id)
    {
        $this->serviceModel->update($id, ['status' => 'inactive']);
        return redirect()->back()->with('success', 'Jasa ditolak.');
    }

    public function jasaDelete($id)
    {
        $this->serviceModel->delete($id);
        return redirect()->to('/admin/jasa')->with('success', 'Jasa dihapus.');
    }

    // ──────────────────────────────────────────────
    // KELOLA KATEGORI
    // ──────────────────────────────────────────────
    public function kategori()
    {
        return view('admin/kategori', [
            'title'      => 'Kelola Kategori',
            'categories' => $this->categoryModel->findAll(),
        ]);
    }

    public function kategoriStore()
    {
        $name = $this->request->getPost('name');
        $this->categoryModel->insert([
            'name' => $name,
            'slug' => url_title($name, '-', true),
            'icon' => $this->request->getPost('icon') ?? 'bi-briefcase',
            'description' => $this->request->getPost('description'),
        ]);
        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function kategoriUpdate($id)
    {
        $name = $this->request->getPost('name');
        $this->categoryModel->update($id, [
            'name'        => $name,
            'slug'        => url_title($name, '-', true),
            'icon'        => $this->request->getPost('icon'),
            'description' => $this->request->getPost('description'),
            'is_active'   => $this->request->getPost('is_active') ?? 1,
        ]);
        return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function kategoriDelete($id)
    {
        $this->categoryModel->delete($id);
        return redirect()->back()->with('success', 'Kategori dihapus.');
    }

    // ──────────────────────────────────────────────
    // TRANSAKSI
    // ──────────────────────────────────────────────
    public function transaksi()
    {
        $orders = $this->orderModel
                       ->select('orders.*, users.name as buyer_name')
                       ->join('users', 'users.id = orders.buyer_id')
                       ->orderBy('orders.created_at', 'DESC')
                       ->paginate(20);

        return view('admin/transaksi', [
            'title'  => 'Monitoring Transaksi',
            'orders' => $orders,
            'pager'  => $this->orderModel->pager,
        ]);
    }

    public function transaksiDetail($id)
    {
        $order = $this->orderModel->find($id);
        $items = $this->orderItemModel->getItemsByOrder($id);

        return view('admin/transaksi_detail', [
            'title' => 'Detail Transaksi',
            'order' => $order,
            'items' => $items,
        ]);
    }

    public function konfirmasiBayar($orderId)
    {   
        $order = $this->orderModel->find($orderId);

        if (!$order) {
            return redirect()->back()->with('error', 'Order tidak ditemukan.');
        }

        // Update order jadi paid & confirmed
        $this->orderModel->update($orderId, [
            'payment_status' => 'paid',
            'status'         => 'confirmed',
            'paid_at'        => date('Y-m-d H:i:s'),
        ]);

        // Update semua order_items jadi confirmed
        $this->orderItemModel->where('order_id', $orderId)
                         ->set(['status' => 'confirmed'])
                         ->update();

        return redirect()->back()->with('success', 'Pembayaran dikonfirmasi! Seller bisa mulai bekerja.');
    }

    // ──────────────────────────────────────────────
    // LAPORAN
    // ──────────────────────────────────────────────
    public function laporan()
    {
        $db = \Config\Database::connect();

        // Total per bulan (12 bulan terakhir)
        $monthlyRevenue = $db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as bulan,
                   SUM(total_amount) as total,
                   COUNT(id) as jumlah_order
            FROM orders
            WHERE payment_status = 'paid'
              AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY bulan
            ORDER BY bulan ASC
        ")->getResultArray();

        // Jasa terlaris
        $topServices = $db->query("
            SELECT s.title, COUNT(oi.id) as total_terjual, SUM(oi.subtotal) as revenue
            FROM order_items oi
            JOIN services s ON s.id = oi.service_id
            JOIN orders o ON o.id = oi.order_id
            WHERE o.payment_status = 'paid'
            GROUP BY oi.service_id
            ORDER BY total_terjual DESC
            LIMIT 10
        ")->getResultArray();

        // Seller terbaik
        $topSellers = $db->query("
            SELECT u.name, COUNT(oi.id) as total_pesanan, SUM(oi.subtotal) as revenue,
                   sl.rating_avg
            FROM order_items oi
            JOIN sellers sl ON sl.id = oi.seller_id
            JOIN users u ON u.id = sl.user_id
            JOIN orders o ON o.id = oi.order_id
            WHERE o.payment_status = 'paid'
            GROUP BY oi.seller_id
            ORDER BY revenue DESC
            LIMIT 10
        ")->getResultArray();

        return view('admin/laporan', [
            'title'          => 'Laporan',
            'monthlyRevenue' => $monthlyRevenue,
            'topServices'    => $topServices,
            'topSellers'     => $topSellers,
        ]);
    }

    public function laporanExport()
    {
        // Export CSV sederhana
        $orders = $this->orderModel->findAll();
        $csv = "No,Kode Order,Buyer,Total,Status,Tanggal\n";
        foreach ($orders as $i => $o) {
            $csv .= ($i+1) . ',' . $o['order_code'] . ',' . $o['buyer_id'] . ','
                  . $o['total_amount'] . ',' . $o['status'] . ',' . $o['created_at'] . "\n";
        }

        return $this->response
                    ->setHeader('Content-Type', 'text/csv')
                    ->setHeader('Content-Disposition', 'attachment; filename="laporan-'.date('Y-m-d').'.csv"')
                    ->setBody($csv);
    }
}
