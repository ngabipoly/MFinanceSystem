<?php
namespace App\Models;
use CodeIgniter\Model;

class TransactionTypesModel extends Model
{

    protected $table = 'transactiontypes';
    protected $primaryKey = 'TransactionTypeID';
    protected $allowedFields = ['TransactionTypeName','TypeCategory','Suffix','TypeDescription','CreatedAt','CreatedBy','UpdatedAt','UpdatedBy','DeletedAt','DeletedBy','Deleted','lastUpdatedBy'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'CreatedAt';
    protected $updatedField  = 'UpdatedAt';
    protected $deletedField  = 'DeletedAt';

    public function transactionTypeList(){
        try {
            $db      = \Config\Database::connect();
            $builder = $db->table('transactiontypes tt');
            $builder->select("tt.*, concat_ws(' ',u.FirstName,u.MiddleName,u.LastName) as createdByName");
            $builder->join("app_users u","u.UserID=tt.createdBy","left");
            $builder->where('tt.DeletedAt IS NULL');
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