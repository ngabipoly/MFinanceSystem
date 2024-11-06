<?php
namespace App\Models;
use CodeIgniter\Model;

class DistrictModel extends Model
{
    protected $table = 'loc_districts';
    protected $primaryKey = 'DistrictID';
    protected $allowedFields = ['DistrictName', 'RegionID','SubRegionID'];
    protected $useTimestamps = true;
    protected $createdField  = 'CreatedAt';
    protected $updatedField  = 'UpdatedAt';
    protected $returnType = 'object';

    public function getDistricts($districtID = null)
    {
        $db=$this->db;
        $builder = $db->table('loc_districts ld');
        $builder->select('ld.*, r.RegionName, sr.SubRegionName');
        $builder->join('loc_regions r', 'r.RegionID = ld.RegionID', 'left');
        $builder->join('locsubregions sr', 'sr.SubRegionID = ld.SubRegionID', 'left');
        if ($districtID) {
            $builder->where('ld.DistrictID', $districtID);
        }
        $builder->orderBy('ld.DistrictName', 'ASC');
        $query = $builder->get();
        return $query->getResult();
    }

}