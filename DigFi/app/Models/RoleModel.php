<?php
namespace App\Models;
use CodeIgniter\Model;
class RoleModel extends Model
{
    protected $table = 'app_roles';
    protected $primaryKey = 'RoleId';
    protected $allowedFields = ['RoleId', 'StartDate', 'EndDate', 'RoleName', 'Description', 'Deleted', 'CreatedBy', 'UpdatedBy', 'DeletedBy'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'CreatedAt';
    protected $updatedField  = 'UpdatedAt';
    protected $deletedField  = 'DeletedDate';
}