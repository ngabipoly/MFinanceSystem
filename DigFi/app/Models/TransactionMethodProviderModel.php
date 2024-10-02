<?php
namespace App\Models;
use CodeIgniter\Model;

class TransactionMethodProviderModel extends Model
{
    protected $table = 'transactionmethodproviders';
    protected $primaryKey = 'ProviderID';
    protected $allowedFields = ['ProviderName', 'ProviderID', 'ProviderStatus', 'MethodID', 'CreatedBy', 'lastUpdatedBy', 'UpdatedAt', 'DeletedBy' , 'DeletedAt'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'CreatedAt';
    protected $updatedField  = 'UpdatedAt';
    protected $deletedField  = 'DeletedAt';

    public function providerList($method){
        try {
            $db      = \Config\Database::connect();
            $builder = $db->table('transactionmethodproviders tp');
            $builder->select("tp.*, concat_ws(' ',u.FirstName,u.MiddleName,u.LastName) as createdByName");
            $builder->join("app_users u","u.UserID=tp.CreatedBy","left");
            //$builder->where('tp.ProviderStatus<>\'Deleted\'');
            $builder->where('tp.MethodID='.$method);
            $results = $builder->get()->getResult();    
            return $results;
        } catch (\Exception $e) {
            //throw $th;
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }
}