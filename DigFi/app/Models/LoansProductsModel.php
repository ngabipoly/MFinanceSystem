<?php
namespace App\Models;
use CodeIgniter\Model;

class LoansProductsModel extends Model
{   

    protected $table = 'loanproducts';
    protected $primaryKey = 'ProductId';
    protected $allowedFields = ['ProductId','ProductName', 'Description','InterestRate','InterestRateType','MinAmount','MaxAmount','MaxTermMonths','MinTermMonths','CreatedAt','CreatedBy','UpdatedAt','UpdatedBy','DeletedAt','DeletedBy','CalculationMethod','CalculationFrequency','ProductStatus','RetiredAt','RetiredReason','RetiredBy'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'CreatedAt';
    protected $updatedField  = 'UpdatedAt';
    protected $deletedField  = 'DeletedAt';

    public function productList(){
        try {
            $db      = \Config\Database::connect();
            $builder = $db->table('loanproducts p');
            $builder->select("p.*");
            $builder->join('tb_roles r',"r.role_id=u.role_id");
            $builder->join('tb_location l',"l.location_id=u.location");
            $results = $builder->get()->getResult();    
            return $results;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}