<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="andhika group">
    <link rel="icon" href="<?php echo base_url("assets/img/andhika.gif"); ?>">

	<title>PT.ADNYANA</title>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lineicons/style.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style-responsive.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.css">
	<script src="<?php echo base_url();?>assets/js/jquery-1.8.3.min.js"></script>
	<script src="<?php echo base_url();?>assets/js/jquery.js"></script>
	<script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url();?>assets/js/jquery-ui-1.9.2.custom.min.js"></script>
	<script type="text/javascript">
    $(document).ready(function(){
      $("#btnSave").click(function(){
        var email = "";
        var pass = "";
        var confirmPass = "";

        email = $("#txtEmail").val();
        pass = $("#txtPass").val();
        confirmPass = $("#txtConfirmPass").val();
        if(email == "")
        {
          alert("Email don't empty..!!");
          return false;
        }
        if(pass == "")
        {
          alert("New Password don't empty..!!");
          return false;
        }
        if(pass != confirmPass)
        {
          alert("Check Password and Confirm Password..!!");
          return false;
        }
        $("#idLoading").show();
        $.post('<?php echo base_url("front/updateMailPass"); ?>',
          {   
            email : email,pass : pass
          },
            function(data) 
            { 
              if(data == "sukses")
              {
				window.location = "<?php echo site_url('front/observasi');?>";
              }else{
				window.location = "<?php echo base_url('front/logout');?>";
              }
             },
           "json"
        );
      });

      $("#btnCancel").click(function (){
        window.location = "<?php echo site_url('front/logout');?>";
      });
    });
	function validateEmail(sEmail) 
	{
		var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
		if (filter.test(sEmail)) 
		{
			return true;
		}else{
			alert("check your email..!!");
			setTimeout(function() { $('input[name="txtEmail"]').focus() }, 300);
			return false;
		}
	}
  function showPass()
  {
    var x = document.getElementById("txtPass");
    var xc = document.getElementById("txtConfirmPass");
    if (x.type === "password")
    {
      x.type = "text";
    } else {
      x.type = "password";
    }
    if (xc.type === "password")
    {
      xc.type = "text";
    } else {
      xc.type = "password";
    }
  }
  </script>
</head>
<body>
<section id="container">
    
      <section class="wrapper site-min-height" style="min-height:400px;">
        <div class="row" id="idForm">
          <div class="col-md-12">
            <div class="form-panel">
              <legend><label id="lblForm"> Add Email & Change Password</label> <img id="idLoading" style="display: none;" src="<?php echo base_url('assets/img/loading.gif'); ?>" ></legend>
              
              <div class="form-group">
                  <label for="txtEmail"><u>Email :</u></label>
                  <input type="email" class="form-control input-sm" name="txtEmail" onchange="validateEmail($(this).val());" id="txtEmail" placeholder="userEmail@domain.com">
              </div>
              <div class="form-group">
                  <label for="txtPass"><u>New Password :</u></label>
                  <input type="password" class="form-control input-sm" name="txtPass" id="txtPass" placeholder="New Password">
              </div>
              <div class="form-group">
                  <label for="txtPass"><u>Confirm Password :</u></label>
                  <input type="password" class="form-control input-sm" name="txtConfirmPass" id="txtConfirmPass" placeholder="Confirm Password">
                  <label class="form-check-label">
                <input type="checkbox" id="idShowPass" name="idShowPass" class="form-check-input" onclick="showPass();" > Show Password
              </label>
              </div>

              <div class="form-group" align="center">
                <input type="hidden" name="" id="txtIdEdit" value="">
                <button id="btnSave" class="btn btn-primary btn-sm" name="btnSave" title="Save">
                  <i class="fa fa-check-square-o"></i>
                  Save
                </button>
                <button id="btnCancel" class="btn btn-danger btn-sm" name="btnCancel" title="Cancel">
                  <i class="fa fa-ban"></i>
                  Cancel
                </button>
              </dir>
            </div>
          </div>
        </div>
      </section>
    
  </section>
</body>
</html>



<script class="include" type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.scrollTo.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/jquery.sparkline.js"></script>

<!--common script for all pages-->
<script src="<?php echo base_url();?>assets/js/common-scripts.js"></script>
    
	
	
