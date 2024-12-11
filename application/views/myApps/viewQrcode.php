<?php $this->load->view('myApps/menu'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script type="text/javascript">
		$(document).ready(function(){

			$("#btnSave").click(function(){
				
				var idEdit = $("#txtIdEdit").val();
				var fullName = $("#txtFullname").val();
				var hp = $("#txtHP").val();
				var phoneHome = $("#txtPhoneHome").val();
				var phoneOffice = $("#txtPhoneOffice").val();
				var address = $("#txtAddress").val();
				var office = $("#txtOffice").val();
				var Position = $("#txtPosition").val();
				var email = $("#txtEmail").val();
				var city = $("#txtCity").val();
				var kodePos = $("#txtPostCode").val();
				var country = $("#txtCountry").val();

				$("#idLoading").show();

				$.post('<?php echo base_url("cqrcode/saveData"); ?>',
				{   
					idEdit : idEdit,fullName : fullName,hp : hp,phoneHome : phoneHome,phoneOffice : phoneOffice,address : address,office : office,Position : Position,email : email,city : city,kodePos : kodePos,country : country
				},
					function(data) 
					{	
						alert(data);
						clearForm();
						reloadPage();
					},
				"json"
				);
			});
		    $("#btnAddData").click(function(){
		    	$("#idDataTable").hide(250);
		    	$("#idForm").show(350);
		    });
		});
		function editData(id)
		{
			$("#idLoading").show();
			$.post('<?php echo base_url("cqrcode/getDataEdit"); ?>',
			{ idEdit : id },
				function(data)
				{
					$("#txtIdEdit").val(data['rsl'][0].id);
					$("#txtFullname").val(data['rsl'][0].fullname);
					$("#txtHP").val(data['rsl'][0].hp);
					$("#txtPhoneHome").val(data['rsl'][0].phone_home);
					$("#txtPhoneOffice").val(data['rsl'][0].phone_office);
					$("#txtAddress").val(data['rsl'][0].address);
					$("#txtOffice").val(data['rsl'][0].office);
					$("#txtPosition").val(data['rsl'][0].position);
					$("#txtEmail").val(data['rsl'][0].email);
					$("#txtCity").val(data['rsl'][0].city);
					$("#txtPostCode").val(data['rsl'][0].kode_pos);
					$("#txtCountry").val(data['rsl'][0].country);

					$("#idDataTable").hide(250);
		    		$("#idForm").show(350);

					$("#idLoading").hide();
				},
				"json"
			);
		}
		function viewQrCode(id)
		{
			$("#idLoading").show();
			$.post('<?php echo base_url("cqrcode/createQrCode"); ?>/json',
			{ id : id },
				function(data)
				{
					$("#idImgViewQrCode").attr("src",data);
					$('#idModalShowQrCode').modal("show");
					$("#idLoading").hide();
				},
				"json"
			);
		}
		function delData(id)
		{
			var cfm = confirm("Yakin Hapus..??");
			$("#idLoading").show();
			if(cfm)
			{
				$.post('<?php echo base_url("cqrcode/delFile"); ?>',
				{ id : id },
				function(data) 
				{
					reloadPage();
				},
				"json"
				);
			}			
		}
		function clearForm()
		{
			$("#txtIdEdit").val('');
			$("#txtFullname").val('');
			$("#txtHP").val('');
			$("#txtPhoneHome").val('');
			$("#txtPhoneOffice").val('');
			$("#txtAddress").val('');
			$("#txtOffice").val('');
			$("#txtPosition").val('');
			$("#txtEmail").val('');
			$("#txtCity").val('');
			$("#txtPostCode").val('');
			$("#txtCountry").val('');			
		}
		function reloadPage()
		{
			window.location = "<?php echo base_url('cqrcode');?>";
		}
	</script>
</head>
<body>
	<section id="container">
		<section id="main-content">
			<section class="wrapper site-min-height" style="min-height:400px;">
				<h3>
					<i class="fa fa-angle-right"></i> 
					QR CODE
					<img src="<?php echo base_url('assets/img/loading.gif'); ?>" id="idLoading" style="display:none;">
				</h3>
				<div class="form-panel" id="idDataTable">
					<div class="row">
						<div class="col-md-2 col-xs-12">
							<button type="button" id="btnAddData" class="btn btn-primary btn-sm btn-block" title="Add"><i class="fa fa-plus-square"></i> Add Data</button>
						</div>
						<div class="col-md-2 col-xs-7">
							<input type="text" id="txtSearch" class="form-control input-s" value="" placeholder="Fullname">
						</div>
						<div class="col-md-2 col-xs-5">
							<button type="button" onclick="alert();" class="btn btn-info btn-sm btn-block" title="Search Data">
								<i class="fa fa-search"></i> Search
							</button>
						</div>
						<div class="col-md-2" id="btnNavAtas">
							<button type="button" id="idBtnRefresh" onclick="reloadPage();" class="btn btn-success btn-sm btn-block" title="Refresh"><i class="glyphicon glyphicon-refresh"></i> Refresh</button>
						</div>
					</div>
					<div class="row mt" id="idData1">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
									<thead>
										<tr style="background-color: #ba5500;color: #FFF;">
											<th style="vertical-align: middle; width:3%;text-align:center;padding:10px;">No</th>
											<th style="vertical-align: middle; width:15%;text-align:center;">Fullname</th>
											<th style="vertical-align: middle; width:15%;text-align:center;">Phone</th>
											<th style="vertical-align: middle; width:20%;text-align:center;">Email</th>
											<th style="vertical-align: middle; width:40%;text-align:center;">Address</th>											
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
				<div class="row" id="idForm" style="display:none;">
					<div class="col-md-12">
						<div class="form-panel">
							<legend><label id="lblForm"> Create Qr Code</label></legend>
							<div class="row">
								<div class="col-md-2 col-xs-12">
									<div class="form-group">
									    <label for="txtFullname"><u>Fullname :</u></label>
									    <input type="input" class="form-control input-sm" id="txtFullname" value="" placeholder="Fullname">
									</div>
								</div>
								<div class="col-md-2 col-xs-12">
									<div class="form-group">
									    <label for="txtHP"><u>Hand Phone :</u></label>
									    <input type="input" class="form-control input-sm" id="txtHP" value="" placeholder="08****">
									</div>
								</div>
								<div class="col-md-2 col-xs-12">
									<div class="form-group">
									    <label for="txtPhoneHome"><u>Phone Home :</u></label>
									    <input type="input" class="form-control input-sm" id="txtPhoneHome" value="" placeholder="0****">
									</div>
								</div>
								<div class="col-md-2 col-xs-12">
									<div class="form-group">
									    <label for="txtPhoneOffice"><u>Phone Office :</u></label>
									    <input type="input" class="form-control input-sm" id="txtPhoneOffice" value="" placeholder="0****">
									</div>
								</div>
								<div class="col-md-4 col-xs-12">
									<div class="form-group">
									    <label for="txtAddress"><u>Address :</u></label>
									    <textarea class="form-control input-sm" id="txtAddress"></textarea>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2 col-xs-12">
									<div class="form-group">
									    <label for="txtOffice"><u>Organization / Office :</u></label>
									    <input type="input" class="form-control input-sm" id="txtOffice" value="" placeholder="Office">
									</div>
								</div>
								<div class="col-md-2 col-xs-12">
									<div class="form-group">
									    <label for="txtPosition"><u>Position :</u></label>
									    <input type="input" class="form-control input-sm" id="txtPosition" value="" placeholder="Position">
									</div>
								</div>
								<div class="col-md-2 col-xs-12">
									<div class="form-group">
									    <label for="txtEmail"><u>Email :</u></label>
									    <input type="input" class="form-control input-sm" id="txtEmail" value="" placeholder="Email">
									</div>
								</div>
								<div class="col-md-2 col-xs-12">
									<div class="form-group">
									    <label for="txtCity"><u>City :</u></label>
									    <input type="input" class="form-control input-sm" id="txtCity" value="" placeholder="City">
									</div>
								</div>
								<div class="col-md-2 col-xs-12">
									<div class="form-group">
									    <label for="txtPostCode"><u>Post Code :</u></label>
									    <input type="input" class="form-control input-sm" id="txtPostCode" value="" placeholder="Code">
									</div>
								</div>
								<div class="col-md-2 col-xs-12">
									<div class="form-group">
									    <label for="txtCountry"><u>Country :</u></label>
									    <input type="input" class="form-control input-sm" id="txtCountry" value="" placeholder="Country">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 col-xs-6">
									<input type="hidden" name="" id="txtIdEdit" value="">
									<button id="btnSave" class="btn btn-primary btn-xs btn-block" title="Save">
										<i class="fa fa-check-square-o"></i>
										Save
									</button>
								</div>
								<div class="col-md-6 col-xs-6">
									<button id="btnCancel" onclick="reloadPage();" class="btn btn-danger btn-xs btn-block" title="Cancel">
										<i class="fa fa-ban"></i>
										Cancel
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</section>
	</section>
	<div class="modal fade" id="idModalShowQrCode" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
		        <div class="modal-header" style="background-color:#d56b03;">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title" id="idTtitleModal">View QR Code</h4>
		        </div>
		        <div class="modal-body">
		          <div class="row">
		          	<div class="col-md-12 col-xs-12">
		          		<img src="" id="idImgViewQrCode" style="width:100%;">
		          	</div>
		          </div>
		        </div>
			</div>
		</div>
	</div>
</body>
</html>

