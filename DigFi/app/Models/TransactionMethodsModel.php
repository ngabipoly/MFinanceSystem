<?php
namespace App\Models;
use CodeIgniter\Model;

class TransactionMethodsModel extends Model
{
    protected $table = 'transactionmethods';
    protected $primaryKey = 'TransactionMethodID';
    protected $allowedFields = ['TransactionMethodName','ProviderListApplicable', 'MethodDescription', 'Deleted', 'CreatedBy', 'lastUpdatedBy', 'UpdatedAt', 'DeletedBy' , 'DeletedOn'];
    protected $useTimestamps = true;
    protected $createdField  = 'CreatedAt';
    protected $updatedField  = 'UpdatedAt';
    protected $deletedField  = 'DeletedOn';

    public function transactionMethodsList(){
        try {
            $db      = \Config\Database::connect();
            $builder = $db->table('transactionmethods tm');
            $builder->select("tm.*, concat_ws(' ',u.FirstName,u.MiddleName,u.LastName) as createdByName");
            $builder->join("app_users u","u.UserID=tm.CreatedBy","left");
            $builder->where('tm.Deleted=0');
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