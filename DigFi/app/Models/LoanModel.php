<?php
namespace App\Models;
use CodeIgniter\Model;

class LoanModel extends Model
{
    protected $table = 'loans';
    protected $primaryKey = 'LoanID';
    protected $allowedFields = ['ClientID', 'ProductID', 'PrincipalAmount', 'InterestRate', 'TermMonths', 'StartDate', 'EndDate', 'Status', 'CreatedBy', 'CreatedAt', 'UpdatedBy', 'UpdatedAt','ApprovedBy','ApprovedDate','RejectedBy','RejectedDate'];


    public function getLoans()
    {
        $builder = $this->db->table('loans ln');
        $builder->select('ln.*, concat_ws(" ",FirstName, MiddleName, LastName) as CustomerName, cs.ClientID, pr.ProductName');
        $builder->join('clients cs', 'cs.ClientID = ln.ClientID', 'left');
        $builder->join('loanproducts pr', 'pr.ProductID = ln.ProductID', 'left');
        $query = $builder->get();
        return $query->getResult();
    }
}