<?php $this->load->view('front/menu');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#idObservasi").addClass("active");
			$("#idLoading").hide();
			$("#idFormObs").hide();
			$("#idFormSearch").hide();
			$( "#txtDateObs" ).datepicker({
				dateFormat: 'yy-mm-dd',
		        showButtonPanel: true,
		        changeMonth: true,
		        changeYear: true,
		        defaultDate: new Date(),
				maxDate: new Date(),
		    });
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
		    $("#btnAdd").click(function(){
		    	$("#idDataTable").hide(250);
		    	$("#idFormObs").show(350);
		    });
		    $("#btnSearchOpenForm").click(function(){
		    	$("#idFormSearch").show();
		    });
		    $("#btnSearch").click(function(){
		    	var sDateSearch = $("#txtStartDate").val();
		    	var eDateSearch = $("#txtEndDate").val();
		    	var slcVesselSearch = $("#slcVesselSearch").val();
		    	var slcJnsObs = $("#slcSearchKatObs").val();

		    	if (sDateSearch == "" & eDateSearch != "")
		    	{
		    		alert("Tanggal Mulai tidak boleh kosong..!!");
		    		return false;
		    	}
		    	else if(sDateSearch != "" & eDateSearch == "")
		    	{
		    		alert("Tanggal Akhir tidak boleh kosong..!!");
		    		return false;
		    	}
		    	
		    	$("#idLoading").show();
				$("#idFormObs").hide();
		    	$("#idDataTable").show();
				
		    	$.post('<?php echo base_url("front/searchData"); ?>',
				{   
					sDateSearch : sDateSearch,eDateSearch : eDateSearch,slcVesselSearch : slcVesselSearch,slcJnsObs : slcJnsObs
				},
					function(data) 
					{	
						var html = data;
						$("#idTbody").empty();
						$('#idTbody').append(html);
						$("#idLoading").hide();
					},
				"json"
				);
		    });
		    $("#slcJnsObs").change(function(){
		    	var jnsObs = $(this).val();
		    	if(jnsObs == "aman")
		    	{
		    		$("#txtTindakan").prop('disabled', true);
		    		$("#txtDampak").prop('disabled', true);
		    	}else{		
		    		$("#txtTindakan").prop('disabled', false);
		    		$("#txtDampak").prop('disabled', false);
		    	}
		    	// alert(jnsObs);
		    });
			$("#btnCancel").click(function(){
		    	window.location = "<?php echo site_url('front/observasi');?>";
		    });
		    $("#btnCancelSearch").click(function(){
		    	window.location = "<?php echo site_url('front/observasi');?>";
		    });
			$("#btnSave").click(function(){
				$("#idLoading").show();
				$('html,body').scrollTop(0);

				var idEdit = $("#txtIdEdit").val();
				var slcJnsObs = $("#slcJnsObs").val();
				var dateObs = $("#txtDateObs").val();
				var namaPengamat =$("#txtNamaPengamatan").val();
				var namaKapal = $("#slcVessel").val();
				var detailLokObs = $("#txtDetailLokObs").val();
				var catatanDetail = $("#txtCatatanDetailObs").val();
				var dampakTerjadi = $("#txtDampak").val();
				var tindakan = $("#txtTindakan").val();
				var alatPelindungDiri = [];
				var alatKerja = [];
				var lingKerja = [];
				var posisiKerja = [];
				var ergonomik = [];
				var sysKerja = [];
				var lainNya = $("#txtLainNya").val();
				var jabatan = $("#slcJabatan").val();

				if(dateObs == "")
				{
					alert("Tanggal Observasi kosong..!!");
					$("#idLoading").hide();
					return false;
				}
				if(detailLokObs == "")
				{
					alert("Detail Lokasi kosong..!!");
					$("#idLoading").hide();
					return false;
				}
				if(catatanDetail == "")
				{
					alert("Catatan Detail kosong..!!");
					$("#idLoading").hide();
					return false;
				}
				if(slcJnsObs != "aman")
				{
					if(dampakTerjadi == "")
					{
						alert("Dampak kosong..!!");
						$("#idLoading").hide();
						return false;
					}
					if(tindakan == "")
					{
						alert("Tindakan Perbaikan kosong..!!");
						$("#idLoading").hide();
						return false;
					}
				}

			  	$('#pelindung_diri:checked').each(function(i){
			    	alatPelindungDiri[i] = $(this).val();
			  	});
			  	$('#alat_kerja:checked').each(function(i){
			    	alatKerja[i] = $(this).val();
			  	});
			  	$('#lingkungan_kerja:checked').each(function(i){
			    	lingKerja[i] = $(this).val();
			  	});
			  	$('#posisi_kerja:checked').each(function(i){
			    	posisiKerja[i] = $(this).val();
			  	});
			  	$('#ergonomik:checked').each(function(i){
			    	ergonomik[i] = $(this).val();
			  	});
			  	$('#sistem_kerja:checked').each(function(i){
			    	sysKerja[i] = $(this).val();
			  	});
			  	if (alatPelindungDiri == "")
			  	{
			  		alatPelindungDiri = 0;
			  	}
			  	if (alatKerja == "")
			  	{
			  		alatKerja = 0;
			  	}
			  	if (lingKerja == "")
			  	{
			  		lingKerja = 0;
			  	}
			  	if (posisiKerja == "")
			  	{
			  		posisiKerja = 0;
			  	}
			  	if (ergonomik == "")
			  	{
			  		ergonomik = 0;
			  	}
			  	if (sysKerja == "")
			  	{
			  		sysKerja = 0;
			  	}
			  	if(alatPelindungDiri == "0" && alatKerja == "0" && lingKerja == "0" && posisiKerja == "0" && ergonomik == "0" && sysKerja == "0" && lainNya == "" )
			  	{
			  		alert("Jenis Observasi Kosong..!!");
			  		$("#idLoading").hide();
					return false;
			  	}

				$.post('<?php echo base_url("front/saveObs"); ?>',
				{   
					dateObs : dateObs,namaPengamat : namaPengamat,namaKapal : namaKapal,detailLokObs : detailLokObs,dampakTerjadi : dampakTerjadi,tindakan : tindakan,alatPelindungDiri : alatPelindungDiri,alatKerja : alatKerja,lingKerja : lingKerja,posisiKerja : posisiKerja,ergonomik : ergonomik,sysKerja :sysKerja,lainNya : lainNya,jabatan : jabatan, idEdit : idEdit,slcJnsObs : slcJnsObs, catatanDetail : catatanDetail
				},
					function(data) 
					{	
						alert(data);
						$("#idLoading").hide();
						clearForm();
						document.getElementById('btnSearch').click();
						// window.location = "<?php echo site_url('front/observasi');?>";
					},
				"json"
				);
			});
			$("#btnExport").click(function(){
				var sdExp = $("#txtStartDate").val();
				var edExp = $("#txtEndDate").val();
				var slcExp = $("#slcVesselSearch").val();
				var slcJnsObs = $("#slcSearchKatObs").val();
				$("#idSdateSearch").val(sdExp);
				$("#idEdateSearch").val(edExp);
				$("#idSlcVslSearch").val(slcExp);
				$("#idSlcKatObsSearch").val(slcJnsObs);
				frmExport.submit();
			});
			var jnsObs = $("#slcJnsObs").val();
		    if(jnsObs == "aman")
		    {
		    	$("#txtTindakan").prop('disabled', true);
		    	$("#txtDampak").prop('disabled', true);
		    }
		});

		function getEdit(id)
		{
			$("#idLoading").show();
			$("#lblForm").text("Edit Data");
			$.post('<?php echo base_url("front/getEdit"); ?>',
			{ id : id },
				function(data) 
				{							
					$.each(data.dataObs, function(i, item)
					{
						$("#txtIdEdit").val(item.id);
						$("#slcJnsObs").val(item.jns_observasi);
						$("#txtDateObs").val(item.tgl_observasi);
						$("#txtNamaPengamatan").val(item.nama_pengamat);
						$("#slcJabatan").val(item.id_jabatan);
						$("#slcVessel").val(item.id_kapal);
						$("#txtDetailLokObs").val(item.detail_Lokasi_observasi);
						$("#txtCatatanDetailObs").val(item.catatan_detail);
						$("#txtDampak").val(item.dampak);
						$("#txtTindakan").val(item.tindakan);
						$("#txtLainNya").val(item.jns_observasi_lain);
						if(item.jns_observasi !== "aman")
					    {
					    	$("#txtTindakan").prop('disabled', false);
					    	$("#txtDampak").prop('disabled', false);
					    }
					});
					$.each(data.dataPelDiri, function(q, dp)
					{
						$('input#pelindung_diri[value="'+dp.idPelindunganDiri+'"]').prop('checked', true);
					});
					$.each(data.alatKerja, function(w, dw)
					{
						$('input#alat_kerja[value="'+dw.idAlatKerja+'"]').prop('checked', true);
					});
					$.each(data.lingkunganKerja, function(r, dr)
					{
						$('input#lingkungan_kerja[value="'+dr.idLingkunganKerja+'"]').prop('checked', true);
					});
					$.each(data.posisiPekerja, function(q, dt)
					{
						$('input#posisi_kerja[value="'+dt.idPosisiKerja+'"]').prop('checked', true);
					});
					$.each(data.ergonomik, function(q, dy)
					{
						$('input#ergonomik[value="'+dy.idErgonomik+'"]').prop('checked', true);
					});
					$.each(data.sistemKerja, function(q, du)
					{
						$('input#sistem_kerja[value="'+du.idSistemKerja+'"]').prop('checked', true);
					});


					$("#idLoading").hide();
					$("#idDataTable").hide(200);
		   		 	$("#idFormObs").show(300);
				},
			"json"
			);
			disAbleCheckBox();
		}
		function disAbleCheckBox()
		{
			$("input#pelindung_diri").attr("disabled", true);
			$("input#alat_kerja").attr("disabled", true);
			$("input#lingkungan_kerja").attr("disabled", true);
			$("input#posisi_kerja").attr("disabled", true);
			$("input#ergonomik").attr("disabled", true);
			$("input#sistem_kerja").attr("disabled", true);
			$("input#txtLainNya").attr("disabled", true);
			$("#btnUncheck").css("display","");
		}
		function enableCheckBox()
		{
			$("input#pelindung_diri").removeAttr("disabled");
			$("input#alat_kerja").removeAttr("disabled");
			$("input#lingkungan_kerja").removeAttr("disabled");
			$("input#posisi_kerja").removeAttr("disabled");
			$("input#ergonomik").removeAttr("disabled");
			$("input#sistem_kerja").removeAttr("disabled");
			$("input#txtLainNya").removeAttr("disabled");

			$("input#pelindung_diri").removeAttr("checked",false);
			$("input#alat_kerja").removeAttr("checked",false);
			$("input#lingkungan_kerja").removeAttr("checked",false);
			$("input#posisi_kerja").removeAttr("checked",false);
			$("input#ergonomik").removeAttr("checked",false);
			$("input#sistem_kerja").removeAttr("checked",false);
			$("input#txtLainNya").val("");

			$("#btnUncheck").css("display","none");
		}		
		function clearForm()
		{
			$("#txtIdEdit").val('');
			$("#slcJnsObs").val('');
			$("#txtDateObs").val('');
			$("#txtNamaPengamatan").val('');
			$("#slcVessel").val('');
			$("#txtDetailLokObs").val('');
			$("#txtCatatanDetailObs").val('');
			$("#txtDampak").val('');
			$("#txtTindakan").val('');
			$("#txtLainNya").val('');
			$("#slcJabatan").val('');
			enableCheckBox();
		}
		
		$( document ).on( 'focus', ':input', function(){
		  $( this ).attr( 'autocomplete', 'off' );
		});
	</script>
</head>
<body>
	<section id="container">
		<section id="main-content">
			<section class="wrapper site-min-height" style="min-height:400px;">
				<h3>
					<i class="fa fa-angle-right"></i> Observasi Keselamatan <span style="padding-left: 20px;" id="idLoading"><img src="<?php echo base_url('assets/img/loading.gif'); ?>" ></span>
				</h3>

				<div class="form-panel" id="idDataTable">
					<div class="row">
						<div class="col-md-12" id="btnNavAtas">
							<button type="button" id="btnAdd" class="btn btn-primary btn-sm" title="Add"><i class="fa fa-plus-square"></i> Add</button>
							<button type="button" id="btnSearchOpenForm" class="btn btn-primary btn-sm" title="Add"><i class="fa fa-search"></i> Search \ Export</button>
						</div>
						<div id="idFormSearch">
							<!-- <form name="searchNya" action="../front/searchData" method="post"> -->
							<dir class="col-md-2 col-xs-12">
								<input placeholder="Tanggal Mulai Observasi" type="text" class="form-control input-sm" id="txtStartDate" name="txtStartDate" value="">
							</dir>
							<dir class="col-md-2 col-xs-12">
								<input placeholder="Tanggal Akhir Observasi" type="text" class="form-control input-sm" id="txtEndDate" name="txtEndDate" value="">
							</dir>
							<dir class="col-md-2 col-xs-12">
								<select name="slcVesselSearch" id="slcVesselSearch" class="form-control input-sm">
									<?php 
										if($this->session->userdata('userType') == "admin")
										{
											echo "<option value=\"\">Semua Kapal</option>";
										}
										echo $vessel; 
									?>
								</select>
							</dir>
							<dir class="col-md-2 col-xs-12">
								<select name="slcSearchKatObs" id="slcSearchKatObs" class="form-control input-sm">
									<option value="">Semua Kategori</option>
							    	<option value="aman">Aman / Safe</option>
							    	<option value="tidak aman">Tidak Aman / Unsafe</option>
							    	<option value="hampir celaka">Hampir Celaka / Nearmiss</option>
							    </select>
							</dir>
							<dir class="col-md-3 col-xs-12">
								<button type="submit" id="btnSearch" class="btn btn-success btn-sm" title="Add"><i class="fa fa-search"></i> Search</button>
								<button type="button" id="btnCancelSearch" class="btn btn-danger btn-sm" title="Cancel"><i class="fa fa-ban"></i> Cancel</button>
								<?php
									if($this->session->userdata('stExport') == "y"){
								?>
								<button type="button" id="btnExport" class="btn btn-primary btn-sm" title="Export"><i class="fa fa-download"></i> Export</button>
								<?php }?>
							</dir>
							<!-- </form> -->
						</div>
					</div>
					<div class="row mt" id="idData1">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
									<thead>
										<tr style="background-color: #1b6583;color: #FFF;">
											<th style="width:3%;text-align: center;">No</th>
											<th style="width:17%;text-align: center;">Tgl Observasi</th>
											<th style="width:10%;text-align: center;">Kat. Observasi</th>
											<th style="width:15%;text-align: center;">Nama Kapal</th>
											<th style="width:20%;text-align: center;">Observasi</th>
											<th style="width:15%;text-align: center;">Nama Pengamat</th>
											<th style="width:10%;text-align: center;">Jabatan</th>
											<th style="width:10%; text-align: center;">Action</th>
										</tr>
									</thead>
									<tbody id="idTbody">
										<?php echo $dataObservasi; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="row" id="idFormObs">
					<div class="col-md-12">
						<div class="form-panel">
							<legend><label id="lblForm"> Add Data</label></legend>
							<div class="form-group">
							    <label for="txtDateObs"><u>Tanggal Observasi :</u></label>
							    <input type="text" class="form-control input-sm" name="txtDateObs" id="txtDateObs" placeholder="Date Observasi">
							</div>
							<div class="form-group">
							    <label for="slcJnsObs"><u>Kategori Observasi :</u></label>
							    <select name="slcJnsObs" id="slcJnsObs" class="form-control input-sm">
							    	<option value="aman">Aman / Safe</option>
							    	<option value="tidak aman">Tidak Aman / Unsafe</option>
							    	<option value="hampir celaka">Hampir Celaka / Nearmiss</option>
							    </select>
							</div>
							<div class="form-group">
							    <label for="txtNamaPengamatan"><u>Nama Pengamat :</u></label>
							    <input type="text" class="form-control input-sm" name="txtNamaPengamatan" id="txtNamaPengamatan" placeholder="Nama Pengamat" value="<?php echo $this->session->userdata('fullName'); ?>">
							</div>
							<div class="form-group">
							    <label for="txtNamaKapal"><u>Jabatan :</u></label>
							    <select name="slcJabatan" id="slcJabatan" class="form-control input-sm">
							    	<!-- <option value="">- Pilih Jabatan -</option> -->
							    	<?php
							    		echo $jabatan; 
							    	?>
							    </select>
							</div>
							<div class="form-group">
							    <label for="txtNamaKapal"><u>Nama Kapal :</u></label>
							    <select name="slcVessel" id="slcVessel" class="form-control input-sm">
							    	<?php
							    		echo $vessel; 
							    	?>
							    </select>
							</div>
							<div class="form-group">
							    <label for="txtDetailLokObs"><u>Detail Lokasi Observasi :</u></label>
							    <input type="text" class="form-control input-sm" name="txtDetailLokObs" id="txtDetailLokObs" placeholder="Detail Lokasi Observasi" value="">
							</div>
							<!--<div class="form-group">
							    <label for="slcDivisi"><u>Divisi :</u></label>
							    <select name="slcDivisi" id="slcDivisi" class="form-control">
							    </select>
							</div>
							<div class="form-group" style="display: none;">
							    <label for="slcDprt">Departemen</label>
							    <select name="slcDprt" id="slcDprt" class="form-control">
							    </select>
							</div>-->
							<div class="form-group">
							    <label for="txtCatatanDetailObs"><u>Catatan Detail Observasi :</u></label>
							    <textarea class="form-control input-sm" name="txtCatatanDetailObs" id="txtCatatanDetailObs" ></textarea>
							</div>
							<div class="form-group">
							    <label for="txtDampak"><u>Dampak yang Mungkin akan terjadi :</u></label>
							    <textarea class="form-control input-sm" name="txtDampak" id="txtDampak" ></textarea>
							</div>
							<div class="form-group">
							    <label for="txtTindakan"><u>Tindakan Perbaikan & Pencegahan :</u></label>
							    <textarea class="form-control input-sm" name="txtTindakan" id="txtTindakan"></textarea>
							</div>
							<fieldset>
								<legend style="text-align: center;padding-top: 10px;">Jenis Observasi <br>
									<label style="font-size: 12px;color:#ff7272;">(Hanya bisa pilih satu jenis saja sesuai Catatan Detail Observasi.)</label>
								</legend>
							<div class="form-group" align="center">
								<button id="btnUncheck" class="btn btn-danger btn-xs" name="btnUncheck" title="Uncheck" style="display: none;" onclick="enableCheckBox();">
									<i class="fa fa-check-circle"></i>
									Uncheck
								</button>
							</div>
							<div class="form-group">
								<label for="alatPelDir"><u>Alat Pelindung Diri :</u></label>
								<div class="form-check-inline" id="alatPelDir">
									<?php echo $pelindungDiri; ?>
								</div>
							</div>
							<div class="form-group">
								<label for="alatKerja"><u>Alat Kerja & Peralatan :</u></label>
								<div class="form-check-inline" id="alatKerja">
									<?php echo $alatKerja; ?>
								</div>
							</div>
							<div class="form-group">
								<label for="lingKer"><u>Lingkungan Kerja :</u></label>
								<div class="form-check-inline" id="lingKer">
									<?php echo $lingkunganKerja; ?>
								</div>
							</div>
							<div class="form-group">
								<label for="posKer"><u>Posisi Kerja :</u></label>
								<div class="form-check-inline" id="posKer">
									<?php echo $posisiPekerja; ?>
								</div>
							</div>
							<div class="form-group">
								<label for="posKer"><u>Ergonomik :</u></label>
								<div class="form-check-inline" id="posKer">
									<?php echo $ergonomik; ?>
								</div>
							</div>
							<div class="form-group">
								<label for="posKer"><u>Sistem Kerja :</u></u></label>
								<div class="form-check-inline" id="posKer">
									<?php echo $sistemKerja; ?>
								</div>
							</div>
							<div class="form-group">
							    <label for="txtNamaKapal"><u>Lainnya :</u></label>
							     <input type="text" class="form-control input-sm" name="txtLainNya" id="txtLainNya" placeholder="" onchange="disAbleCheckBox()">
							</div>
							<legend></legend>
							</fieldset>
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
	<form name="frmExport" action="../front/exportData" method="post" id="frmExport">
		<input type="hidden" id="idSdateSearch" name="idSdateSearch" value="">
		<input type="hidden" id="idEdateSearch" name="idEdateSearch" value="">
		<input type="hidden" id="idSlcVslSearch" name="idSlcVslSearch" value="">
		<input type="hidden" id="idSlcKatObsSearch" name="idSlcKatObsSearch" value="">
	</form>
</body>
</html>

