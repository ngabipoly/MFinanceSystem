</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?php echo base_url();?>/assets/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url();?>/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url();?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?php echo base_url();?>/assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>/assets/js/adminlte.js"></script>
<!-- Print Elements -->
<script src="<?php echo base_url();?>/assets/plugins/printThis/printThis.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url();?>/assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url();?>/assets/plugins/daterangepicker/daterangepicker.js"></script>

<!-- Select2 -->
<script src="<?php echo base_url();?>/assets/plugins/select2/js/select2.full.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="<?php echo base_url();?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url();?>/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url();?>/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo base_url();?>/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url();?>/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo base_url();?>/assets/plugins/jszip/jszip.min.js"></script>
<script src="<?php echo base_url();?>/assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo base_url();?>/assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo base_url();?>/assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url();?>/assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url();?>/assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- Toastr -->
<script src="<?php echo base_url();?>/assets/plugins/toastr/toastr.min.js"></script>
<script>
    //Date range picker
    $('.daterange').daterangepicker({
        locale: {
        format: 'YYYY-MM-DD',
        separator: ' : ',
      }
    })  

    //data table with buttons
    $(".data-table").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    //simple basic datatable with pageination selector    
    $(".data-table-simple").DataTable({
      displayLength: 10,"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      pagination: true, "responsive": true, "lengthChange": true, "autoWidth": false,
    });



    //data table with
    let customHeaderTitle = $(".table-exportable").data('title');

    $(".table-exportable").DataTable({
                dom: 'Blfrtip',
                displayLength: 10,
                "lengthMenu": [ [10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, "All"] ],
                "lengthChange": true,
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="fa fa-copy"></i> Copy',
                        messageTop: customHeaderTitle
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fa fa-file-csv"></i> CSV',
                        messageTop: customHeaderTitle
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel"></i> Excel',
                        messageTop: customHeaderTitle
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-file-pdf"></i> PDF',
                        message: customHeaderTitle,
                        customize: function (doc) {
                          doc.pageOrientation = 'landscape';
                          doc.content.splice(0, 0, {
                                text: customHeaderTitle,
                                style: 'title'
                            });
                            doc.styles.title = {
                                fontSize: 18,
                                bold: true,
                                margin: [0, 0, 0, 10]
                            };
                        }
                    },
                    {
                        extend: 'print',
                        //add image to print
                        text: '<i class="fa fa-print"></i> Print',
                        messageTop: '<h3>' + customHeaderTitle + '</h3>'
                    }
                ]
            });
	
    
    //bind enter in manual loiading form
    $('#num-add').keyup(function(e){
      if(e.which===13){
        e.preventDefault();
        $('#btn-add-number').click();
      }
    });

    //Add MSISDN to loader area
    $('.btn-add-number').click(function(e){
        e.preventDefault();
        let sourceElm = $(this).data('source');
        let targetElm = $(this).data('target');
        let amountElm = $(this).data('amount');
        let pFormElm = $(this).data('parent');

        let feedBkElm = $('#load-status');
        let msisdn = $(sourceElm).val();
        let amount = $(amountElm).val();
        let prevEntries = $(targetElm).val();
        let newEntries = "";

        console.log(msisdn.length, msisdn.substr)

        if(!msisdn && !amount){
          feedBkElm.hide().empty();
          return false;
        }
        if(!msisdn){
          toastr.error("Please enter MSISDN");
          return false; 
        }

        if(!amount && amountElm){
          toastr.error("Please Enter Amount");
          return false; 
        }else if(amount && amountElm){
          newEntries=(!prevEntries)? `${msisdn},${amount}`:`\n${msisdn},${amount}`;
        }else{
          newEntries=(!prevEntries)? `${msisdn}`:`\n${msisdn}`;
        }

        if((msisdn.length ==9 && (msisdn.substring(0,2)=="71" || msisdn.substring(0,1)=="4"))||msisdn.substring(0,1)=="8"){
          const fieldCheck = checkRequired($(pFormElm).find('.required'),feedBkElm);
          if(fieldCheck===false){
            return false;
          }
          console.log(`Adding ${msisdn}, ${amount}`);
          $(targetElm).append(newEntries)
          $(sourceElm).val('');
          $(sourceElm).focus();
        }else{
          toastr.error("Invalid MSISDN");
          return false;          
        }
    })


    //post loadings
    $(document).on('click', '#btn-post-loading',function(e){
      e.preventDefault();
      let message = 'Posting Loadings';
      let feedBkElm = $('#load-status');
      let loadings = $('#loader-area').val();
      let uri = $(this).data('url');
      let form = $(this).closest('form');

      if(!loadings){
        return toastr.error("No Data Submitted!")
      }

      let data = {
        'loader-data':loadings,
      };

   try {
        const dataSubmission = async()=>{
			  const response = await submitData(uri,data,message,feedBkElm);
			  let returns = JSON.parse(response);
        console.log(`Returns, ${returns}`);
			  if(returns.status){

          form.find('textarea').text('');
          form[0].reset();
				  console.log(`File Added! Reference Number is ${returns.file_ref}, ${returns.ld_msg}`);
          $(feedBkElm).removeClass('alert-danger alert-warning').addClass('alert-success').text(`File Added! Reference Number is ${returns.file_ref}, ${returns.ld_msg}`).show();
			  }else{
				  console.error(`Operation Failed! ${returns}`);
				  feedBkElm.removeClass('alert-success alert-warning').addClass('alert-danger').text(`Error Creating Loadings File`).show() ;
				  feedBkElm.css('display','block');
			  }

		  } 
      dataSubmission();   
    }catch (error) {
      console.log(error)
    }         

    });
//Control display of Bulk and Single entry options for miscellenious actions
$('#o-type').change(function(){
  if($(this).val()=="I"){
    $('#bulk-ops').attr('hidden','hidden');
    $('#single-entry-ops').show();
  }else{
    $('#single-entry-ops').hide();
    $('#bulk-ops').removeAttr('hidden');
  }
})

//Add Number by Number function to miscellenious actions
$("#misc-exec").click(function(e){
  e.preventDefault();
    let actionType = $("#action-key").val();
    let opType = $("#o-type").val();
    let numbers = $("#o-queue").val();
    let form = $(this).closest('form');
    let url = $(this).data('url');
    let feedBkElm = $('#messages');
    let message = "Starting Operation.."

    if(!numbers){
      return toastr.error("Nothing in Queue! Please add MSISDNs.")
    }

    let formData = form.serialize();


    console.log(formData)
    try {
        const dataSubmission = async()=>{
			  const response = await submitData(url,formData,message,feedBkElm);
			  let returns = JSON.parse(response);
        console.log(`Returns, ${JSON.stringify(response)}`);
			  if(returns.status){

          form.find('textarea').text('');
          form[0].reset();
				  console.log(`File Added! Reference Number is ${returns.file_ref}, ${returns.ld_msg}`);
          $(feedBkElm).removeClass('alert-danger alert-warning').addClass('alert-success').text(`File Added! Reference Number is ${returns.file_ref}, ${returns.ld_msg}`).show();
			  }else{
				  console.error(`Operation Failed! ${returns}`);
				  feedBkElm.removeClass('alert-success alert-warning').addClass('alert-danger').text(`Error Creating Loadings File`).show() ;
				  feedBkElm.css('display','block');
          if(returns.message){
            toastr.error(`${returns.message}`)
          }
			  }

		  } 
      dataSubmission();   
    }catch (error) {
      console.log(error)
    }    

});


//load user information into modal
$('.user-edit').on('click',function(e){
  $("#user-mgr-h").text("Edit User");
  $('#uid').val($(this).data('uid'));
  $('#pf-number').val($(this).data('pf'));
  $('#first-name').val($(this).data('fname'));
  $('#last-name').val($(this).data('lname'));;
  $('#user-email').val($(this).data('uemail'));
  $('#user-role').val($(this).data('urole'));;
  $('#user-status').val($(this).data('ustatus'));
  $('#reset-pwd').show();
});

$('#new-user').on('click',function(){
    if($("#user-mgr-h").text()==="Edit User"){
      $("#user-mgr-h").text("New User");
      $("#frm-user-mgt").trigger("reset");
      $('#reset-pwd').hide();
    }  
});

$('#new-role').on('click',function(){
  $("#roleModalLabel").text("Add Role");
  $("#role-form").trigger("reset");
  $('#role-action').text(`Create Role`);
});

//load role details for editing
$('.get-role-rights').on('click', function(e) {
    $("#roleModalLabel").text("Edit Role");
    let url = $(this).data('url');
    let entity_id = $(this).data('roleid');
    let role_name = $(this).data('rolename');
    let role_status = $(this).data('rolestatus');
    let role_desc = $(this).data('desc');
    let entity_type = 'G';

    $('#user-role-name').val(role_name);
    $('#role-id').val(entity_id);
    $('#entity-id').val(entity_id);
    $('#role-status').val(role_status);
    $('#exec-mode').val("edit");
    $('#user-role-description').text(role_desc);
    $('#spn-role-name').text(role_name);
    $('#role-action').text(`Modify Role ${role_name}`);

    // Get Assigned Menu
    let data = { entity_id: entity_id, entity_type: entity_type };
    let elm = $('#roles')
    loadElm(url,data,elm,'Fetching Role Menus...')
});

//Queue Menus for Addition
$(document).on('click','#btn-assign',function(e){
    e.preventDefault();
    let assigned_menu_ids =''
    console.log('assigning')
    $('.chk-unassigned').each(function(){
      if ($(this).prop('checked')) {
        let menu_id = $(this).val() 
        // Concatenate menu IDs with colon delimiter
        assigned_menu_ids =(!assigned_menu_ids)? menu_id: `${assigned_menu_ids}:${menu_id}`;
      }      
    })
    $('#assign-list').text(assigned_menu_ids);
});

//Queue Menus for revockation
$(document).on('click','#btn-revoke',function(e){
    e.preventDefault();
    let revoked_menu_ids =''
    console.log('assigning')
    $('.chk-assigned').each(function(){
      if ($(this).prop('checked')) {
        let menu_id = $(this).val() 
        // Concatenate menu IDs with colon delimiter
        revoked_menu_ids =(!revoked_menu_ids)? menu_id: `${revoked_menu_ids}:${menu_id}`;
      }      
    })
    $('#revoke-list').text(revoked_menu_ids);
});

//save to excel
$(".save_excel").click(function(e) {   
   let content = $(this).data('content');
   window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#content').html())); // content is the id of the DIV element  
   e.preventDefault();   
}); 

const loadElm = (url,data,element,init_msg)=>{
    $.ajax({
        type: 'GET',
        dataType: 'html',
        url: url,
        data: data,
        beforeSend: function(){
          toastr.info(init_msg);
        },
        success: function(response) {
            // Update the content of the #roles element with the received HTML
            element.html(response);
        },
        complete: function() {
          toastr.info("Loading Complete!")
        },
        error: function(xhr) {
            console.error(xhr);
        }
    });
};

//clear forms
    $(document).ready(function() {

        $('.reset').click(function() {
          let form = $(this).closest('form');
          form.find('textarea').text('');
          form[0].reset();
        });

            //Initialize Select2 Elements
        $('.select2').select2()

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })
  });

//General function to check that all required fields are set
const checkRequired =(element,errorElm)=>{
  let messages = '<ul>';
  let passed = true;
  element.each(function(){
      if($(this).val()==''){
        passed=false
         messages+=`<li> ${$(this).data('emptymsg')}</li>`;
      }
  })
  messages+='</ul>'
  errorElm.removeClass('alert-success alert-danger').addClass('alert-warning').html(messages);
  return passed;
}

//Ajax Submission
  const submitData=(url,data,message,messageElm) =>{
    const submitFormData = new Promise((resolve, reject) => {
            $.ajax({
                url: url,
                type:"POST",
                data:data,
                  beforeSend: function(){
                    toastr.info(`${message}`);
                  },
                  success: function(resp){
                    resolve(resp) 
                  },
                  error: function(xhr, status, error){
                    reject(error);
                  }
            });
      });  
      return submitFormData;
  }
  

  $(document).ready(function() {

    $(document).on('click','.load-file-dtls',function(){
      let url = $(this).data("url");
      let file = $(this).data('fileid');
      let elm = $('#file-details');
      let user = $('#usr-logged-in').text();
      let data = {'file-id':file}

      $("#print-user").text(user);
      elm.empty();
      console.log(url);
      loadElm(url,data,elm,'Loading File Details');
    });

    $('#reset-pwd').click(function(e){
      console.log('resetting');
      uri = "<?php echo base_url('administration/reset-pwd');?>";
      message="Resetting Password";
      feedBkElm=$('#rtn-errors');
      data = {
        uid: $('#uid').val(),
        'first-name':$('#first-name').val(),
        'last-name' :$('#last-name').val(),
        'user-email':$('#user-email').val()
      };

        try {
              const dataSubmission = async()=>{
              const response = await submitData(uri,data,message,feedBkElm);
              let returns = JSON.parse(response);
              console.log(`Returns, ${returns}`);
              if(returns.status=="success"){
                toastr.success(returns.message)
              }else if(returns.status=="error"){
                toastr.error(returns.message);
              }else{
                console.error(`Operation Failed! ${returns}`);
                feedBkElm.removeClass('alert-success alert-warning').addClass('alert-danger').text(`Error Creating Loadings File`).show() ;
                feedBkElm.css('display','block');
              }
            
            } 
            dataSubmission();   
          }catch (error) {
            console.log(error)
          } 
    });

  //bulk file handling
    $('#frm-user-mgt').submit(function(e) {
        e.preventDefault();
        let form = $(this);
        let file_elm = $('#user-photo');
        let upload_key =  file_elm.attr('name');
        bulkFileUpload(form,file_elm,upload_key);
    });

    $('#frm-misc-actions').submit(function(e) {
        e.preventDefault();
        let form = $(this);
        let file_elm = $('#action-file');
        let action_key =  $('#action-key');
        bulkFileUpload(form,file_elm,action_key)
    });
    $('#log-out').click(function(e){
      e.preventDefault();
      url = $(this).attr('href');
      location.replace(url);
    })
    $('.db-submit').submit(function(e){
      e.preventDefault();
      let target = $('#target-elm')
      let msg = $(this).data('initmsg');
      db_submit(target,$(this),msg);
    });

    $('#new-product').click(function(e){
      e.preventDefault();
      $('#execMode').val('add');
      $('#spn-action').text('Create New');
    });

    $('#new-nature').click(function(e){
      $('#spn-action').text('Create');
      $('#spn-nature').text('');      
    });

    $('.edit-nature').click(function(e){
      e.preventDefault();
      let natureId = $(this).data('editid');
      let natureName = $(this).data('editname');
      let description = $(this).data('editdesc');
      let minholder = $(this).data('editminholders');
      let maxholder = $(this).data('editmaxholders');

      $('#spn-action').text('Edit ');
      $('#spn-nature').text(natureName);
      $('#nature-name').val(natureName);
      $('#nature-description').val(description);
      $('#min-holders').val(minholder);
      $('#max-holders').val(maxholder);
      $('#nature-id').val(natureId);
      $('#execMode').val('edit');
    });

    $('.delete-nature').click(function(e){
        let deleteid = $(this).data('deleteid');
        let nature = $(this).data('nature');
        $('#del-nature-id').val(deleteid);
        $('#spn-del-nature-name').text(nature);
    });

    $('.edit-loan-product').click(function(e){
      e.preventDefault();
      $('#spn-action').text('Edit');

      let productId = $(this).data('editid');
      let productName = $(this).data('editname');
      let description = $(this).data('editdesc');
      let interest = $(this).data('editintrate');
      let interestType = $(this).data('editinttype');
      let calcFrequency = $(this).data('editcalcstyle');
      let calcMethod = $(this).data('editcalcmethod');
      let maxTerm = $(this).data('editmaxterm');
      let minTerm = $(this).data('editminterm');
      let maxAmt = $(this).data('editmaxamount');
      let minAmt = $(this).data('editminamount');
      let status = $(this).data('editstatus');

      $('#execMode').val('edit');
      $('#product-name').val(productName);
      $('#product-description').val(description);
      $('#interest-rate').val(interest);
      $('#interest-rate-type').val(interestType);
      $('#calculation-style').val(calcFrequency);
      $('#calculation-method').val(calcMethod);
      $('#maximum-term').val(maxTerm);
      $('#minimum-term').val(minTerm);
      $('#product-max-amount').val(maxAmt);
      $('#product-min-amount').val(minAmt);
      $('#product-status').val(status);
      $('#product-id').val(productId);
      $('#spn-edit-product-name').text(`Edit ${productName}`);  
    });

    $('.delete-loan-product').click(function(e){
      e.preventDefault();
      let productId = $(this).data('deleteid');
      let productName = $(this).data('product');
      $('#del-product-id').val(productId);
      $('#spn-del-product-name').text(productName);
    })

    $('.retire-loan-product').click(function(e){
      e.preventDefault();
      let productId = $(this).data('retireid');
      let productName = $(this).data('retname');
      $('#retire-product-id').val(productId);
      $('#spn-retire-product-name').text(productName);
    })

    //Account Types/Product Controls
    $('#new-ac-product').click(function(e){
        $('#procuct-form').trigger('reset');
        $('#spn-action').text('Create');
        $('#spn-account').text('');
    });
    
    $('.edit-account-product').click(function(e){
        let accountProduct = $(this).data('editid');
        let productName = $(this).data('productname');
        let minBal  = $(this).data('minbal');
        let openbal =$(this).data('openbal');
        let desc = $(this).data('desc');
        let prodNature = $(this).data('nature');

        $('#product-nature').val(prodNature);
        $('#product-name').val(productName);
        $('#product-description').val(desc);
        $('#opening-balance').val(openbal);
        $('#ac-product-id').val(accountProduct);
        $('#minimum-balance').val(minBal);
        $('#execMode').val('edit');
        $('#spn-action').text('Edit');
        $('#spn-account').text(productName);        
    }); 

    $('.delete-account-product').click(function(e){
      let accountTypeId = $(this).data('deleteid');
      let typeName = $(this).data('accounttype');

      $('#spn-del-account-product-name').text(typeName);
      $('#del-account-product-id').val(accountTypeId);
    })

    //ID Types Controls
    $('#new-id-type').click(function(e){
      $('#id-type-form').trigger('reset');
      $('#spn-action').text('Create');
      $('#spn-id-type-name').text('');
      $('#execMode').val('create');
    });

    $('.edit-id-type').click(function(e){
      e.preventDefault();
      let typeId = $(this).data('editid');
      let typeName = $(this).data('typename');
      let description = $(this).data('desc');
      let typeLength = $(this).data('len');
      let pattern = $(this).data('pattern');
      let category = $(this).data('category');

      $('#execMode').val('edit');
      $('#type-id').val(typeId);
      $('#id-type-name').val(typeName);
      $('#id-description').val(description);
      $('#id-type-category').val(category);
      $('#id-number-length').val(typeLength);
      $('#number-pattern').val(pattern);
      $('#spn-action').text('Edit');
      $('#spn-id-type-name').text(`${typeName}`);
    });

    $('.delete-id-type').click(function(e){
      e.preventDefault();
      let typeId = $(this).data('deleteid');
      let typeName = $(this).data('typename');
      $('#del-id-type-id').val(typeId);
      $('#spn-del-id-type-name').text(typeName);
    })

    //Transaction Type Controls
    $('#new-trans-type').click(function(e){
      $('#transaction-type-form').trigger('reset');
      $('#spn-action').text('Create');
      $('#spn-trans-type-name').text('');
      $('#execMode').val('create');
    });

    $('.edit-trans-type').click(function(e){
      e.preventDefault();
      let typeId = $(this).data('editid');
      let typeName = $(this).data('typename');
      let description = $(this).data('desc');
      let category = $(this).data('category');
      let suffix = $(this).data('suffix');

      $('#execMode').val('edit');
      $('#type-id').val(typeId);
      $('#transaction-type-name').val(typeName);
      $('#transaction-description').val(description);
      $('#transaction-type-category').val(category);
      $('#number-suffix').val(suffix);
      $('#spn-action').text('Edit');
      $('#spn-trans-type-name').text(`${typeName}`);
    });

    $('.delete-trans-type').click(function(e){
      e.preventDefault();
      let typeId = $(this).data('deleteid');
      let typeName = $(this).data('typename');
      $('#del-transaction-type-id').val(typeId);
      $('#spn-del-transaction-type-name').text(typeName);
    }); 

    //Transaction Method Controls
    $('.edit-trans-method').click(function(e){
      e.preventDefault();
      let transId = $(this).data('editid');
      let methodName = $(this).data('methodname');
      let providerList = $(this).data('providerlist');
      let desc = $(this).data('desc');

      $('#execMode').val('edit');
      $('#method-id').val(transId);
      $('#transaction-method-name').val(methodName);
      $('#transaction-method-description').val(desc);
      $('#select-provider-listing').val(providerList);
      $('#spn-action').text('Edit');
      $('#spn-trans-method-name').text(`${methodName}`);
    });

    $('#new-method').click(function(e){
      $('#transaction-method-form').trigger('reset');
      $('#spn-action').text('Create');
      $('#spn-trans-method-name').text('');
      $('#execMode').val('create');
    });

    $('.delete-trans-method').click(function(e){
      e.preventDefault();
      let transId = $(this).data('deleteid');
      let methodName = $(this).data('methodname');
      $('#del-transaction-method-id').val(transId);
      $('#spn-del-transaction-method-name').text(methodName);
    });

    //Method Provider management
// Event handler for clicking provider list
$('.provider-list').click(function(e) {
    e.preventDefault();
    let methodId = $(this).data('methodid');
    let methodName = $(this).data('methodname');

    $('#provider-method-id').val(methodId);
    $('#spn-provider-method-name').text(methodName);
    refreshDataTable(methodId,methodName)
});

$(document).on('click','.edit-provider',function(e){
  console.log($(this).data('provider-id'));
  e.preventDefault();
  let providerId = $(this).data('provider-id');
  let providerName = $(this).data('provider-name');
  let methodId = $(this).data('provider-method-id');
  $('#provider-id').val(providerId);
  $('#payment-provider-name').val(providerName);
  $('#pExecMode').val('edit');
});

let changeUrl = '<?php echo base_url('setup/transaction-method-provider/change-status');?>';

$(document).on('click','.status-change',function(e){
  console.log($(this).data('provider-new-status'));
  e.preventDefault();
  let providerId = $(this).data('provider-id');
  let status = $(this).data('provider-new-status');
  let methodId = $(this).data('provider-method-id');
  let methodName = $('#spn-provider-method-name').text();
  let data = { 'provider-id': providerId, 'provider-status': status };
  changeProviderStatus(changeUrl,data,methodId,methodName);
});

function changeProviderStatus(url,data,methodId,methodName) {
  $.post(changeUrl, data, function(response) {
    response = typeof response === 'object' ? response : JSON.parse(response);
    console.log(response); // Log the entire response object
    if (response.status === 'error') {
        return toastr.error(response.message || 'Error occurred, but no message provided.');
    }
    toastr.success(response.message || 'Success, but no message provided.');

  }).always(function() {
    refreshDataTable(methodId,methodName)
  });
}

// Function to refresh the DataTable with new data
function refreshDataTable(methodId,methodName) {  
  let url = "<?php echo base_url('setup/transaction-method-providers');?>";
  $.post(url, { 'methodId': methodId, 'methodName': methodName }, function(response) {
      response = typeof response === 'object' ? response : JSON.parse(response);

        if (response.status === 'error') {
            return toastr.error(response.message || 'Error occurred, but no message provided.');
        }

        toastr.success(response.message || 'Success, but no message provided.');

        providers = response.data;
        // Clear and destroy the existing DataTable
        let table = $('#method-provider-table').DataTable();
        table.clear().destroy();
        $('#method-provider-table tbody').empty();

        // Append new data to the table body
        $.each(providers, function(index, provider) {
            let deleteLink = provider.ProviderStatus === 'Deleted' ? '' : `<a href="#" class="btn btn-danger btn-xs delete-provider status-change" data-provider-new-status="Deleted" data-provider-method-id="${provider.MethodID}" data-provider-id="${provider.ProviderID}" title="Delete Provider"><i class="fas fa-trash"></i></a> `;
            let disableLink = (provider.ProviderStatus === 'Inactive' || provider.ProviderStatus === 'Deleted')? '': `<a href="#" class="btn btn-warning btn-xs disable-provider status-change" data-provider-new-status="Inactive" data-provider-method-id="${provider.MethodID}" data-provider-id="${provider.ProviderID}" title="Disable Provider"><i class="fas fa-ban"></i>
          </a>` ;
            let enableLink = provider.ProviderStatus === 'Inactive'? `<a href="#" class="btn btn-success btn-xs enable-provider status-change" data-provider-new-status="Active" data-provider-method-id="${provider.MethodID}" data-provider-id="${provider.ProviderID}" title="Enable Provider"><i class="fas fa-check"></i></a> `:'';
            let editLink = provider.ProviderStatus === 'Inactive'? `<a href="#" class="btn btn-primary btn-xs edit-provider" data-provider-id="${provider.ProviderID}" data-provider-name="${provider.ProviderName}"  data-provider-method-id="${provider.MethodID}" title="Edit Provider"><i class="fas fa-edit"></i></a>`:'';

            let row = `
                <tr>
                    <td><small>${provider.ProviderName}</small></td>
                    <td><small>${provider.ProviderStatus}</small></td>
                    <td><small>${provider.createdByName}</small></td>
                    <td><small>${provider.CreatedAt}</small></td>
                    <td>
                      <small>                      
                          ${editLink}
                          ${enableLink}
                          ${disableLink}
                          ${deleteLink} 
                      </small>          
                    </td>
                </tr>
            `;
            $('#method-provider-table tbody').append(row);
        });
        // Reinitialize the DataTable
        $('#method-provider-table').DataTable({
            "responsive": true,
            "autoWidth": false,
        "initComplete": function() {
            // Implement the dropdown filter for the status column (2nd column)
            this.api().columns(1).every(function() {
                let column = this;

                $('#filter-status').on('change', function() {
                    let val = $.fn.dataTable.util.escapeRegex($(this).val());

                    // Filter the column based on the dropdown selection
                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                });
            });
        }
    });


    }).fail(function(xhr, status, error) {
        console.error("AJAX request failed:", error);
        toastr.error("An error occurred while processing the request.");
    });
}

$('#save-customer').click(function(e){
  e.preventDefault();
  $('#customer-registration-form').submit();
})

$('#add-holder').click(function(e) {
  e.preventDefault();

  let url = $(this).attr('href');
  let table = $('#customer-table').DataTable();

  // Send the AJAX request
  $.get(url, function(response) {
    // Ensure the response is a valid JSON object
    response = typeof response === 'object' ? response : JSON.parse(response);
    
    if (response.status === 'success') {
      toastr.success(response.message || 'Success, but no message provided.');

      // Clear the table first to avoid duplication
      table.clear();

      // Add the rows via DataTable API
      $.each(response.data, function(index, customer) {
        let addLink = `
          <a href="#" class="btn btn-success btn-xs btn-add-holder rounded-circle" data-customer-id="${customer.ClientID}" data-customer-name="${customer.LastName} ${customer.FirstName}" data-customer-occupation="${customer.Occupation}" data-customer-phone="${customer.PhoneNumber}" data-customer-idnumber="${customer.IDNumber}" data-customer-photo="${customer.CustomerPhoto}" data-customer-dob="${customer.DateOfBirth}" title="Add Customer">
            <i class="fas fa-plus"></i>
          </a>`;

        table.row.add([
          `<small>${customer.LastName} ${customer.FirstName}</small>`,
          `<small>${customer.IDNumber}</small>`,
          `<small>${customer.Occupation}</small>`,
          `<small>${customer.PhoneNumber}</small>`,
          `<small>${addLink}</small>`
        ]);
      });

      // Draw the updated rows
      table.draw();
    } else {
      toastr.error(response.message);
    }
  })
  .fail(function(jqXHR, textStatus, errorThrown) {
    // Handle request errors (e.g., network issues, server failure)
    toastr.error('Failed to retrieve data. Error: ' + textStatus);
  });
});

$(document).on('click', '.btn-add-holder', function() {
  // Get customer data from data attributes
  let customerId = $(this).data('customer-id');
  let customerName = $('<div>').text($(this).data('customer-name')).html();
  let customerOccupation = $('<div>').text($(this).data('customer-occupation')).html();
  let customerPhoneNumber = $('<div>').text($(this).data('customer-phone')).html();
  let customerIDNumber = $('<div>').text($(this).data('customer-idnumber')).html();
  let customerPhoto = $(this).data('customer-photo');
  let customerDOB = $('<div>').text($(this).data('customer-dob')).html();
  let selected = $('#holders').val();

  selectedArray = selected.split(':');
  if (selectedArray.includes(String(customerId)) || selectedArray.includes(customerId)) {
    toastr.error(`Customer ${customerName} is already selected.`);
    return false;
  }

  selected = selected ? `${selected}:${customerId}` : customerId;
  $('#holders').val(selected);
  
  // photoPath is passed as a JSON-encoded string from PHP
  let photoPath = <?php echo json_encode(CLIENT_PHOTO_PATH); ?>;

  // Create customer card with escaped details
  let card = ` 
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <strong>${customerName}</strong>
        </div>
        <div class="card-body">
          <img src="${photoPath}${customerPhoto}" class="img-fluid" alt="Customer Photo" style="height: 64px; width: 64px">
          <p><strong>Occupation:</strong> ${customerOccupation} </p>
          <p><strong>Phone Number:</strong> ${customerPhoneNumber} </p>
          <p><strong>ID Number:</strong> ${customerIDNumber} </p>
          <p><strong>Date of Birth:</strong> ${customerDOB} </p>
        </div>
      </div>
    </div>
  `;

  // Add the card to the holder-details section
  $('#holder-details').append(card);

});

$('#account-category').change(function(){
  let maxHolders = $(this).find(':selected').data('maxholders');
  let minHolders = $(this).find(':selected').data('minholders');
  $('#max-holders').val(maxHolders);
  $('#min-holders').val(minHolders);
});

$('#save-account').click(function(e){
  e.preventDefault();
  $('#account-registration-form').submit();
})

$('.select-customer').click(function(e){
  e.preventDefault();
  let customerPhoto = $(this).data('customer-photo');
  let customerName = $(this).data('customer-names');
  let customerID = $(this).data('customer-id');
  let customerIDNumber = $(this).data('customer-idnumber');
  let occupation = $(this).data('customer-occupation');
  let phonenumber = $(this).data('customer-phonenumber');
  let adddress = $(this).data('customer-address');
  let dob = $(this).data('customer-dob');
  let age = $(this).data('customer-age');
  let idFront = $(this).data('customer-idfront');
  let idBack = $(this).data('customer-idback');
  let gender = $(this).data('customer-gender');

  $('#customer-photo').attr('src',customerPhoto);
  $('#customer-photo').attr('alt',customerName);
  $('#customer-idfront').attr('src',idFront);
  $('#customer-idback').attr('src',idBack);
  $('#customer-name').text(customerName);
  $('#customer-gender').text(gender);
  $('#customer-id').val(customerID);
  $('#customer-idnumber').text(customerIDNumber);
  $('#customer-occupation').text(occupation);
  $('#customer-phone').text(phonenumber);
  $('#customer-address').text(adddress);
  $('#customer-dob').text(dob);
  $('#customer-age').text(age);

  $('#customer-modal').modal('hide');
})

$('#loan-product').change(function(){
  let maxAmt = $(this).find(':selected').data('max-amt');
  let minAmt = $(this).find(':selected').data('min-amt');
  let interest = $(this).find(':selected').data('interest-rate');
  let minTerm = $(this).find(':selected').data('min-term-months');
  let maxTerm = $(this).find(':selected').data('max-term-months');
  $('#max-amount').val(maxAmt);
  $('#min-amount').val(minAmt);
  $('#interest-rate').val(interest);
});

$('#save-application').click(function(e){
  e.preventDefault();
  $('#loan-application-form').submit();
});

$('#group-district').change(function(){
  let region = $(this).find(':selected').data('group-region-name');
  let subRegion = $(this).find(':selected').data('subregion-name');
  let regionId = $(this).find(':selected').data('region-id');
  let subRegionId = $(this).find(':selected').data('subregion-id');
  
  $('#sub-region').val(subRegion);
  $('#group-region').val(region);
});

$('#group-add-btn').click(function(e){
  e.preventDefault();
  console.log('submitting form')
  $('#group-addition-form').submit();
});

$('.group-status-change').click(function(e){
  e.preventDefault();
  let groupStatus = $(this).data('group-status');
  let groupStatusName = $(this).data('group-status');
  let groupName = $(this).data('group-name');
  let execMode = $(this).data('group-new-status');
  let action = $(this).attr('title');

  $('#spn-action').text(action);
  $('#group-status').val(groupStatus);
  $('#spn-group-name').text(groupName);
  $('#group-status').text(groupStatusName);
  $('#group-id').val($(this).data('group-id'));
  $('#group-new-status').val($(this).data('group-new-status'));
  $('#group-status-modal').modal('show');
});

$('#group-status-btn').click(function(e){
  e.preventDefault();
  $('#group-status-form').submit();
});

///webcam control
const video = $('#video')[0];
const canvas = $('#canvas')[0];
const photo = $('#photo')[0];

let stream;
let userImage = null;

// Function to start webcam
function startWebcam() {
  try {
      if (stream) {
          return;
      }

      navigator.mediaDevices.getUserMedia({ video: true })
          .then((s) => {
              stream = s;
              video.srcObject = stream;
              video.play(); // Optional: Start playing the video immediately
          })
          .catch((err) => {
              console.error("Error starting webcam: " + err);
          });
  } catch (e) {
    console.error("Error accessing webcam: " + e);
    toastr.error("An error occurred while starting the webcam.");    
  }    
  
}

// Function to stop webcam
function stopWebcam() {
  try {
      if (stream) {
          const tracks = stream.getTracks();
          tracks.forEach(track => track.stop());
          stream = null; // Clear the stream variable
      }
  } catch (e) {
    console.error("Error Stopping webcam: " + e);
    toastr.error("An error occurred while stopping the webcam.");    
  }    
  
}

// Take a snapshot when the button is clicked
$('#snap').click(function (e) {
    e.preventDefault(); // Prevent the default action
    const context = canvas.getContext('2d');
    canvas.width = video.videoWidth; // Set canvas width to video width
    canvas.height = video.videoHeight; // Set canvas height to video height
    context.drawImage(video, 0, 0, canvas.width, canvas.height); // Draw the video frame on the canvas

    canvas.toBlob((blob) => {
      userImage = blob;
    })
    const dataURL = canvas.toDataURL('image/png'); // Convert canvas to data URL
    photo.src = dataURL; // Set the image source to the data URL
    $(this).hide();
    $('#photo-uploader').hide();
    $('#video').hide();
    $('#photo').show();
    stopWebcam();
    $('.start-camera').show();
});

// Stop webcam when modal is closed
$('#group-member-modal').on('hidden.bs.modal', function () {
    stopWebcam();
});

// Start webcam when the snapshot button is clicked
$('.start-camera').click(function(e) {
    e.preventDefault();
    startWebcam(); 
    $(this).hide();
    $('#video').show();
    $('#photo').hide();
    $('#takePhoto').show();
    $('#snap').show();

});

$('#photo-upload').click(function(e){
  e.preventDefault();
  $('#photo-uploader').show();
  $('#video').hide();
  $('#photo').show();
  $('#takePhoto').hide();
  stopWebcam();
});

$('#member-photo').change(function(e) {
        // Get the selected file
        let file = this.files[0];
        userImage = null;
        
        if (file) {
            $(this).siblings('.custom-file-label').text(file.name);
            
            if (file.type.startsWith('image/')) {

                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#photo').attr('src', e.target.result);
                }                
                // Read the file as a Data URL (base64 encoded)
                reader.readAsDataURL(file);
            } else {
                alert("Please select a valid image file.");
            }
        }
});

$('.add-group-members').click(function(e){
  e.preventDefault();
  let groupID = $(this).data('group-id');
  let url = `<?php echo base_url(); ?>/entities/get-members/G/${groupID}`;
  $('#save-group-member').show().text('Save');
  $('#spn-group-exc-mode').text('Add');
  location.href=encodeURI(url);
});

$('#add-group-member').click(function(e) {
  e.preventDefault();
  $('#save-group-member').show().text('Save');
  $('#spn-group-exc-mode').text('Add');
  $('#exec-mode').val("add");

  // Make form editable by enabling all form fields
  $('#group-member-form :input').each(function() {
    if ($(this).attr('type') !== 'hidden') {
      console.log($(this).attr('id'));
      $(this).prop('disabled', false);
      $(this).val(''); // Clear only visible and non-hidden fields
    }
  });

  // Reset image preview to default or blank
  $('#photo').attr('src', "<?php echo base_url('assets/images/member-default.png'); ?>"); // Replace with default path if needed
});


$('#save-group-member').click(function(e){
  e.preventDefault();
  try {
      let form = $('#group-member-form');
      let fileInput = $('#member-photo')[0].files[0];
      let execMode = $('#exec-mode').val();

      if (!userImage && !fileInput && execMode == 'add') {
          toastr.error("Please take or upload a photo first.");
          return;
      }

      let formData = new FormData(form[0]);
      let formUrl = form.attr('action');

      // If photo was taken, add it to form data
      if (userImage) {
          formData.append('member-photo', userImage, 'snapshot.png');
      }

      console.log(formData.get('member-photo'));
      $.ajax({
          url: formUrl, 
          type: 'POST',
          data: formData,
          processData: false, 
          contentType: false,
          success: function (response) {
              let data = typeof response === 'string' ? JSON.parse(response) : response;
              console.log("Group Member response", data);
              if (data.status == 'error') {
                  toastr.error(data.message);
                  return false;
              }
              toastr.success(data.message);
              $('#group-member-modal').modal('hide');
              //redirect after 3 seconds
              setTimeout(() => {
                  location.replace(data.redirect);
              },5000);
          },
          error: function (xhr, status, error) {
              console.error("Error uploading image: " + error);
              alert("Failed to upload image");
          }
      }); 
    } catch (e) {
      console.error("Error saving group members: " + e);
      toastr.error("An error occurred while saving group members.");
    }

});

$('.get-group-member').click(function(e){
  e.preventDefault();
  let currentElement = $(this);
  let memberID = $(this).data('member-id');
  let entiyID = $(this).data('entity-id');
  let entityType = $(this).data('entity-type');
  let url = $(this).data('link');
  let data = { entity_id: entiyID, entity_type: entityType, member_id: memberID };
  
  getLinkData(url, data, "Fetching data...")
    .then((response) => {
        console.log("Success:", response);
        memberData = response.data;
        memberNames = memberData.MemberName.trim().split(/\s+/);
        $("#group-member-id").val(memberData.GroupMemberID);
        $('#entity-id').val(memberData.GroupID);
        $('#entity-type').val(entityType);
        $('#photo').attr('src', `<?php echo base_url('assets/images/group-members/'); ?>${memberData.MemberPhoto}`);
        $('#id-number').val(memberData.MemberID);
        $('#group-member-first-name').val(memberNames[0]);
        $('#group-member-last-name').val(memberNames[1]);
        $('#group-member-email').val(memberData.MemberEmail);
        $('#group-member-telephone').val(memberData.MemberPhoneNumber);
        $('#group-member-status').val(memberData.GroupMemberStatus);
        $('#group-member-address').val(memberData.memberAddress);
        $('#group-member-dob').val(memberData.MemberDob);
        if(currentElement.hasClass('edit-member')){
          $('#exec-mode').val("edit");
          $('#spn-group-exc-mode').text('Edit');
          $('#save-group-member').show().text('Update');
          //make all fileds editable
          $('#group-member-form :input').prop('disabled', false);
        }else{
            $('#spn-group-exc-mode').text('View');
            $('#group-member-form :input').prop('disabled', true); 
            $('#save-group-member').hide();            
        }           
    })
    .catch((error) => {
        console.log("Failed:", error);      
    });
});

function getLinkData(url, data, msg) {
    toastr.info(msg);

    return new Promise((resolve, reject) => {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: url,
            data: data,
            success: function(response) {
                if (response.status !== 'success') {
                    toastr.error(response.message);
                    reject(response); // Reject the promise with response in case of error status
                } else {
                    toastr.success(response.message);
                    resolve(response); // Resolve the promise with response on success
                }
            },
            error: function(xhr, status, error) {
                console.error("Error Fetching Data: " + error);
                toastr.error("An error occurred while Fetching Data.");
                reject(error); // Reject the promise with error details
            }
        });
    });
}




function db_submit(resTarget,formSubmited,sendMsg){
  let alerts = '';
       $.ajax({
        type:'POST', 
        dataType:'json',
        url: $(formSubmited).attr('action'), 
        data:$(formSubmited).serialize(), 
        beforeSend: function() {
          //message to user confirming progress
          toastr.info(sendMsg);
          //empty multiple message element
          $(resTarget).html('');
        },
      success: function(response) {
          let redirectUrl = '';
          
          if($.isArray(response.message)){
            //construct and show multiple messages if available
            let messages = '';
            $.each(response.message,function(index,value){
              messages = messages+value+'<br/>';
            });
            alerts = '<div class="alert alert-info">'+messages+'</div>'
            $(resTarget).html(alerts).show();              
          }else{
            console.log(response);
          	if(response.status=='success'){
          		toastr.success(response.message);
              $(formSubmited).trigger("reset");    
              if(typeof response.redir_to !='undefined' || response.redirect !=''){
                redirectUrl = response.redir_to || response.redirect;
                async function pauseExecution() {
                  console.log(`Redirecting in 5 seconds`);
                  await sleep(5000); //5000 milliseconds = 5 seconds
                  console.log('Redirecting Now!');
                }

                (async function main() {
                  await pauseExecution();
                  // Code here will run after the 10-second pause
                  location.replace(redirectUrl);
                })();        
              }  	
          	}else if(response.status=='error'){
          		toastr.error(response.message)
          	}else{
              toastr.info(response.message)
            }
          }        
        },
      complete: function() {
          
       },
      error: function(xhr){
        toastr.error(`${xhr.status}:${xhr.statusText} - ${xhr.responseText}`);
        console.log(xhr)
      }
  });
    
}

<?php
  if(1===2){ ?>

 <?php }?>
    //load file details
    status = {S:'Submitted', P:'Processed'};
    file_table = $('#data-table').DataTable({
          processing: true,
          serverSide: false,
          // order datatable by first row descending
          order:[[0,'desc']],
          ajax: {
              url: '<?php echo site_url('front-office/wb-file-status');?>',
              type: 'POST'
          },
          columns: [
              { data: 'file_id',
                render: function(data, type,row){
                  return '<small><a href="#" data-fileid="' + data + '" data-url="<?php echo base_url();?>front-office/wb-file-details/" data-toggle="modal" class="load-file-dtls" data-target="#details-modal">' + data + '</a></small>';
                }
              },
              { data: 'source_file',
                render:function(data,type,row){
                  return (data)?'<small>'+data+'</small>': "";
                }
               },
              { data: 'in_file',
                render:function(data,type,row){
                  return (data)?'<small>'+data+'</small>': "";
                }
               },
              { data: 'submit_by',
                render:function(data,type,row){
                  return (data)?'<small>'+data+'</small>': "";
                }
               },
              { data: 'date_submitted',
                render:function(data,type,row){
                  return (data)?'<small>'+data+'</small>': "";
                }
               },
              { 
                data: 'file_status',
                render:function(data,type,row){
                  //console.log('data is',status)
                  if (data === 'S') {
                    return '<small>Submitted</small>';
                  } else if (data === 'P') {
                    return '<small>Processed</small>';
                  } else {
                    return '<small>'+data+'</small>'; // Handle other cases if necessary
                  }
                } 
              }
          ]
      });

    setInterval( function () {
      file_table.ajax.reload();
      }, 15000 );
});

function fileUpload(form, file_elm, upload_key=null) {
    let url = form.attr('action');
    let formData = new FormData(form[0]);
    let fileInput = file_elm[0];

    if (fileInput.files.length > 0) {
        formData.append(upload_key, fileInput.files[0]);

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    // Attempt to parse JSON response
                    let data = typeof response === 'string' ? JSON.parse(response) : response;
                    if (data.status === 'success') {
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                } catch (e) {
                    console.error('Failed to parse JSON response:', e);
                    toastr.error('An unexpected error occurred.');
                }
                file_elm.val(''); // Clear the file input
            },
            error: function(xhr, status, error) {
                // Handle error and parse error message from xhr.responseText
                console.error('AJAX Error:', xhr.status, status, error);
                try {
                    let errorMessage = JSON.parse(xhr.responseText).message || 'An unexpected error occurred.';
                    toastr.error(errorMessage);
                } catch (e) {
                    toastr.error('An unexpected error occurred.');
                }
            }
        });
    } else {
        toastr.error('No file selected.');
    }
}

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

$('#print-report').click(function(){
  let elm = $(this).data('report');
    console.log('attempting printing',elm)
    print_elm(elm)
})



function print_elm(elem){
  $(elem).printThis();
}
</script>
</body>
</html>