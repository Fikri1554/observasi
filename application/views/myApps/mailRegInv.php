<?php $this->load->view('myApps/menu'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#idLoading").hide();
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
				$(this).hide(100);
		    	$("#idFormSearch").show();
		    });
		    $("#btnCancelSearch").click(function(){
		    	reloadPage();
		    });
		    $("#btnUnrec").click(function(){
		    	var sDateSearch = $("#txtStartDate").val();
		    	var eDateSearch = $("#txtEndDate").val();
		    	var dtChecked = [];
		        $(':checkbox:checked').each(function(i){
		          dtChecked[i] = $(this).val();
		        });
		        $.post('<?php echo base_url("myapps/unReceive"); ?>',
				{ dtChecked : dtChecked },
					function(data) 
					{
						alert(data);
						if (sDateSearch == "" & eDateSearch == "")
					    {
					    	reloadPage();
					    }else{
					    	searchData();
					    	$("#txtIdMailModal").val('');
							$('#idModalAccept').modal("hide");
					    }
					},
				"json"
				);
		    });
		});
		function searchData()
		{
			var searchUnit = $("#slcSearchUnit").val();
			var sDateSearch = $("#txtStartDate").val();
		    var eDateSearch = $("#txtEndDate").val();

		    if(searchUnit == "")
		    {
		    	alert("Unit Empty..!!");
		    	return false;
		    }

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
				searchUnit : searchUnit,sDateSearch : sDateSearch,eDateSearch : eDateSearch
			},
				function(data) 
				{
					var html = data.trNya;
					$('#idTbody').append(html);
					$("#idLoading").hide();
				},
			"json"
			);
		}
		function showModalAccept(id)
		{
			$("#txtIdMailModal").val('');
			$("#txtIdMailModal").val(id);
			$('#idModalAccept').modal("show");
		}
		function showModalReject(id)
		{
			$("#txtIdMailModalReject").val('');
			$("#txtIdMailModalReject").val(id);
			$('#idModalReject').modal("show");
		}
		function acceptNya()
		{
			var idMail = $("#txtIdMailModal").val();
			var txtReason = $("#txtReason").val();
			var sDateSearch = $("#txtStartDate").val();
		    var eDateSearch = $("#txtEndDate").val();

			$.post('<?php echo base_url("myapps/updateDataReceive"); ?>',
			{ id : idMail,txtReason : txtReason },
				function(data) 
				{
					alert(data);
					if (sDateSearch == "" & eDateSearch == "")
				    {
				    	window.location = "<?php echo base_url("/myapps/getMailRegInv");?>";
				    }else{
				    	searchData();
				    	$("#txtIdMailModal").val('');
				    	$("#txtReason").val('');
						$('#idModalAccept').modal("hide");
				    }
				},
			"json"
			);
		}
		function rejectNya()
		{
			var idMail = $("#txtIdMailModalReject").val();
			var txtReason = $("#txtReasonReject").val();
			var sDateSearch = $("#txtStartDate").val();
		    var eDateSearch = $("#txtEndDate").val();

			if(txtReason == "")
			{
				alert("Reason Empty..!!");
				return false;
			}

			$.post('<?php echo base_url("myapps/updateDataReject"); ?>',
			{ id : idMail,txtReason : txtReason },
				function(data) 
				{
					alert(data);
					if (sDateSearch == "" & eDateSearch == "")
				    {
				    	window.location = "<?php echo base_url("/myapps/getMailRegInv");?>";
				    }else{
				    	searchData();
				    	$("#txtIdMailModalReject").val('');
				    	$("#txtReasonReject").val('');
						$('#idModalReject').modal("hide");
				    }
				},
			"json"
			);
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
						<div class="col-md-2 col-xs-12" style="margin-top:5px;">
							<select class="form-control input-sm" id="slcSearchUnit">
								<?php echo $optUnit; ?>
							</select>
						</div>
						<div class="col-md-2 col-xs-12" style="margin-top:5px;">
							<input placeholder="From Date" autocomplete="off" type="text" class="form-control input-sm" id="txtStartDate" name="txtStartDate" value="">
						</div>
						<div class="col-md-2 col-xs-12" style="margin-top:5px;">
							<input placeholder="To Date" autocomplete="off" type="text" class="form-control input-sm" id="txtEndDate" name="txtEndDate" value="">
						</div>
						<div class="col-md-4 col-xs-12" style="margin-top:5px;">
							<button type="submit" id="btnSearch" onclick="searchData();" class="btn btn-primary btn-sm" title="Add"><i class="fa fa-search"></i> Search</button>
							<button type="button" id="btnCancelSearch" class="btn btn-danger btn-sm" title="Cancel"><i class="fa fa-ban"></i> Cancel</button>
							<button type="button" id="btnCancelSearch" onclick="reloadPage();" class="btn btn-success btn-sm" title="Cancel"><i class="fa fa-ban"></i> Refresh</button>
						</div>
						<div class="col-md-2 col-xs-12" id="btnNavAtas" style="margin-top:5px;">
							<button style="float: right;" type="submit" id="btnUnrec" class="btn btn-primary btn-sm" title="Un Accept"><i class="fa fa-check-square-o"></i> Un Accept</button>
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
											<th colspan="2" style="vertical-align: middle; width:15%;text-align: center;">Status</th>
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
	<div class="modal fade" id="idModalAccept" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
		        <div class="modal-header" style="background-color:#d56b03;">
		          <button type="button" class="close" data-dismiss="modal" style="color:#FFF;">&times;</button>
		          <h4 class="modal-title" id="idTtitleModal"><i>:: Accept Mail & Invoice ::</i></h4>
		        </div>
		        <div class="modal-body">
		          <div class="row">
		          	<div class="col-md-12 col-xs-12">
		          		<input type="hidden" value="" id="txtIdMailModal">
		          		<label for="txtReason">Reason :</label>
		          		<textarea class="form-control input-sm" id="txtReason"></textarea>
		          	</div>
		          </div>
		          <div class="row" style="margin-top:10px;">
		          	<div class="col-md-6 col-xs-12">
		          		<button type="button" id="btnAcceptModal" class="btn btn-primary btn-xs btn-block" onclick="acceptNya();" title="Accept"><i class="fa fa-check-square-o"></i> Accept</button>
		          	</div>
		          	<div class="col-md-6 col-xs-12">
		          		<button type="button" id="btnCancelModal" class="btn btn-danger btn-xs btn-block" onclick="reloadPage();" title="Cancel"><i class="fa fa-times"></i> Cancel</button>
		          	</div>
		          </div>
		        </div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="idModalReject" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
		        <div class="modal-header" style="background-color:#d56b03;">
		          <button type="button" class="close" data-dismiss="modal" style="color:#FFF;">&times;</button>
		          <h4 class="modal-title" id="idTtitleModal"><i>:: Reject Mail & Invoice ::</i></h4>
		        </div>
		        <div class="modal-body">
		          <div class="row">
		          	<div class="col-md-12 col-xs-12">
		          		<input type="hidden" value="" id="txtIdMailModalReject">
		          		<label for="txtReasonReject">Reason :</label>
		          		<textarea class="form-control input-sm" id="txtReasonReject"></textarea>
		          	</div>
		          </div>
		          <div class="row" style="margin-top:10px;">
		          	<div class="col-md-6 col-xs-12">
		          		<button type="button" id="btnRejectModal" class="btn btn-primary btn-xs btn-block" onclick="rejectNya();" title="Reject"><i class="fa fa-check-square-o"></i> Reject</button>
		          	</div>
		          	<div class="col-md-6 col-xs-12">
		          		<button type="button" id="btnCancelModalReject" class="btn btn-danger btn-xs btn-block" onclick="reloadPage();" title="Cancel"><i class="fa fa-times"></i> Cancel</button>
		          	</div>
		          </div>
		        </div>
			</div>
		</div>
	</div>
</body>
</html>

