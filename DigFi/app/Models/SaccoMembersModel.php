<?php
namespace App\Models;
use CodeIgniter\Model;

class SaccoMembersModel extends Model
{
    protected $table = 'saccomembers';
    protected $primaryKey = 'SaccoMemberID';

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'CreatedAt';
    protected $updatedField  = 'UpdatedAt';
    protected $deletedField  = 'DeleteDate';
    protected $returnType = 'object';

    protected $validationRules = [
        'MemberFirstName' => 'required|min_length[3]',
        'MemberLastName' => 'required|min_length[3]',
        'MemberPhoneNumber' => 'required|min_length[9]',
        'MemberIDType' => 'required',
        'MemberIDNumber'=> 'required',
        'SaccoID' => 'required',
        'Gender' => 'required',
        'MemberDoB' => 'required',
        'Occupation' => 'required',
        'KinAddress' => 'required',
        'KinPhone' => 'required',
        'Relation' => 'required',
        'MemberAddress' => 'required'  
    ];

    protected $validationMessages = [
        'MemberFirstName' => [
            'required' => 'Please Provide Member first Name',
            'min_length' => 'Member first Name must be at least 3 characters long'
        ],
        'MemberLastName' => [
            'required' => 'Please Provide Member Last Name',
            'min_length' => 'Member Last Name must be at least 3 characters long'
        ],
        'MemberPhoneNumber' => [
            'required' => 'Please Provide a Member Phone number',
            'min_length' => 'Member Phone Number must be at least 9 characters long'
        ],
        'MemberIDNumber' => [
            'required' => 'Please Provide Member ID Number'
        ],
        'SaccoID' => [
            'required' => 'Sacco Id was not populated, please notify Admin'
        ],
        'Gender' => [
            'required' => 'Please Specify Member Gender'
        ],
        'MemberDoB' => [
            'required' => 'Please Select Member Date of Birth'
        ],
        'Occupation' => [
            'required' => 'Please Specify a Member Occupation'
        ],
        'KinAddress' => [
            'required' => 'Please Provide Member Next of Kin Address'
        ],
        'KinPhone' => [
            'required' => 'Please Provide Member Next of Kin Phone Number'
        ],
        'Relation' => [
            'required' => 'Please Specify Relationship with Next of Kin '
        ],
        'MemberAddress' => [
            'required' => 'Please Provide Member Address'
        ]
    ];

    protected $allowedFields = [
        'SaccoMemberID',
        'SaccoID',
        'MemberFirstName',
        'MemberLastName',
        'MemberEmail',
        'MemberStatus',
        'MemberPhoto',
        'MemberPhoneNumber',
        'MemberIDType',
        'MemberIDNumber',
        'lastUpdatedBy',
        'Occupation',
        'MemberAddress',
        'Gender',
        'MemberDoB',
        'NextOfKin',
        'KinAddress',
        'KinPhone',
        'Relation',
        'createdBy',
        'CreatedAt',
        'UpdatedAt',
        'Deleted',
        'DeletedBy',
        'DeleteDate'
    ];
    protected $beforeInsert = ['setCreatedBy'];
    protected $beforeUpdate = ['setLastUpdatedBy'];
    
    protected function setCreatedBy(array $data) {
        $data['data']['createdBy'] = session()->get('user_id');
        return $data;
    }
    
    protected function setLastUpdatedBy(array $data) {
        $data['data']['lastUpdatedBy'] = session()->get('user_id');
        return $data;
    }
    
}