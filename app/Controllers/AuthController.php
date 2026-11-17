<?php
// app/Controllers/AuthController.php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SellerModel;
use App\Models\BuyerModel;

class AuthController extends BaseController
{
    protected UserModel  $userModel;
    protected SellerModel $sellerModel;
    protected BuyerModel  $buyerModel;

    public function __construct()
    {
        $this->userModel   = new UserModel();
        $this->sellerModel = new SellerModel();
        $this->buyerModel  = new BuyerModel();
    }

    // ──────────────────────────────────────────────
    // Halaman Login
    // ──────────────────────────────────────────────
    public function loginPage()
    {
        if (session()->has('user_id')) {
            return $this->redirectByRole();
        }
        return view('auth/login');
    }

    // ──────────────────────────────────────────────
    // Proses Login
    // ──────────────────────────────────────────────
    public function loginProcess()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('email', $email)->where('is_active', 1)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }

        // Set session
        session()->set([
            'user_id'   => $user['id'],
            'name'      => $user['name'],
            'email'     => $user['email'],
            'role'      => $user['role'],
            'avatar'    => $user['avatar'],
            'logged_in' => true,
        ]);

        return $this->redirectByRole();
    }

    // ──────────────────────────────────────────────
    // Halaman Register
    // ──────────────────────────────────────────────
    public function registerPage()
    {
        if (session()->has('user_id')) {
            return $this->redirectByRole();
        }
        return view('auth/register');
    }

    // ──────────────────────────────────────────────
    // Proses Register
    // ──────────────────────────────────────────────
    public function registerProcess()
    {
        $rules = [
            'name'      => 'required|min_length[3]|max_length[100]',
            'email'     => 'required|valid_email|is_unique[users.email]',
            'password'  => 'required|min_length[8]',
            'confirm'   => 'required|matches[password]',
            'role'      => 'required|in_list[buyer,seller]',
        ];

        $messages = [
            'email' => ['is_unique' => 'Email sudah terdaftar.'],
            'confirm' => ['matches' => 'Konfirmasi password tidak cocok.'],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $role = $this->request->getPost('role');

        // Simpan user
        $userId = $this->userModel->insert([
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'role'     => $role,
        ]);

        // Buat profil seller / buyer
        if ($role === 'seller') {
            $this->sellerModel->insert([
                'user_id'    => $userId,
                'university' => $this->request->getPost('university'),
                'major'      => $this->request->getPost('major'),
            ]);
        } else {
            $this->buyerModel->insert(['user_id' => $userId]);
        }

        return redirect()->to('/auth/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // ──────────────────────────────────────────────
    // Logout
    // ──────────────────────────────────────────────
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')->with('success', 'Berhasil logout.');
    }

    // ──────────────────────────────────────────────
    // Helper redirect by role
    // ──────────────────────────────────────────────
    private function redirectByRole()
    {
        $role = session()->get('role');
        return redirect()->to("/{$role}/dashboard");
    }
}
