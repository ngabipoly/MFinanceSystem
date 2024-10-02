<?php
namespace App\Models;
use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table = 'app_menus';
    protected $primaryKey = 'MenuId';
    protected $allowedFields = ['MenuId', 'MenuName', 'MenuDescription', 'MenuIcon', 'MenuUrl','MenuOrder', 'MenuStatus', 'MenuGroup', 'MenuType',  'MenuGroupIcon'];
}