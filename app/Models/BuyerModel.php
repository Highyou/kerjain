<?php
namespace App\Models;
use CodeIgniter\Model;
 
class BuyerModel extends Model
{
    protected $table         = 'buyers';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['user_id','address'];
    protected $useTimestamps  = true;
}