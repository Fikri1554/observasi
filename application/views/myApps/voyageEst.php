<?php $this->load->view('myApps/menu'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#idLoading").hide();
			$("#idForm").hide();
			
			$("#idBtnAdd").click(function(){
				$("#idDataTable").hide();
				$("#idForm").show(255);
			});
			$("#btnSave").click(function(){
				var idEdit,fileName,vslName,cargoType,typeTrip,txtTypeTrip,lPort,lPortCost,lPortWtgDay,dPort,dPortCost,dPortWtgDay,vslDwt,vslDrft,speedL,speedB,foLdn,foBlst,stemDo,portDo,portFo,distLp,distDp,seeMarginLp,seeMarginDp,priceFo,priceDo,etaEts,drftBss,tpc,loadingRate,dischRate,demmurage,despatch,ttLp,ttDp,term,lessComm,fragTax,handling,ohPI,tceFrom,tceTo,cargoSize1,cargoSize2,cargoSize3,cargoSize4,cargoSize5,cargoSize6,cargoSize7,curr,ifCrgOnly = "";
				
				idEdit = $("#txtIdEdit").val();
				fileName = $("#txtFileName").val();
				vslName = $("#txtVslName").val();
				cargoType = $("#txtCrgType").val();
				typeTrip = $("#slcTypeTrip").val();
				txtTypeTrip = $("#txt1StBal").val();
				lPort = $("#txtLP").val();
				lPortCost = $("#txtLPPortCost").val();
				lPortWtgDay = $("#txtLPWtg").val();
				dPort = $("#txtDP").val();
				dPortCost = $("#txtDPPortCost ").val();
				dPortWtgDay = $("#txtDPWtg").val();
				vslDwt = $("#txtDwt").val();
				vslDrft = $("#txtDrft").val();
				speedL = $("#txtSpeedL").val();
				speedB = $("#txtSpeedB").val();
				foLdn = $("#txtFoLdn").val();
				foBlst = $("#txtFoBlst").val();
				stemDo = $("#txtStemDo").val();
				portDo = $("#txtPortDo").val();
				portFo = $("#txtPortFo").val();
				distLp = $("#txtDistLp").val();
				distDp = $("#txtDistDp").val();
				seeMarginLp = $("#txtSeeMarginLp").val();
				seeMarginDp = $("#txtSeeMarginDp").val();
				priceFo = $("#txtPriceFo").val();
				priceDo = $("#txtPriceDo").val();
				etaEts = $("#txtEtaEts").val();
				drftBss = $("#txtDrfBss").val();
				tpc = $("#txtTPC").val();
				loadingRate = $("#txtLoadingRate").val();
				dischRate = $("#txtDischRate").val();
				demmurage = $("#txtDemmurage").val();
				despatch = $("#txtDespatch").val();
				ttLp = $("#txtTTLP").val();
				ttDp = $("#txtTTDP").val();
				term = $("#txtTerm").val();
				lessComm = $("#txtLessComm").val();
				fragTax = $("#txtFraTax").val();
				handling = $("#txtHandling").val();
				ohPI = $("#txtOHPI").val();
				tceFrom = $("#txtTceFrom").val();
				tceTo = $("#txtTceTo").val();
				cargoSize1 = $("#txtCS1").val();
				cargoSize2 = $("#txtCS2").val();
				cargoSize3 = $("#txtCS3").val();
				cargoSize4 = $("#txtCS4").val();
				cargoSize5 = $("#txtCS5").val();
				cargoSize6 = $("#txtCS6").val();
				cargoSize7 = $("#txtCS7").val();
				curr = $('input[name=rdCurr]:checked').val();
				ifCrgOnly = $("#txtIfCargo").val();
				
				$.post('<?php echo base_url("shipCommercial/addEstVoyage"); ?>',
				{ 	idEdit : idEdit,fileName : fileName,vslName : vslName,cargoType : cargoType,typeTrip : typeTrip,txtTypeTrip : txtTypeTrip,lPort : lPort,lPortCost : lPortCost,lPortWtgDay : lPortWtgDay,dPort : dPort,dPortCost : dPortCost,dPortWtgDay : dPortWtgDay,vslDwt : vslDwt,vslDrft : vslDrft,speedL : speedL,speedB : speedB,foLdn : foLdn,foBlst : foBlst,stemDo : stemDo,portDo : portDo,portFo : portFo,distLp : distLp,distDp : distDp,seeMarginLp : seeMarginLp,seeMarginDp : seeMarginDp,priceFo : priceFo,priceDo : priceDo,etaEts : etaEts,drftBss : drftBss,tpc : tpc,loadingRate : loadingRate,dischRate : dischRate,demmurage : demmurage,despatch : despatch,ttLp : ttLp,ttDp : ttDp,term : term,lessComm : lessComm,fragTax : fragTax,handling : handling,ohPI : ohPI,tceFrom : tceFrom,tceTo : tceTo,cargoSize1 : cargoSize1,cargoSize2 : cargoSize2,cargoSize3 : cargoSize3,cargoSize4 : cargoSize4,cargoSize5 : cargoSize5,cargoSize6 : cargoSize6,cargoSize7 : cargoSize7,curr : curr,ifCrgOnly : ifCrgOnly },
				function(data) 
				{
					alert(data);
					reloadPage();
				},
				"json"
				);
			});
			$("#slcTypeTrip").change(function(){
				var slcNya = $(this).val();
				if(slcNya == '2')
				{
					$("#txt1StBal").attr("disabled",false);
				}else{
					$("#txt1StBal").val("");
					$("#txt1StBal").attr("disabled",true);
				}
			});
			$("#idBtnViewTceTble").click(function(){
				fromViewTce.target = "_blank";
				fromViewTce.submit();
			});
			$("#idBtnViewRateDetail").click(function(){
				formRateDetail.target = "_blank";
				formRateDetail.submit();
			});
			$("#idbtnSearch").click(function(){
				var searchNya = "";

				searchNya = $("#txtSearchName").val();
				$("#idLoading").show();
				$("#idBody").empty();
				$("#idPageNya").empty();
				$.post('<?php echo base_url("shipCommercial/getVoyageEst/search"); ?>',
				{   
					searchNya : searchNya
				},
					function(data) 
					{
						$("#idBody").append(data.dataTr);
						$("#idLoading").hide();
					},
				"json"
				);
				// $('#idModalShowCost').modal("show");
			});
		});		
		function editEstVoyage(id,actionType)
		{
			$("#idLoading").show();
			$("#idDataTable").hide();
			$("#idForm").show(255);
			$.post('<?php echo base_url("shipCommercial/getEdit"); ?>',
			{   
				uniqueKey : id,action : "editEstVoyage"
			},
				function(data) 
				{	
					$.each( data.data, function( key, value )
					{
						$("#txtIdEdit").val(value.uniquekey);
						$("#txtFileName").val(value.filenm);
						$("#txtVslName").val(value.vsl);
						$("#txtCrgType").val(value.vslcargo);
						$("#slcTypeTrip").val(value.voy);
						$("#txt1StBal").val(value.bal);
						$("#txtLP").val(value.lport);
						$("#txtLPPortCost").val(value.lportcost);
						$("#txtLPWtg").val(value.lwtgday);
						$("#txtDP").val(value.dport);
						$("#txtDPPortCost ").val(value.dportcost);
						$("#txtDPWtg").val(value.dwtgday);
						$("#txtDwt").val(value.dwt);
						$("#txtDrft").val(value.drft);
						$("#txtSpeedL").val(value.speedl);
						$("#txtSpeedB").val(value.speedb);
						$("#txtFoLdn").val(value.foldn);
						$("#txtFoBlst").val(value.foblst);
						$("#txtStemDo").val(value.stemdo);
						$("#txtPortDo").val(value.portdo);
						$("#txtPortFo").val(value.portfo);
						$("#txtDistLp").val(value.v1dist);
						$("#txtDistDp").val(value.v2dist);
						$("#txtSeeMarginLp").val(value.v1seamar);
						$("#txtSeeMarginDp").val(value.v2seamar);
						$("#txtPriceFo").val(value.vpricefo);
						$("#txtPriceDo").val(value.vpricedo);
						$("#txtEtaEts").val(value.vetaets);
						$("#txtDrfBss").val(value.draftbss);
						$("#txtTPC").val(value.tpc);
						$("#txtLoadingRate").val(value.lrate);
						$("#txtDischRate").val(value.drate);
						$("#txtDemmurage").val(value.dmg);
						$("#txtDespatch").val(value.dpch);
						$("#txtTTLP").val(value.ltt);
						$("#txtTTDP").val(value.dtt);
						$("#txtTerm").val(value.term);
						$("#txtLessComm").val(value.comm);
						$("#txtFraTax").val(value.frgtax);
						$("#txtHandling").val(value.handle);
						$("#txtOHPI").val(value.ohpi);
						$("#txtTceFrom").val(value.tcefm);
						$("#txtTceTo").val(value.tceto);
						$("#txtCS1").val(value.carsize1);
						$("#txtCS2").val(value.carsize2);
						$("#txtCS3").val(value.carsize3);
						$("#txtCS4").val(value.carsize4);
						$("#txtCS5").val(value.carsize5);
						$("#txtCS6").val(value.carsize6);
						$("#txtCS7").val(value.carsize7);
						if(value.currency == "1")
						{
							$("#rdCurrUsd").attr("checked",true);
						}else{
							$("#rdCurrIdr").attr("checked",true);
						}
						if(value.voy == "2")
						{
							$("#txt1StBal").attr("disabled",false);
						}
						$("#txtIfCargo").val(value.ifcar);
					});
					if(actionType == 'view')
					{ 
						viewData();
					}
					$("#idLoading").hide();
				},
			"json"
			);
		}
		function actShowCost(uKey)
		{
			$("#idLoading").show();
			$.post('<?php echo base_url("shipCommercial/getActShowCost"); ?>',
			{   
				uniqueKey : uKey
			},
				function(data) 
				{
					$("#txtMVoyageCost").val(data.ttlVoyCost);
					$("#txtMTtlFuelCost").val(data.ttlFODOCost);
					$("#txtModelFO").val(data.ttlFoCost);
					$("#txtModelDO").val(data.ttlDoCost);
					$("#txtMCostTon").val(data.ttlCostTon);
					$("#idLoading").hide();
				},
			"json"
			);
			$('#idModalShowCost').modal("show");
		}		
		function actTceTable(uKey)
		{
			document.getElementById("fromViewTce").reset();
			$("#txtIdUniqKey").val(uKey);
			$('#idModalTceTable').modal("show");
		}
		function actFreightTable(uKey)
		{
			$("#txtIdUniqKeyFreight").val(uKey);
			formFreightTable.target = "_blank";
			$("#formFreightTable").submit();
		}
		function actRateDetail(uKey)
		{
			document.getElementById("formRateDetail").reset();
			$("#txtIdUniqKeyRateDetail").val(uKey);
			$('#idModalRateDetail').modal("show");
		}
		function actSensAnalys(uKey)
		{
			document.getElementById("formSensAnalys").reset();
			$("#txtIdUniqKeySensAnalys").val(uKey);
			$('#idModalSensAnalys').modal("show");
		}
		function reloadPage(uKey)
		{
			window.location = "<?php echo base_url('shipCommercial/getVoyageEst');?>";
		}
		function viewData()
		{
			$("#idForm input").prop("disabled", true);
			$("#idForm option").prop("disabled", true);
			$("#btnSave").hide();
			$("#btnCancel").text("Close");
		}
		function formatTime(objFormField)
		{
			intFieldLength = objFormField.value.length;
			if(intFieldLength==2 || intFieldLength == 2)
			{
				objFormField.value = objFormField.value + ":";
				return false;
			} 
		}
	</script>
</head>
<body>
	<section id="container">
		<section id="main-content">
			<section class="wrapper site-min-height" style="min-height:400px;">
				<h3>
					<i class="fa fa-angle-right"></i> Voyage Estimator<span style="padding-left: 20px;" id="idLoading"><img src="<?php echo base_url('assets/img/loading.gif'); ?>" ></span>
				</h3>
				<div class="form-panel" id="idDataTable" style="">
					<div class="row" id="idData1">
						<div id="idFormSearch">
							<dir class="col-md-1 col-xs-12">
								<button type="button" id="idBtnAdd" class="btn btn-primary btn-sm btn-block" title="Add"><i class="fa fa-plus-square" style="margin-right: 5px;"></i> Add</button>
							</dir>
							<dir class="col-md-3 col-xs-12" id="idtxtSearch" style="display: ">
								<input placeholder="File Name" type="text" class="form-control input-sm" id="txtSearchName" name="txtSearchName" value="">
							</dir>
							<dir class="col-md-2 col-xs-6" id="idSearch" style="display: ">
								<button type="button" id="idbtnSearch" class="btn btn-primary btn-sm btn-block" title="Search"><i class="fa fa-search" style="margin-right: 5px;"></i> Search</button>
							</dir>
							<dir class="col-md-2 col-xs-6">
								<button type="button" id="idBtnRefresh" onclick="reloadPage();" class="btn btn-success btn-sm btn-block" title="Refresh"><i class="glyphicon glyphicon-refresh"></i> Refresh</button>
							</dir>
						</div>
						<div class="col-md-12 col-xs-12">
							<div class="table-responsive">
								<table class="table table-hover table-border table-bordered table-condensed table-advance">
									<thead>
										<tr style="background-color: #ba5500;color: #FFF;">
											<th style="vertical-align:middle;width:3%;text-align:center;padding:5px;">No</th>
											<th style="vertical-align:middle;width:30%;text-align:center;">File Name</th>
											<th style="vertical-align:middle;width:12%;text-align:center;">Last Update</th>
											<th style="vertical-align:middle;width:50%;text-align:center;">Action</th>
										</tr>
									</thead>
									<tbody id="idBody">
										<?php echo $dataTr; ?>
									</tbody>
								</table>
							</div>
							<div id="idPageNya">
								<?php echo $pageNya; ?>
							</div>
						</div>
					</div>
				</div>
				<div class="row" id="idForm">
					<fieldset>
						<div class="col-md-4 col-xs-12">
							<div class="form-panel">							
								<div class="form-group">
								    <label for="txtFileName"><u>File Name :</u></label>
								    <input placeholder="File Name" type="text" class="form-control input-sm" id="txtFileName" name="txtFileName" value="">
								</div>
							</div>
						</div>
						<div class="col-md-4 col-xs-12">
							<div class="form-panel">
								<div class="form-group">
								    <label for="txtVslName"><u>Vissel Name :</u></label>
								    <input placeholder="Vessel Name" type="text" class="form-control input-sm" id="txtVslName" name="txtVslName" value="">
								</div>
							</div>
						</div>
						<div class="col-md-4 col-xs-12">
							<div class="form-panel">
								<div class="form-group">
								    <label for="txtCrgType"><u>Cargo Type :</u></label>
								    <input placeholder="Cargo Type" type="text" class="form-control input-sm" id="txtCrgType" name="txtCrgType" value="">
								</div>
							</div>
						</div>						
						<div class="col-md-4 col-xs-12">
							<div class="form-panel">
								<div class="form-group">
								    <select name="slcTypeTrip" id="slcTypeTrip" class="form-control input-sm">
								    	<option value="1">None</option>
								    	<option value="2">1st Bal</option>
								    	<option value="3">Round Voyage</option>
								    </select>
								    <input type="text" class="form-control input-sm" id="txt1StBal" disabled="disabled" name="txt1StBal" style="margin-top: 5px;">
								</div>
							</div>
						</div>
						<div class="col-md-4 col-xs-12">
							<div class="form-panel">
								<div class="form-group">
								<fieldset>
									<div class="col-md-12 col-xs-12" style="padding: 0px;">
										 <input placeholder="L/P" type="text" class="form-control input-sm" id="txtLP">
									</div>							
									<div class="col-md-6 col-xs-12" style="padding: 1px;">
										<input type="text" class="form-control input-sm" id="txtLPPortCost" placeholder="Port Cosh" style="margin-top: 5px;">
									</div>
									<div class="col-md-6 col-xs-12" style="padding: 1px;">
										<input type="text" class="form-control input-sm" id="txtLPWtg" placeholder="Wtg Days" style="margin-top: 5px;">
									</div>
								</fieldset>
								</div>
							</div>
						</div>
						<div class="col-md-4 col-xs-12">
							<div class="form-panel">
								<div class="form-group">
								<fieldset>
									<div class="col-md-12 col-xs-12" style="padding: 0px;">
										 <input placeholder="D/P" type="text" class="form-control input-sm" id="txtDP" name="txtLoadPort" value="">
									</div>							
									<div class="col-md-6 col-xs-12" style="padding: 1px;">
										<input type="text" class="form-control input-sm" id="txtDPPortCost" placeholder="Port Cosh" style="margin-top: 5px;">
									</div>
									<div class="col-md-6 col-xs-12" style="padding: 1px;">
										<input type="text" class="form-control input-sm" id="txtDPWtg" placeholder="Wtg Days" style="margin-top: 5px;">
									</div>
								</fieldset>
								</div>
							</div>
						</div>
						<div class="col-md-12 col-xs-12">
							<div class="form-panel">
								<div class="form-group">
									<fieldset>
									    <div class="col-md-1 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtCrgType"><u>Vessel DWT :</u></label>
									    	<input style="text-align: right;" placeholder="0.00" type="text" class="form-control input-sm" id="txtDwt" value="">
									    </div>
									    <div class="col-md-1 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtCrgType"><u>Vessel Drft :</u></label>
									    	<input style="text-align: right;" placeholder="0.00" type="text" class="form-control input-sm" id="txtDrft" value="">
									    </div>
									    <div class="col-md-1 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtCrgType"><u>Speed L :</u></label>
									    	<input style="text-align: right;" placeholder="0.00" type="text" class="form-control input-sm" id="txtSpeedL" value="">
									    </div>
									    <div class="col-md-1 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtCrgType"><u>Speed B :</u></label>
									    	<input style="text-align: right;" placeholder="0.00" type="text" class="form-control input-sm" id="txtSpeedB" value="">
									    </div>
									    <div class="col-md-1 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtCrgType"><u>FO LDN :</u></label>
									    	<input style="text-align: right;" placeholder="0.00" type="text" class="form-control input-sm" id="txtFoLdn" value="">
									    </div>
									    <div class="col-md-1 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtCrgType"><u>FO BLST :</u></label>
									    	<input style="text-align: right;" placeholder="0.00" type="text" class="form-control input-sm" id="txtFoBlst" value="">
									    </div>
									    <div class="col-md-1 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtCrgType"><u>Stem DO :</u></label>
									    	<input style="text-align: right;" placeholder="0.00" type="text" class="form-control input-sm" id="txtStemDo" value="">
									    </div>
									    <div class="col-md-1 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtCrgType"><u>Port DO :</u></label>
									    	<input style="text-align: right;" placeholder="0.00" type="text" class="form-control input-sm" id="txtPortDo" value="">
									    </div>
									    <div class="col-md-1 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtCrgType"><u>Port FO :</u></label>
									    	<input style="text-align: right;" placeholder="0.00" type="text" class="form-control input-sm" id="txtPortFo" value="">
									    </div>
									</fieldset>
								</div>
							</div>
						</div>
						<div class="col-md-4 col-xs-12">
							<div class="form-panel">
								<div class="form-group">
								<fieldset>
									<div class="col-md-6 col-xs-12" style="padding: 2px;">
										<label for="txtDistLp"><u>Dist(NM) :</u></label>
										<input placeholder="L/P or 1st Bal" type="text" class="form-control input-sm" id="txtDistLp" value="" style="margin-bottom: 5px;">
										<input placeholder="D/P or L/P" type="text" class="form-control input-sm" id="txtDistDp" value="">
									</div>
									<div class="col-md-6 col-xs-12" style="padding: 2px;">
										<label for="txtSeeMarginLp"><u>See Margin :</u></label>
										<input placeholder="L/P or 1st Bal (%)" type="text" class="form-control input-sm" id="txtSeeMarginLp" value="" style="margin-bottom: 5px;">
										<input placeholder="D/P or L/P (%)" type="text" class="form-control input-sm" id="txtSeeMarginDp" value="">
									</div>
								</fieldset>
								</div>
							</div>
						</div>
						<div class="col-md-4 col-xs-12">
							<div class="form-panel">
								<div class="form-group">
								<fieldset>
									<div class="col-md-4 col-xs-12" style="padding: 2px;">
										<label for="txtPriceFo"><u>Price FO :</u></label>
										<input style="text-align: right;" placeholder="0.00" type="text" class="form-control input-sm" id="txtPriceFo" value="">
									</div>
									<div class="col-md-4 col-xs-12" style="padding: 2px;">
										<label for="txtPriceDo"><u>Price DO :</u></label>
										<input style="text-align: right;" placeholder="0.00" type="text" class="form-control input-sm" id="txtPriceDo" value="">
									</div>
									<div class="col-md-4 col-xs-12" style="padding: 2px;">
										<label for="txtEtaEts"><u>ETA/ETS :</u></label>
										<input style="text-align: right;" type="text" class="form-control input-sm" id="txtEtaEts" value="">
									</div>
								</fieldset>
								</div>
							</div>
						</div>
						<div class="col-md-4 col-xs-12">
							<div class="form-panel">
								<div class="form-group">
								<fieldset>
									<div class="col-md-6 col-xs-12" style="padding: 2px;">
										<label for="txtDrfBss"><u>Draft Bss :</u></label>
										<input style="text-align: right;" placeholder="0.00" type="text" class="form-control input-sm" id="txtDrfBss" value="">
									</div>
									<div class="col-md-6 col-xs-12" style="padding: 2px;">
										<label for="txtTPC"><u>TPC :</u></label>
										<input style="text-align: right;" placeholder="0.00" type="text" class="form-control input-sm" id="txtTPC" value="">
									</div>
								</fieldset>
								</div>
							</div>
						</div>
						<div class="col-md-12 col-xs-12">
							<div class="form-panel">
								<div class="form-group">
									<fieldset>
									    <div class="col-md-2 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtLoadRate"><u>Loading Rate :</u></label>
									    	<input style="text-align: right;" placeholder="0.00" type="text" class="form-control input-sm" id="txtLoadingRate" value="">
									    </div>
									    <div class="col-md-2 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtDischRate"><u>Discharging Rate :</u></label>
									    	<input style="text-align: right;" placeholder="0.00" type="text" class="form-control input-sm" id="txtDischRate" value="">
									    </div>
									    <div class="col-md-2 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtDemmurage"><u>Demmurage :</u></label>
									    	<input style="text-align: right;" placeholder="0.00" type="text" class="form-control input-sm" id="txtDemmurage" value="">
									    </div>
									    <div class="col-md-1 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtCrgType"><u>Despatch :</u></label>
									    	<input style="text-align: right;" placeholder="0.00" type="text" class="form-control input-sm" id="txtDespatch" value="">
									    </div>
									    <div class="col-md-1 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtCrgType"><u>TT L/P :</u></label>
									    	<input type="text" onKeyPress="formatTime(this);" maxlength="5" placeholder="00:00" class="form-control input-sm" id="txtTTLP" value="">
									    </div>
									    <div class="col-md-1 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtCrgType"><u>TT D/P :</u></label>
									    	<input type="text" onKeyPress="formatTime(this);" maxlength="5" placeholder="00:00" class="form-control input-sm" id="txtTTDP" value="">
									    </div>
									    <div class="col-md-2 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtCrgType"><u>Term :</u></label>
									    	<input type="text" class="form-control input-sm" id="txtTerm" value="">
									    </div>
									</fieldset>
								</div>
							</div>
						</div>
						<div class="col-md-8 col-xs-12">
							<div class="form-panel">
								<div class="form-group">
									<fieldset>
									    <div class="col-md-2 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtLessComm"><u>Less Comm :</u></label>
									    	<input style="text-align: right;" placeholder="0.00" type="text" class="form-control input-sm" id="txtLessComm" value="">
									    </div>
									    <div class="col-md-2 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtFraTax"><u>Fraight Tax :</u></label>
									    	<input style="text-align: right;" placeholder="0.00" type="text" class="form-control input-sm" id="txtFraTax" value="">
									    </div>
									    <div class="col-md-3 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtHandling"><u>Handling :</u></label>
									    	<input style="text-align: right;" placeholder="0.00" type="text" class="form-control input-sm" id="txtHandling" value="">
									    </div>
									    <div class="col-md-3 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtOHPI"><u>O/H&P&I :</u></label>
									    	<input style="text-align: right;" placeholder="0.00" type="text" class="form-control input-sm" id="txtOHPI" value="">
									    </div>
									</fieldset>
								</div>
							</div>
						</div>
						<div class="col-md-4 col-xs-12">
							<div class="form-panel">
								<div class="form-group">
									<label><u>TCE :</u></label>
								<fieldset>									
									<div class="col-md-6 col-xs-12" style="padding: 2px;">
										<input placeholder="From" type="text" class="form-control input-sm" id="txtTceFrom" value="" style="margin-bottom: 5px;">
									</div>
									<div class="col-md-6 col-xs-12" style="padding: 2px;">
										<input placeholder="To" type="text" class="form-control input-sm" id="txtTceTo" value="" style="margin-bottom: 5px;">
									</div>
								</fieldset>
								</div>
							</div>
						</div>
						<div class="col-md-12 col-xs-12">
							<div class="form-panel">
								<div class="form-group">
									<label><u>Cargo Size :</u></label>
									<fieldset>
									    <div class="col-md-1 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtCS1"># 1 :</label>
									    	<input style="text-align: right;" type="text" placeholder="0.00" class="form-control input-sm" id="txtCS1" value="">
									    </div>
									    <div class="col-md-1 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtCS2"># 2 :</label>
									    	<input style="text-align: right;" type="text" placeholder="0.00" class="form-control input-sm" id="txtCS2" value="">
									    </div>
									    <div class="col-md-1 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtCS3"># 3 :</label>
									    	<input style="text-align: right;" type="text" placeholder="0.00" class="form-control input-sm" id="txtCS3" value="">
									    </div>
									    <div class="col-md-1 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtCS4"># 4 :</label>
									    	<input style="text-align: right;" type="text" placeholder="0.00" class="form-control input-sm" id="txtCS4" value="">
									    </div>
									    <div class="col-md-1 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtCS5"># 5 :</label>
									    	<input style="text-align: right;" type="text" placeholder="0.00" class="form-control input-sm" id="txtCS5" value="">
									    </div>
									    <div class="col-md-1 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtCS6"># 6 :</label>
									    	<input style="text-align: right;" type="text" placeholder="0.00" class="form-control input-sm" id="txtCS6" value="">
									    </div>
									    <div class="col-md-1 col-xs-12" style="padding: 2px;margin-right: 10px;">
									    	<label for="txtCS7"># 7 :</label>
									    	<input style="text-align: right;" type="text" placeholder="0.00" class="form-control input-sm" id="txtCS7" value="">
									    </div>
									</fieldset>
								</div>
							</div>
						</div>
						<div class="col-md-12 col-xs-12">
							<div class="form-panel">
								<div class="form-group">
									<label><u>Currency :</u></label>
								<fieldset>									
									<div class="col-md-3 col-xs-12" style="padding: 2px;">
										<div class="radio-inline">
										  <label><input type="radio" id="rdCurrUsd" name="rdCurr" value="1">USD</label>
										</div>
										<div class="radio-inline">
										  <label><input type="radio" id="rdCurrIdr" name="rdCurr" value="2">IDR</label>
										</div>
									</div>
									<div class="col-md-1 col-xs-12" style="padding: 2px;">
										<label>If Cargo Only :</label>
									</div>
									<div class="col-md-2 col-xs-12" style="padding: 2px;">
										<input placeholder="0" type="text" class="form-control input-sm" id="txtIfCargo" value="" style="margin-bottom: 5px;">
									</div>
								</fieldset>
								</div>								
							</div>
						</div>
					</fieldset>
					<div class="col-md-12">
						<div class="form-panel">
							<div class="form-group" align="center" style="padding-top: 20px;">
								<input type="hidden" name="" id="txtIdEdit" value="">
								<button id="btnSave" class="btn btn-primary btn-sm" name="btnSave" title="Save">
									<i class="fa fa-check-square-o"></i> Save</button>
								<button id="btnCancel" onclick="reloadPage();" class="btn btn-danger btn-sm" name="btnCancel" title="Cancel"><i class="fa fa-ban"></i> Cancel</button>
							</div>
						</div>
					</div>
				</div>
			</section>
		</section>
	</section>
	<div class="modal fade" id="idModalShowCost" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
		        <div class="modal-header" style="background-color:#d56b03;">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title" id="idTtitleModal">Show Cost</h4>
		        </div>
		        <div class="modal-body">
		          <div class="row">
		          	<div class="col-md-12 col-xs-12">
		          		<label for="txtMVoyageCost">Voyage Cost :</label>
						<input type="text" disabled="disabled" class="form-control input-sm" id="txtMVoyageCost" value="">
		          	</div>
		          	<div class="col-md-4 col-xs-12">
		          		<label for="txtMTtlFuelCost">Total Fuel Cost :</label>
						<input type="text" disabled="disabled" class="form-control input-sm" id="txtMTtlFuelCost" value="">
		          	</div>
		          	<div class="col-md-2 col-xs-12">
		          		<label style="font-size: 10px;color: red;">Total Fuel Cost => FO + DO.</label>
		          	</div>
		          	<div class="col-md-3 col-xs-12">
		          		<label for="txtModelFO">FO :</label>
						<input type="text" disabled="disabled" class="form-control input-sm" id="txtModelFO" value="">
		          	</div>
		          	<div class="col-md-3 col-xs-12">
		          		<label for="txtModelDO">DO :</label>
						<input type="text" disabled="disabled" class="form-control input-sm" id="txtModelDO" value="">
		          	</div>
		          	<div class="col-md-12 col-xs-12">
		          		<label for="txtMCostTon">Cost / Ton :</label>
						<input type="text" disabled="disabled" class="form-control input-sm" id="txtMCostTon" value="">
		          	</div>
		          </div>
		        </div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="idModalTceTable" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
		        <div class="modal-header" style="background-color:#d56b03;">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title" id="idTtitleModal">Print TCE Table</h4>
		        </div>
		        <div class="modal-body">
		        	<form name="fromViewTce" action="<?php echo base_url("shipCommercial/getViewTceTable"); ?>" method="post" id="fromViewTce">
		          	<div class="row">
		          		<div class="col-md-6 col-xs-12">
		          			<label for="txtIdFrom">From :</label>
							<input type="text" placeholder="0.00" name="txtIdFrom" class="form-control input-sm" id="txtIdFrom" value="">
		          		</div>
		          		<div class="col-md-6 col-xs-12">
		          			<label for="txtIdTo">To :</label>
							<input type="text" placeholder="0.00" name="txtIdTo" class="form-control input-sm" id="txtIdTo" value="">
		          		</div>
		          		<div class="col-md-6 col-xs-12">
		          			<label for="slcIntrvlTceTbl">Interval :</label>
							<select name="slcIntrvlTceTbl" id="slcIntrvlTceTbl" class="form-control input-sm">
								<option value="0.01">0.01</option>
								<option value="0.05">0.05</option>
								<option value="0.25">0.25</option>
							</select>
		          		</div>
		          		<div class="col-md-6 col-xs-12">
		          			<label for="idBtnViewTceTble"></label>
		          			<input type="hidden" name="txtIdUniqKey" id="txtIdUniqKey" value="">
		          			<button type="button" id="idBtnViewTceTble" class="btn btn-success btn-sm btn-block" title="View"><i class="fa fa-eye" style="margin-right: 5px;"></i> View</button>
		          		</div>
		          	</div>
		          </form>
		        </div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="idModalRateDetail" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
		        <div class="modal-header" style="background-color:#d56b03;">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title" id="idTtitleModal">Freight Rate Detail</h4>
		        </div>
		        <div class="modal-body">
		        	<form name="formRateDetail" action="<?php echo base_url("shipCommercial/getViewFreightRateDetail"); ?>" method="post" id="formRateDetail">
		          	<div class="row">
		          		<div class="col-md-4 col-xs-12">
		          			<label for="txtCargoSize">Cargo Size :</label>
							<input type="text" placeholder="0.00" name="txtCargoSize" class="form-control input-sm" id="txtCargoSize" value="">
		          		</div>
		          		<div class="col-md-4 col-xs-12">
		          			<label for="txtFreightRate">Freight Rate :</label>
							<input type="text" placeholder="0.00" name="txtFreightRate" class="form-control input-sm" id="txtFreightRate" value="">
		          		</div>
		          		<div class="col-md-4 col-xs-12">
		          			<label for="idBtnViewRateDetail"></label>
		          			<input type="hidden" name="txtIdUniqKeyRateDetail" id="txtIdUniqKeyRateDetail" value="">
		          			<button type="button" id="idBtnViewRateDetail" class="btn btn-success btn-sm btn-block" title="View"><i class="fa fa-eye" style="margin-right: 5px;"></i> View</button>
		          		</div>
		          	</div>
		          </form>
		        </div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="idModalSensAnalys" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
		        <div class="modal-header" style="background-color:#d56b03;">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title" id="idTtitleModal">Print Sensitivity Analysis</h4>
		        </div>
		        <div class="modal-body">
		        	<form name="formSensAnalys" action="<?php echo base_url("shipCommercial/getViewSensAnalys"); ?>" target="_blank" method="post" id="formSensAnalys">
		          	<div class="row">
		          		<div class="col-md-4 col-xs-12">
		          			<label for="txtCargoSizeSensAnalys">Cargo Size :</label>
							<input type="text" placeholder="0.00" name="txtCargoSizeSensAnalys" class="form-control input-sm" id="txtCargoSizeSensAnalys" value="">
		          		</div>
		          		<div class="col-md-4 col-xs-12">
		          			<label for="txtIdFromSensAnalys">From :</label>
							<input type="text" placeholder="0.00" name="txtIdFromSensAnalys" class="form-control input-sm" id="txtIdFromSensAnalys" value="">
		          		</div>
		          		<div class="col-md-4 col-xs-12">
		          			<label for="txtIdToSensAnalys">To :</label>
							<input type="text" placeholder="0.00" name="txtIdToSensAnalys" class="form-control input-sm" id="txtIdToSensAnalys" value="">
		          		</div>
		          		<div class="col-md-6 col-xs-12">
		          			<label for="slcIntrvlSensAnalys">Interval :</label>
							<select name="slcIntrvlSensAnalys" id="slcIntrvlSensAnalys" class="form-control input-sm">
								<option value="0.01">0.01</option>
								<option value="0.05">0.05</option>
								<option value="0.25">0.25</option>
							</select>
		          		</div>
		          		<div class="col-md-6 col-xs-12">
		          			<label for="idBtnViewTceTble"></label>
		          			<input type="hidden" name="txtIdUniqKeySensAnalys" id="txtIdUniqKeySensAnalys" value="">
		          			<button type="submit" id="idBtnViewSensANalys" class="btn btn-success btn-sm btn-block" title="View"><i class="fa fa-eye" style="margin-right: 5px;"></i> View</button>
		          		</div>
		          	</div>
		          </form>
		        </div>
			</div>
		</div>
	</div>
	<form name="formFreightTable" action="<?php echo base_url("shipCommercial/getViewFreightTable"); ?>" method="post" id="formFreightTable">
		<input type="hidden" name="txtIdUniqKeyFreight" id="txtIdUniqKeyFreight" value="">
	</form>
</body>
</html>

