<?php
// app/Controllers/BuyerController.php
namespace App\Controllers;

use App\Models\ServiceModel;
use App\Models\CartModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ReviewModel;
use App\Models\CategoryModel;

class BuyerController extends BaseController
{
    protected $serviceModel;
    protected $cartModel;
    protected $orderModel;
    protected $orderItemModel;
    protected $reviewModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->serviceModel   = new ServiceModel();
        $this->cartModel      = new CartModel();
        $this->orderModel     = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->reviewModel    = new ReviewModel();
        $this->categoryModel  = new CategoryModel();
    }

    // ──────────────────────────────────────────────
    // Dashboard Buyer
    // ──────────────────────────────────────────────
    public function dashboard()
    {
        $buyerId = session()->get('user_id');

        $data = [
            'title'         => 'Dashboard Buyer',
            'total_orders'  => $this->orderModel->countByBuyer($buyerId),
            'active_orders' => $this->orderModel->countActiveByBuyer($buyerId),
            'recent_orders' => $this->orderModel->getRecentByBuyer($buyerId, 5),
            'cart_count'    => $this->cartModel->countByBuyer($buyerId),
        ];

        return view('buyer/dashboard', $data);
    }

    // ──────────────────────────────────────────────
    // CART
    // ──────────────────────────────────────────────
    public function cart()
    {
        $buyerId = session()->get('user_id');
        $items   = $this->cartModel->getCartWithDetails($buyerId);
        $total   = array_sum(array_column($items, 'price'));

        return view('buyer/cart', [
            'title' => 'Keranjang Belanja',
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function cartAdd()
    {
        $buyerId   = session()->get('user_id');
        $serviceId = $this->request->getPost('service_id');

        // Validasi jasa exists & active
        $service = $this->serviceModel->find($serviceId);
        if (!$service || $service['status'] !== 'active') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Jasa tidak tersedia']);
        }

        $existing = $this->cartModel->where('buyer_id', $buyerId)
                                    ->where('service_id', $serviceId)->first();
        if ($existing) {
            return $this->response->setJSON(['status' => 'info', 'message' => 'Jasa sudah ada di keranjang']);
        }

        $this->cartModel->insert([
            'buyer_id'       => $buyerId,
            'service_id'     => $serviceId,
            'quantity'       => 1,
            'custom_request' => $this->request->getPost('custom_request'),
        ]);

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Berhasil ditambahkan ke keranjang',
            'count'   => $this->cartModel->countByBuyer($buyerId),
        ]);
    }

    public function cartRemove($cartId)
    {
        $buyerId = session()->get('user_id');
        $this->cartModel->where('id', $cartId)->where('buyer_id', $buyerId)->delete();
        return redirect()->to('/buyer/cart')->with('success', 'Item dihapus dari keranjang.');
    }

    public function cartClear()
    {
        $this->cartModel->where('buyer_id', session()->get('user_id'))->delete();
        return redirect()->to('/buyer/cart');
    }

    // ──────────────────────────────────────────────
    // CHECKOUT
    // ──────────────────────────────────────────────
    public function checkout()
    {
        $buyerId = session()->get('user_id');
        $items   = $this->cartModel->getCartWithDetails($buyerId);

        if (empty($items)) {
            return redirect()->to('/buyer/cart')->with('error', 'Keranjang kosong!');
        }

        $total = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $items));

        return view('buyer/checkout', [
            'title' => 'Checkout',
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function checkoutProcess()
    {
        $buyerId = session()->get('user_id');
        $items   = $this->cartModel->getCartWithDetails($buyerId);

        if (empty($items)) {
            return redirect()->to('/buyer/cart')->with('error', 'Keranjang kosong!');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $total = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $items));

            // Generate kode order unik
            $orderCode = 'ORD-' . strtoupper(uniqid());

            // Simpan header order
            $orderId = $this->orderModel->insert([
                'order_code'     => $orderCode,
                'buyer_id'       => $buyerId,
                'total_amount'   => $total,
                'status'         => 'pending',
                'payment_method' => $this->request->getPost('payment_method'),
                'payment_status' => 'unpaid',
                'note'           => $this->request->getPost('note'),
            ]);

            // Simpan setiap item ke order_items
            foreach ($items as $item) {
                $this->orderItemModel->insert([
                    'order_id'       => $orderId,
                    'service_id'     => $item['service_id'],
                    'seller_id'      => $item['seller_id'],
                    'price'          => $item['price'],
                    'quantity'       => $item['quantity'],
                    'subtotal'       => $item['price'] * $item['quantity'],
                    'custom_request' => $item['custom_request'],
                    'status'         => 'pending',
                ]);

                // Update total order seller
                $this->serviceModel->incrementOrder($item['service_id']);
            }

            // Kosongkan keranjang
            $this->cartModel->where('buyer_id', $buyerId)->delete();

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaksi gagal');
            }

            return redirect()->to("/buyer/orders/{$orderId}")
                             ->with('success', "Order #{$orderCode} berhasil dibuat!");

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }

    public function orderSelesai($orderItemId)
    {
        $buyerId = session()->get('user_id');
        $item    = $this->orderItemModel->find($orderItemId);

        if (!$item) {
            return redirect()->back()->with('error', 'Item tidak ditemukan.');
        }

        // Update item jadi completed
        $this->orderItemModel->update($orderItemId, [
            'status' => 'completed',
        ]);

        // Cek apakah semua item dalam order sudah completed
        $order      = $this->orderModel->find($item['order_id']);
        $totalItems = $this->orderItemModel
                        ->where('order_id', $item['order_id'])
                        ->countAllResults();
        $doneItems  = $this->orderItemModel
                        ->where('order_id', $item['order_id'])
                        ->where('status', 'completed')
                        ->countAllResults();

        // Kalau semua item selesai, update header order juga
        if ($totalItems === $doneItems) {
            $this->orderModel->update($item['order_id'], [
                'status'       => 'completed',
                'completed_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->back()->with('success', 'Pesanan dikonfirmasi selesai! Silakan beri ulasan.');
    }

    // ──────────────────────────────────────────────
    // ORDER LIST & DETAIL
    // ──────────────────────────────────────────────
    public function orders()
    {
        $buyerId = session()->get('user_id');
        $orders  = $this->orderModel->getByBuyer($buyerId);

        return view('buyer/orders', [
            'title'  => 'Pesanan Saya',
            'orders' => $orders,
        ]);
    }

    public function orderDetail($orderId)
    {
        $buyerId = session()->get('user_id');
        $order   = $this->orderModel->find($orderId);

        if (!$order || $order['buyer_id'] != $buyerId) {
            return redirect()->to('/buyer/orders')->with('error', 'Order tidak ditemukan.');
        }

        $items = $this->orderItemModel->getItemsByOrder($orderId);

        return view('buyer/order_detail', [
            'title' => 'Detail Pesanan',
            'order' => $order,
            'items' => $items,
        ]);
    }

    // ──────────────────────────────────────────────
    // REVIEW
    // ──────────────────────────────────────────────
    public function reviewPage($orderItemId)
    {
        // Pakai getDetailWithService() agar thumbnail & seller_name ikut terambil
        $item = $this->orderItemModel
                    ->select('order_items.*, services.title, services.thumbnail,
                            sellers.id as seller_id_ref, users.name as seller_name')
                    ->join('services', 'services.id = order_items.service_id')
                    ->join('sellers',  'sellers.id  = order_items.seller_id')
                    ->join('users',    'users.id    = sellers.user_id')
                    ->where('order_items.id', $orderItemId)
                    ->first();

        if (!$item || $item['status'] !== 'completed') {
            return redirect()->to(base_url('buyer/orders'))
                            ->with('error', 'Review tidak tersedia.');
        }

        $existing = $this->reviewModel->where('order_item_id', $orderItemId)->first();
        if ($existing) {
            return redirect()->to(base_url('buyer/orders'))
                            ->with('info', 'Anda sudah memberi review.');
        }

        return view('buyer/review_form', [
            'title' => 'Beri Ulasan',
            'item'  => $item,
        ]);
    }

    public function reviewSubmit()
    {
        $rules = [
            'order_item_id' => 'required|is_natural_no_zero',
            'rating'        => 'required|in_list[1,2,3,4,5]',
            'comment'       => 'permit_empty|max_length[1000]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $orderItemId = $this->request->getPost('order_item_id');
        $item        = $this->orderItemModel->find($orderItemId);

        $this->reviewModel->insert([
            'order_item_id' => $orderItemId,
            'service_id'    => $item['service_id'],
            'buyer_id'      => session()->get('user_id'),
            'seller_id'     => $item['seller_id'],
            'rating'        => $this->request->getPost('rating'),
            'comment'       => $this->request->getPost('comment'),
        ]);

        // Update rating rata-rata di tabel services
        $this->serviceModel->recalcRating($item['service_id']);

        return redirect()->to('/buyer/orders')->with('success', 'Ulasan berhasil dikirim!');
    }

    // ──────────────────────────────────────────────
    // PROFIL
    // ──────────────────────────────────────────────
    public function profile()
    {
        return view('buyer/profile', ['title' => 'Profil Saya']);
    }

    public function profileUpdate()
    {
        // update name, phone, avatar
        $userId = session()->get('user_id');
        $userModel = new \App\Models\UserModel();

        $data = ['name' => $this->request->getPost('name'), 'phone' => $this->request->getPost('phone')];

        $avatar = $this->request->getFile('avatar');
        if ($avatar && $avatar->isValid()) {
            $newName = $avatar->getRandomName();
            $avatar->move(ROOTPATH . 'public/uploads/avatars', $newName);
            $data['avatar'] = $newName;
            session()->set('avatar', $newName);
        }

        $userModel->update($userId, $data);
        session()->set('name', $data['name']);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
