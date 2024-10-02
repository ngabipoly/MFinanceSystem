<?php
namespace App\Models;
use CodeIgniter\Model;

class AccountModel extends Model
{
    protected $table = 'customeraccounts';
    protected $primaryKey = 'AccountID';
    protected $allowedFields = ['AccountNature', 'AccountName', 'AccountNumber', 'AccountType', 'Balance', 'AccountStatus', 'CreatedAt', 'createdBy', 'UpdatedAt', 'lastUpdatedBy', 'Deleted','DeletedBy','DeletedOn'];
}