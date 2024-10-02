<?php
namespace App\Models;
use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table = 'clients';
    protected $primaryKey = 'ClientId';
    protected $allowedFields = ['ClientId', 'FirstName', 'LastName', 'MiddleName', 'Email', 'PhoneNumber', 'CreatedBy','DateOfBirth', 'Address', 'CustomerSignature', 'AnnualIncome', 'IDNumber', 'IdType', 'Gender',  'Deleted','IDFrontFace','IDBackFace','Occupation','CreatedAt','UpdatedAt','DeletedAt','DeletedBy','NextOfKin','NextOfKinRelationship','NextOfKinPhoneNumber','NextOfKinAddress','CreatedBy','MaritalStatus','DeletedBy','Deleted'];
    protected $useTimestamps = true;
    protected $createdField  = 'CreatedAt';
    protected $updatedField  = 'UpdatedAt';
    protected $softDelete = true;
    protected $deletedField  = 'DeletedAt';

    public function customerList(){
        $builder = $this->db->table('clients cs');
        $builder->select('cs.*, concat_ws(" ",FirstName, MiddleName, LastName) as Names,TIMESTAMPDIFF(YEAR, DateOfBirth, CURDATE()) AS Age');
        $builder->where('Deleted',0);
        $builder->orderBy('ClientId', 'ASC');
        $results = $builder->get()->getResult();
        return $results;
    }
}