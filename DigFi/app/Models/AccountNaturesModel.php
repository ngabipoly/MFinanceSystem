<?php
namespace App\Models;
use CodeIgniter\Model;

class AccountNaturesModel extends model{
    protected $table = 'accountnatures';
    protected $primaryKey = 'AccountNatureID';
    protected $allowedFields = ['AccountNatureName', 'Description','lastUpdatedBy','maxHolders','MinHolders','deletedAt','deletedBy','createdBy','CreatedAt','CreatedBy','UpdatedAt'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'CreatedAt';
    protected $updatedField  = 'UpdatedAt';
    protected $deletedField  = 'DeletedAt';
}