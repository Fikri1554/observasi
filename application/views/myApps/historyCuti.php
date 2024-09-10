<?php $this->load->view('myApps/menu'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#idLoading").hide();
		    $("#btnSearch").click(function(){
		    	$("#idTbody").empty();
		    	var yearSearch = $("#slcYear").val();
		    	$("#idLoading").show();
		    	$.post('<?php echo base_url("cuti/getHistory/search"); ?>',
				{   
					yearSearch : yearSearch
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
		function reloadPage()
		{
			window.location = "<?php base_url('cuti/getHistory');?>";
		}
	</script>
</head>
<body>
	<section id="container">
		<section id="main-content">
			<section class="wrapper site-min-height" style="min-height:400px;">
				<h3>
					<i class="fa fa-angle-right"></i> Status Cuti<span style="padding-left: 20px;" id="idLoading"><img src="<?php echo base_url('assets/img/loading.gif'); ?>" ></span>
				</h3>
				<div class="form-panel" id="idDataTable">
					<div class="row" id="idData1">
						<div id="idFormSearch">
							<dir class="col-md-2 col-xs-12">
								<select name="slcYear" id="slcYear" class="form-control input-sm">
									<?php echo $trOpt; ?>
								</select>
							</dir>
							<dir class="col-md-2 col-xs-6">
								<button type="button" id="btnSearch" class="btn btn-primary btn-sm btn-block" title="Add"><i class="fa fa-search"></i> Search</button>								
							</dir>
							<dir class="col-md-2 col-xs-6">
								<button type="button" id="btnCancelSearch" onclick="reloadPage();" class="btn btn-success btn-sm btn-block" title="Cancel"><i class="fa fa-refresh"></i> Refresh</button>
							</dir>
						</div>
						<div class="col-md-12 col-xs-12">
							<div class="table-responsive">
								<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
									<thead>
										<tr style="background-color: #ba5500;color: #FFF;">
											<th style="vertical-align:middle;width:3%;text-align:center;padding:10px;">No</th>
											<th style="vertical-align:middle;width:10%;text-align:center;">Nama</th>
											<th style="vertical-align:middle;width:10%;text-align:center;">Mulai</th>
											<th style="vertical-align:middle;width:10%;text-align:center;">Akhir</th>
											<th style="vertical-align:middle;width:20%;text-align:center;">Keterangan</th>
											<th style="vertical-align: middle; width:7%;text-align: center;">Status</th>
										</tr>
									</thead>
									<tbody id="idTbody">
										<?php echo $trNya; ?>
									</tbody>
								</table>
							</div>
							<div class="table-responsive" align="center">
								<label style="font-weight: bold;color: #0078ff;" >Sisa Cuti : <?php echo $sisaCuti; ?> Hari</label>
							</div>
						</div>
					</div>
				</div>
			</section>
		</section>
	</section>
</body>
</html>

