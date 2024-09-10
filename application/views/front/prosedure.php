<?php $this->load->view('front/menu');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/wysihtml5.min.css">
	<script src="<?php echo base_url();?>assets/js/wysihtml5x-toolbar.min.js"></script>
	<script src="<?php echo base_url();?>assets/js/handlebars.runtime.min.js"></script>
	<script src="<?php echo base_url();?>assets/js/wysihtml5.min.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function(){
			document.addEventListener('contextmenu', event => event.preventDefault());
			document.onselectstart = new Function("return false");
			$('#txtDetailDoc').wysihtml5({
					toolbar: {
				    	fa: true
				    }
				});

			$("[id^=txtDateDoc]").datepicker({
				dateFormat: 'yy-mm-dd',
		        showButtonPanel: true,
		        changeMonth: true,
		        changeYear: true,
		        defaultDate: new Date(),
		    });		    
		    $("#btnAdd").click(function(){
		    	$('#txtDetailDoc').wysihtml5({
					toolbar: {
				    	fa: true
				    }
				});
		    	$("#idDataTable").hide();
		    	$("#idForm").show(200);
		    });
		    $("#btnEdit").click(function(){
		    	$("#idViewDoc").hide();
		    	$('#idForm').show(200);
		    });
		    $("#btnSubmit").click(function(){
		    	var dateDoc = $("#txtDateDoc").val();
		    	var vsl = $('#slcVessel :selected').text();
		    	var sbjDoc = $("#txtSubProsedure").val();
		    	var detDoc = $("#txtDetailDoc").val();

		    	$("#lblDocDate").text(dateDoc);
		    	$("#lblVesselName").text(vsl);
		    	$("#lblSbjProsedure").text(sbjDoc);

		    	$("#lblDetailDoc").empty();
		    	$("#lblDetailDoc").append(detDoc);

		    	$("#idForm").hide();
		    	$('#idViewDoc').show(200);
		    });
		    $("#btnSave").click(function(){
		    	var formData = new FormData();

		    	var dateDoc = $("#txtDateDoc").val();
		    	var vsl = $("#slcVessel").val();
		    	var sbjDoc = $("#txtSubProsedure").val();
		    	var detDoc = $("#txtDetailDoc").val();

		    	formData.append('dateDoc',dateDoc);
		    	formData.append('vsl',vsl);
		    	formData.append('sbjDoc',sbjDoc);
		    	formData.append('detDoc',detDoc);

		    	$("#idLoading").show();
			    $(this).attr("disabled",true);

			    $.ajax({
				    type: "POST",
				    url: "<?php echo base_url('prosedure/saveDoc'); ?>",
				    data: formData,
				    cache: false,
				    contentType: false,
				    processData: false,
				    success:  function(response){
				        alert(response);
				        reloadPage();
				    }
				});
		    });
		    $("#btnSearch").click(function(){
		    	var slcType = $("#slcTypeSearch").val();
		    	var teks = $("#txtSearch").val();

		    	if(slcType == "")
		    	{
		    		alert("Type Search Empty..!!");
		    		return false;
		    	}

		    	if(teks == "")
		    	{
		    		alert("Text Search Empty..!!");
		    		return false;
		    	}

		    	$("#idLoading").show();

		    	$.post('<?php echo base_url("prosedure/getData/search"); ?>/',
				{ slcType : slcType,teks : teks },
					function(data) 
					{
						$("#idTbody").empty();
						$("#idTbody").append(data);
						$("#idLoading").hide();
					},
				"json"
				);

		    });
		});
		function viewDoc(id)
		{
			if (window.sidebar)
			{
			    document.onmousedown = disableselect;
			    document.onclick = reEnable;
			}
			$("#idLoading").show();
			$.post('<?php echo base_url("prosedure/viewDoc"); ?>/',
			{ id : id },
				function(data) 
				{
					$("#lblDocDate").text(data.dateDoc);
			    	$("#lblVesselName").text(data.detDoc[0]['nameVessel']);
			    	$("#lblSbjProsedure").text(data.detDoc[0]['subject_document']);

			    	$("#idDivBtnBottom").empty();
			    	$("#lblDetailDoc").empty();
			    	$("#lblDetailDoc").append(data.detDoc[0]['detail_document']);
			    	$("#idDivBtnBottom").append(data.btnClose);

			    	$("#idDataTable").hide();
			    	$('#idViewDoc').show(200);
			    	$("#idLoading").hide();
				},
			"json"
			);
		}
		function delDoc(id)
		{
			var cfm = confirm("Yakin di Hapus..??");
			if(cfm)
			{
				$("#idLoading").show();
				$.post('<?php echo base_url("prosedure/deldoc"); ?>/',
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
		function disableselect(e)
		{
		    return false;
		}

		function reEnable()
		{
		    return true;
		}
		function reloadPage()
		{
			window.location = "<?php echo base_url('prosedure/');?>";
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
					<i class="fa fa-angle-right"></i>
					Prosedur
					<img id="idLoading" style="display:none;" src="<?php echo base_url('assets/img/loading.gif'); ?>">
				</h3>
				<div class="form-panel" id="idDataTable" style="">
					<div class="row">
						<div class="col-md-1 col-xs-12" style="<?php echo $displayAdd; ?>">
							<button type="button" id="btnAdd" class="btn btn-primary btn-sm btn-block" title="Add"><i class="fa fa-plus-square"></i> Add</button>
						</div>
						<div class="col-md-2 col-xs-12">
							<select id="slcTypeSearch" class="form-control input-sm">
								<option value="">- Type Search -</option>
								<option value="subject">Subject Dokumen</option>
								<option value="vessel">Vessel</option>
							</select>
						</div>
						<div class="col-md-2 col-xs-12">
							<input type="text" id="txtSearch" value="" class="form-control input-sm" placeholder="Text Search">
						</div>
						<div class="col-md-2 col-xs-12">
							<button type="button" id="btnSearch" class="btn btn-warning btn-sm btn-block" title="Search"><i class="fa fa-search"></i> Search</button>
						</div>
						<div class="col-md-2 col-xs-12">
							<button type="button" id="btnRefresh" class="btn btn-success btn-sm btn-block" title="Refresh" onclick="reloadPage();"><i class="fa fa-search"></i> Refresh</button>
						</div>
					</div>
					<div class="row mt" id="idData1">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
									<thead>
										<tr style="background-color: #1b6583;color: #FFF;height:40px;">
											<th style="width:5%;text-align:center;vertical-align:middle;">No</th>
											<th style="width:10%;text-align:center;vertical-align:middle;">Tanggal</th>
											<th style="width:15%;text-align:center;vertical-align:middle;">Nama Kapal</th>
											<th style="width:60%;text-align:center;vertical-align:middle;">Subject Prosedure</th>
											<th style="width:10%;text-align:center;vertical-align:middle;">Action</th>
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
				<div class="form-panel" id="idForm" style="margin-bottom:50px;display:none;">
					<div class="row">
						<div class="col-md-12 col-xs-12" align="right">
							<legend><i>:: Add Data ::</i></legend>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2 col-xs-12">
							<div class="form-group">
							    <label for="txtDateDoc"><u>Document Date :</u></label>
							    <input type="text" class="form-control input-sm" id="txtDateDoc" placeholder="Document Date">
							</div>
						</div>
						<div class="col-md-3 col-xs-12">
							<div class="form-group">
							    <label for="slcVessel"><u>Vessel Name :</u></label>
							    <select class="form-control input-sm" id="slcVessel">
							    	<option value="">- Select -</option>
							    	<?php echo $vessel; ?>
							    </select>
							</div>
						</div>
						<div class="col-md-7 col-xs-12">
							<div class="form-group">
							    <label for="txtSubProsedure"><u>Subject Prosedure :</u></label>
							    <input type="text" class="form-control input-sm" id="txtSubProsedure" placeholder="Subject Prosedure">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-xs-12">
							<div class="form-group">
							    <label for="txtDetailDoc"><u>Detail Document :</u></label>
							    <textarea class="form-control input-sm" id="txtDetailDoc" style="height:300px;"></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 col-xs-12">
							<button class="btn btn-primary btn-xs btn-block" id="btnSubmit"><i class="fa fa-check-square-o"></i> Submit</button>
						</div>
						<div class="col-md-6 col-xs-12">
							<button class="btn btn-danger btn-xs btn-block" onclick="reloadPage();"><i class="fa fa-times-circle"></i> Cancel</button>
						</div>
					</div>
				</div>
				<div class="form-panel" id="idViewDoc" style="margin-bottom:50px;display:none;">
					<div class="row">
						<div class="col-md-12 col-xs-12" align="right">
							<legend><i>:: View Data ::</i></legend>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3 col-xs-12">
							<label><b>Document Date :</b></label>
							<p id="lblDocDate"></p>
						</div>
						<div class="col-md-3 col-xs-12">
							<label><b>Vessel Name :</b></label>
							<p id="lblVesselName"></p>
						</div>
						<div class="col-md-6 col-xs-12">
							<label><b>Subject Prosedure :</b></label>
							<p id="lblSbjProsedure"></p>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 col-xs-12">
							<label for="txtDetailDoc"><b>Detail Document :</b></label>
							<div id="lblDetailDoc" style="background-color:#E1E1E1;padding:10px;margin-bottom:10px;">
							</div>
						</div>
					</div>
					<div class="row" id="idDivBtnBottom">
						<div class="col-md-4 col-xs-12">
							<button class="btn btn-primary btn-xs btn-block" id="btnSave"><i class="fa fa-check-square-o"></i> Save</button>
						</div>
						<div class="col-md-4 col-xs-12">
							<button class="btn btn-success btn-xs btn-block" id="btnEdit"><i class="fa fa-edit"></i> Edit</button>
						</div>
						<div class="col-md-4 col-xs-12">
							<button class="btn btn-danger btn-xs btn-block" onclick="reloadPage();"><i class="fa fa-times-circle"></i> Cancel</button>
						</div>
					</div>
				</div>
			</section>
		</section>
	</section>
</body>
</html>