<?php
namespace App\Models;
use CodeIgniter\Model;

class CustomerAccountModel extends Model
{
    protected $table = 'customeraccounts';
    protected $primaryKey = 'AccountID';
    protected $allowedFields = ['AccountNature', 'AccountName', 'AccountNumber', 'AccountType', 'Balance', 'AccountStatus', 'CreatedAt', 'createdBy', 'UpdatedAt', 'lastUpdatedBy', 'Deleted','DeletedBy','DeletedOn'];
    protected $useTimestamps = true;
    protected $createdField  = 'CreatedAt';
    protected $updatedField  = 'UpdatedAt';
    protected $softDelete = true;
    protected $deletedField  = 'DeletedAt';

    public function getAccounts()
    {
        $builder = $this->db->table("$this->table cs");
        $builder->select("cs.*, at.AccountTypeName, an.AccountNatureName");
        $builder->join('accounttypes at', 'at.AccountTypeID = cs.AccountType', 'left');
        $builder->join('accountnatures an', 'an.AccountNatureID = cs.AccountNature', 'left');
        $builder->where('cs.Deleted', 0);
        $builder->orderBy('cs.AccountName', 'ASC');
        $results = $builder->get()->getResult();
        return $results;
    }  
}