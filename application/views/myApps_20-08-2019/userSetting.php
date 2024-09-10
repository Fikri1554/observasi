<?php require('menu.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#idForm").hide();
			$("#idLoading").hide();

			$("#btnSave").click(function(){
				$("#idLoading").show();
				var usrId = $("#slcUsrName").val();
				var fullName = $("#slcUsrName option:selected").html();
				var myApps = $("#slcMyaApps").val();				
				if(usrId == '0')
				{
					alert("User Empty..!!");
					return false;
				}
				$.post('<?php echo base_url("myapps/addUserSetting"); ?>',
				{   
					usrId : usrId,myApps : myApps,fullName : fullName
				},
					function(data) 
					{	
						alert(data);
						reloadPage();
					},
				"json"
				);

			});
		    $("#btnAddData").click(function(){
		    	$("#idDataTable").hide(250);
		    	$("#idForm").show(350);
		    });
		    $("#slcUsrName").change(function(){
		    	$("#idLoading").show();
		    	$("#slcMyaApps").empty();
		    	var usrId = $(this).val();
		    	var fullName = $("#slcUsrName option:selected").html();
		    	if(usrId == '0')
		    	{
		    		$("#slcMyaApps").empty();
		    		$("#idLoading").hide();
		    		$("#slcMyaApps").append('<option value="0">- Select -</option>');
		    		return false;
		    	}
		    	$.post('<?php echo base_url("myapps/getOptMyApps"); ?>',
				{   
					usrId : usrId,fullName : fullName
				},
					function(data) 
					{	
						$("#slcMyaApps").append(data);
					},
				"json"
				);
				$("#idLoading").hide();
		    });
		    $("#btnCancelSearch").click(function(){
		    	window.location = "<?php echo site_url('myapps/getMailRegInv');?>";
		    });
		});
		function delData(id)
		{
			var cfm = confirm("Yakin Hapus..??");
			if(cfm)
			{
				$.post('<?php echo base_url("myapps/delUserSetting"); ?>',
				{ id : id },
				function(data) 
				{
					reloadPage();
				},
				"json"
				);
			}			
		}
		function reloadPage()
		{
			window.location.reload(true);
		}
	</script>
</head>
<body>
	<section id="container">
		<section id="main-content">
			<section class="wrapper site-min-height" style="min-height:400px;">
				<h3>
					<i class="fa fa-angle-right"></i> User Setting Apps<span style="padding-left: 20px;" id="idLoading"><img src="<?php echo base_url('assets/img/loading.gif'); ?>" ></span>
				</h3>
				<div class="form-panel" id="idDataTable">
					<div class="row">
						<div class="col-md-12" id="btnNavAtas">
							<button type="button" id="btnAddData" class="btn btn-primary btn-sm" title="Add"><i class="fa fa-plus-square"></i>&nbsp&nbspAdd Data</button>
						</div>
					</div>
					<div class="row mt" id="idData1">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
									<thead>
										<tr style="background-color: #ba5500;color: #FFF;">
											<th style="vertical-align: middle; width:3%;text-align:center;padding: 10px;">No</th>
											<th style="vertical-align: middle; width:10%;text-align:center;">User</th>
											<th style="vertical-align: middle; width:40%;text-align:center;">My Apps</th>											
											<th style="vertical-align: middle; width:10%;text-align:center;">Action</th>
										</tr>
									</thead>
									<tbody id="idTbody">
										<?php echo $trNya; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="row" id="idForm">
					<div class="col-md-12">
						<div class="form-panel">
							<legend><label id="lblForm"> Add Data</label></legend>
							<div class="col-md-6 col-cs-12">
								<div class="form-group">
								    <label for="slcUsrName"><u>Username :</u></label>
								    <select name="slcUsrName" id="slcUsrName" class="form-control input-sm">
								    	<option value="0">- Select -</option>
								    	<?php echo $optUsr; ?>
								    </select>
								</div>
							</div>
							<div class="col-md-6 col-cs-12">
								<div class="form-group">
								    <label for="slcMyaApps"><u>My Apps :</u></label>
								    <select name="slcMyaApps" id="slcMyaApps" class="form-control input-sm">
								    	<option value="0">- Select -</option>
								    </select>
								</div>
							</div>
							</fieldset>
							<div class="form-group" align="center">
								<input type="hidden" name="" id="txtIdEdit" value="">
								<button id="btnSave" class="btn btn-primary btn-sm" name="btnSave" title="Save">
									<i class="fa fa-check-square-o"></i>
									Save
								</button>
								<button id="btnCancel" onclick="reloadPage();" class="btn btn-danger btn-sm" name="btnCancel" title="Cancel">
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

