<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'app_users';
    protected $primaryKey = 'UserId';
    protected $allowedFields = ['UserId', 'FirstName', 'LastName', 'MiddleName', 'Email', 'PhoneNumber', 'RoleId', 'AccountStatus', 'CreatedBy', 'Username', 'SecretHash','UserCode','UserPhoto','LastLogin','FailedLoginAttempts','ResetRequired'];
    protected $useTimestamps = true;
    protected $createdField  = 'CreatedAt';
    protected $updatedField  = 'UpdatedAt';
    protected $deletedField  = 'DeletedAt';
}