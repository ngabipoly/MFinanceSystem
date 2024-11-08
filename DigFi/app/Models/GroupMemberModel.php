<?php
namespace App\Models;
use CodeIgniter\Model;

class GroupMemberModel extends Model
{
    protected $table = 'groupmembers';
    protected $allowedFields = ['GroupID', 'MemberID','GroupMemberStatus','MemberName','MemberEmail','MemberPhoneNumber','DemNetID','MemberPhoto','LastUpdatedBy', 'CreatorType','Deleted','DeletedBy','CreatedBy','memberAddress','MemberDob','MemberGender'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'CreatedAt';
    protected $updatedField  = 'UpdatedAt';
    protected $deletedField  = 'DeletedAt';
    protected $primaryKey = 'GroupMemberID';
    protected $returnType = 'object';
}