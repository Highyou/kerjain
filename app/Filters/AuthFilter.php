<?php
// app/Filters/AuthFilter.php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Belum login → redirect ke login
        if (!$session->has('user_id')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek role jika argumen role diberikan
        if ($arguments) {
            $requiredRole = $arguments[0];
            $userRole     = $session->get('role');

            if ($userRole !== $requiredRole) {
                if (in_array($userRole, ['admin', 'seller', 'buyer'], true)) {
                    return redirect()->to("/{$userRole}/dashboard")
                                     ->with('error', 'Akses ditolak.');
                }

                return redirect()->to('/auth/login')->with('error', 'Silakan login kembali.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // kosong
    }
}
