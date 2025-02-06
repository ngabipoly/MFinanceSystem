<?php
namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'TransactionID';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $allowedFields = [
        'TransactionNumber',
        'AccountID',
        'TransactionTypeID',
        'TransactionAmount',
        'TransactionDate',
        'TransactionMethodID',
        'TransActorName',
        'TransactorId',
        'TransactorPhone',
        'TransactionDescription',
        'TransactionStatus',
        'createdBy',
        'createdAt',
        'updatedBy',
        'updatedAt'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'createdAt';
    protected $updatedField = 'UpdatedAt';
    protected $dateFormat = 'datetime';

    public function getTransactions($periodStart = null, $periodEnd=null,  $transactionID=null, $accountID=null) {
        // Get the transaction details from the database based on the transaction ID
        // join the transactionmethods table to get the transaction method name
        $builder = $this->db->table("$this->table t");
        $builder->select('t.TransactionID, t.TransactionNumber,t.TransactionDescription, t.TransactionStatus, t.TransActorName, t.TransactorId, t.TransactorPhone, t.TransactionDate, if(tt.TypeCategory = "CRD", t.TransactionAmount, 0 - t.TransactionAmount) as TransactionAmount, tm.TransactionMethodName, tm.MethodDescription, tt.TransactionTypeName, tt.DocumentTitle');
        $builder->join('transactionmethods tm', 'tm.TransactionMethodID = t.TransactionMethodID', 'left');
        $builder->join('transactiontypes tt', 'tt.TransactionTypeID = t.TransactionTypeID', 'left');
        if($transactionID){
            $builder->where('t.TransactionID', $transactionID);
        }
        if($periodStart!=null && $periodEnd !=null){
            $builder->where('t.TransactionDate >=', $periodStart);
            $builder->where('t.TransactionDate <=', $periodEnd);
        }
        if($accountID){
            $builder->where('t.AccountID', $accountID);
        }
        
        $results = $builder->get()->getResult();
        return $results;
    }
}