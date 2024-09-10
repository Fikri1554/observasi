<?php $this->load->view('myApps/menu'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script type="text/javascript">
		$(document).ready(function(){
		});
		function viewSurveyCust(id)
		{
			$(".classListSurvey").empty();
			$("#idLoading").show();
			$(".classViewSurvey").show(200);
			$("#idBodySurvey").empty();

			$.post('<?php echo base_url("surveyCustomer/getSurveyCust"); ?>',
			{   
				idCust : id
			},
				function(data) 
				{	
					$("#idBodySurvey").append(data.trNya);
				},
			"json"
			);
		}
		function viewSummari(id)
		{
			$(".classListSurvey").empty();
			$.post('<?php echo base_url("surveyCustomer/viewSummary"); ?>',
			{   
				idCust : id
			},
				function(data) 
				{	
					$("#idTbodySummary").append(data.trNya);
					$("#idTbodyKriteria").append(data.trNyaCriteria);
					$(".classViewSummary").show(200);
				},
			"json"
			);
		}
		function reloadPage()
		{
			window.location = "<?php echo base_url('surveyCustomer/getDataSurvey');?>";
		}
	</script>
</head>
<body>
	<section id="container" class="classListSurvey">
		<section id="main-content">
			<section class="wrapper site-min-height" style="min-height:400px;">
				<h3>
					<i class="fa fa-angle-right"></i> Surveyed List
				</h3>
				<div class="form-panel" id="idDataTable">
					<div class="row">
						<div class="col-md-2 col-xs-12">
							<button type="button" id="idBtnRefresh" onclick="reloadPage();" class="btn btn-success btn-xs btn-block" title="Refresh"><i class="glyphicon glyphicon-refresh"></i> Refresh</button>
						</div>
					</div>
					<div class="row mt" id="idData1">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
									<thead>
										<tr style="background-color: #ba5500;color: #FFF;">
											<th style="vertical-align: middle; width:3%;text-align:center;padding: 10px;">No</th>
											<th style="vertical-align: middle; width:10%;text-align:center;">Surveyed Date</th>
											<th style="vertical-align: middle; width:20%;text-align:center;">Company</th>
											<th style="vertical-align: middle; width:20%;text-align:center;">Customer Name</th>
											<th style="vertical-align: middle; width:20%;text-align:center;">Position</th>
											<th style="vertical-align: middle; width:10%;text-align:center;">Status</th>
										</tr>
									</thead>
									<tbody id="idTbody">
										<?php echo $trNya; ?>
									</tbody>
								</table>
							</div>
							<div id="idPageNya">
								<label style="float: left;"><?php echo $pageNya; ?></label>
								<label style="float: right;font-weight:bold;"><?php echo $ttlData; ?></label>
							</div>
						</div>
					</div>
				</div>
			</section>
		</section>
	</section>
	<section id="container" class="classViewSurvey" style="display: none;">
		<section id="main-content">
			<section class="wrapper site-min-height" style="min-height:400px;">
				<h3>
					<i class="fa fa-angle-right"></i> Survey Customer
					<span style="padding-left:20px;display:none;" id="idLoadingSurvey"><img src="<?php echo base_url('assets/img/loading.gif'); ?>"></span>
				</h3>
				<div class="form-panel" id="idDataTable">
					<div class="row">
						<div class="col-md-1" id="btnNavAtas">
							<button type="button" id="idBtnRefresh" onclick="reloadPage();" class="btn btn-primary btn-xs btn-block" title="Refresh"><i class="fa fa-reply-all"></i> Back</button>
						</div>
					</div>
					<div class="row mt" id="idData1">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
									<thead>
						              <tr style="background-color: #ba5500;color: #FFF;">
						                <th rowspan="2" colspan="2" style="width:25%;text-align:center;vertical-align:middle;">Criteria Of Customer Satisfaction</th>
						                <th colspan="5" style="width:30%;text-align: center;">Importance Rating</th>
						                <th colspan="5" style="width:30%;text-align: center;">Perfomance Rating</th>
						                <th rowspan="2" style="width:15%;text-align: center;vertical-align:middle;">Remark</th>
						              </tr>
						              <tr style="background-color: #ba5500;color: #FFF;">
						                <th style="text-align: center;">Unimportant</th>
						                <th style="text-align: center;">Slightly</th>
						                <th style="text-align: center;">Moderately</th>
						                <th style="text-align: center;">Important</th>
						                <th style="text-align: center;">Very Important</th>
						                <th style="text-align: center;">Very Poor</th>
						                <th style="text-align: center;">Poor</th>
						                <th style="text-align: center;">Fair</th>
						                <th style="text-align: center;">Good</th>
						                <th style="text-align: center;">Excellent</th>
						              </tr>
						            </thead>
						            <tbody id="idBodySurvey">
						            </tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</section>
		</section>
	</section>
	<section id="container" class="classViewSummary" style="display: none;">
		<section id="main-content">
			<section class="wrapper site-min-height" style="min-height:400px;">
				<h3>
					<i class="fa fa-angle-right"></i> Tabel Customer Satisfaction Index (CSI)
				</h3>
				<div class="form-panel" id="idDataTable">
					<div class="row">
						<div class="col-md-1" id="btnNavAtas">
							<button type="button" id="idBtnRefresh" onclick="reloadPage();" class="btn btn-primary btn-xs btn-block" title="Refresh"><i class="fa fa-reply-all"></i> Back</button>
						</div>
					</div>
					<div class="row mt" id="idData1">
						<div class="col-md-6 col-xs-12">
							<div class="table-responsive">
								<label>- Data Survey</label>
								<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
									<thead>
										<tr style="background-color: #ba5500;color: #FFF;">
											<th style="vertical-align: middle; width:10%;text-align:center;padding: 5px;">KODE</th>
											<th style="vertical-align: middle; width:10%;text-align:center;">( I )</th>
											<th style="vertical-align: middle; width:20%;text-align:center;">( P )</th>
											<th style="vertical-align: middle; width:20%;text-align:center;">( I x P )</th>
										</tr>
									</thead>
									<tbody id="idTbodySummary">
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-md-6 col-xs-12">
							<div class="table-responsive">
								<label>- Kriteria Tingkat Kepuasan</label>
								<table class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
									<thead>
										<tr style="background-color: #ba5500;color: #FFF;">
											<th style="vertical-align: middle; width:3%;text-align:center;padding: 5px;">No</th>
											<th style="vertical-align: middle; width:10%;text-align:center;">Nilai CSI (%)</th>
											<th style="vertical-align: middle; width:20%;text-align:center;">Keterangan (CSI)</th>
										</tr>
									</thead>
									<tbody id="idTbodyKriteria">
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

