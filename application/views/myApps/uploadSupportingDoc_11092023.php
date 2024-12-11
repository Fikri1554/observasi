<?php $this->load->view('myApps/menu'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#slcTypeDocSearch").change(function(){
				var slcType = $(this).val();
				$("#txtBarcodeSearch").val("");
				if(slcType != "")
				{
					if(slcType == "voucher")
					{
						$("#txtBarcodeSearch").attr('placeholder','Batch no');
					}else{
						$("#txtBarcodeSearch").attr('placeholder','Barcode no');
					}
				}else{
					$("#txtBarcodeSearch").attr('placeholder','Search');
				}
			});
		});
		function searchData()
		{
			var slcType = $("#slcTypeDocSearch").val();
			var txtBarcode = $("#txtBarcodeSearch").val();

			if(slcType == "")
			{
				alert("Type Document Empty..!!");
				return false;
			}else{
				if(txtBarcode == "")
				{
					alert("Search Empty..!!");
					return false;
				}
			}

		    $("#idTbody").empty();
		    $("#idLoading").show();

		    $.post('<?php echo base_url("myapps/getUploadSupportingDoc/search"); ?>',
			{ 
				txtBarcode : txtBarcode, slcType:slcType
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
		function saveUploadFile()
		{
			var id = $("#txtIdModal").val();
			var barcode = $("#txtBarcodeModal").val();
			var typeDoc = $("#txtTypeDoc").val();
			var formData = new FormData();

			var fileUploadNya = $("#uploadFile").prop('files')[0];
			var cekFile = $("#uploadFile").val();
			var remark = $("#txtRemark").val();

		 	$("#idLoadingModal").show();

			formData.append('typeDoc',typeDoc);
			formData.append('id',id);
			formData.append('barcode',barcode);
			formData.append('cekFile',cekFile);
        	formData.append('fileUploadNya',fileUploadNya);
        	formData.append('remark',remark);

        	$("#idBtnSaveModalFile").attr('disabled','disabled');
        	$.ajax("<?php echo base_url('myapps/updateDataModalSupportingDoc'); ?>",{
		        method: "POST",
		        data: formData,
		        cache: false,
		        contentType: false,
		        processData: false,
		    	success: function(response){
		            alert(response);
		        	reloadPage();
		    	}
		  	});
		}
		function showModalUpload(id,barcode)
		{
			$("#idLoading").show();
			$.post('<?php echo base_url("myapps/cekSesseion"); ?>',
			{ },
				function(data) 
				{
					if(data == "ada")
					{
						getDataModal(id);
						$("#txtTypeDoc").val("invoiceRegister");
						$("#txtIdModal").val(id);
						$("#txtBarcodeModal").val(barcode);
						$('#idModalUpload').modal("show");
						$("#idLoading").hide();
					}else{
						alert("Session Expired, Please Login..!!");
						reloadPage();
					}					
				},
			"json"
			);
		}
		function getDataModal(id)
		{
			$.post('<?php echo base_url("myapps/getDataModalSupportingDoc/invoiceRegister"); ?>',
			{ id : id },
				function(data) 
				{
					$("#lblModalBatchNo").text(data.batchNo);
					$("#lblModalBarcode").text(data.barcode);
					$("#lblModalSenderVendor").text(data.senderVendor);
					$("#lblModalCompany").text(data.company);
					$("#lblModalInvNo").text(data.invNo);
					$("#lblModalAmount").html(data.amountNya);
					$("#txtRemark").val(data.remark);
				},
			"json"
			);
		}
		function showModalUploadVoucher(id,barcode)
		{
			$("#idLoading").show();
			$.post('<?php echo base_url("myapps/cekSesseion"); ?>',
			{ },
				function(data) 
				{
					$("#idDivRemark").css('display','none');
					if(data == "ada")
					{
						getDataModalVoucher(id);
						$("#txtTypeDoc").val("voucher");
						$("#txtIdModal").val(id);
						$("#txtBarcodeModal").val(barcode);
						$('#idModalUpload').modal("show");
						$("#idLoading").hide();
					}else{
						alert("Session Expired, Please Login..!!");
						reloadPage();
					}					
				},
			"json"
			);
		}
		function getDataModalVoucher(id)
		{
			$.post('<?php echo base_url("myapps/getDataModalSupportingDoc/voucher"); ?>',
			{ id : id },
				function(data) 
				{
					$("#lblModalBatchNo").text(data.batchNo);
					$("#lblModalBarcode").text(data.barcode);
					$("#lblModalSenderVendor").text(data.senderVendor);
					$("#lblModalCompany").text(data.company);
					$("#lblModalInvNo").text(data.invNo);
					$("#lblModalAmount").html(data.amountNya);
					$("#txtRemark").val(data.remark);
				},
			"json"
			);
		}
		function reloadPage()
		{
			window.location = "<?php echo base_url('myapps/getUploadSupportingDoc');?>";
		}
	</script>
</head>
<body>
	<section id="container">
		<section id="main-content">
			<section class="wrapper site-min-height" style="min-height:400px;">
				<h3>
					<i class="fa fa-angle-right"></i> Upload Supporting Document<span style="padding-left:20px;display:none;" id="idLoading"><img src="<?php echo base_url('assets/img/loading.gif'); ?>" ></span>
				</h3>
				<div class="form-panel" id="idDataTable">
					<div class="row">
						<div class="col-md-2 col-xs-12" style="margin-top:5px;">
							<select class="form-control input-sm" id="slcTypeDocSearch">
								<option value="">- Type Document -</option>
								<option value="invoiceRegister">Invoice Register</option>
								<option value="voucher">Voucher</option>
							</select>
						</div>
						<div class="col-md-3 col-xs-12" style="margin-top:5px;">
							<input placeholder="Search" autocomplete="off" type="text" class="form-control input-sm" id="txtBarcodeSearch" value="">
						</div>
						<div class="col-md-4 col-xs-12" style="margin-top:5px;">							
							<button type="submit" id="btnSearch" onclick="searchData();" class="btn btn-primary btn-sm" title="Search"><i class="fa fa-search"></i> Search</button>
							<button type="button" id="btnCancelSearch" onclick="reloadPage();" class="btn btn-success btn-sm" title="Refresh"><i class="fa fa-refresh"></i> Refresh</button>
						</div>
					</div>
					<div class="row mt" id="idData1">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
									<thead>
										<tr style="background-color: #ba5500;color: #FFF;">
											<th style="vertical-align: middle; width:5%;text-align: center;padding: 10px;">No</th>
											<th style="vertical-align: middle; width:10%;text-align: center;">BATCH NO</th>
											<th style="vertical-align: middle; width:25%;text-align: center;">SENDER / VENDOR NAME</th>
											<th style="vertical-align: middle; width:10%;text-align: center;">BARCODE</th>
											<th style="vertical-align: middle; width:20%;text-align: center;">COMPANY</th>
											<th style="vertical-align: middle; width:15%;text-align: center;">INV. NUMBER</th>
											<th style="vertical-align: middle; width:15%;text-align: center;">AMOUNT</th>
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
	<div class="modal fade" id="idModalUpload" role="dialog">
		<div class="modal-dialog  modal-lg">
			<div class="modal-content">
		        <div class="modal-header" style="background-color:#d56b03;">
		          <button type="button" class="close" data-dismiss="modal" style="color:#FFF;">&times;</button>
		          <h4 class="modal-title" id="idTtitleModal">
		          	<i>:: UPLOAD SUPPORTING DOCUMENT ::</i>
		          	<img id="idLoadingModal" style="display:none;" src="<?php echo base_url('assets/img/loading.gif'); ?>">
		          </h4>
		        </div>
		        <div class="modal-body">
		          <div class="row" style="font-size:12px;">
		          	<div class="col-md-6 col-xs-12">
		          		<div class="col-md-4 col-xs-12">			          		
			          		<label><b><u>BATCHNO<b><u></label><label style="float:right;"><b>:</b></label>
			          	</div>
			          	<div class="col-md-8 col-xs-12" id="lblModalBatchNo"></div>
		          	</div>
		          	<div class="col-md-6 col-xs-12">
		          		<div class="col-md-4 col-xs-12">
			          		<label><b><u>BARCODE</u></b></label><label style="float:right;"><b>:</b></label>
			          	</div>
			          	<div class="col-md-8 col-xs-12" id="lblModalBarcode"></div>
		          	</div>
		          </div>
		          <div class="row" style="font-size:12px;">
		          	<div class="col-md-6 col-xs-12">
		          		<div class="col-md-4 col-xs-12">
			          		<label><b><u>SENDER/VENDOR</u></b></label><label style="float:right;"><b>:</b></label>
			          	</div>
			          	<div class="col-md-8 col-xs-12" id="lblModalSenderVendor"></div>
		          	</div>
		          	<div class="col-md-6 col-xs-12">
		          		<div class="col-md-4 col-xs-12">
			          		<label><b><u>COMPANY</u></b></label><label style="float:right;"><b>:</b></label>
			          	</div>
			          	<div class="col-md-8 col-xs-12" id="lblModalCompany"></div>
		          	</div>
		          </div>
		          <div class="row" style="font-size:12px;">
		          	<div class="col-md-6 col-xs-12">
		          		<div class="col-md-4 col-xs-12">
			          		<label><b><u>INVOICE NO</u></b></label><label style="float:right;"><b>:</b></label>
			          	</div>
			          	<div class="col-md-8 col-xs-12" id="lblModalInvNo"></div>
		          	</div>
		          	<div class="col-md-6 col-xs-12">
		          		<div class="col-md-4 col-xs-12">
			          		<label><b><u>AMOUNT</u></b></label><label style="float:right;"><b>:</b></label>
			          	</div>
			          	<div class="col-md-8 col-xs-12" id="lblModalAmount"></div>
		          	</div>
		          </div>
		          <div class="row" style="margin:50px 0px 50px 0px;">
		          	<div class="col-md-4 col-xs-12">
						<label for="uploadFile"><b><u>File Upload :</u></b></label>
						<input type="file" id="uploadFile" class="form-control input-sm" value="">
					</div>
		          	<div class="col-md-1 col-xs-12">
		          		<button type="button" class="btn btn-warning btn-xs btn-block" onclick="$('#uploadFile').val('');">Clear</button>
		          	</div>
		          	<div class="col-md-1 col-xs-12">
		          		<img style="display:none;" src="<?php echo base_url('assets/img/loading.gif'); ?>" >
		          	</div>
		          	<div class="col-md-6 col-xs-12" id="idDivRemark">
						<label for="txtRemark"><b><u>Remark :</u></b></label>
						<textarea class="form-control input-sm" id="txtRemark"></textarea>
					</div>
		          </div>
		          <div class="row" style="margin-top:10px;">
		          	<div class="col-md-6 col-xs-12">
		          		<input type="hidden" value="" id="txtIdModal">
		          		<input type="hidden" value="" id="txtBarcodeModal">
		          		<input type="hidden" value="" id="txtTypeDoc">
		          		<button id="idBtnSaveModalFile" type="button" class="btn btn-primary btn-xs btn-block" onclick="saveUploadFile();" title="SAVE FILE"><i class="fa fa-check-square-o"></i> SAVE</button>
		          	</div>
		          	<div class="col-md-6 col-xs-12">
		          		<button type="button" class="btn btn-danger btn-xs btn-block" onclick="reloadPage();" title="CANCEL"><i class="fa fa-times"></i> CANCEL</button>
		          	</div>
		          </div>
		        </div>
			</div>
		</div>
	</div>
</body>
</html>

