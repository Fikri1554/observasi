<?php $this->load->view('myApps/menu'); ?>
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
		    $("#idBtnProses").click(function(){
		    	var sDate,eDate,ijinName,kdIjin = "";
		    	sDate = $("#txtSdate").val();
		    	eDate = $("#txtEdate").val();
		    	ijinName = $("#slcIjin option:selected").text();
		    	kdIjin = $("#slcIjin").val();
		    	$.post('<?php echo base_url("ijin/saveIjin"); ?>',
				{   
					sDate : sDate,eDate : eDate,ijinName : ijinName,kdIjin : kdIjin
				},
					function(data) 
					{
						alert(data);
						reloadPage();
					},
				"json"
				);
		    });
		    $("#slcIjin").change(function(){
		    	$("#idLoading").show();
		    	$("#idBody").empty();
		    	$("#idLabel").text("");
		    	$("#idLblJml").text("");
		    	$("#txtKetSlcIjin").val("");
		    	if($("#txtStartDate").val() == "" )
		    	{
		    		alert("Start Date empty..!!");
		    		$("#idLoading").hide();
		    		return false;
		    	}
		    	if($(this).val() == "")
		    	{
		    		$("#idLoading").hide();		    		
		    	}else{
			    	var slcIjin = $("#slcIjin option:selected").text();
			    	var sDate = $("#txtStartDate").val();
			    	var slcValue = $("#slcIjin").val();
			    	$("#txtKetSlcIjin").val(slcIjin);
			    	$.post('<?php echo base_url("ijin/searchIjin"); ?>',
					{   
						slcValue : slcValue,sDate : sDate
					},
						function(data) 
						{
							$("#txtSdate").val(data.sDate);
							$("#txtEdate").val(data.eDate);
							$("#idLabel").text(data.durasiDate);
							$("#idLblJml").text(data.teksTotal);
							$("#idBody").append(data.dataDetail);
					    	$("#idLoading").hide();
						},
					"json"
					);
			    }
		    });
		});
		function reloadPage()
		{
			window.location = "<?php echo base_url('ijin/requestIjin');?>";
		}
	</script>
</head>
<body>
	<section id="container">
		<section id="main-content">
			<section class="wrapper site-min-height" style="min-height:400px;">
				<h3>
					<i class="fa fa-angle-right"></i> Pengajuan Ijin<span style="padding-left: 20px;" id="idLoading"><img src="<?php echo base_url('assets/img/loading.gif'); ?>" ></span>
				</h3>
				<div class="form-panel" id="idDataTable">
					<div class="row" id="idData1">
						<div id="idFormSearch">
							<dir class="col-md-2 col-xs-12">
								<input placeholder="Start Date" type="text" class="form-control input-sm" id="txtStartDate" name="txtStartDate" value="">
							</dir>
							<dir class="col-md-4 col-xs-12">
								<select name="slcIjin" id="slcIjin" class="form-control input-sm">
									<option value="">- Select -</option>
									<?php echo $optIjin; ?>
								</select>
							</dir>
							<dir class="col-md-6 col-xs-12">
								<input type="text" class="form-control input-sm" id="txtKetSlcIjin" name="txtKetSlcIjin" value="">
							</dir>
							<dir class="col-md-2 col-xs-6">
								<button type="button" id="idBtnProses" class="btn btn-primary btn-sm btn-block" title="Proses"><i class="fa fa-check-square-o" style="margin-right: 5px;"></i> P r o s e s</button>
								<input type="hidden" name="txtSdate" id="txtSdate">
								<input type="hidden" name="txtEdate" id="txtEdate">
							</dir>
							<dir class="col-md-2 col-xs-6">
								<button type="button" id="idBtnRefresh" onclick="reloadPage();" class="btn btn-success btn-sm btn-block" title="Cancel"><i class="fa fa-refresh"></i> R e f r e s h</button>
							</dir>							
							<dir class="col-md-8 col-xs-12" style="text-align: center;">
								<label id="idLabel" ></label><br>
								<label id="idLblJml" ></label>
							</dir>
						</div>
						<div class="col-md-12 col-xs-12">
							<div class="table-responsive">
								<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
									<thead>
										<tr style="background-color: #ba5500;color: #FFF;">
											<th style="vertical-align:middle;width:3%;text-align:center;padding:10px;">No</th>
											<th style="vertical-align:middle;width:10%;text-align:center;">Tanggal</th>
											<th style="vertical-align:middle;width:10%;text-align:center;">Hari</th>
											<th style="vertical-align:middle;width:20%;text-align:center;">Keterangan</th>
										</tr>
									</thead>
									<tbody id="idBody">
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

