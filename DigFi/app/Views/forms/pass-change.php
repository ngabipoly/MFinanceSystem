
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $page;?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url();?>/assets/plugins/fontawesome-free/css/all.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>/assets/css/adminlte.min.css">
<!-- Toastr -->
<link rel="stylesheet" href="<?php echo base_url()?>/assets/plugins/toastr/toastr.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="#" class="h1"><b>Digi</b>Fi</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Change Password</p>
      <form action="<?php echo base_url('difi-account-management/difi-save-new-password');?>" class="db-submit" method="post" data-initmsg="Attempting Login">
        <?php echo csrf_field();?>
        <input type="hidden" name="mandated" value="1">
        <div class="input-group mb-3">
          <input type="text" name="user-code" class="form-control" placeholder="User Code">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="old-pass-phrase" class="form-control" placeholder="Old Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
            <div class="input-group mb-3">
                <input type="password" name="new-pass-phrase" class="form-control" placeholder="New Password">
                <div class="input-group-append">
                    <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
        <div class="input-group mb-3">
          <input type="password" name="confirm-pass-phrase" class="form-control" placeholder="Confirm Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" name="verification-code" class="form-control" placeholder="Verification Code">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user-secret"></span>
            </div>
          </div>
        </div> 
        <div class="row">
          <div class="col-sm-12">
            <button type="submit" class="btn btn-primary btn-block float-right">Change Password</button>
          </div>
          <!-- /.col -->
        </div>               
        </div>
        </div>


      </form>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->
 <!-- jQuery -->
  <script src="<?php echo base_url();?>/assets/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
<script src="<?php echo base_url();?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Toastr -->
<script src="<?php echo base_url();?>/assets/plugins/toastr/toastr.min.js"></script>
<script type="text/javascript">
    $('.db-submit').submit(function(e){
        e.preventDefault();
        let target = $('#target-elm')
        let msg = $(this).data('initmsg');
        db_submit(target,$(this),msg);
        });

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
                    console.log(response);
                    if(response.status=='success'){
                        toastr.success(response.message);
                        //wait 3 seconds
                        setTimeout(() => {
                            //redirect to login page
                            location.replace("<?php echo base_url('/');?>") 
                        }, 3000);
                    $(formSubmited).trigger("reset");          	
                    }else{
                      toastr.error(response.message)
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

</script>
</body>
</html>