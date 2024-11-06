<?php
namespace App\Models;
use CodeIgniter\Model;

class GroupModel extends Model
{
    protected $table = 'groupentities';
    protected $primaryKey = 'GroupID';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $allowedFields = ['GroupID', 'GroupName', 'GroupDescription', 'GroupStatus','GroupDistrict', 'Deleted', 'CreatedBy','CreatedDate','lastUpdatedBy','UpdatedAt'];
    protected $useTimestamps = true;
    protected $createdField  = 'CreatedAt';
    protected $updatedField  = 'UpdatedAt';
    protected $deletedField  = 'DeletedAt';

    public function getGroups(){
        $builder = $this->db->table('groupentities ge');
        $builder->select('ge.*, ld.DistrictID, ld.DistrictName, lsr.SubRegionName, lr.RegionID, lsr.SubRegionID, lr.RegionName');
        $builder->join('loc_districts ld', 'ld.DistrictID = ge.GroupDistrict', 'left');
        $builder->join('locsubregions lsr', 'lsr.SubRegionID = ld.SubRegionID', 'left');
        $builder->join('loc_regions lr', 'lr.RegionID = ld.RegionID', 'left');
        $builder->where('ge.DeletedAt IS NULL OR ge.Deleted=0');
        $query = $builder->get();
        return $query->getResult();
    }
}