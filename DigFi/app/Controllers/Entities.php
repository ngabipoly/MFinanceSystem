<?php
namespace App\Controllers;
use App\Models\DistrictModel;
use App\Models\GroupModel;
use App\Models\GroupMemberModel;
use App\Models\SaccoModel;
use App\Models\SaccoMembersModel;

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

        $execMode = $this->request->getPost('execMode');
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

    public function SaccoIndex(){
        $sacco = new SaccoModel();
        $data['title'] = 'Saccos';
        $data['page'] = 'Sacco Listing';
        $data['saccos'] = $sacco->findAll();

        return view('entities/saccos', $data);
    }

    public function addSacco(){
        $data = [
            'SaccoName' => $this->request->getPost('sacco-name'),
            'SaccoDescription' => $this->request->getPost('sacco-description'),
            'SaccoStatus' => $this->request->getPost('sacco-status'),
            'LocType' => $this->request->getPost('loc-type'),
            'LocID' => $this->request->getPost('loc-id'),
            'CreatedAt' => date('Y-m-d H:i:s'),
            'CreatedBy' => $this->user['UserId']
        ];

        $rules = [
            'sacco-name' => 'required',
            'sacco-description' => 'required',
            'sacco-status' => 'required',
            'loc-type' => 'required',
            'loc-id' => 'required'
        ];

        $messages = [
            'sacco-name' => [
                'required' => 'Please provide a Sacco Name'
            ],
            'sacco-description' => [
                'required' => 'Sacco Description is mandatory'
            ],
            'sacco-status' => [
                'required' => 'Specify Sacco Status'
            ],
            'loc-type' => [
                'required' => 'Location Type is not Selected'
            ],
            'loc-id' => [
                'required' => 'Location is not elected'
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

        $sacco = new SaccoModel();
        $sacco->insert($data);
        $data = [
            'status'=> 'success',
            'message' => "<h6>Execution Successful</h6> Sacco added successfully",
            'data' => $data,
            'redirect' => base_url('entities/saccos')
        ]; 
        return json_encode($data);
    }

    public function changeSaccoStatus(){

        $data = [
            'SaccoID' => $this->request->getPost('sacco-id'),
            'SaccoStatus' => $this->request->getPost('sacco-status')
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
            'message' => "<h6>Execution Successful</h6> Sacco status changed successfully",
            'data' => $data,
            'redirect' => base_url('entities/saccos')
        ]; 
        return json_encode($data);
    }
        

    
    public function getMembers(){
        $entityType = $this->request->uri->getSegment(3);
        $entityId = $this->request->uri->getSegment(4);
        $data= [];

        if($entityType == 'G'){
            $member = new GroupMemberModel();
            $group = new GroupModel();
            $data['group'] = $group->where('GroupID', $entityId)->first();
            $data['members'] = $member->where('GroupID', $entityId)->findAll();
        }

        if($entityType == 'S'){
            $member = new SaccoMembersModel();
            $data['members'] = $member->where('SaccoID', $entityId)->findAll();
        }
        $entityTypeList = ['G'=>'Group', 'S'=>'Sacco'];
        $data['entityType'] = $entityTypeList[$entityType];
        $data['entityId'] = $entityId;
        $data['title'] = "{$entityTypeList[$entityType]} Members";
        return view('entities/members', $data);
    }

    public function memberExists($memberId, $entityId, $entityType){
        try{
            $memberPresent = null;
            if($entityType == 'G'){
                $member = new GroupMemberModel();
                $memberPresent = $this->member->where('GroupID', $entityId)->where('MemberID', $memberId)->first();        
            }

            if($entityType == 'S'){
                $member = new SaccoMembersModel();
                $memberPresent = $this->member->where('SaccoID', $entityId)->where('MemberID', $memberId)->first();
            }
            //if member Present has one record
            if($memberPresent->count() > 0){
                return true;
            }
            return false;            
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return false;
        }

    }

    public function saveMember(){
        try
        {
            if(!$this->request->getFile('member-photo')){
                $data = [
                    'status'=> 'error',
                    'message' => "<h6>Failed to add member</h6> \n Please take/upload Member Photo!",
                    'data' => [
                        $this->request->getPost()
                    ]
                ]; 
                return json_encode($data);   
            }
                $entityId = $this->request->getPost('entity-id');
                $entityType = $this->request->getPost('entity-type');
                $execMode = $this->request->getPost('execMode');
                $execMessages = null;
                $memberPresent = $this->memberExists($this->request->getPost('member-id'), $entityId, $entityType);
        
                if($execMode != 'edit'){
                    if($memberPresent){
                        $execMessages = "<h6>Member ID already exists</h6> \n Member ID already exists";
                        $data = [
                            'status'=> 'error',
                            'message' => $execMessages,
                            'data' => []
                        ]; 
                        return json_encode($data);   
                    }
                }
        
                if($entityType == 'G'){
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
        
                    if(!$this->validate($rules, $messages)){
                        $data = [
                            'status'=> 'error',
                            'message' => "<h6>Failed to add member</h6> \n". $this->validator->listErrors(),
                            'data' => [
                                $this->request->getPost()
                            ]
                        ]; 
                        return json_encode($data);   
                    }

                    //member photo upload
                    $newName = null;
                    //check whether file is not present in payload                   
                    $file = $this->request->getFile('member-photo');
                    if ($file->isValid() && !$file->hasMoved()) {
                        //compress image save space but maintain quality
                    
                        $newName = $file->getRandomName();
                        $file->move(ROOTPATH . 'public/assets/images/group-members', $newName);
                    }
        
                    $data = [
                        'MemberID' => $this->request->getPost('id-number'),
                        'GroupMemberStatus' => $this->request->getPost('group-member-status'),
                        'MemberName' => $this->request->getPost('group-member-first-name').' '.$this->request->getPost('group-member-last-name'),
                        'MemberEmail' => $this->request->getPost('group-member-email'),
                        'MemberGender' => $this->request->getPost('group-member-gender'),
                        'MemberPhoneNumber' => $this->request->getPost('group-member-telephone'),
                        'MemberPhoto' => $newName,
                        'GroupID' => $this->request->getPost('entity-id'),
                        'CreatedAt' => date('Y-m-d H:i:s'),
                        'CreatedBy' => $this->user['UserId']
                    ];
                    $member = new GroupMemberModel();   
                    $execute = $member->save($data);
                    if(!$execute){
                        $data = [
                            'status'=> 'error',
                            'message' => "<h6>Failed to Save Member Details</h6> \n". $member->errors(),
                            'data' => []
                        ]; 
                        return json_encode($data);   
                    }
        
                    if($execMode == 'edit'){
                        $execMessages = "<h6>Execution Successful</h6> Member Details updated successfully";
                    }
        
                    $execMessages = "<h6>Execution Successful</h6> Group Member added successfully";
        
                    $data = [
                        'status'=> 'success',
                        'message' => $execMessages,
                        'data' => $data,
                        'redirect' => base_url('entities/members/'.$entityType.'/'.$entityId)
                    ]; 
                    return json_encode($data);
                }
        
                if($entityType == 'S'){
                    $rules = [
                        'member-id' => 'required',
                        'member-status' => 'required',
                        'member-first-name' => 'required',
                        'member-last-name' => 'required',
                        'member-email' => 'required',
                        'member-status' => 'required',
                        'member-phone' => 'required',
                        'member-occupation' => 'required',
                        'member-photo' => 'required',
                        'entity-id' => 'required',
                        'member-role' => 'required'                
                    ];
        
                    $messages = [
                        'member-id' => [
                            'required' => 'Member ID is required'
                        ],
                        'member-status' => [
                            'required' => 'Member Status is not set'
                        ],
                        'member-first-name' => [
                            'required' => 'Member First Name is required'
                        ],
                        'member-last-name' => [
                            'required' => 'Member Last Name is required'
                        ],
                        'member-email' => [
                            'required' => 'Member Email is required'
                        ],
                        'member-status' => [
                            'required' => 'Member Status is not set'
                        ],
                        'member-phone' => [
                            'required' => 'Member Phone Number is required'
                        ],
                        'member-occupation' => [
                            'required' => 'Member Occupation is required'
                        ],
                        'member-photo' => [
                            'required' => 'Upload a Member Photo'
                        ],
                        'entity-id' => [
                            'required' => 'Group ID is not set'
                        ],
                        'member-role' => [
                            'required' => 'Member Role is required'
                        ]
                    ];
        
                    if(!$this->validate($rules, $messages)){
                        $data = [
                            'status'=> 'error',
                            'message' => "<h6>Failed to add member</h6> \n". $this->validator->listErrors(),
                            'data' => []
                        ]; 
                        return json_encode($data);   
                    }
        
                    //get member photo
                    $photo = $this->request->getFile('member-photo');
                    if($photo->isValid() && !$photo->hasMoved()){
                        $photoName = $photo->getRandomName();
                        $photo->move('assets/images/sacco-members', $photoName);
                        $photo = $photoName;
                    }
        
                    $data = [
                        'MemberID' => $this->request->getPost('member-id'),
                        'MemberIDType' =>  $this->request->getPost('member-id-type'),
                        'MemberStatus' => $this->request->getPost('member-status'),
                        'MemberFirstName' => $this->request->getPost('member-first-name'),
                        'MemberLastName' => $this->request->getPost('member-last-name'),
                        'MemberEmail' => $this->request->getPost('member-email'),
                        'MemberPhoneNumber' => $this->request->getPost('member-phone'),
                        'MemberOccupation' => $this->request->getPost('member-occupation'),
                        'MemberPhoto' => $photo,
                        'SaccoID' => $this->request->getPost('sacco-id'),
                        'CreatedAt' => date('Y-m-d H:i:s'),
                        'CreatedBy' => $this->user['UserId']
                    ];
        
                    if($execMode == 'edit'){
                        $data['SaccoMemberID'] = $this->request->getPost('sacco-member-id');
                        $data['UpdatedAt'] = date('Y-m-d H:i:s');
                    }
        
                    $member = new GroupMemberModel();
                    $execute = $member->save($data);
                    if(!$execute){
                        $data = [
                            'status'=> 'error',
                            'message' => "<h6>Failed to Save Member Details</h6> \n". $member->errors(),
                            'data' => []
                        ]; 
                        return json_encode($data);   
                    }
        
                    $execMessages =($execMode == 'add')? "<h6>Execution Successful</h6> Member added successfully": "<h6>Execution Successful</h6> Member updated successfully";
        
                    $data = [
                        'status'=> 'success',
                        'message' => $execMessages,
                        'data' => $data,
                        'redirect' => base_url('entities/members/'.$entityType.'/'.$entityId)
                    ]; 
                    return json_encode($data);
                }
        
            } catch (\Exception $e) {
            $data = [
                'status'=> 'error',
                'message' => "<h6>Failed to add member</h6> \n". $e->getMessage(),
                'data' => []
            ]; 
            return json_encode($data);   
        }
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
                return $data = [
                    'status'=> 'error',
                    'message' => "Member not found",
                    'data' => []
                ];
            }

            return $data = [
                'status'=> 'success',
                'message' => "Execution Successful",
                'data' => $member
            ];
        } catch (\Exception $e) {
            return $data = [
                'status'=> 'error',
                'message' => "Error getting member details: ".$e->getMessage(),
                'data' => []
            ];
        }

    }


}