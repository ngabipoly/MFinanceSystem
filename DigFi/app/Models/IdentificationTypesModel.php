<?php
namespace App\Models;
use CodeIgniter\Model;

class IdentificationTypesModel extends Model
{

    protected $table = 'idtypes';
    protected $primaryKey = 'IDTypeID';
    protected $allowedFields = ['IDTypeName', 'TypeCategory','IdPattern','IdNumberLength','IDTypeDescription','CreatedAt','CreatedBy','UpdatedAt','UpdatedBy','DeletedAt','DeletedBy'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'CreatedAt';
    protected $updatedField  = 'UpdatedAt';
    protected $deletedField  = 'DeletedAt';

    public function idTypeList(){
        try {
            $db      = \Config\Database::connect();
            $builder = $db->table('idtypes idt');
            $builder->select("idt.*, concat_ws(' ',u.FirstName,u.MiddleName,u.LastName) as createdByName");
            $builder->join("app_users u","u.UserID=idt.createdBy","left");
            $builder->where('idt.DeletedAt IS NULL');
            $results = $builder->get()->getResult();    
            return $results;
        } catch (\Exception $e) {
            //throw $th;
        }
    }

}