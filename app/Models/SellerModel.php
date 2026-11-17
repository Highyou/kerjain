<?php
namespace App\Models;
use CodeIgniter\Model;
 
class SellerModel extends Model
{
    protected $table         = 'sellers';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['user_id','university','major','bio','portfolio_url','rating_avg','total_orders','is_verified'];
    protected $useTimestamps  = true;
}