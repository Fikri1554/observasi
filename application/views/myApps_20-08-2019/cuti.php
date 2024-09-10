<?php require('menu.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#idLoading").hide();
			$( "#txtStartDate" ).datepicker({
				dateFormat: 'yy-mm-dd',
		        showButtonPanel: true,
		        changeMonth: true,
		        changeYear: true,
		        defaultDate: new Date(),
		    });
		    $( "#txtEndDate" ).datepicker({
				dateFormat: 'yy-mm-dd',
		        showButtonPanel: true,
		        changeMonth: true,
		        changeYear: true,
		        defaultDate: new Date(),
		    });
		    $("#btnSearch").click(function(){		    	
		    	$("#idTbody").empty();
		    	var stCuti = $("#slcStatus").val();
		    	var searchName = $("#txtSearchName").val();
		    	var sDate = $("#txtStartDate").val();
		    	var eDate = $("#txtEndDate").val();

		    	// if(sDate == ""){ alert("Start Date empty..!!");return false; }
		    	// if(eDate == ""){ alert("Start Date empty..!!");return false; }
		    	
		    	$("#idLoading").show();
		    	$.post('<?php echo base_url("myapps/getCuti/search"); ?>',
				{   
					stCuti : stCuti,searchName : searchName,sDate : sDate,eDate : eDate
				},
					function(data) 
					{	
						$("#idTbody").append(data);
						$("#idLoading").hide();
					},
				"json"
				);
		    });
		});
		function actionCuti(empNo,sDate,eDate)
		{
			var st = confirm("approved..??");
			if(st)
			{
				$.post('<?php echo base_url("myapps/approve"); ?>',
				{   
					empNo : empNo,sDate : sDate,eDate : eDate
				},
					function(data) 
					{	
						reloadPage();
					},
				"json"
				);
			}
			
		}
		function actionReject(empNo,sDate,eDate)
		{
			var st = prompt("Reject Reason :", "");
			if(st != null && st != '')
			{
				var remark = st;
				$.post('<?php echo base_url("myapps/reject"); ?>',
				{   
					empNo : empNo,sDate : sDate,eDate : eDate,remark : remark
				},
					function(data) 
					{	
						reloadPage();
					},
				"json"
				);
			}else{
				if(st == '')
				{
					alert("Reason Empty..!!");
					return false;
				}				
			}
		}
		function reloadPage()
		{
			window.location = "<?php echo site_url('myapps/getCuti');?>";
		}
	</script>
</head>
<body>
	<section id="container">
		<section id="main-content">
			<section class="wrapper site-min-height" style="min-height:400px;">
				<h3>
					<i class="fa fa-angle-right"></i> Informasi Cuti<span style="padding-left: 20px;" id="idLoading"><img src="<?php echo base_url('assets/img/loading.gif'); ?>" ></span>
				</h3>
				<div class="form-panel" id="idDataTable">
					<div class="row mt" id="idData1">
						<div id="idFormSearch">
							<dir class="col-md-2 col-xs-12">
								<select name="slcStatus" id="slcStatus" class="form-control input-sm">
									<option value="">All</option>
									<option value="P">Pending</option>
									<option value="A">Approved</option>
									<option value="C">Reject</option>
								</select>
							</dir>
							<dir class="col-md-2 col-xs-12">
								<input placeholder="By Name" type="text" class="form-control input-sm" id="txtSearchName" name="txtSearchName" value="">
							</dir>
							<dir class="col-md-2 col-xs-12">
								<input placeholder="Start Date" type="text" class="form-control input-sm" id="txtStartDate" name="txtStartDate" value="">
							</dir>
							<dir class="col-md-2 col-xs-12">
								<input placeholder="End Date" type="text" class="form-control input-sm" id="txtEndDate" name="txtEndDate" value="">
							</dir>
							<dir class="col-md-3 col-xs-12">
								<button type="submit" id="btnSearch" class="btn btn-primary btn-sm" title="Add"><i class="fa fa-search"></i> Search</button>
								<button type="button" id="btnCancelSearch" onclick="reloadPage();" class="btn btn-success btn-sm" title="Cancel"><i class="fa fa-refresh"></i> Refresh</button>
							</dir>
						</div>
						<div class="col-md-12 col-xs-12">
							<div class="table-responsive">
								<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
									<thead>
										<tr style="background-color: #ba5500;color: #FFF;">
											<th style="vertical-align: middle; width:3%;text-align: center;padding: 10px;">No</th>
											<th style="vertical-align: middle; width:15%;text-align: center;">Nama</th>
											<th style="vertical-align: middle; width:10%;text-align: center;">Mulai</th>
											<th style="vertical-align: middle; width:10%;text-align: center;">Akhir</th>
											<th style="vertical-align: middle; width:8%;text-align: center;">Jumlah hari</th>
											<th style="vertical-align: middle; width:20%;text-align: center;">Keterangan</th>
											<th style="vertical-align: middle; width:8%;text-align: center;">Status</th>
											<th colspan="2" style="vertical-align: middle; width:10%; text-align: center;">Action</th>
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
			</section>
		</section>
	</section>
</body>
</html>

