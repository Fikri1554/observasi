<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="andhika group">
    <meta name="keyword" content="andhika line, andhika group, andhika">
	<!--<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />-->
    <link rel="icon" href="<?php echo base_url("assets/img/andhika.gif"); ?>">
    <title>LOGIN - PT. ADNYANA</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.css" rel="stylesheet">

    <!--external css-->
    <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        
    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/style-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	 <!-- js placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url();?>assets/js/jquery.js"></script>
    <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>

    <!--BACKSTRETCH-->
    <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.backstretch.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#noteForgetPass").hide();
            $("#txtPass").keyup(function(event) {
              if (event.keyCode === 13) {
                $("#btnLogin").click();
              }
            });
            $("#btnLogin").click(function(){
              var user = $("#txtUser").val();
              var pass = $("#txtPass").val();
			  //alert("<?php echo base_url('front/login');?>");//return false;
              $("#lblAlertUser").text("");
			  
			$.post('<?php echo base_url("Testnya/cekteknya"); ?>',
			{   
				user : user,pass : pass
			},
				function(data) 
				{	
					alert(data);
				},
				"json"
			);

              if (user == "")
              {
                $("#lblAlertUser").text("User ID tidak boleh kosong..!!");
                return false;
              }
              if(pass == "")
              {
                $("#lblAlertUser").text("Password tidak boleh kosong..!!");
                return false;
              }

              $.post('<?php echo base_url("Front/login"); ?>',
              {   
                user : user,pass : pass
              },
                function(data) 
                { 
                    if(data.status)
                    {
                      window.location = "<?php echo base_url('Front/observasi');?>";
                    }else{
                      var txtAlert = "User tidak ditemukan..!!(hub. QHSE) ";
                      if(data.user)
                      {
                        txtAlert = "Password Salah..!!";
                      }

                      $("#lblAlertUser").text(txtAlert);
                      return false;
                    }
                },
              "json"
              );

            });
            $("#linkPass").click(function(){
              $("#noteForgetPass").show();
            });
        });
        function showPass()
        {
            var x = document.getElementById("txtPass");
            if (x.type === "password")
            {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>

  </head>
  <body>

	  <div id="login-page">
	  	<div class="container">
		      <form class="form-login">
            <center>
              <img style="padding-top: 10px;" src="<?php echo base_url(); ?>assets/img/andhika.gif" class="img-circle" width="20%" >
                <br>
                <label style="font-size: 24px;font-weight: bolder;">
                  PT. ADNYANA
                </label>
            </center>
		        <h2 class="form-login-heading" style="background-color:#2a86aa;">
              OBSERVASI KESELAMATAN & PROSEDUR
            </h2>
		        <div class="login-wrap">
		          <input type="text" id="txtUser" name="txtUser" class="form-control" placeholder="User ID" autofocus>
		          <br>
		            <input type="password" id="txtPass" name="txtPass" class="form-control" placeholder="Password">
              <hr>
              <label class="form-check-label">
                <input type="checkbox" id="idShowPass" name="idShowPass" class="form-check-input" onclick="showPass();" > Show Password
              </label>
              <br>
                <small id="lblAlertUser" class="form-text text-muted" style="color: red;"></small>
						    <button class="btn btn-primary btn-block" id="btnLogin" type="button"><i class="fa fa-lock"></i> SIGN IN</button>
                <a id="linkPass" style="margin-top: 100px;cursor: pointer;">Forget Password</a>
                <div id="noteForgetPass" style="color:red;" align="center">Please Email Administrator <b>qhse@andhika.com</b>..!!</div>
		          <hr style="margin: 10px 0px 10px 0px;">
              <center>
                <label style="font-size: 11px;">Copyright @ <?php echo date("Y"); ?> Andhika Group</label>
              </center>
		        </div>
		      </form>	  	
	  	</div>
	  </div>
  </body>
	<script>
		$.backstretch("<?php echo base_url(); ?>assets/img/kapalAndhika.jpg", {speed: 1200});
  </script>
</html>
