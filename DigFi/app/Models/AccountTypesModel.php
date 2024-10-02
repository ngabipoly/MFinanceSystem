<?php
namespace App\Models;
use CodeIgniter\Model;

class AccountTypesModel extends model{
    protected $table = 'accounttypes';
    protected $primaryKey = 'AccountTypeID';
    protected $allowedFields = ['AccountTypeName', 'AccountNature','Description','lastUpdatedBy','OpeningBal','MinBal','deletedAt','deletedBy','createdBy','CreatedAt','CreatedBy','UpdatedAt'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'CreatedAt';
    protected $updatedField  = 'UpdatedAt';
    protected $deletedField  = 'DeletedAt';

    public function accountProductList(){
        try {
            $db      = \Config\Database::connect();
            $builder = $db->table('accounttypes at');
            $builder->select("at.*,AccountNatureName");
            $builder->join('accountnatures an',"at.AccountNature=an.AccountNatureID","left");
            $results = $builder->get()->getResult();    
            return $results;
        } catch (\exception $e) {
            //throw $th;
        }
    }
}