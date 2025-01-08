<?php
namespace App\Controllers;
use App\Models\DistrictModel;
use App\Models\GroupModel;
use App\Models\GroupMemberModel;
use App\Models\SaccoModel;
use App\Models\SaccoMembersModel;
use App\Models\IdentificationTypesModel as idTypesModel;

class Entities extends BaseController{
    protected $user;
    protected $group;
    protected $district;

    public function __construct(){
        $this->user = session()->get('userData');
        $this->group = new GroupModel();
        $this->district = new DistrictModel();
    }

    public function groupIndex(){
        $data = [
            'user' => $this->user,
            'groups' => $this->group->getGroups(),
            'districts' => $this->district->getDistricts(),
            'page' => 'ManageGroup'
        ];
        return view('entities/group-entites', $data);
    }

    public function createGroup(){
        $rules = [
            'group-name' => 'required',
            'group-description' => 'required',
            'group-district' => 'required'
        ];

        $messages = [
            'group-name' => [
                'required' => 'Group Name is required'
            ],
            'group-description' => [
                'required' => 'Group Description is required'
            ],
            'group-district' => [
                'required' => 'Group District is required'
            ]
        ];


        if(!$this->validate($rules, $messages)){
            $messages = $this->validator->getErrors();
            $errorsMessages = nl2br(esc(implode("\n", $messages))) ;
            $data = [
                'status'=> 'error',
                'message' => "<h6><strong>Validation Failed!</strong></h6> \n".$errorsMessages,
                'data' => []
            ]; 
            return json_encode($data);   
        }

        $execMode = $this->request->getPost('exec-mode');
        $responseMessage =[];
        $execute = null;
        $addGroup = true;

        $data = [
            'GroupName' => $this->request->getPost('group-name'),
            'GroupDescription' => $this->request->getPost('group-description'),
            'GroupDistrict' => $this->request->getPost('group-district')
        ];

        if($execMode == 'edit'){
            $addGroup = false;
            $data['GroupID'] = $this->request->getPost('group-id');
            $data['UpdatedAt'] = date('Y-m-d H:i:s');
            $data['UpdatedBy'] = $this->user['UserId'];

            $execute = $this->group->update($data['GroupID'], $data);
        }else{
            $data['CreatedAt'] = date('Y-m-d H:i:s');
            $data['CreatedBy'] = $this->user['UserId'];
            $execute = $this->group->insert($data);
        }        

        if(!$execute){
            $message = ($addGroup) ? 'Failed to add group ' : 'Failed to update group ';
            $data = [
                'status'=> 'error',
                'message' => $message.$this->group->errors(),
                'data' => []
            ]; 
            return json_encode($data);   
        }        

        $message = ($addGroup) ? 'Group added successfully' : 'Group updated successfully';
        $data = [
            'status'=> 'success',
            'message' => $message,
            'data' => $data,
            'redirect' => base_url('entities/groups')
        ]; 
        return json_encode($data);
    }

    public function changeGroupStatus(){
        $rules = [
            'group-new-status' => 'required',
            'group-id' => 'required'
        ];

        $messages = [
            'group-new-status' => [
                'required' => 'Group New Status is not set'
            ],
            'group-id' => [
                'required' => 'Group ID is not set'
            ]
        ];

        if(!$this->validate($rules, $messages)){
            $messages = $this->validator->getErrors();
            $errorsMessages = nl2br(esc(implode("\n", $messages))) ;
            $data = [
                'status'=> 'error',
                'message' => "<h6><strong>Validation Failed!</strong></h6> \n".$errorsMessages,
                'data' => []
            ]; 
            return json_encode($data);   
        }   
            
        $data = [
            'GroupID' => $this->request->getPost('group-id')
        ];

        if($this->request->getPost('group-new-status') == 'Deleted'){
            $data['DeletedAt'] = date('Y-m-d H:i:s');
            $data['DeletedBy'] = $this->user['UserId'];
            $data['Deleted'] = 1;
        } 

        if($this->request->getPost('group-new-status') != 'Deleted'){
            $data['GroupStatus'] = $this->request->getPost('group-new-status');
        }

        $statusUpdate = $this->group->update($data['GroupID'], $data);

        if(!$statusUpdate){
            $data = [
                'status'=> 'error',
                'message' => "<h6>Failed to change group status</h6> \n". $this->group->errors(),
                'data' => []
            ]; 
            return json_encode($data);   
        }

        $data = [
            'status'=> 'success',
            'message' => "<h6>Execution Successful</h6> Group status changed successfully",
            'data' => $data,
            'redirect' => base_url('entities/groups')
        ]; 
        return json_encode($data);
    }  

    public function saccoIndex(){
        $sacco = new SaccoModel();
        $data = [
            'title' => 'Saccos',
            'page' => 'Sacco Listing',
            'saccos' => $sacco->findAll(),        
            'districts' => $this->district->getDistricts(),
        ];
        return view('entities/saccos', $data);
    }

    public function saveSacco(){
        try{
            $sacco = new SaccoModel();
            $execMode = $this->request->getPost('exec-mode');
            $data = [
                'SaccoDescription' => $this->request->getPost('sacco-description'),
                'SaccoStatus' => $this->request->getPost('sacco-status'),
                'LocType' => 'District',
                'LocID' => $this->request->getPost('sacco-district'),
                'Email' => $this->request->getPost('sacco-email'),
                'Phone' => $this->request->getPost('sacco-phone'),
                'SaccoAddress' => $this->request->getPost('sacco-address'),
            ];


            if ($execMode == 'edit') {
                $data['SaccoID'] = $this->request->getPost('sacco-id');
                $data['UpdatedAt'] = date('Y-m-d H:i:s');
                $data['LastUpdateBy'] = $this->user['UserId'];
                $saccoUpdate = $sacco->update($data['SaccoID'], $data);
                //get update statement
                $statement = $sacco->db->getLastQuery();
                log_message('info', $statement);
                if(!$saccoUpdate){
                    $updateError = implode(', ', $sacco->errors());
                    $error = "Error Updating Sacco: ".$data['SaccoID']." - ". $data['SaccoName']."\n".$updateError;
                    log_message('error', $error);
                    throw new \Exception($error);                    
                }
                $returnData = [
                    'status'=>'success',
                    'message' => "<h6>Execution Successful</h6> Sacco Modified Successfully",
                    'data' => $data,
                    'redirect' => base_url('entities/saccos')
                ]; 
                return json_encode($returnData);
            }            
            if($execMode == 'add'){
                $data['SaccoName'] = $this->request->getPost('sacco-name');                
                $data['CreatedAt'] = date('Y-m-d H:i:s');
                $data['CreatedBy'] = $this->user['UserId'];
            }

            if(!$sacco->validate($data)){
                $messages = $sacco->errors();
                $errorsMessages = nl2br(esc(implode("\n", $messages))) ;
                $data = [
                    'status'=> 'error',
                    'message' => "<h6><strong>Validation Failed!</strong></h6> \n".$errorsMessages,
                    'data' => []
                ]; 
                return json_encode($data);   
            } 
            $sacco->insert($data);
            $returnData = [
                'status'=> 'success',
                'message' => "<h6>Execution Successful</h6> Sacco added successfully",
                'data' => $data,
                'redirect' => base_url('entities/saccos')
            ]; 
            return json_encode($returnData);            
        } catch (\Exception $e) {
            $returnData = [
                'status'=> 'error',
                'message' => $e->getMessage(),
                'data' => $data
            ]; 
            return json_encode($returnData);
        }

    }

    public function getSacco(){
        $sacco = new SaccoModel();
        try {
            $saccoId = $this->request->getPost('sacco');
            $saccoData = $sacco->where('SaccoID', $saccoId)->first();
            $data = [
                'status'=> 'success',
                'message' => "Data Fetch Sucessfull",
                'data' => $saccoData
            ];
            return json_encode($data);
        } catch (\Execption $e) {
            $returnData = [
                'status'=> 'error',
                'message' => $e->getMessage(),
                'data' => []
            ]; 
            return json_encode($returnData);
        }
    }

    public function getGroup(){
        $group = new GroupModel();
        try {
            $groupId = $this->request->getPost('group');
            $groupData = $group->where('GroupID', $groupId)->first();
            $returnData = [
                'status'=> 'success',
                'message' => "Data Fetch Sucessfull",
                'data' => $groupData
            ];
            return json_encode($returnData);
        } catch (\Execption $e) {
            $error = $e->getMessage();
            $returnData = [
                'status'=> 'error',
                'message' => $error,
                'data' => []
            ];
            return json_encode($returnData);
        }
    }

    public function changeSaccoStatus(){
        try {
                $saccoId = $this->request->getPost('saccos-id');
                $newStatus = $this->request->getPost('sacco-new-status');
                $oldStatus = $this->request->getPost('sacco-status');
                $data = [
                    'SaccoID' => $saccoId ,
                    'SaccoStatus' => $newStatus
                ];
        
                $sacco = new SaccoModel();
                $statusUpdate = $sacco->update($data['SaccoID'], $data);
                if(!$statusUpdate){
                    $data = [
                        'status'=> 'error',
                        'message' => "<h6>Failed to change Sacco status</h6> \n". $sacco->errors(),
                        'data' => []
                    ]; 
                    return json_encode($data);   
                }
        
                $data = [
                    'status'=> 'success',
                    'message' => "<h6>Execution Successful</h6> Sacco status changed from $oldStatus to $newStatus successfully",
                    'data' => $data,
                    'redirect' => base_url('entities/saccos')
                ]; 
                return json_encode($data);
        } catch (\Exception $e) {
            $data = [
                'status'=> 'error',
                'message' => $e->getMessage(),
                'data' => []
            ]; 
            return json_encode($data);
        }
    }
        

    
    public function getMembers(){
        $entityType = $this->request->uri->getSegment(3);
        $entityId = $this->request->uri->getSegment(4);
        $idTypes = new idTypesModel();
        $types = $idTypes->findAll();
        $data= [
            'idTypes'=>$types
        ];

        if($entityType == 'G'){
            $member = new GroupMemberModel();
            $group = new GroupModel();
            $data['group'] = $group->where('GroupID', $entityId)->first();
            $data['members'] = $member->where('GroupID', $entityId)->findAll();
        }

        if($entityType == 'S'){
            $member = new SaccoMembersModel();
            $sacco = new SaccoModel();
            $data['sacco'] = $sacco->where('SaccoID', $entityId)->first();
            $data['members'] = $member->where('SaccoID', $entityId)->findAll();
        }
        $entityTypeList = ['G'=>'Group', 'S'=>'Sacco'];
        $data['entityType'] = $entityTypeList[$entityType];
        $data['entityId'] = $entityId;
        $data['title'] = "{$entityTypeList[$entityType]} Members";
        return view('entities/members', $data);
    }

    public function memberExists($memberId, $email, $entityId, $entityType)
    {
        try {
            log_message('info', "Checking if member exists with EntityType: $entityType, EntityId: $entityId");
    
            $memberPresent = null;
    
            if ($entityType === 'G') {
                log_message('info', 'Using GroupMemberModel');
                $member = new GroupMemberModel();
    
                // Check for a member in the group by memberId or email
                $memberPresent = $member->where('GroupID', $entityId)
                                        ->groupStart()
                                        ->where('GroupMemberID', $memberId)
                                        ->orWhere('MemberEmail', $email)
                                        ->groupEnd()
                                        ->first();
    
                log_message('debug', 'Group Member Query: ' . $member->getLastQuery());
            }
    
            if ($entityType === 'S') {
                log_message('info', 'Using SaccoMembersModel');
                $member = new SaccoMembersModel();
    
                // Check for a member in the sacco by memberId or email
                $memberPresent = $member->where('SaccoID', $entityId)
                                        ->groupStart()
                                        ->where('MemberID', $memberId)
                                        ->orWhere('Email', $email)
                                        ->groupEnd()
                                        ->first();
    
                log_message('debug', 'Sacco Member Query: ' . $member->getLastQuery());
            }
    
            if ($memberPresent) {
                log_message('info', 'Member found');
                return true;
            }
    
            log_message('info', 'Member not found');
            return false;
        } catch (\Exception $e) {
            log_message('error', 'Error in memberExists: ' . $e->getMessage() . ' at line ' . $e->getLine());
            return false;
        }
    }
    

    public function saveMember(){
        try {
            log_message('debug', 'Entering saveMember function.');

            $entityId = $this->request->getPost('entity-id');
            $entityType = $this->request->getPost('entity-type');
            $execMode = $this->request->getPost('exec-mode');
            $idType = $this->request->getPost('id-type');
            $execMessages = null;

            log_message('debug', 'entityId: ' . $entityId . ', entityType: ' . $entityType . ', execMode: ' . $execMode);

            if (!$this->request->getFile('member-photo') && $execMode != 'edit') {
                log_message('error', 'Missing Member Photo on non-edit mode.');
                $data = [
                    'status' => 'error',
                    'message' => "<h6>Missing Info!</h6> \n Please take/upload Member Photo!",
                    'data' => [
                        $this->request->getPost()
                    ]
                ];
                return json_encode($data);
            }


            // Member photo upload
            $newName = null;
            $file = $this->request->getFile('member-photo');  

            $path = ($entityType == 'G') ? GROUP_MEMBER_PATH : SACCO_MEMBER_PATH;
            log_message('debug', 'File: ' . $file . ', ExecMode: ' . $execMode . ', Path: ' . $path);
            if($execMode == 'add' || ($execMode =='edit' && $file->getName())){ 

                $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/bmp', 'image/webp'];
                if (!in_array($file->getMimeType(), $allowedTypes)) {
                    log_message('error', 'Invalid file type: ' . $file->getMimeType());
                    $data = [
                        'status' => 'error',
                        'message' => "<h6>Invalid File Type!</h6> \n Please select a valid image file.",
                        'data' => [
                            'File Type' => $file->getMimeType()
                        ]
                        ];
                    return json_encode($data);
                }

                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    if(!$file->move($path, $newName)){
                        log_message('error', 'Error Uploading file to destination: ' . $path . $newName);
                        $data = [
                            'status' => 'error',
                            'message' => "<h6>Member Photo Upload Error!</h6> \n Please try again",
                            'data' => ['Destination' => $path]
                        ];
                        return json_encode($data);
                    }
                    log_message('debug', 'Uploaded file moved to ' . $path . $newName);
                }                
            }

            $memberId = ($entityType == 'S') ? $this->request->getPost('member-id'): $this->request->getPost('id-number');

            $memberEmail = ($entityType == 'S') ? $this->request->getPost('member-email'): $this->request->getPost('group-member-email');

            $memberPresent = $this->memberExists($memberId,$memberEmail, $entityId, $entityType);
            log_message('debug', "Member present? " . ($memberPresent ? 'Yes' : 'No'));

            if ($execMode != 'edit' && $memberPresent) {
                log_message('error', 'Member ID already exists.');
                $execMessages = "<h6>Member ID already exists</h6> \n Member with the same ID  or Email already exists";
                $data = [
                    'status' => 'error',
                    'message' => $execMessages,
                    'data' => []
                ];
                return json_encode($data);
            }

            if ($entityType == 'G') {
                $rules = [
                    'id-number' => 'required',
                    'group-member-status' => 'required',
                    'group-member-first-name' => 'required',
                    'group-member-last-name' => 'required',
                    'group-member-gender' => 'required',
                    'group-member-email' => 'required',
                    'group-member-telephone' => 'required',
                    'entity-id' => 'required'
                ];

                $messages = [
                    'id-number' => [
                        'required' => 'Member ID Number is required'
                    ],
                    'group-member-status' => [
                        'required' => 'Member Status is not set'
                    ],
                    'group-member-first-name' => [
                        'required' => 'Member First Name is required'
                    ],
                    'group-member-last-name' => [
                        'required' => 'Member Last Name is required'
                    ],
                    'group-member-gender' => [
                        'required' => 'Member Gender is required'
                    ],
                    'group-member-email' => [
                        'required' => 'Member Email is required'
                    ],
                    'group-member-telephone' => [
                        'required' => 'Member Phone Number is required'
                    ],
                    'entity-id' => [
                        'required' => 'Group ID is not set'
                    ],
                    'member-role' => [
                        'required' => 'Member Role is required'
                    ]
                ];

                if (!$this->validate($rules, $messages)) {
                    log_message('error', 'Validation failed: ' . json_encode($this->validator->getErrors()));
                    $data = [
                        'status' => 'error',
                        'message' => "<h6>Failed to add member</h6> \n" . $this->validator->listErrors(),
                        'data' => [
                            $this->request->getPost()
                        ]
                    ];
                    return json_encode($data);
                }

                $data = [
                    'MemberID' => $this->request->getPost('id-number'),
                    'GroupMemberStatus' => $this->request->getPost('group-member-status'),
                    'MemberName' => $this->request->getPost('group-member-first-name') . ' ' . $this->request->getPost('group-member-last-name'),
                    'MemberEmail' => $this->request->getPost('group-member-email'),
                    'MemberGender' => $this->request->getPost('group-member-gender'),
                    'MemberPhoneNumber' => $this->request->getPost('group-member-telephone'),
                    'MemberDob' => $this->request->getPost('group-member-dob'),
                    'memberAddress' => $this->request->getPost('group-member-address'),
                    'GroupID' => $this->request->getPost('entity-id'),
                    'CreatedAt' => date('Y-m-d H:i:s'),
                    'CreatedBy' => $this->user['UserId']
                ];
                if ($execMode == 'add') {
                    $data['MemberPhoto'] = $newName;
                }

                if ($execMode == 'edit') {
                    $data['GroupMemberID'] = $this->request->getPost('group-member-id');
                    if ($newName) {
                        $data['MemberPhoto'] = $newName;
                    }

                    $data['UpdatedAt'] = date('Y-m-d H:i:s');
                    $data['UpdatedBy'] = $this->user['UserId'];
                }
                
                $member = new GroupMemberModel();

                $execute = $member->save($data);
                if (!$execute) {
                    log_message('error', 'Failed to save member details: ' . json_encode($member->errors()));
                    $returnData = [
                        'status' => 'error',
                        'message' => "<h6>Failed to Save Member Details</h6> \n" . $member->errors(),
                        'data' => $data
                    ];
                    return json_encode($returnData);
                }

                $execMessages = ($execMode == 'edit') ? "<h6>Execution Successful</h6> Member Details updated successfully" : "<h6>Execution Successful</h6> Group Member added successfully";

                $returnData = [
                    'status' => 'success',
                    'message' => $execMessages,
                    'data' => $data,
                    'redirect' => base_url('entities/get-members/' . $entityType . '/' . $entityId)
                ];
                return json_encode($returnData);
            }

            if ($entityType == 'S') {
                $member = new SaccoMembersModel();

                $data = [
                    'MemberIDNumber' => $this->request->getPost('member-id-number'),
                    'MemberIDType' => $idType,
                    'MemberStatus' => $this->request->getPost('member-status'),
                    'MemberFirstName' => $this->request->getPost('first-name'),
                    'MemberLastName' => $this->request->getPost('last-name'),
                    'MemberEmail' => $this->request->getPost('member-email'),
                    'MemberPhoneNumber' => $this->request->getPost('member-phone-number'),
                    'Gender' => $this->request->getPost('gender'),
                    'MemberDoB' =>  date('Y-m-d', strtotime($this->request->getPost('date-of-birth'))),
                    'Occupation' => $this->request->getPost('member-occupation'),
                    'MemberAddress' => $this->request->getPost('member-address'),
                    'NextOfKin' => $this->request->getPost('member-nok-name'),
                    'Relation' => $this->request->getPost('nok-relationship'),
                    'KinPhone' => $this->request->getPost('member-nok-phone-number'),
                    'KinAddress' => $this->request->getPost('member-nok-address'),
                    'SaccoID' => $this->request->getPost('entity-id'),
                ];

                if ($execMode == 'add') {
                    $data['MemberPhoto'] = $newName;
                    $data['CreatedAt'] = date('Y-m-d H:i:s');
                    $data['CreatedBy'] = $this->user['UserId'];
                }

                log_message('debug', 'Data: ' . json_encode($data));

                if (!$member->validate($data)) {
                    log_message('error', 'Validation failed for Sacco member: ' . json_encode($member->errors()));
                    $messages = $member->errors();
                    $errorsMessages = nl2br(esc(implode("\n", $messages)));
                    $returnData = [
                        'status' => 'error',
                        'message' => "<h6>Failed to add member</h6> \n" . $errorsMessages,
                        'data' => $data
                    ];
                    return json_encode($returnData);
                }

                if ($execMode == 'edit') {
                    if ($newName) {
                        $data['MemberPhoto'] = $newName;
                    }
                    $data['SaccoMemberID'] = $this->request->getPost('sacco-member-id');
                    $data['UpdatedAt'] = date('Y-m-d H:i:s');
                    $data['lastUpdatedBy'] = $this->user['UserId'];
                }

                $data['CreatedAt'] = date('Y-m-d H:i:s');
                $data['CreatedBy'] = $this->user['UserId'];

                $execute = $member->save($data);
                if (!$execute) {
                    $errorsMessages = nl2br(esc(implode("\n", $member->errors())));
                    log_message('error', 'Failed to save Sacco member details: ' . json_encode($member->errors()));
                    $returnData = [
                        'status' => 'error',
                        'message' => "<h6>Failed to Save Member Details</h6> \n" . $errorsMessages,
                        'data' => json_encode($data)
                    ];
                    return json_encode($returnData);
                }

                $execMessages = ($execMode == 'add') ? "<h6>Execution Successful</h6> Member added successfully" : "<h6>Execution Successful</h6> Member updated successfully";

                $returnData = [
                    'status' => 'success',
                    'message' => $execMessages,
                    'data' => $data,
                    'redirect' => base_url('entities/get-members/' . $entityType . '/' . $entityId)
                ];
                return json_encode($returnData);
            }

        } catch (\Exception $e) {
            log_message('error', 'Exception in saveMember: ' . $e->getMessage().'on line '.$e->getLine());
            $returnData = [
                'status' => 'error',
                'message' => "<h6>Failed to add member</h6> \n" . $e->getMessage(),
                'data' => $this->request->getPost()
            ];
            return json_encode($returnData);
        }
    }

    public function changeMemberStatus(){
        $memberId = $this->request->getPost('member-id');
        $entityType = $this->request->getPost('entity-type');
        $entityId = $this->request->getPost('entity-id');
        $memberStatus = $this->request->getPost('new-status');
        $updateMember = $this->updateMember($entityType, $entityId, $memberId, $memberStatus);
        if($updateMember){
            $returnData = [
                'status' => 'success',
                'message' => "<h6>Execution Successful</h6> Member status updated successfully",
                'redirect' => base_url('entities/get-members/' . $entityType . '/' . $entityId)
            ];
            return json_encode($returnData);
        }else{
            $returnData = [
                'status' => 'error',
                'message' => "<h6>Failed to update member status</h6>",
                'redirect' => base_url('entities/get-members/' . $entityType . '/' . $entityId)
            ];
            return json_encode($returnData);
        }
    }

    public function updateMember($entityType, $entityId, $memberId, $memberStatus)
    {
        try {
            log_message('info', "Starting updateMember with EntityType: $entityType, EntityId: $entityId, MemberId: $memberId, MemberStatus: $memberStatus");

            $model = null;
    
            if ($entityType === 'G') {
                log_message('info', 'Using GroupMemberModel');
                $model = new GroupMemberModel();
                $entityField = 'GroupID';
                $memberField = 'GroupMemberID';
                $statusField = 'GroupMemberStatus';
                $updateByField = 'LastUpdatedBy';
                $modifyDateField = 'UpdatedAt'; 
                $deleteField = 'Deleted';
                $deletedByField = 'DeletedBy';
                $deletedAtField = 'DeletedAt';   

            } elseif ($entityType === 'S') {
                log_message('info', 'Using SaccoMembersModel');
                $model = new SaccoMembersModel();
                $entityField = 'SaccoID';
                $memberField = 'SaccoMemberID';
                $statusField = 'MemberStatus';
                $updateByField = 'lastUpdatedBy';
                $modifyDateField = 'UpdatedAt'; 
                $deleteField = 'Deleted';
                $deletedByField = 'DeletedBy';
                $deletedAtField = 'DeletedAt';                  
            } else {
                $error = "Invalid entity type: $entityType";
                log_message('error', $error);
                throw new \Exception($error);
            }
    
            log_message('info', "Finding member with EntityField: $entityField, EntityId: $entityId, MemberField: $memberField, MemberId: $memberId");
            $member = $model->where($entityField, $entityId)
                            ->where($memberField, $memberId)
                            ->first();
    
            if (!$member) {
                $error = "Member not found for Entity ID: $entityId and Member ID: $memberId";
                log_message('error', $error);
                throw new \Exception($error);
            }

            if($memberStatus == 'Deleted'){
                $member->{$deleteField} = 1;
                $member->{$deletedByField} = $this->user['UserId'];
                $member->{$deletedAtField} = date('Y-m-d H:i:s');
                if (!$model->update($member->$memberField, (array)$member)) {
                    $sqlError = json_encode($model->errors());
                    $error = "Failed to delete member $sqlError";
                    log_message('error', $error);
                    throw new \Exception($error);
                }
                log_message('info', "Successfully deleted member for MemberId: $memberId");
                return true;
            }
    
            log_message('info', "Updating status for MemberId: $memberId");
            $member->{$statusField} = $memberStatus;
            $member->{$updateByField} = $this->user['UserId'];
            $member->{$modifyDateField} = date('Y-m-d H:i:s');
    
            if (!$model->update($member->$memberField, (array)$member)) {
                $error = "Failed to update member status";
                log_message('error', $error);
                throw new \Exception($error);
            }
    
            log_message('info', "Successfully updated member status for MemberId: $memberId");
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Exception in updateMember: ' . $e->getMessage() . ' on line ' . $e->getLine());
            return false;
        }
    }
    
    

    public function loadMemberData(){
        $entityType = $this->request->getPost('entity_type');
        $entityId = $this->request->getPost('entity_id');
        $memberId = $this->request->getPost('member_id');
        return $this->getMember($entityType, $entityId, $memberId);
    }

    public function getMember($entityType, $entityId, $groupMemberId){
        $member = null;
        $data = [];
        try{
            if($entityType == 'G'){
                $member = new GroupMemberModel();
                $member = $member->where('GroupID', $entityId)->where('GroupMemberID', $groupMemberId)->first();
            }
            if($entityType == 'S'){
                $member = new SaccoMembersModel();
                $member = $member->where('SaccoID', $entityId)->where('SaccoMemberID', $groupMemberId)->first();
            }

            //if member not found
            if(!$member){
                $data = [
                    'status'=> 'error',
                    'message' => "Member not found",
                    'data' => []
                ];
                return json_encode($data);
            }

            $data = [
                'status'=> 'success',
                'message' => "Execution Successful",
                'data' => $member
            ];

            return json_encode($data);
        } catch (\Exception $e) {
            $data = [
                'status'=> 'error',
                'message' => "Error getting member details: ".$e->getMessage(),
                'data' => []
            ];
            return json_encode($data);
        }

    }


}