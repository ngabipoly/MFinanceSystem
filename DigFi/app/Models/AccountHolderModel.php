<?php
namespace App\Models;
use CodeIgniter\Model;

class AccountHolderModel extends Model
{
    protected $table = 'accountholders';
    protected $primaryKey = 'AccountHolderID';
    protected $allowedFields = ['AccountID','CustomerID','CreatedAt','UpdatedAt','createdBy','lastUpdatedBy','DeletedAt','DeletedBy','Deleted'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'CreatedAt';
    protected $updatedField  = 'UpdatedAt';
    protected $deletedField  = 'Deleted';

    public function getAccountHolders($accountID)
    {
        $builder = $this->db->table('accountholders');
        $builder->select('accountholders.*, concat_ws(" ", cust.FirstName, cust.MiddleName, cust.LastName) CustomerName, cust.ClientID,IDNumber,IdType,DateOfBirth,CustomerPhoto,Gender,PhoneNumber,Email,Address');
        $builder->join('clients cust', 'cust.ClientID = accountholders.CustomerID', 'left');
        $builder->where('accountholders.AccountID', $accountID);
        $builder->where('accountholders.Deleted !=', 1);
        $query = $builder->get();
        return $query->getResult();
    }
}