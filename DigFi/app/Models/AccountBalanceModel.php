<?php 

namespace App\Models;

use CodeIgniter\Model;

class AccountBalanceModel extends Model
{
    protected $table = 'account_balances';
    protected $primaryKey = 'id';
    protected $allowedFields = ['AccountID','EntityID', 'EntityType', 'balance','LastUpdated'];
}