<?php require('menu.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#idLoading").hide();
			$("#idFormSearch").hide();
			$("#btnUnrec").attr("disabled","disabled");
		    $( "#txtStartDate" ).datepicker({
				dateFormat: 'yymmdd',
		        showButtonPanel: true,
		        changeMonth: true,
		        changeYear: true,
		        defaultDate: new Date(),
		    });
		    $( "#txtEndDate" ).datepicker({
				dateFormat: 'yymmdd',
		        showButtonPanel: true,
		        changeMonth: true,
		        changeYear: true,
		        defaultDate: new Date(),
		    });
		    $("#btnSearchOpenForm").click(function(){
		    	$("#idFormSearch").show();
		    });
		    $("#btnSearch").click(function(){
		    	var sDateSearch = $("#txtStartDate").val();
		    	var eDateSearch = $("#txtEndDate").val();

		    	if (sDateSearch == "" & eDateSearch == "")
		    	{
		    		alert("Date Search Empty..!!");
		    		return false;
		    	}
		    	if (sDateSearch == "" & eDateSearch != "")
		    	{
		    		alert("From Date Empty..!!");
		    		return false;
		    	}
		    	else if(sDateSearch != "" & eDateSearch == "")
		    	{
		    		alert("To Date Empty..!!");
		    		return false;
		    	}		    	
		    	$("#idTbody").empty();
		    	$("#idLoading").show();
		    	// return false;
		    	$.post('<?php echo base_url("myapps/getMailRegInv/search"); ?>',
				{   
					sDateSearch : sDateSearch,eDateSearch : eDateSearch
				},
					function(data) 
					{
						var html = data.trNya;
						$('#idTbody').append(html);
						$("#idLoading").hide();
					},
				"json"
				);
		    });
		    $("#btnCancelSearch").click(function(){
		    	reloadPage();
		    });
		    $("#btnUnrec").click(function(){
		    	var dtChecked = [];
		        $(':checkbox:checked').each(function(i){
		          dtChecked[i] = $(this).val();
		        });
		        $.post('<?php echo base_url("myapps/unReceive"); ?>',
				{ dtChecked : dtChecked },
					function(data) 
					{
						alert(data);
						reloadPage();
					},
				"json"
				);
		    });
		});
		function receviceNya(id)
		{
			var cfm = confirm("Sure Accepted..??");
			if(cfm)
			{
				$.post('<?php echo base_url("myapps/updateDataReceive"); ?>',
				{   
					id : id
				},
					function(data) 
					{	
						window.location = "<?php echo base_url("/myapps/getMailRegInv");?>";
					},
				"json"
				);
			}
		}
		function cekCheck()
		{
			var cekCheck = [];
		   	$(':checkbox:checked').each(function(i){
		    	cekCheck[i] = $(this).val();
		   	});
		   	if(cekCheck.length == 0)
		   	{
		   		$("#btnUnrec").attr("disabled",true);
		   	}else{
		   		$("#btnUnrec").attr("disabled",false);
		   	}
		}
		function reloadPage()
		{
			window.location = "<?php echo site_url('myapps/getMailRegInv');?>";
		}
	</script>
</head>
<body>
	<section id="container">
		<section id="main-content">
			<section class="wrapper site-min-height" style="min-height:400px;">
				<h3>
					<i class="fa fa-angle-right"></i> Mail Register & Invoice Distribution<span style="padding-left: 20px;" id="idLoading"><img src="<?php echo base_url('assets/img/loading.gif'); ?>" ></span>
				</h3>
				<div class="form-panel" id="idDataTable">
					<div class="row">
						<div class="col-md-12" id="btnNavAtas">
							<button type="button" id="btnSearchOpenForm" class="btn btn-warning btn-sm" title="Add"><i class="fa fa-search"></i> Search Data</button>
							<button style="float: right;" type="submit" id="btnUnrec" class="btn btn-primary btn-sm" title="Un Received"><i class="fa fa-check-square-o"></i> Un Received</button>
						</div>
						<div id="idFormSearch">
							<dir class="col-md-2 col-xs-12">
								<input placeholder="From Date" type="text" class="form-control input-sm" id="txtStartDate" name="txtStartDate" value="">
							</dir>
							<dir class="col-md-2 col-xs-12">
								<input placeholder="To Date" type="text" class="form-control input-sm" id="txtEndDate" name="txtEndDate" value="">
							</dir>
							<dir class="col-md-3 col-xs-12">
								<button type="submit" id="btnSearch" class="btn btn-primary btn-sm" title="Add"><i class="fa fa-search"></i> Search</button>
								<button type="button" id="btnCancelSearch" class="btn btn-danger btn-sm" title="Cancel"><i class="fa fa-ban"></i> Cancel</button>
								<button type="button" id="btnCancelSearch" onclick="reloadPage();" class="btn btn-success btn-sm" title="Cancel"><i class="fa fa-ban"></i> Refresh</button>
							</dir>
						</div>
					</div>
					<div class="row mt" id="idData1">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
									<thead>
										<tr style="background-color: #ba5500;color: #FFF;">
											<th style="vertical-align: middle; width:3%;text-align: center;padding: 10px;">SNo</th>
											<th style="vertical-align: middle; width:40%;text-align: center;">Sender / Remark</th>
											<th style="vertical-align: middle; width:8%;text-align: center;">Mail ID</th>
											<th style="vertical-align: middle; width:15%;text-align: center;">Invoice No / Amount</th>
											<th style="vertical-align: middle; width:10%;text-align: center;">Batch No</th>
											<th colspan="2" style="vertical-align: middle; width:15%;text-align: center;">Receive By</th>
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

