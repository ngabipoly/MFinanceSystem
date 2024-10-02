<?php
namespace App\Models;
use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table = 'customeraccounts';
    protected $primaryKey = 'AccountId';
    protected $allowedFields = ['AccountNature', 'AccountNumber', 'AccountType', 'AccountName', 'AccountStatus', 'AccountDescription'];
    protected $useTimestamps = true;
    protected $createdField  = 'CreatedAt';
    protected $updatedField  = 'UpdatedAt';
    protected $softDelete = true;
    protected $deletedField  = 'DeletedAt';
}