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
			$("#txtTanggal").datepicker({
				dateFormat: 'yy-mm-dd',
		        showButtonPanel: true,
		        changeMonth: true,
		        changeYear: true,
		        defaultDate: new Date(),
		    });
		    $("[id^=txtDate]").datepicker({
				dateFormat: 'yy-mm-dd',
		        showButtonPanel: true,
		        changeMonth: true,
		        changeYear: true,
		        defaultDate: new Date(),
		    });
			$("#btnSave").click(function(){
				var batchNo = $("#txtBatchNo").val();
				var tgl = $("#txtTanggal").val();
				var noSurat = $("#txtNoSurat").val();
				var company = $("#slcPerusahaan").val();
				var diKeluarkan = $("#slcDikeluarkan").val();
				var diTandaTangani = $("#slcTandaTangan").val();
				var alamat = $("#txtAlamat").val();
				var keterangan = $("#txtKeterangan").val();
				var pembuatSurat = $("#txtPembuat").val();
				var copy = "";
				var batal = "";

				if ($('#chkCopy').is(":checked")){ copy = "check"; }
				if ($('#chkBatal').is(":checked")){ batal = "check"; }

				$("#idLoadingAdd").show();

				$.post('<?php echo base_url("myLetter/addUpdateData"); ?>',
				{   
					batchNo : batchNo,tgl : tgl,noSurat : noSurat,company : company,diKeluarkan : diKeluarkan,diTandaTangani : diTandaTangani, alamat : alamat,keterangan : keterangan,pembuatSurat : pembuatSurat,copy : copy,batal : batal
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
		    $("#idBtnSearch").click(function(){
		    	var cmp = $("#searchSlcCompany").val();
		    	var sDate = $("#txtDateStartSearch").val();
		    	var eDate = $("#txtDateEndSearch").val();

		    	if(sDate == "")
		    	{
		    		alert("Start Date Empty..!!");
		    		return false;
		    	}

		    	if(eDate == "")
		    	{
		    		alert("End Date Empty..!!");
		    		return false;
		    	}

		    	$("#idLoading").show();
		    	$.post('<?php echo base_url("myLetter/getData/search"); ?>',
				{ sDate : sDate,eDate : eDate,cmp : cmp },
					function(data) 
					{
						$("#idTbody").empty();
						$("#idTbody").append(data.trNya);
						$("#idLoading").hide();
					},
				"json"
				);
		    });
		});
		function getNoSurat()
		{
			var batchNo = $("#txtBatchNo").val();
			var tgl = $("#txtTanggal").val();
			var codeCmp = $("#slcPerusahaan").val();
			var codeKeluar = $("#slcDikeluarkan").val();
			var codeTtd = $("#slcTandaTangan").val();

			if(batchNo == "")
			{
				if(tgl == "")
				{
					alert("Tanggal Kosong..!!");
					return false;
				}

				if(codeCmp == "")
				{
					alert("Perusahaan Kosong..!!");
					return false;
				}

				$("#idLoading").show();

				$.post('<?php echo base_url("myLetter/getNoSurat"); ?>',
				{ tgl : tgl,codeCmp : codeCmp,codeKeluar : codeKeluar,codeTtd : codeTtd },
					function(data)
					{
						$("#idLoading").hide();
						$("#txtNoSurat").val(data.noSurat);
					},
					"json"
				);
			}
		}
		function editData(batchNo = "")
		{
			$("#idLoading").show();
			$.post('<?php echo base_url("myLetter/editData"); ?>',
			{ batchNo : batchNo },
				function(data)
				{
					$("#idDataTable").hide(250);
		    		$("#idForm").show(350);
		    		$("#txtBatchNo").val(data[0].batchno);
					$("#txtTanggal").val(data[0].tglsurat);
					$("#txtNoSurat").val(data[0].nosurat);
					$("#slcPerusahaan").val(data[0].cmpcode);
					$("#slcDikeluarkan").val(data[0].issueddiv);
					$("#slcTandaTangan").val(data[0].signedby);
					$("#txtAlamat").val(data[0].address);
					$("#txtKeterangan").val(data[0].ket);
					$("#txtPembuat").val(data[0].createdby);
					if(data[0].copydoc == "1")
					{
						$("#chkCopy").attr("checked","checked");
					}
					if(data[0].canceldoc == "1")
					{
						$("#chkBatal").attr("checked","checked");
					}
					$("#txtTanggal").attr("disabled","disabled");
					$("#slcPerusahaan").attr("disabled","disabled");
					$("#slcDikeluarkan").attr("disabled","disabled");
					$("#slcTandaTangan").attr("disabled","disabled");
					$("#txtPembuat").attr("disabled","disabled");
					$("#idLoading").hide();
				},
				"json"
			);
		}
		function viewQrCode(id)
		{
			$("#idLoading").show();
			$.post('<?php echo base_url("myLetter/viewQrCode"); ?>',
			{ batchNo : id },
			function(data) 
			{
				$("#idLoading").hide();
				window.open(data,'_blank');
			},
			"json"
			);
		}
		function hitungLimitCharKet()
		{
			var ttlCharKet = $("#txtKeterangan").val().length;
			var sisaChar = 100 - ttlCharKet;

			if(ttlCharKet > 100)
			{
				sisaChar = 0;
				var valKetNya = $("#txtKeterangan").val();

				valKetNya = valKetNya.substr(0,100);

				$("#txtKeterangan").val(valKetNya);
			}
			$("#lblLimitChar").text(sisaChar);
		}
		function delData(id)
		{
			var cfm = confirm("Yakin Hapus..??");
			if(cfm)
			{
				// $.post('<?php echo base_url("myapps/delUserSetting"); ?>',
				// { id : id },
				// function(data) 
				// {
				// 	reloadPage();
				// },
				// "json"
				// );
			}			
		}
		function reloadPage()
		{
			window.location = "<?php echo base_url('myLetter/');?>";
		}
	</script>
</head>
<body>
	<section id="container">
		<section id="main-content">
			<section class="wrapper site-min-height" style="min-height:400px;">
				<h3>
					<i class="fa fa-angle-right"></i> Surat<span style="padding-left:20px;display:none;" id="idLoading"><img src="<?php echo base_url('assets/img/loading.gif'); ?>" ></span>
				</h3>
				<div class="form-panel" id="idDataTable">
					<div class="row">
						<div class="col-md-2" style="margin-top:5px;">
							<button type="button" id="btnAddData" class="btn btn-primary btn-sm btn-block" title="Add"><i class="fa fa-plus-square"></i> Add Data</button>
						</div>
						<div class="col-md-2" style="margin-top:5px;">
							<input type="text" class="form-control input-sm" id="txtDateStartSearch" value="" placeholder="Start Date">
						</div>
						<div class="col-md-2" style="margin-top:5px;">
							<input type="text" class="form-control input-sm" id="txtDateEndSearch" value="" placeholder="End Date">
						</div>
						<div class="col-md-2" style="margin-top:5px;">
							<select name="searchSlcCompany" id="searchSlcCompany" class="form-control input-sm">
								<option value="">- Select -</option>
								<?php echo $getOptCompany; ?>
							</select>
						</div>
						<div class="col-md-2" style="margin-top:5px;">
							<button type="button" id="idBtnSearch" class="btn btn-info btn-sm btn-block" title="Search"><i class="glyphicon glyphicon-ok"></i> Search</button>
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
											<th rowspan="2" style="vertical-align: middle; width:3%;text-align:center;padding: 10px;">No</th>
											<th rowspan="2" style="vertical-align: middle; width:20%;text-align:center;">Perusahaan</th>
											<th rowspan="2" style="vertical-align: middle; width:10%;text-align:center;">Tgl. Surat</th>
											<th rowspan="2" style="vertical-align: middle; width:15%;text-align:center;">No. Surat</th>
											<th rowspan="2" style="vertical-align: middle; width:20%;text-align:center;">Alamat Penerima</th>
											<th rowspan="2" style="vertical-align: middle; width:20%;text-align:center;">Keterangan</th>
											<th rowspan="2" style="vertical-align: middle; width:20%;text-align:center;">Pembuat</th>
											<th colspan="3" style="vertical-align: middle; width:20%;text-align:center;">Action</th>
										</tr>
										<tr style="background-color: #ba5500;color: #FFF;">
											<th style="vertical-align: middle; width:5%;text-align:center;">Copy</th>
											<th style="vertical-align: middle; width:5%;text-align:center;">Batal</th>
											<th style="vertical-align: middle; width:5%;text-align:center;"></th>
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
								    <label for="txtTanggal"><b><u>Tanggal :</u></b></label>
								    <input type="text" class="form-control input-sm" id="txtTanggal" value="" placeholder="Tanggal">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
								    <label for="txtNoSurat"><b><u>No Surat :</u></b></label>
								    <input type="text" disabled="disabled" class="form-control input-sm" id="txtNoSurat" value="" placeholder="No Surat">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
								    <label for="slcPerusahaan"><b><u>Perusahaan :</u></b></label>
								    <select id="slcPerusahaan" onchange="getNoSurat();" class="form-control input-sm">
								    	<option value="">- Select -</option>
								    	<?php echo $getOptCompany; ?>
								    </select>
								</div>
							</div>
							<div class="col-md-3 col-xs-12">
								<div class="form-group">
								    <label for="slcDikeluarkan"><b><u>Dikeluarkan :</u></b></label>
								    <select id="slcDikeluarkan" onchange="getNoSurat();" class="form-control input-sm">
								    	<option value="">- Select -</option>
								    	<?php echo $getOptSignOut; ?>
								    </select>
								</div>
							</div>
							<div class="col-md-3 col-xs-12">
								<div class="form-group">
								    <label for="slcTandaTangan"><b><u>Ditandatangani :</u></b></label>
								    <select id="slcTandaTangan" onchange="getNoSurat();" class="form-control input-sm">
								    	<option value="">- Select -</option>
								    	<?php echo $getOptSignOut; ?>
								    </select>
								</div>
							</div>
							<div class="col-md-3 col-xs-12">
								<div class="form-group">
								    <label for="txtAlamat"><b><u>Alamat Penerima :</u></b></label>
									<textarea class="form-control input-sm" id="txtAlamat"></textarea>
								</div>
							</div>
							<div class="col-md-3 col-xs-12">
								<div class="form-group">
								    <label for="txtKeterangan"><b><u>Keterangan :</u></b></label>&nbsp<label id="lblLimitChar" style="color:red;font-style:italic;"></label>
								    <textarea class="form-control input-sm" id="txtKeterangan" oninput="hitungLimitCharKet();"></textarea>
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
								    <label for="txtPembuat"><b><u>Pembuat No. Surat :</u></b></label>
								    <input type="text" class="form-control input-sm" id="txtPembuat" value="<?php echo $fullNameLogin; ?>" placeholder="Pembuat">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="checkbox">
									<label class="checkbox-inline">
										<input type="checkbox" value="1" id="chkCopy"> Copy
									</label>
									<label class="checkbox-inline">
										<input type="checkbox" value="1" id="chkBatal"> Batal
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="form-panel">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group" align="center">
									<input type="hidden" name="" id="txtBatchNo" value="">
									<button id="btnSave" class="btn btn-primary btn-sm" name="btnSave" title="Save">
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

