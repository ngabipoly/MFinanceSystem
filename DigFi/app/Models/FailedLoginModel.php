<?php
namespace App\Models;
use CodeIgniter\Model;

class FailedLoginModel extends Model
{
    protected $table = 'app_failedlogin';
    protected $primaryKey = 'FailedLoginId';
    protected $allowedFields = ['user_code','IPAddress'];
    protected $useTimestamps = true;
    protected $createdField  = 'FailedDate';
}