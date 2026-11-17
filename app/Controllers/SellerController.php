<?php
// app/Controllers/SellerController.php
namespace App\Controllers;

use App\Models\ServiceModel;
use App\Models\CategoryModel;
use App\Models\OrderItemModel;
use App\Models\ReviewModel;
use App\Models\SellerModel;

class SellerController extends BaseController
{
    protected $serviceModel;
    protected $categoryModel;
    protected $orderItemModel;
    protected $reviewModel;
    protected $sellerModel;

    public function __construct()
    {
        $this->serviceModel   = new ServiceModel();
        $this->categoryModel  = new CategoryModel();
        $this->orderItemModel = new OrderItemModel();
        $this->reviewModel    = new ReviewModel();
        $this->sellerModel    = new SellerModel();
    }

    private function getSellerId(): int
    {
        $userId = session()->get('user_id');
        $seller = $this->sellerModel->where('user_id', $userId)->first();
        return $seller['id'];
    }

    // ──────────────────────────────────────────────
    // Dashboard
    // ──────────────────────────────────────────────
    public function dashboard()
    {
        $sellerId = $this->getSellerId();

        $data = [
            'title'          => 'Dashboard Seller',
            'total_jasa'     => $this->serviceModel->where('seller_id', $sellerId)->countAllResults(),
            'total_pesanan'  => $this->orderItemModel->where('seller_id', $sellerId)->countAllResults(),
            'pending_orders' => $this->orderItemModel->where('seller_id', $sellerId)->where('status', 'pending')->countAllResults(),
            'total_revenue'  => $this->orderItemModel->getTotalRevenue($sellerId),
            'recent_orders'  => $this->orderItemModel->getRecentBySeller($sellerId, 5),
            'my_services'    => $this->serviceModel->where('seller_id', $sellerId)->findAll(5),
        ];

        return view('seller/dashboard', $data);
    }

    // ──────────────────────────────────────────────
    // KELOLA JASA
    // ──────────────────────────────────────────────
    public function jasaList()
    {
        $sellerId = $this->getSellerId();
        $services = $this->serviceModel->where('seller_id', $sellerId)->findAll();

        return view('seller/jasa_list', [
            'title'    => 'Jasa Saya',
            'services' => $services,
        ]);
    }

    public function jasaCreate()
    {
        return view('seller/jasa_form', [
            'title'      => 'Tambah Jasa',
            'categories' => $this->categoryModel->where('is_active', 1)->findAll(),
            'service'    => null,
        ]);
    }

    public function jasaStore()
    {
        $rules = [
            'title'         => 'required|min_length[10]|max_length[200]',
            'category_id'   => 'required|is_natural_no_zero',
            'description'   => 'required|min_length[50]',
            'price'         => 'required|numeric|greater_than[0]',
            'delivery_days' => 'required|is_natural_no_zero',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $sellerId = $this->getSellerId();
        $title    = $this->request->getPost('title');
        $slug     = url_title($title, '-', true) . '-' . time();

        // Upload thumbnail
        $thumbnailName = null;
        $thumbnail     = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            $thumbnailName = $thumbnail->getRandomName();
            $thumbnail->move(ROOTPATH . 'public/uploads/thumbnails', $thumbnailName);
        }

        $this->serviceModel->insert([
            'seller_id'      => $sellerId,
            'category_id'    => $this->request->getPost('category_id'),
            'title'          => $title,
            'slug'           => $slug,
            'description'    => $this->request->getPost('description'),
            'price'          => $this->request->getPost('price'),
            'delivery_days'  => $this->request->getPost('delivery_days'),
            'revision_count' => $this->request->getPost('revision_count') ?? 1,
            'thumbnail'      => $thumbnailName,
            'tags'           => $this->request->getPost('tags'),
            'status'         => 'pending', // Menunggu approve admin
        ]);

        return redirect()->to('/seller/jasa')->with('success', 'Jasa berhasil ditambahkan! Menunggu persetujuan admin.');
    }

    public function jasaEdit($id)
    {
        $sellerId = $this->getSellerId();
        $service  = $this->serviceModel->where('id', $id)->where('seller_id', $sellerId)->first();

        if (!$service) {
            return redirect()->to('/seller/jasa')->with('error', 'Jasa tidak ditemukan.');
        }

        return view('seller/jasa_form', [
            'title'      => 'Edit Jasa',
            'categories' => $this->categoryModel->where('is_active', 1)->findAll(),
            'service'    => $service,
        ]);
    }

    public function jasaUpdate($id)
    {
        $sellerId = $this->getSellerId();
        $service  = $this->serviceModel->where('id', $id)->where('seller_id', $sellerId)->first();

        if (!$service) {
            return redirect()->to('/seller/jasa')->with('error', 'Jasa tidak ditemukan.');
        }

        $data = [
            'category_id'    => $this->request->getPost('category_id'),
            'title'          => $this->request->getPost('title'),
            'description'    => $this->request->getPost('description'),
            'price'          => $this->request->getPost('price'),
            'delivery_days'  => $this->request->getPost('delivery_days'),
            'revision_count' => $this->request->getPost('revision_count'),
            'tags'           => $this->request->getPost('tags'),
        ];

        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid() && !$thumbnail->hasMoved()) {
            $newName = $thumbnail->getRandomName();
            $thumbnail->move(ROOTPATH . 'public/uploads/thumbnails', $newName);
            $data['thumbnail'] = $newName;
        }

        $this->serviceModel->update($id, $data);
        return redirect()->to('/seller/jasa')->with('success', 'Jasa berhasil diperbarui!');
    }

    public function jasaDelete($id)
    {
        $sellerId = $this->getSellerId();
        $this->serviceModel->where('id', $id)->where('seller_id', $sellerId)->delete();
        return redirect()->to('/seller/jasa')->with('success', 'Jasa berhasil dihapus.');
    }

    // ──────────────────────────────────────────────
    // PESANAN
    // ──────────────────────────────────────────────
    public function pesananList()
    {
        $sellerId = $this->getSellerId();
        $status   = $this->request->getGet('status') ?? '';
        $pesanan  = $this->orderItemModel->getBySeller($sellerId, $status);

        return view('seller/pesanan_list', [
            'title'   => 'Pesanan Masuk',
            'pesanan' => $pesanan,
            'status'  => $status,
        ]);
    }

    public function pesananDetail($id)
    {
        $sellerId = $this->getSellerId();
        $item     = $this->orderItemModel->getDetailBySeller($id, $sellerId);

        if (!$item) {
            return redirect()->to('/seller/pesanan')->with('error', 'Pesanan tidak ditemukan.');
        }

        return view('seller/pesanan_detail', [
            'title' => 'Detail Pesanan',
            'item'  => $item,
        ]);
    }

    public function pesananUpdateStatus()
    {
        $id     = $this->request->getPost('order_item_id');
        $status = $this->request->getPost('status');
        $allowed = ['confirmed', 'in_progress', 'delivered'];

        if (!in_array($status, $allowed)) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $sellerId = $this->getSellerId();
        $item     = $this->orderItemModel->where('id', $id)->where('seller_id', $sellerId)->first();

        if (!$item) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
        }

        $this->orderItemModel->update($id, [
            'status'      => $status,
            'note_seller' => $this->request->getPost('note_seller'),
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function pesananUploadFile()
    {
        $id       = $this->request->getPost('order_item_id');
        $sellerId = $this->getSellerId();
        $item     = $this->orderItemModel->where('id', $id)->where('seller_id', $sellerId)->first();

        if (!$item) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
        }

        $file = $this->request->getFile('delivery_file');
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid.');
        }

        $newName = $file->getRandomName();
        $file->move(ROOTPATH . 'public/uploads/deliveries', $newName);

        $this->orderItemModel->update($id, [
            'delivery_file' => $newName,
            'status'        => 'delivered',
        ]);

        return redirect()->back()->with('success', 'File hasil kerja berhasil diupload.');
    }

    // ──────────────────────────────────────────────
    // Earnings
    // ──────────────────────────────────────────────
    public function earnings()
    {
        $sellerId = $this->getSellerId();

        return view('seller/earnings', [
            'title'         => 'Pendapatan',
            'monthly_data'  => $this->orderItemModel->getMonthlyRevenue($sellerId),
            'total_revenue' => $this->orderItemModel->getTotalRevenue($sellerId),
        ]);
    }

    public function profile()
    {
        $userId  = session()->get('user_id');
        $seller  = $this->sellerModel->where('user_id', $userId)->first();
        return view('seller/profile', ['title' => 'Profil Seller', 'seller' => $seller]);
    }

    public function profileUpdate()
    {
        $userId = session()->get('user_id');
        $seller = $this->sellerModel->where('user_id', $userId)->first();

        $this->sellerModel->update($seller['id'], [
            'university'    => $this->request->getPost('university'),
            'major'         => $this->request->getPost('major'),
            'bio'           => $this->request->getPost('bio'),
            'portfolio_url' => $this->request->getPost('portfolio_url'),
        ]);

        return redirect()->back()->with('success', 'Profil seller berhasil diperbarui.');
    }
}
