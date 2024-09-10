<?php require('menu.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#idSetting").addClass("dcjq-parent active");
			$("#idLoading").hide();
			$("#idForm").hide();

			$("#btnAdd").click(function(){
				$("#idDataTable").hide(250);
				$("#idForm").show(350);
			});
			$("#btnCancel").click(function(){
		    	window.location = "<?php echo site_url('setting');?>";
		    });
			$("#btnRefresh").click(function(){
		    	window.location = "<?php echo site_url('setting');?>";
		    });
			$("#btnSave").click(function(){
				$("#idLoading").show();
				$('html,body').scrollTop(0);
				var idEdit = $("#txtIdEdit").val();
				var fullName = $("#txtFullName").val();
				var user = $("#txtUser").val();
				var pass = $("#txtPass").val();
				var userType = $("#slcUserType").val();
				var position = $("#slcJabatan").val();
				var vessel = $("#slcVessel").val();
				var idName = $("#txtIdName").val();
				var stBtn = $("#slcBtnExport").val();

				if(userType == "user")
				{
					if(position == "")
					{
						alert("Position Can't Empty..!!");
						$("#idLoading").hide();
						return false;
					}
					if(vessel == "")
					{
						alert("Vessel Name Can't Empty..!!");
						$("#idLoading").hide();
						return false;
					}					
				}

				$.post('<?php echo base_url("setting/saveData"); ?>',
				{   
					idEdit : idEdit,fullName : fullName,user : user,pass : pass,userType : userType,position : position,vessel : vessel,idName : idName,stBtn : stBtn
				},
					function(data) 
					{		
						$("#idLoading").hide();
						if(data != "")
						{
							alert(data);
							return false;
						}else{
							alert("success..!!");
							window.location = "<?php site_url("setting"); ?>";
						}
						
					},
				"json"
				);
			});
			
			$("#btnSearch").click(function(){
				var txtSearch = "";
				var slcVsl = "";
				txtSearch = $("#txtSearch").val();
				slcVsl = $("#slcVesselSrc").val();
				if(txtSearch == "" && slcVsl == "")
				{
					alert("data Search Empty..!!");
					return false;
				}
				$("#idLoading").show();
				$("#idBody").empty();
				$.post('<?php echo base_url("setting/getData/search"); ?>',
				{ txtSearch : txtSearch,slcVsl : slcVsl },
					function(data) 
					{							
						$("#idBody").append(data.dataLogin);
						$("#idLoading").hide();
					},
				"json"
				);
			});

		});

		function getEdit(id)
		{
			$("#idLoading").show();
			$("#lblForm").text("Edit User");
			$("#idDataTable").hide(250);
			
			$.post('<?php echo base_url("setting/getDataEdit"); ?>',
			{ id : id },
				function(data) 
				{							
					$.each(data.dataLogin, function(i, item)
					{
						$("#txtIdEdit").val(item.id);
						$("#txtFullName").val(item.full_name);
						$("#txtUser").val(item.username);
						$("#slcUserType").val(item.user_type);
						$("#slcJabatan").val(item.id_jabatan);
						$("#slcVessel").val(item.vessel);
						$("#txtIdName").val(item.id_name);
						$("#slcBtnExport").val(item.export);
					});
					$("#idLoading").hide();
					$("#idForm").show(350);
				},
			"json"
			);
		}
	</script>
</head>
<body>
	<section id="container">
		<section id="main-content">
			<section class="wrapper site-min-height" style="min-height:400px;">
				<h3>
					<i class="fa fa-angle-right"></i> Setting <i class="fa fa-angle-right"></i> User <span style="padding-left: 20px;" id="idLoading"><img src="<?php echo base_url('assets/img/loading.gif'); ?>" >
				</span></h3>

				<div class="form-panel" id="idDataTable">
					<div class="row">
						<div class="col-md-1 col-xs-12">
							<button type="button" id="btnAdd" class="btn btn-info btn-sm btn-block" title="Add"><i class="fa fa-plus-square"></i>&nbsp Add</button>
						</div>
						<div class="col-md-2 col-xs-12">
							<input type="text" class="form-control input-sm" style="margin-bottom: 5px;" name="txtSearch" id="txtSearch" placeholder="Search">
						</div>
						<div class="col-md-2 col-xs-12">
							<select name="slcVesselSrc" id="slcVesselSrc" class="form-control input-sm">
								<option value="">- Select Vessel -</option>
							    	<?php echo $vessel; ?>
							</select>
						</div>
						<div class="col-md-1 col-xs-12">
							<button type="button" id="btnSearch" class="btn btn-success btn-sm btn-block" title="Search"> Search</button>
						</div>
						<div class="col-md-1 col-xs-12">
							<button type="button" id="btnRefresh" class="btn btn-danger btn-sm btn-block" title="Refresh"> Refresh</button>
						</div>
					</div>
					<div class="row mt">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
									<thead>
										<tr style="background-color: #1b6583;color: #FFF;">
											<th style="width:3%;text-align: center;">No</th>
											<th style="width:7%;text-align: center;">Crew ID</th>
											<th style="width:10%;text-align: center;">Nama</th>
											<th style="width:10%;text-align: center;">User</th>
											<th style="width:19%;text-align: center;">Email</th>
											<th style="width:10%;text-align: center;">Jabatan</th>
											<th style="width:10%;text-align: center;">Kapal</th>
											<th style="width:10%;text-align: center;">Type</th>
											<th style="width:13%; text-align: center;">Action</th>
										</tr>
									</thead>
									<tbody id="idBody">
										<?php echo $dataLogin; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="row" id="idForm">
					<div class="col-md-12">
						<div class="form-panel">
							<legend><label id="lblForm"> Add User</label></legend>
							<div class="form-group">
							    <label for="txtIdName"><u>Crew ID :</u></label>
							    <input type="text" class="form-control input-sm" name="txtIdName" id="txtIdName" placeholder="ID">
							</div>
							<div class="form-group">
							    <label for="txtFullName"><u>Full Name :</u></label>
							    <input type="text" class="form-control input-sm" name="txtFullName" id="txtFullName" placeholder="Full Name">
							</div>
							<div class="form-group">
							    <label for="txtUser"><u>Username :</u></label>
							    <input type="text" class="form-control input-sm" name="txtUser" id="txtUser" placeholder="Username">
							</div>
							<div class="form-group">
							    <label for="txtPass"><u>Password :</u></label>
							    <input type="password" class="form-control input-sm" name="txtPass" id="txtPass" placeholder="Password">
							</div>
							<div class="form-group">
							    <label for="slcUserType"><u>User Type :</u></label>
							    <select name="slcUserType" id="slcUserType" class="form-control input-sm">
							    	<option value="admin">Admin</option>
							    	<option value="user">user</option>
							    </select>
							</div>
							<div class="form-group">
							    <label for="slcJabatan"><u>Position :</u></label>
							    <select name="slcJabatan" id="slcJabatan" class="form-control input-sm">
							    	<option value="">- Select Position -</option>
							    	<?php echo $jabatan; ?>
							    </select>
							</div>
							<div class="form-group">
							    <label for="txtNamaKapal"><u>Vessel Name :</u></label>
							    <select name="slcVessel" id="slcVessel" class="form-control input-sm">
							    	<option value="">- Select Vessel -</option>
							    	<?php echo $vessel; ?>
							    </select>
							</div>
							<div class="form-group">
							    <label for="slcBtnExport"><u>Button Export :</u></label>
							    <select name="slcBtnExport" id="slcBtnExport" class="form-control input-sm">
							    	<option value="n">No</option>
							    	<option value="y">Yes</option>
							    </select>
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
	</section>
</body>
</html>

