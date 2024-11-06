<?php 
namespace App\Models;
use CodeIgniter\Model;
class RegionModel extends Model{
    protected $table = 'loc_regions';
    protected $primaryKey = 'RegionID';
    protected $returnType = 'object';
    protected $useTimestamps = true;
    protected $allowedFields = ['RegionName'];
}