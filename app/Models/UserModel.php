<?php
namespace App\Models;
use CodeIgniter\Model;
 
class UserModel extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['name','email','password','role','avatar','phone','is_active'];
    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
 
    // Sembunyikan password dari hasil query
    protected $hiddenFields = ['password'];
}