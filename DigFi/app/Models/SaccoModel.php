<?php
namespace App\Models;
use CodeIgniter\Model;

class SaccoModel extends Model
{
    protected $table = 'sacco';
    protected $primaryKey = 'SaccoID';

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'CreatedAt';
    protected $updatedField  = 'UpdatedAt';
    protected $deletedField  = 'DeleteDate';
    protected $returnType = 'object';

    protected $validationRules = [
        'SaccoName' => 'required|min_length[3]|is_unique[sacco.SaccoName]',
        'SaccoDescription' => 'required|min_length[10]',
        'LocID' => 'required'
    ];

    protected $validationMessages = [
        'SaccoName' => [
            'required' => 'Please Provide a Sacco Name',
            'is_unique' => 'This Sacco Name Already Exists',
            'min_length' => 'Sacco Name must be at least 3 characters long'
        ],
        'SaccoDescription' => [
            'required' => 'Add a Short  Description of the Sacco',
            'min_length' => 'Sacco Description must be at least 10 characters long'
        ],
        'LocID' => [
            'required' => 'Please Select District Location'
        ]
    ];

    protected $allowedFields = [
        'SaccoName',
        'SaccoDescription',
        'SaccoStatus',
        'SaccoLogo',
        'LocType',
        'LocID',
        'CreatedAt',
        'CreatedBy',
        'UpdatedAt',
        'LastUpdatedBy',
        'Email',
        'Phone',
        'SaccoAddress'
    ];
    protected $beforeInsert = ['setCreatedBy'];
    protected $beforeUpdate = ['setLastUpdatedBy'];
    
    protected function setCreatedBy(array $data) {
        $data['data']['CreatedBy'] = session()->get('user_id');
        return $data;
    }
    
    protected function setLastUpdatedBy(array $data) {
        $data['data']['LastUpdatedBy'] = session()->get('user_id');
        return $data;
    }
    
}