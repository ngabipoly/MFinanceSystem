<?php
namespace App\Models;
use CodeIgniter\Model;

class RoleRightsModel extends Model
{
    protected $table = 'app_role_menus';
    protected $primaryKey = 'RoleMenuId';
    protected $allowedFields = ['AssignmentType', 'RoleID', 'MenuID', 'StartDate', 'EndDate', 'MenuAccess', 'CreatedBy',  'UpdatedBy',  'DeletedBy','DeletionDate'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'CreationDate';
    protected $updatedField  = 'UpdatedAt';
    protected $deletedField  = 'DeletionDate';
}