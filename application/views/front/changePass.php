<?php require('menu.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#idSetting").addClass("dcjq-parent active");
			$("#idLoading").hide();

			$("#btnCancel").click(function(){
		    	window.location = "<?php echo site_url('setting');?>";
		    });
			$("#btnSave").click(function(){
				$("#idLoading").show();
				var userId = $("#txtIdLogin").val();
				var newPass = $("#txtPass").val();
				if(newPass == "")
				{
					alert("new password empty..!!");
					$("#idLoading").hide();
					return false;
				}
				$.post('<?php echo base_url("setting/updNewPass"); ?>',
				{   
					userId : userId,newPass : newPass
				},
					function(data) 
					{	
						alert(data);
						$("#idLoading").hide();
						window.location = "<?php echo site_url('setting/getChangePass');?>";
					},
				"json"
				);
			});
			$("#btnCancel").click(function(){
		    	window.location = "<?php echo site_url('front/observasi');?>";
		    });

		});		
	</script>
</head>
<body>
	<section id="container">
		<section id="main-content">
			<section class="wrapper site-min-height" style="min-height:400px;">
				<h3>
					<i class="fa fa-angle-right"></i> Setting <i class="fa fa-angle-right"></i> Change Password <span style="padding-left: 20px;" id="idLoading"><img src="<?php echo base_url('assets/img/loading.gif'); ?>" >
				</span></h3>

				<div class="row" id="idForm">
					<div class="col-md-12">
						<div class="form-panel">
							<legend><label id="lblForm"> Change Password</label></legend>
							<div class="form-group">
							    <label for="txtPass"><u>New Password :</u></label>
							    <input type="password" class="form-control input-sm" name="txtPass" id="txtPass" placeholder="New Password">
							</div>
							<div class="form-group" align="center">
								<input type="hidden" name="" id="txtIdLogin" value="<?php echo $userId; ?>">
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
	</section>
</body>
</html>

