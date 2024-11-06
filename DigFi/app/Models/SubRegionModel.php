<?php
namespace App\Models;
use CodeIgniter\Model;

class SubRegionModel extends Model
{
    protected $table = 'locsubregions';
    protected $primaryKey = 'SubRegionID';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['SubRegionID', 'RegionID', 'SubRegionName'];
    protected $useTimestamps = true;
    protected $returnType = 'object';
}