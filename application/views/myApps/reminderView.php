<?php $this->load->view('myApps/menu'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="andhika group">
    <link rel="icon" href="<?php echo base_url("assets/img/andhika.gif"); ?>">
    
	<script type="text/javascript">
		$(document).ready(function(){
			$("[id^=txtDate]").datepicker({
				dateFormat: 'yy-mm-dd',
		        showButtonPanel: true,
		        changeMonth: true,
		        changeYear: true,
		        defaultDate: new Date(),
		    });

		    $("#btnAddData").click(function(){
		    	$("#idDataTable").hide();
		    	$("#idForm").show(150);
		    });
		});

		function searchData()
		{
			var txtSearch = $("#txtSearch").val();
			var startDate = $("#txtDateStartSearch").val();
			var endDate = $("#txtDateEndSearch").val();

			$("#idLoading").show();
			$.post('<?php echo base_url("reminderDoc/getData/search"); ?>',
			{ txtSearch : txtSearch,startDate : startDate,endDate : endDate },
				function(data)
				{
					$("#idTbody").empty();
					$("#idTbody").append(data.trNya);
					$("#idPage").empty();
					$("#idLoading").hide();
				},
				"json"
			);
		}

		function saveData()
		{
			var idEdit = $("#txtIdEdit").val();
			var formData = new FormData();

			var fileUploadNya = $("#fileDoc").prop('files')[0];
			var cekFile = $("#fileDoc").val();

		 	$("#idLoading").show();

			formData.append('idEdit',idEdit);			
			formData.append('txtDate',$("#txtDate").val());
			formData.append('txtDocName',$("#txtDocName").val());
			formData.append('txtIngatKan',$("#txtIngatKan").val());
			formData.append('txtPenerima',$("#txtPenerima").val());
			formData.append('txtKeterangan',$("#txtKeterangan").val());
			formData.append('cekFile',cekFile);
        	formData.append('fileUploadNya',fileUploadNya);

        	$("#btnSave").attr('disabled','disabled');
        	$.ajax("<?php echo base_url('reminderDoc/saveData'); ?>",{
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

		function editData(id)
		{
			$("#idLoading").show();
			$.post('<?php echo base_url("reminderDoc/editData"); ?>',
			{ idEdit : id },
				function(data)
				{
					$("#txtIdEdit").val(id);
					$("#txtDate").val(data.txtDate);
					$("#txtDocName").val(data.txtDocName);
					$("#txtIngatKan").val(data.txtReminder);
					$("#txtPenerima").val(data.txtPenerima);
					$("#txtKeterangan").val(data.txtKet);

					$("#idViewFile").empty();
					$("#idViewFile").append(data.fileName);

					$("#idDataTable").hide();
		    		$("#idForm").show(150);

					$("#idLoading").hide();
				},
				"json"
			);
		}

		function delData(id)
		{
			var cfm = confirm("Yakin Hapus Data..??");
			if(cfm)
			{
				$("#idLoadingAdd").show();

				$.post('<?php echo base_url("reminderDoc/delData"); ?>',
				{ id : id },
					function(data) 
					{
						alert(data);
						reloadPage();
					},
				"json"
				);
			}
		}

		function delFile(id,fileName)
		{
			var cfm = confirm("Yakin Hapus File..??");
			if(cfm)
			{
				$("#idLoadingAdd").show();

				$.post('<?php echo base_url("reminderDoc/delFile"); ?>',
				{ id : id,fileName : fileName },
					function(data) 
					{
						alert(data);
						$("#idViewFile").empty();
	                    $("#idLoadingAdd").hide();
					},
				"json"
				);
			}
		}

		function reloadPage()
		{
			window.location = "<?php echo base_url('reminderDoc/');?>";
		}
	</script>
</head>
<body>
	<section id="container">
		<section id="main-content">
			<section class="wrapper site-min-height" style="min-height:400px;">
				<h3>
					<i class="fa fa-angle-right"></i> Reminder Document<span style="padding-left:20px;display:none;" id="idLoading">
						<img src="<?php echo base_url('assets/img/loading.gif'); ?>" ></span>
				</h3>
				<div class="form-panel" id="idDataTable">
					<div class="row">
						<div class="col-md-2" style="margin-top:5px;">
							<button type="button" id="btnAddData" class="btn btn-primary btn-sm btn-block" title="Add"><i class="fa fa-plus-square"></i> Add Data</button>
						</div>
						<div class="col-md-2" style="margin-top:5px;">
							<input type="text" class="form-control input-sm" id="txtSearch" value="" placeholder="Search Document">
						</div>
						<div class="col-md-2" style="margin-top:5px;">
							<input type="text" class="form-control input-sm" id="txtDateStartSearch" value="" placeholder="Start Date Doc.">
						</div>
						<div class="col-md-2" style="margin-top:5px;">
							<input type="text" class="form-control input-sm" id="txtDateEndSearch" value="" placeholder="End Date Doc.">
						</div>
						<div class="col-md-2" style="margin-top:5px;">
							<button onclick="searchData();" type="button" id="idBtnSearch" class="btn btn-info btn-sm btn-block" title="Search"><i class="glyphicon glyphicon-ok"></i> Search</button>
						</div>
						<div class="col-md-2" style="margin-top:5px;">
							<button type="button" id="idBtnRefresh" onclick="reloadPage();" class="btn btn-success btn-sm btn-block" title="Refresh"><i class="glyphicon glyphicon-refresh"></i> Refresh</button>
						</div>
					</div>
					<div class="row mt" id="idData1">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
									<thead>
										<tr style="background-color: #ba5500;color: #FFF;">
											<th style="vertical-align: middle; width:5%;text-align:center;padding: 10px;">No</th>
											<th style="vertical-align: middle; width:10%;text-align:center;">Tanggal Expired</th>
											<th style="vertical-align: middle; width:20%;text-align:center;">Nama Dokumen</th>
											<th style="vertical-align: middle; width:25%;text-align:center;">Penerima</th>
											<th style="vertical-align: middle; width:35%;text-align:center;">Keterangan</th>
											<th style="vertical-align: middle; width:10%;text-align:center;">Reminder</th>
											<th style="vertical-align: middle; width:15%;text-align:center;">Pengirim</th>
											<th style="vertical-align: middle; width:10%;text-align:center;">Action</th>
										</tr>
									</thead>
									<tbody id="idTbody">
										<?php echo $trNya; ?>
									</tbody>
								</table>
							</div>
							<div id="idPage"><?php echo $listPage; ?></div>
						</div>
					</div>
				</div>
				<div id="idForm" style="display:none;">
					<div class="form-panel">
						<div class="row">
							<div class="col-md-12 col-xs-12">
								<legend>
									<label id="lblForm"> Add Data</label>
									<img id="idLoadingAdd" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="padding-left:10px;display:none;" >
								</legend>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
								    <label for="txtDate"><b><u>Date Expired :</u></b></label>
								    <input type="text" class="form-control input-sm" id="txtDate" value="" placeholder="Date">
								</div>
							</div>
							<div class="col-md-4 col-xs-12">
								<div class="form-group">
								    <label for="txtDocName"><b><u>Doc. Name :</u></b></label>
								    <input type="text" class="form-control input-sm" id="txtDocName" value="" placeholder="Doc. Name">
								</div>
							</div>
							<div class="col-md-3 col-xs-12">
								<label for="fileDoc"><b><u>File Upload :</u></b></label>
								<input type="file" id="fileDoc" class="form-control input-sm" value="">
							</div>
							<div class="col-md-1 col-xs-12">
								<label>&nbsp</label>
								<button id="btnClearFile" onclick="$('#fileDoc').val('');" class="btn btn-warning btn-xs btn-block" title="Clear">Clear</button>
								<div id="idViewFile" style="margin-top:5px;"></div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="col-md-6 col-xs-12">
									<div class="form-group">
									    <label for="txtIngatKan"><b><u>Reminder&nbsp:</u></b></label>
									    <input type="text" class="form-control input-sm" id="txtIngatKan" value="10" placeholder="0">
									    <span style="font-size:10px;color:red;font-weight:bold;">Hari&nbspsebelumnya</span>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 col-xs-12">
								<div class="form-group">
								    <label for="txtPenerima"><b><u>Penerima <span style="color:red;font-size:10px;">(exp : abc@mail.com,def@mail.com,dst)</span> :</u></b></label>
								    <input type="text" class="form-control input-sm" id="txtPenerima" value="" placeholder="Mail">
								</div>
							</div>
							<div class="col-md-4 col-xs-12">
								<div class="form-group">
								    <label for="txtKeterangan"><b><u>Keterangan :</u></b></label>
									<textarea class="form-control input-sm" id="txtKeterangan"></textarea>
								</div>
							</div>							
						</div>
					</div>
					<div class="form-panel">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group" align="center">
									<input type="hidden" name="" id="txtIdEdit" value="">
									<button id="btnSave" class="btn btn-primary btn-sm" name="btnSave" title="Save" onclick="saveData();">
										<i class="fa fa-check-square-o"></i>
										Submit
									</button>
									<button id="btnCancel" onclick="reloadPage();" class="btn btn-danger btn-sm" name="btnCancel" title="Cancel">
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
</body>
</html>

