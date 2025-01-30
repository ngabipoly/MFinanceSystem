<?php
namespace App\Models;
use CodeIgniter\Model;

class OrganizationModel extends Model{
    protected $table = 'org_settings';
    protected $primaryKey = 'AppID';
    protected $allowedFields = ['OrgName', 'orgLogo', 'orgAddress', 'orgPhoneNumber', 'orgEmail', 'orgWebsite', 'accountId','AppID'];
    protected $returnType = 'object';
}