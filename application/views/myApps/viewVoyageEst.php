<?php $this->load->view('myApps/menu'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<script type="text/javascript">
		$(document).ready(function(){
			$( "[id^=txtDate]" ).datepicker({
				dateFormat: 'yy-mm-dd',
		        showButtonPanel: true,
		        changeMonth: true,
		        changeYear: true,
		        defaultDate: new Date(),
		    });
			$("#idBtnAdd").click(function(){
				$("#idDataTable").hide();
				$("#idForm").show(255);
			});
			$("#idBtnAddFormVoyEst").click(function(){
				$("#idDataTable").hide();
				$("#idForm").hide();
				$("#idDataTableFreight").hide();

				$("#idFormFreight").show();
			});
			$("#idBtnAddModalBunkerRegional").click(function(){
				getDataBunkerRegional();
				$('#idModalBunkerRegional').modal("show");
			});
			$("#idBtnAddModalForm").click(function(){
				$("#idModalBunkerDataTable").hide();
				$("#idModalBunkerForm").show(255);
			});
		});

		function saveData()
		{
			var formData = new FormData();

			formData.append('idEdit',$('#txtIdEditEstimate').val());
			formData.append('txtBunkerTitle',$('#txtBunkerTitle').val());
			formData.append('txtDate_prepared',$('#txtDate_prepared').val());
			formData.append('txtCargo',$('#txtCargo').val());
			formData.append('txtVslType',$('#txtVslType').val());
			formData.append('txtCargoShipment',$('#txtCargoShipment').val());
			formData.append('txtLoadPort',$('#txtLoadPort').val());
			formData.append('txtDischPort',$('#txtDischPort').val());

			formData.append('txtAllowLoad',$('#txtAllowLoad').val());
			formData.append('txtAllowDisch',$('#txtAllowDisch').val());
			formData.append('txtActualLoad',$('#txtActualLoad').val());
			formData.append('txtActualDisch',$('#txtActualDisch').val());
			formData.append('txtAllowTTLP',$('#txtAllowTTLP').val());
			formData.append('txtAllowTTDP',$('#txtAllowTTDP').val());

			formData.append('txtActualTTLP',$('#txtActualTTLP').val());
			formData.append('txtActualTTDP',$('#txtActualTTDP').val());
			formData.append('txtWaitingLP',$('#txtWaitingLP').val());
			formData.append('txtWaitingDP',$('#txtWaitingDP').val());
			formData.append('slcDemmurage',$('#slcDemmurage').val());
			formData.append('txtDemmurage',$('#txtDemmurage').val());
			formData.append('slcDespatch',$('#slcDespatch').val());
			formData.append('txtDespatch',$('#txtDespatch').val());

			formData.append('txtDistanceLaden',$('#txtDistanceLaden').val());
			formData.append('txtDistanceBallast',$('#txtDistanceBallast').val());
			formData.append('txtSeaSpeedLaden',$('#txtSeaSpeedLaden').val());
			formData.append('txtSeaSpeedBallast',$('#txtSeaSpeedBallast').val());
			formData.append('txtSailingLaden',$('#txtSailingLaden').val());
			formData.append('txtSailingBallast',$('#txtSailingBallast').val());

			formData.append('txtTotalSailingDays',$('#txtTotalSailingDays').val());
			formData.append('txtAllowLPDays',$('#txtAllowLPDays').val());
			formData.append('txtAllowDPDays',$('#txtAllowDPDays').val());
			formData.append('txtAllowPortDays',$('#txtAllowPortDays').val());
			formData.append('txtActualLPDays',$('#txtActualLPDays').val());
			formData.append('txtActualDPDays',$('#txtActualDPDays').val());

			formData.append('txtActualPortDays',$('#txtActualPortDays').val());
			formData.append('txtAllowRVDays',$('#txtAllowRVDays').val());
			formData.append('txtActualRVDays',$('#txtActualRVDays').val());
			formData.append('slcBunkerPricePeriodId',$('#slcBunkerPricePeriod').val());
			formData.append('slcBunkerPricePeriodName',$("#slcBunkerPricePeriod option:selected").text());
			formData.append('txtIFOprice',$('#txtIFOprice').val());
			formData.append('txtMGOPrice',$('#txtMGOPrice').val());

			formData.append('txtDiscOnIFO',$('#txtDiscOnIFO').val());
			formData.append('txtDiscOnMFO',$('#txtDiscOnMFO').val());
			formData.append('txtIFOPriceAfterDisc',$('#txtIFOPriceAfterDisc').val());
			formData.append('txtMGOPriceAfterDisc',$('#txtMGOPriceAfterDisc').val());
			formData.append('txtIFOConsSeaLdn',$('#txtIFOConsSeaLdn').val());
			formData.append('txtIFOConsSeaBllst',$('#txtIFOConsSeaBllst').val());

			formData.append('txtMGOConsSeaLdn',$('#txtMGOConsSeaLdn').val());
			formData.append('txtMGOConsSeaBllst',$('#txtMGOConsSeaBllst').val());
			formData.append('txtIFOConsPortIdle',$('#txtIFOConsPortIdle').val());
			formData.append('txtIFOConsPortWrkg',$('#txtIFOConsPortWrkg').val());
			formData.append('txtMGOConsPortIdle',$('#txtMGOConsPortIdle').val());
			formData.append('txtMGOConsPortWrkg',$('#txtMGOConsPortWrkg').val());

			formData.append('slcPDALP',$('#slcPDALP').val());
			formData.append('txtPDALP',$('#txtPDALP').val());
			formData.append('slcPDADP',$('#slcPDADP').val());
			formData.append('txtPDADP',$('#txtPDADP').val());
			formData.append('txtNumberShips',$('#txtNumberShips').val());
			formData.append('txtMaxCgoQty',$('#txtMaxCgoQty').val());
			formData.append('txtAddCommPMT',$('#txtAddCommPMT').val());

			formData.append('slcOtherCostPMT',$('#slcOtherCostPMT').val());
			formData.append('txtOtherCostPMT',$('#txtOtherCostPMT').val());
			formData.append('slcFXRpUsd',$('#slcFXRpUsd').val());
			formData.append('txtFXRpUsd',$('#txtFXRpUsd').val());

			formData.append('txtFloatingCrane',$('#txtFloatingCrane').val());
			formData.append('txtAdditif',$('#txtAdditif').val());
			formData.append('txtOther',$('#txtOther').val());

			$("#idLoadingAdd").show();
			$("#btnSave").attr('disabled',true);
				
			$.ajax("<?php echo base_url('shipCommercial/saveDataEstimate'); ?>",{
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
		function editDataNew(id)
		{
			$("#idLoading").show();

			$.post('<?php echo base_url("shipCommercial/getEdit"); ?>',
            { id : id, action : 'editEstVoyNew' },
				function(data) 
				{
					$("#txtIdEditEstimate").val(data['data'][0].id);
					$("#txtBunkerTitle").val(data['data'][0].title);
					$('#txtDate_prepared').val(data['data'][0].date_prepared);
					$('#txtCargo').val(data['data'][0].cargo);
					$('#txtVslType').val(data['data'][0].vessel_type);
					$('#txtCargoShipment').val(data['data'][0].total_cargo);
					$('#txtLoadPort').val(data['data'][0].load_port);
					$('#txtDischPort').val(data['data'][0].discharge_port);
					$('#txtAllowLoad').val(data['data'][0].allow_load_rate);
					$('#txtAllowDisch').val(data['data'][0].allow_disch_rate);
					$('#txtActualLoad').val(data['data'][0].actual_load_rate);
					$('#txtActualDisch').val(data['data'][0].actual_disch_rate);
					$('#txtAllowTTLP').val(data['data'][0].allowable_tt_lp);
					$('#txtAllowTTDP').val(data['data'][0].allowable_tt_dp);
					$('#txtActualTTLP').val(data['data'][0].actual_tt_lp);
					$('#txtActualTTDP').val(data['data'][0].actual_tt_dp);
					$('#txtWaitingLP').val(data['data'][0].waiting_lp);
					$('#txtWaitingDP').val(data['data'][0].waiting_dp);
					$('#slcDemmurage').val(data['data'][0].demmurage_curr);
					$('#txtDemmurage').val(data['data'][0].demmurage);
					$('#slcDespatch').val(data['data'][0].despatch_curr);
					$('#txtDespatch').val(data['data'][0].despatch);
					$('#txtDistanceLaden').val(data['data'][0].distance_laden);
					$('#txtDistanceBallast').val(data['data'][0].distance_ballast);
					$('#txtSeaSpeedLaden').val(data['data'][0].sea_speed_laden);
					$('#txtSeaSpeedBallast').val(data['data'][0].sea_speed_ballast);
					$('#txtSailingLaden').val(data['data'][0].sailing_laden);
					$('#txtSailingBallast').val(data['data'][0].sailing_ballast);
					$('#txtTotalSailingDays').val(data['data'][0].sailing_total);
					$('#txtAllowLPDays').val(data['data'][0].allow_lp_day);
					$('#txtAllowDPDays').val(data['data'][0].allow_dp_day);
					$('#txtAllowPortDays').val(data['data'][0].allow_port_day);
					$('#txtActualLPDays').val(data['data'][0].actual_lp_day);
					$('#txtActualDPDays').val(data['data'][0].actual_dp_day);
					$('#txtActualPortDays').val(data['data'][0].actual_port_day);
					$('#txtAllowRVDays').val(data['data'][0].allow_rv_day);
					$('#txtActualRVDays').val(data['data'][0].actual_rv_day);
					$('#slcBunkerPricePeriod').val(data['data'][0].banker_price_periodId);
					$('#txtIFOprice').val(data['data'][0].ifo_price);
					$('#txtMGOPrice').val(data['data'][0].mgo_price);
					$('#txtDiscOnIFO').val(data['data'][0].disc_on_ifo);
					$('#txtDiscOnMFO').val(data['data'][0].disc_on_mgo);
					$('#txtIFOPriceAfterDisc').val(data['data'][0].ifo_after_disc);
					$('#txtMGOPriceAfterDisc').val(data['data'][0].mgo_after_disc);
					$('#txtIFOConsSeaLdn').val(data['data'][0].ifo_cons_seaLadden);
					$('#txtIFOConsSeaBllst').val(data['data'][0].ifo_cons_seaBallast);
					$('#txtMGOConsSeaLdn').val(data['data'][0].mgo_cons_seaLaden);
					$('#txtMGOConsSeaBllst').val(data['data'][0].mgo_cons_seaBallast);
					$('#txtIFOConsPortIdle').val(data['data'][0].ifo_cons_portIdle);
					$('#txtIFOConsPortWrkg').val(data['data'][0].ifo_cons_portWorking);
					$('#txtMGOConsPortIdle').val(data['data'][0].mgo_cons_portIdle);
					$('#txtMGOConsPortWrkg').val(data['data'][0].mgo_cons_portWorking);
					$('#slcPDALP').val(data['data'][0].pda_lp_curr);
					$('#txtPDALP').val(data['data'][0].pda_lp);
					$('#slcPDADP').val(data['data'][0].pda_dp_curr);
					$('#txtPDADP').val(data['data'][0].pda_dp);
					$('#txtNumberShips').val(data['data'][0].no_of_ship);
					$('#txtMaxCgoQty').val(data['data'][0].max_cargo);
					$('#txtAddCommPMT').val(data['data'][0].addcomm_pmt);
					$('#slcOtherCostPMT').val(data['data'][0].other_cost_curr);
					$('#txtOtherCostPMT').val(data['data'][0].other_cost);
					$('#slcFXRpUsd').val(data['data'][0].fx_curr);
					$('#txtFXRpUsd').val(data['data'][0].fx);
					$('#txtFloatingCrane').val(data['data'][0].floating_crane_pmt);
					$('#txtAdditif').val(data['data'][0].additif_pmt);
					$('#txtOther').val(data['data'][0].other_pmt);
					$("#lblTtlFloatingCrane").text(data['ttlFloating']);
					$("#lblTtlAdditif").text(data['ttlAddt']);
					$("#lblTtlOther").text(data['ttlOther']);
					$("#lblTtlCostPMT").text(data['ttlCostPMT']);

					$("#idLoading").hide();
					$("#idDataTable").hide();
					$("#idForm").show(255);
				},
				"json"
			);
		}
		function viewModalVoyageEstNew(id)
		{
			$("#idLoading").show();

			$.post('<?php echo base_url("shipCommercial/viewModalVoyageEstNew"); ?>',
            { id : id },
				function(data)
				{
					$('#lblModalDatePrepared').text(data['datePrepared']);
					$("#lblModalBunkerTitle").text(data['data'][0].title);
					$('#lblModalCargo').text(data['data'][0].cargo);
					$('#lblModalVesselType').text(data['data'][0].vessel_type);
					$('#lblModalTotalCargo').text(parseFloat(data['data'][0].total_cargo).toLocaleString()+" MT/Voy");
					$('#lblModalLoadPortLP').text(data['data'][0].load_port);
					$('#lblModalDischPortDP').text(data['data'][0].discharge_port);
					$('#lblModalAllowLoadRate').text(parseFloat(data['data'][0].allow_load_rate).toLocaleString()+" MT/Day");
					$('#lblModalAllowDischRate').text(parseFloat(data['data'][0].allow_disch_rate).toLocaleString()+" MT/Day");
					$('#lblModalActualLoadRate').text(parseFloat(data['data'][0].actual_load_rate).toLocaleString()+" MT/Day");
					$('#lblModalActualDischRate').text(parseFloat(data['data'][0].actual_disch_rate).toLocaleString()+" MT/Day");
					$('#lblModalAllowTTatLP').text(parseFloat(data['data'][0].allowable_tt_lp).toLocaleString()+" Day");
					$('#lblModalAllowTTatDP').text(parseFloat(data['data'][0].allowable_tt_dp).toLocaleString()+" Day");
					$('#lblModalActualTTatLP').text(parseFloat(data['data'][0].actual_tt_lp).toLocaleString()+" Day");
					$('#lblModalActualTTatDP').text(parseFloat(data['data'][0].actual_tt_dp).toLocaleString()+" Day");
					$('#lblModalWaitatLP').text(parseFloat(data['data'][0].waiting_lp).toLocaleString()+" Day");
					$('#lblModalWaitatDP').text(parseFloat(data['data'][0].waiting_dp).toLocaleString()+" Day");
					$('#lblModalDemmurage').text(data['data'][0].demmurage_curr+" "+parseFloat(data['data'][0].demmurage).toLocaleString());
					$('#lblModalDespatch').text(data['data'][0].despatch_curr+" "+parseFloat(data['data'][0].despatch).toLocaleString());
					$('#lblModalDistanceLaden').text(parseFloat(data['data'][0].distance_laden).toLocaleString()+" nm");
					$('#lblModalDistanceBallast').text(parseFloat(data['data'][0].distance_ballast).toLocaleString()+" nm");
					$('#lblModalSeaSpeedLaden').text(parseFloat(data['data'][0].sea_speed_laden).toLocaleString()+" Knots/Hr");
					$('#lblModalSeaSpeedBallast').text(parseFloat(data['data'][0].sea_speed_ballast).toLocaleString()+" Knots/Hr");
					$('#lblModalSailingLaden').text(parseFloat(data['data'][0].sailing_laden).toLocaleString()+" Days/Voy");
					$('#lblModalSailingBallast').text(parseFloat(data['data'][0].sailing_ballast).toLocaleString()+" Days/Voy");
					$('#lblModalTotalSailing').text(parseFloat(data['data'][0].sailing_total).toLocaleString()+" Days/Voy");
					$('#lblModalAllowLP').text(parseFloat(data['data'][0].allow_lp_day).toLocaleString()+" Days/Voy");
					$('#lblModalAllowDP').text(parseFloat(data['data'][0].allow_dp_day).toLocaleString()+" Days/Voy");
					$('#lblModalAllowPort').text(parseFloat(data['data'][0].allow_port_day).toLocaleString()+" Days/Voy");
					$('#lblModalActualLP').text(parseFloat(data['data'][0].actual_lp_day).toLocaleString()+" Days/Voy");
					$('#lblModalActualDP').text(parseFloat(data['data'][0].actual_dp_day).toLocaleString()+" Days/Voy");
					$('#lblModalActualPort').text(parseFloat(data['data'][0].actual_port_day).toLocaleString()+" Days/Voy");
					$('#lblModalAllowRV').text(parseFloat(data['data'][0].allow_rv_day).toLocaleString()+" Days/Voy");
					$('#lblModalActualRV').text(parseFloat(data['data'][0].actual_rv_day).toLocaleString()+" Days/Voy");
					$('#lblModalBunkerPricePeriod').text(data['data'][0].banker_price_period);
					$('#lblModalIFOPrice').text(parseFloat(data['data'][0].ifo_price).toLocaleString()+" /ltr");
					$('#lblModalMGOPrice').text(parseFloat(data['data'][0].mgo_price).toLocaleString()+" /ltr");
					$('#lblModalDiscIFO').text(parseFloat(data['data'][0].disc_on_ifo).toLocaleString()+" %");
					$('#lblModalDiscMGO').text(parseFloat(data['data'][0].disc_on_mgo).toLocaleString()+" %");
					$('#lblModalIFOAfterDisc').text(parseFloat(data['data'][0].ifo_after_disc).toLocaleString()+" /ltr");
					$('#lblModalMGOAfterDisc').text(parseFloat(data['data'][0].mgo_after_disc).toLocaleString()+" /ltr");
					$('#lblModalIFOConsAtSeaLdn').text(parseFloat(data['data'][0].ifo_cons_seaLadden).toLocaleString()+" MT/Day");
					$('#lblModalIFOConsAtSeaBllst').text(parseFloat(data['data'][0].ifo_cons_seaBallast).toLocaleString()+" MT/Day");
					$('#lblModalMGOConsAtSeaLdn').text(parseFloat(data['data'][0].mgo_cons_seaLaden).toLocaleString()+" MT/Day");
					$('#lblModalMGOConsAtSeaBllst').text(parseFloat(data['data'][0].mgo_cons_seaBallast).toLocaleString()+" MT/Day");
					$('#lblModalIFOConsAtPortIdle').text(parseFloat(data['data'][0].ifo_cons_portIdle).toLocaleString()+" MT/Day");
					$('#lblModalIFOConsAtPortWrkg').text(parseFloat(data['data'][0].ifo_cons_portWorking).toLocaleString()+" MT/Day");
					$('#lblModalMGOConsAtPortIdle').text(parseFloat(data['data'][0].mgo_cons_portIdle).toLocaleString()+" MT/Day");
					$('#lblModalMGOConsAtPortWrkg').text(parseFloat(data['data'][0].mgo_cons_portWorking).toLocaleString()+" MT/Day");
					$('#lblModalPDALP').text(data['data'][0].pda_lp_curr+" "+parseFloat(data['data'][0].pda_lp).toLocaleString());
					$('#lblModalPDADP').text(data['data'][0].pda_dp_curr+" "+parseFloat(data['data'][0].pda_dp).toLocaleString());
					$('#lblModalNoShip').text(parseFloat(data['data'][0].no_of_ship).toLocaleString());
					$('#lblModalMaxCgoQtyYear').text(parseFloat(data['data'][0].max_cargo).toLocaleString());
					$('#lblModalAddCommPMT').text(parseFloat(data['data'][0].addcomm_pmt).toLocaleString()+" %");
					$('#lblModalOtherCostPMT').text(data['data'][0].other_cost_curr+" "+parseFloat(data['data'][0].other_cost).toLocaleString());
					$('#lblModalFixRpUsd').text(data['data'][0].fx_curr+" "+parseFloat(data['data'][0].fx).toLocaleString());
					$('#lblModalIFOConsAtSeaLdnTitle').html("&nbsp("+parseFloat(data['data'][0].floating_crane_pmt).toLocaleString()+")");
					$('#lblModalAdditifPMTTitle').html("&nbsp("+parseFloat(data['data'][0].additif_pmt).toLocaleString()+")");
					$('#lblModalOtherPMTTitle').html("&nbsp("+parseFloat(data['data'][0].other_pmt).toLocaleString()+")");
					$('#lblModalFloatingCranePMT').text(data['ttlFloating']);
					$('#lblModalAdditifPMT').text(data['ttlAddt']);
					$('#lblModalOtherPMT').text(data['ttlOther']);
					$('#lblModalTotalPMT').text(data['ttlCostPMT']);

					$("#btnModalExportPDF").attr('onclick',"exportData('"+id+"','pdf')");

					$("#idLoading").hide();
					$('#idModalShowVoyEstNew').modal("show");
				},
				"json"
			);			
		}
		function saveDataModalBunker()
		{
			var formData = new FormData();

			formData.append('idEdit',$('#txtIdEditModalBunker').val());
			formData.append('slcModalBunkerPeriodMonthId',$('#slcModalBunkerPeriodMonth').val());
			formData.append('slcModalBunkerPeriodMonthName',$("#slcModalBunkerPeriodMonth option:selected").text());
			formData.append('txtModalBunkerPeriod',$('#txtModalBunkerPeriod').val());
			formData.append('slModalBunkerCurrMFO',$('#slModalBunkerCurrMFO').val());
			formData.append('txtModalBunkerPriceMFO',$('#txtModalBunkerPriceMFO').val());
			formData.append('slcModalBunkerCurrMGO',$('#slcModalBunkerCurrMGO').val());
			formData.append('txtModalBunkerPriceMGO',$('#txtModalBunkerPriceMGO').val());
			formData.append('txtModalBunkerRemark',$('#txtModalBunkerRemark').val());

			$("#idLoadingModalBunker").show();
				
			$.ajax("<?php echo base_url('shipCommercial/saveDataModalBunker'); ?>",{
		    	method: "POST",
		        data: formData,
		        cache: false,
		        contentType: false,
		        processData: false,
		        success: function(response){
		        	alert(response);
		            getDataBunkerRegional();
		        }
		 	});
		}
		function saveDataReportVoyEst()
		{
			var formData = new FormData();
			var idVoyEst = $('#txtIdEditVoyEst').val();

			var idEdit = "";
			var valIdEdit = $("input[id^='txtIdEditReportVoyage_']").map(function(){return $(this).val();}).get();
			for (var l = 0; l < valIdEdit.length; l++)
			{
				if(valIdEdit[l] == "")
				{
					valIdEdit[l] = "^";
				}
				if(idEdit == ""){ idEdit = valIdEdit[l]; }else{ idEdit += "*"+valIdEdit[l]; }
			}

			var earIDR = "";
			var valEarIDR = $("td[id^='erningIDR_']").map(function(){return $(this).text();}).get();
			for (var l = 0; l < valEarIDR.length; l++)
			{
				if(valEarIDR[l] == "")
				{
					valEarIDR[l] = "^";
				}
				if(earIDR == ""){ earIDR = valEarIDR[l]; }else{ earIDR += "*"+valEarIDR[l]; }
			}

			var earUSD = "";
			var valEarUSD = $("td[id^='erningUSD_']").map(function(){return $(this).text();}).get();
			for (var l = 0; l < valEarUSD.length; l++)
			{
				if(valEarUSD[l] == "")
				{
					valEarUSD[l] = "^";
				}
				if(earUSD == ""){ earUSD = valEarUSD[l]; }else{ earUSD += "*"+valEarUSD[l]; }
			}

			var freighBaseInput = "";
			var valFreighBaseInput = $("input[id^='txtFreight_']").map(function(){return $(this).val();}).get();
			for (var l = 0; l < valFreighBaseInput.length; l++)
			{
				if(valFreighBaseInput[l] == "")
				{
					valFreighBaseInput[l] = "^";
				}
				if(freighBaseInput == ""){ freighBaseInput = valFreighBaseInput[l]; }else{ freighBaseInput += "*"+valFreighBaseInput[l]; }
			}

			var tceUSD = "";
			var valTceUSD = $("td[id^='tce_']").map(function(){return $(this).text();}).get();
			for (var l = 0; l < valTceUSD.length; l++)
			{
				if(valTceUSD[l] == "")
				{
					valTceUSD[l] = "^";
				}
				if(tceUSD == ""){ tceUSD = valTceUSD[l]; }else{ tceUSD += "*"+valTceUSD[l]; }
			}

			var freighBaseUSD = "";
			var valFreighBaseUSD = $("td[id^='freightBaseConv_']").map(function(){return $(this).text();}).get();
			for (var l = 0; l < valFreighBaseUSD.length; l++)
			{
				if(valFreighBaseUSD[l] == "")
				{
					valFreighBaseUSD[l] = "^";
				}
				if(freighBaseUSD == ""){ freighBaseUSD = valFreighBaseUSD[l]; }else{ freighBaseUSD += "*"+valFreighBaseUSD[l]; }
			}

			var grossProfit = "";
			var valGrossProfit = $("td[id^='grossProfit_']").map(function(){return $(this).text();}).get();
			for (var l = 0; l < valGrossProfit.length; l++)
			{
				if(valGrossProfit[l] == "")
				{
					valGrossProfit[l] = "^";
				}
				if(grossProfit == ""){ grossProfit = valGrossProfit[l]; }else{ grossProfit += "*"+valGrossProfit[l]; }
			}

			var addComm = "";
			var valAddComm = $("td[id^='addComm_']").map(function(){return $(this).text();}).get();
			for (var l = 0; l < valAddComm.length; l++)
			{
				if(valAddComm[l] == "")
				{
					valAddComm[l] = "^";
				}
				if(addComm == ""){ addComm = valAddComm[l]; }else{ addComm += "*"+valAddComm[l]; }
			}

			formData.append('idVoyEst',idVoyEst);
			formData.append('idEdit',idEdit);
			formData.append('earIDR',earIDR);
			formData.append('earUSD',earUSD);
			formData.append('freighBaseInput',freighBaseInput);
			formData.append('tceUSD',tceUSD);
			formData.append('freighBaseUSD',freighBaseUSD);
			formData.append('grossProfit',grossProfit);
			formData.append('addComm',addComm);

			$("#idLoading").show();
				
			$.ajax("<?php echo base_url('shipCommercial/saveDataReportVoyEst'); ?>",{
		    	method: "POST",
		        data: formData,
		        cache: false,
		        contentType: false,
		        processData: false,
		        success: function(response){
		        	alert(response);
		        	$("#idLoading").hide();
		            getDataFreight(idVoyEst);
		        }
		 	});
		}
		function editDataReportVoyEstEdit(id)
		{
			$("#idLoading").show();

			$.post('<?php echo base_url("shipCommercial/getEdit"); ?>',
			{ id : id, action : "editDataReportVoyEstEdit" },
				function(data) 
				{
					$("#txtIdEditReportVoyage_1").val(data['data'][0].id);
					$("#txtFreight_1").val(data['data'][0].freightbase_idr_ton);
					hitungEstVoyReport('1');

					$("#idDataTable").hide();
					$("#idForm").hide();
					$("#idDataTableFreight").hide();
					$("#idFormFreight").show();
					$("#idLoading").hide();	
				},
				"json"
			);
		}
		function delDataReportVoyEst(id,idVoyEst)
		{
			var cfm = confirm("Yakin Hapus..??");
			if(cfm)
			{
				$.post('<?php echo base_url("shipCommercial/delDataReportVoyEst"); ?>',
				{ id : id },
				function(data) 
				{
					alert(data);
					getDataFreight(idVoyEst);
				},
				"json"
				);
			}			
		}
		function searchDataBunkerRegional()
		{
			var txtSearch = $("#txtSearchModalBunker").val();

			if(txtSearch == "")
			{
				alert("Text Search Empty..!!");
				return false;
			}

			$("#idLoadingModalBunker").show();
			$.post('<?php echo base_url("shipCommercial/getDataModalBunkerRegion/search"); ?>',
	    	{ txtSearch : txtSearch },
				function(data) 
				{
					clearFormModalBunker();

					$("#idBodyModalBunker").empty();
					$("#idBodyModalBunker").append(data.trNya);

					$("#idLoadingModalBunker").hide();
				},
				"json"
			);
		}
		function getDataBunkerRegional()
		{
			$("#idLoadingModalBunker").show();
			$.post('<?php echo base_url("shipCommercial/getDataModalBunkerRegion"); ?>',
	    	{ },
				function(data) 
				{
					clearFormModalBunker();

					$("#idBodyModalBunker").empty();
					$("#idBodyModalBunker").append(data.trNya);

					$("#idModalBunkerDataTable").show();
					$("#idModalBunkerForm").hide();

					$("#idLoadingModalBunker").hide();
				},
				"json"
			);
		}
		function editDataModalBunker(id)
		{
			$("#idLoadingModalBunker").show();

			$.post('<?php echo base_url("shipCommercial/getEdit"); ?>',
            { id : id, action : 'editDataModalBunker' },
				function(data) 
				{
					$("#txtIdEditModalBunker").val(data['data'][0].id);
					$('#slcModalBunkerPeriodMonth').val(data['data'][0].periode_month);
					$('#txtModalBunkerPeriod').val(data['data'][0].periode);
					$('#slModalBunkerCurrMFO').val(data['data'][0].curr_mfo);
					$('#txtModalBunkerPriceMFO').val(data['data'][0].price_mfo);
					$('#slcModalBunkerCurrMGO').val(data['data'][0].curr_mgo);
					$('#txtModalBunkerPriceMGO').val(data['data'][0].price_mgo);
					$('#txtModalBunkerRemark').val(data['data'][0].remark);

					$("#idLoadingModalBunker").hide();
					$("#idModalBunkerDataTable").hide();
					$("#idModalBunkerForm").show(255);
				},
				"json"
			);
		}
		function exportData(id,type)
		{
			if(type == 'pdf')
			{
				window.open('<?php echo base_url('shipCommercial/exportDataPDF');?>'+'/'+id, '_blank');
			}
		}
		function delDataNew(id)
		{
			var cfm = confirm("Yakin Hapus..??");
			if(cfm)
			{
				$.post('<?php echo base_url("shipCommercial/delData"); ?>',
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
		function delDataModalBunker(id)
		{
			var cfm = confirm("Yakin Hapus..??");
			if(cfm)
			{
				$.post('<?php echo base_url("shipCommercial/delDataModalBunker"); ?>',
				{ id : id },
				function(data) 
				{
					alert(data);
					getDataBunkerRegional();
				},
				"json"
				);
			}			
		}
		function getDataFreight(id)
		{
			$("#idLoading").show();

			$.post('<?php echo base_url("shipCommercial/getFreightVoyEst"); ?>',
			{ id : id },
				function(data) 
				{
					$("#titleTblAnalis").text(data.titleNya);
					$('#txtIdEditVoyEst').val(id);
					$("#totalRowTableAnalisVoyage").val(data.totalRow);

					$("#txtTotalCargoForm").val(data.totalCargo);
					$("#txtTotalFXForm").val(data.totalFX);
					$("#txtTotalAddCommForm").val(data.totalAddComm);
					$("#txtTotalActRVForm").val(data.totalActRV);
					$("#txtTotalOptCost").val(data.totalOptCost);

					$("#idBodyDataTableFreight").empty();
					$("#idBodyDataTableFreight").append(data.trNya);

					$("#idBodyAnalisaVoy").empty();
					$("#idBodyAnalisaVoy").append(data.trAnalisNya);

					if(data.trNya == "")
					{
						$("#idBtnAddFormVoyEst").attr('disabled',true);
					}else{
						$("#idBtnAddFormVoyEst").attr('disabled',false);
					}

					$("#idDataTable").hide();
					$("#idFormFreight").hide();

					$("#idFormPanelDataFreight").show();
					$("#idDataTableFreight").show();
					$("#idLoading").hide();	
				},
				"json"
			);
		}
		function hitungForm(type = '')
		{
			var grandTotal = 0;
			var grandTotal2 = 0;
			var grandTotal3 = 0;
			var grandTotal4 = 0;
			var total = 0;
			var total2 = 0;
			var total3 = 0;
			var total4 = 0;
			var totalAllow1 = 0;
			var totalAllow2 = 0;
			var totalAllow3 = 0;
			var totalAllow4 = 0;
			var val1 = 0;
			var val2 = 0;
			var val3 = 0;
			var val4 = 0;
			var val5 = 0;
			var val6 = 0;

			if(type == 'sailingladendays')
			{
				if($("#txtDistanceLaden").val() != "")
				{
					val1 = $("#txtDistanceLaden").val();
				}
				if($("#txtSeaSpeedLaden").val() != "")
				{
					val2 = $("#txtSeaSpeedLaden").val();
				}
				if(val1 > 0 && val2 > 0)
				{
					total = (parseFloat(val1)/parseFloat(val2)/24).toFixed(2);
				}

				$("#txtSailingLaden").val(total);

				if($("#txtSailingLaden").val() != "")
				{
					val3 = $("#txtSailingLaden").val();
				}
				if($("#txtSailingBallast").val() != "")
				{
					val4 = $("#txtSailingBallast").val();
				}

				grandTotal = parseFloat(val3) + parseFloat(val4);
				$("#txtTotalSailingDays").val(grandTotal.toFixed(2));
			}
			else if(type == 'sailingBallastdays')
			{
				if($("#txtDistanceBallast").val() != "")
				{
					val1 = $("#txtDistanceBallast").val();
				}
				if($("#txtSeaSpeedBallast").val() != "")
				{
					val2 = $("#txtSeaSpeedBallast").val();
				}
				if(val1 > 0 && val2 > 0)
				{
					total = (parseFloat(val1)/parseFloat(val2)/24).toFixed(2);
				}

				$("#txtSailingBallast").val(total);

				if($("#txtSailingLaden").val() != "")
				{
					val3 = $("#txtSailingLaden").val();
				}
				if($("#txtSailingBallast").val() != "")
				{
					val4 = $("#txtSailingBallast").val();
				}

				grandTotal = parseFloat(val3) + parseFloat(val4);
				$("#txtTotalSailingDays").val(grandTotal.toFixed(2));
			}
			else if(type == 'allowLPDays')
			{
				if($("#txtCargoShipment").val() != "")
				{
					val1 = $("#txtCargoShipment").val();
				}
				if($("#txtAllowLoad").val() != "")
				{
					val2 = $("#txtAllowLoad").val();
				}
				if($("#txtAllowTTLP").val() != "")
				{
					val3 = $("#txtAllowTTLP").val();
				}

				if(val1 > 0 && val2 > 0)
				{
					total = ((parseFloat(val1)/parseFloat(val2))+parseFloat(val3)).toFixed(2);
				}

				$("#txtAllowLPDays").val(total);

				if($("#txtAllowLPDays").val() != "")
				{
					val3 = $("#txtAllowLPDays").val();
				}
				if($("#txtAllowDPDays").val() != "")
				{
					val4 = $("#txtAllowDPDays").val();
				}

				grandTotal = parseFloat(val3) + parseFloat(val4);
				$("#txtAllowPortDays").val(grandTotal.toFixed(2));
			}
			else if(type == 'allowDPDays')
			{
				if($("#txtCargoShipment").val() != "")
				{
					val1 = $("#txtCargoShipment").val();
				}
				if($("#txtAllowDisch").val() != "")
				{
					val2 = $("#txtAllowDisch").val();
				}
				if($("#txtAllowTTDP").val() != "")
				{
					val3 = $("#txtAllowTTDP").val();
				}

				if(val1 > 0 && val2 > 0)
				{
					total = ((parseFloat(val1)/parseFloat(val2))+parseFloat(val3)).toFixed(2);
				}

				$("#txtAllowDPDays").val(total);

				if($("#txtAllowLPDays").val() != "")
				{
					val3 = $("#txtAllowLPDays").val();
				}
				if($("#txtAllowDPDays").val() != "")
				{
					val4 = $("#txtAllowDPDays").val();
				}

				grandTotal = parseFloat(val3) + parseFloat(val4);
				$("#txtAllowPortDays").val(grandTotal.toFixed(2));
			}
			else if(type == 'actualLPDays')
			{
				if($("#txtCargoShipment").val() != "")
				{
					val1 = $("#txtCargoShipment").val();
				}
				if($("#txtActualLoad").val() != "")
				{
					val2 = $("#txtActualLoad").val();
				}
				if($("#txtActualTTLP").val() != "")
				{
					val3 = $("#txtActualTTLP").val();
				}
				if($("#txtWaitingLP").val() != "")
				{
					val4 = $("#txtWaitingLP").val();
				}

				if(val1 > 0 && val2 > 0)
				{
					total = (parseFloat(val1)/parseFloat(val2))+parseFloat(val3)+parseFloat(val4);
					total = parseFloatNya(total,2);
				}

				$("#txtActualLPDays").val(total);

				if($("#txtActualDPDays").val() != "")
				{
					val5 = $("#txtActualDPDays").val();
				}
				if($("#txtActualLPDays").val() != "")
				{
					val6 = $("#txtActualLPDays").val();
				}

				grandTotal = parseFloat(val5) + parseFloat(val6);
				$("#txtActualPortDays").val(grandTotal.toFixed(2));
			}
			else if(type == 'actualDPDays')
			{
				if($("#txtCargoShipment").val() != "")
				{
					val1 = $("#txtCargoShipment").val();
				}
				if($("#txtActualDisch").val() != "")
				{
					val2 = $("#txtActualDisch").val();
				}
				if($("#txtActualTTDP").val() != "")
				{
					val3 = $("#txtActualTTDP").val();
				}
				if($("#txtWaitingDP").val() != "")
				{
					val4 = $("#txtWaitingDP").val();
				}

				if(val1 > 0 && val2 > 0)
				{
					total = (parseFloat(val1)/parseFloat(val2))+parseFloat(val3)+parseFloat(val4);
					total = parseFloatNya(total,2);
				}

				$("#txtActualDPDays").val(total);

				if($("#txtActualDPDays").val() != "")
				{
					val5 = $("#txtActualDPDays").val();
				}
				if($("#txtActualLPDays").val() != "")
				{
					val6 = $("#txtActualLPDays").val();
				}

				grandTotal = parseFloat(val5) + parseFloat(val6);
				$("#txtActualPortDays").val(grandTotal.toFixed(2));
			}

			if($("#txtTotalSailingDays").val() != "")
			{
				totalAllow1 = $("#txtTotalSailingDays").val();
			}
			if($("#txtAllowLPDays").val() != "")
			{
				totalAllow2 = $("#txtAllowLPDays").val();
			}
			if($("#txtAllowDPDays").val() != "")
			{
				totalAllow3 = $("#txtAllowDPDays").val();
			}
			grandTotal2 = parseFloat(totalAllow1) + parseFloat(totalAllow2) + parseFloat(totalAllow3);
			$("#txtAllowRVDays").val(grandTotal2.toFixed(2));

			if($("#txtTotalSailingDays").val() != "")
			{
				total2 = $("#txtTotalSailingDays").val();
			}
			if($("#txtActualPortDays").val() != "")
			{
				total3 = $("#txtActualPortDays").val();
			}

			grandTotal3 = (parseFloat(total2) + parseFloat(total3)).toFixed(2);

			$("#txtActualRVDays").val(grandTotal3);
		}
		function getBunkerPrice(id)
		{
			if(id == '')
			{
				$("#txtIFOPriceAfterDisc").val('0');
				$("#txtMGOPriceAfterDisc").val('0');
				$("#txtIFOprice").val('0');
				$("#txtMGOPrice").val('0');
			}else{
				$.post('<?php echo base_url("shipCommercial/getPriceBunkerById"); ?>',
	            { id : id },
					function(data) 
					{
						$("#txtIFOprice").val(data.mfo);
						$("#txtMGOPrice").val(data.mgo);
						hitungDiscBunker();
					},
					"json"
				);
			}
		}
		function saveTableAnalisVoyage()
		{
			$("#idLoading").show();
			var formData = new FormData();
			var ttlRowTblAnalis = $("#totalRowTableAnalisVoyage").val();
			var idEdit = $("#txtIdEditTblAnalisVoy").val();
			var idEstVoy = $("#txtIdEstVoyage").val();
			var nameNya = "";
			var valNya = "";
			var tempVar = "";

			if(ttlRowTblAnalis == "")
			{
				ttlRowTblAnalis = 0;
			}

			formData.append('idEdit',idEdit);
			formData.append('idEstVoy',idEstVoy);

			for (var lan = 1; lan < parseFloat(ttlRowTblAnalis); lan++)
			{
				nameNya = $('#analisName_'+lan).text();
				valNya = $('#analisValue_'+lan).text();

				if(lan >= 5 && lan <= 13)
				{
					valNya = $('#txtTglHid_'+lan).val();
				}

				if(lan >= 47 && lan <= 48)
				{
					valNya = $('#txtFormInput_'+lan).val();
				}

				if(lan >= 58 && lan <= 59)
				{
					valNya = $('#txtFormInput_'+lan).val();
				}

				if(lan == 60)
				{
					valNya = $('#lblInput_'+lan).text();
				}

				if(lan >= 61 && lan <= 65)
				{
					valNya = $('#txtFormInput_'+lan).val();
				}

				if(nameNya == "")
				{
					nameNya = "^";
				}

				if(valNya == "")
				{
					valNya = "^";
				}

				if(tempVar == "")
				{
					tempVar = nameNya+"*"+valNya;
				}else{
					tempVar += "<=>"+nameNya+"*"+valNya;
				}
			}

			formData.append('tempVar',tempVar);
			
        	$.ajax("<?php echo base_url('shipCommercial/saveTableAnalisVoyage'); ?>",{
		        method: "POST",
		        data: formData,
		        cache: false,
		        contentType: false,
		        processData: false,
		    	success: function(response){
		            alert(response);
		            getDataFreight(idEstVoy);
		    	}
		  	});
		}
		function hitungEstVoyReport(rowId = '')
		{
			var ttlEarIDR = 0;
			var ttlEarUSD = 0;
			var expectTCE = 0;
			var freightBase = 0;
			var grossProfit = 0;
			var addComm = 0;

			var totalCargoForm = $('#txtTotalCargoForm').val();
			var totalFXForm = $('#txtTotalFXForm').val();
			var totalAddCommForm = $('#txtTotalAddCommForm').val();
			var totalActRVForm = $('#txtTotalActRVForm').val();
			var totalOptCost = $('#txtTotalOptCost').val();
			var varInput = $("#txtFreight_"+rowId).val();

			totalAddCommForm = parseFloat(totalAddCommForm) / 100;

			ttlEarIDR = parseFloat(totalCargoForm) * parseFloat(varInput);
			freightBase = parseFloat(varInput) / parseFloat(totalFXForm);
			ttlEarUSD = parseFloat(totalCargoForm) * parseFloat(freightBase);
			addComm = parseFloat(ttlEarIDR) * parseFloat(totalAddCommForm);

			expectTCE = ((parseFloat(varInput) * parseFloat(totalCargoForm)) - (parseFloat(ttlEarIDR) * parseFloat(totalAddCommForm)) - parseFloat(totalOptCost)) / parseFloat(totalActRVForm) / parseFloat(totalFXForm);

			grossProfit = parseFloat(expectTCE) * parseFloat(totalActRVForm) * parseFloat(totalFXForm);


			const format2blkgKoma = new Intl.NumberFormat(undefined, {minimumFractionDigits: 2});
			const format0blkgKoma = new Intl.NumberFormat(undefined, {minimumFractionDigits: 0});

			const cttlEarIDR = ttlEarIDR.toFixed(2);
			const cttlEarUSD = ttlEarUSD.toFixed(2);
			const cfreightBase = freightBase.toFixed(2);
			const caddComm = addComm.toFixed(0);
			const cexpectTCE = expectTCE.toFixed(2);
			const cgrossProfit = grossProfit.toFixed(0);

			ttlEarIDR = format2blkgKoma.format(cttlEarIDR);
			ttlEarUSD = format2blkgKoma.format(cttlEarUSD);
			freightBase = format2blkgKoma.format(cfreightBase);
			addComm = format0blkgKoma.format(caddComm);
			expectTCE = format2blkgKoma.format(cexpectTCE);
			grossProfit = format0blkgKoma.format(cgrossProfit);

			$("#erningIDR_"+rowId).text(ttlEarIDR);
			$("#erningUSD_"+rowId).text(ttlEarUSD);
			$("#tce_"+rowId).text(expectTCE);
			$("#freightBaseConv_"+rowId).text(freightBase);
			$("#grossProfit_"+rowId).text(grossProfit);
			$("#addComm_"+rowId).text(addComm);
		}
		function hitungDiscBunker()
		{
			var total1 = 0;
			var total2 = 0;
			var disc1 = 0;
			var disc2 = 0;
			var afterDisc1 = 0;
			var afterDisc2 = 0;

			if($('#txtIFOprice').val() != '')
			{
				total1 = $('#txtIFOprice').val();
			}
			if($('#txtMGOPrice').val() != '')
			{
				total2 = $('#txtMGOPrice').val();
			}
			if($('#txtDiscOnIFO').val() != '')
			{
				disc1 = $('#txtDiscOnIFO').val();
				disc1 = 1 - (disc1 / 100);
			}
			if($('#txtDiscOnMFO').val() != '')
			{
				disc2 = $('#txtDiscOnMFO').val();
				disc2 = 1 - (disc2 / 100);
			}

			afterDisc1 = total1 * disc1;
			afterDisc2 = total2 * disc2;

			$('#txtIFOPriceAfterDisc').val(afterDisc1.toFixed(2));
			$('#txtMGOPriceAfterDisc').val(afterDisc2.toFixed(2));
		}
		function hitungOtherCost(type = '')
		{
			var totalCargo = 0;
			var total = 0;
			var total2 = 0;
			var total3 = 0;
			var grandTotal = 0;

			if($("#txtCargoShipment").val() != '')
			{
				totalCargo = $("#txtCargoShipment").val();				
			}

			if(type == 'floating')
			{
				total = $("#txtFloatingCrane").val();
				total = totalCargo * total;
				$("#lblTtlFloatingCrane").text(total.toLocaleString());
			}
			else if(type == 'additif')
			{
				total = $("#txtAdditif").val();
				total = totalCargo * total;
				$("#lblTtlAdditif").text(total.toLocaleString());
			}
			else if(type == 'other')
			{
				total = $("#txtOther").val();
				total = totalCargo * total;
				$("#lblTtlOther").text(total.toLocaleString());
			}

			total = 0;

			if($('#txtFloatingCrane').val() != '')
			{
				total = $("#txtFloatingCrane").val();
				total = totalCargo * total;
			}
			if($('#txtAdditif').val() != '')
			{
				total2 = $("#txtAdditif").val();
				total2 = totalCargo * total2;
			}
			if($('#txtOther').val() != '')
			{
				total3 = $("#txtOther").val();
				total3 = totalCargo * total3;
			}
			
			grandTotal = parseFloat(total) + parseFloat(total2) +parseFloat(total3);

			$("#lblTtlCostPMT").text(grandTotal.toLocaleString());
		}
		function hitungTblAnalisVoy(type = '')
		{
			var total = 0;
			var totalFresh = 0;

			var txtShipUnloader = $('#txtFormInput_47').val();
			var txtCleaningHolds = $('#txtFormInput_48').val();
			var txtFwInPort = $('#txtFormInput_58').val();
			var txtFwAtSea = $('#txtFormInput_59').val();
			var txtPremi = $('#txtFormInput_61').val();
			var txtOtherCost = $('#txtFormInput_62').val();
			var txtCommission = $('#txtFormInput_63').val();
			var txtPph = $('#txtFormInput_64').val();
			var txtPerizinan = $('#txtFormInput_65').val();

			var totalDemmurage = $("#txtIdHiddenDemmurage").val();
			var totalAll = $("#txtTotalTempAnalis").val();

			if(txtShipUnloader == ''){ txtShipUnloader = "0"; }
			if(txtCleaningHolds == ''){ txtCleaningHolds = "0"; }
			if(txtFwInPort == ''){ txtFwInPort = "0"; }
			if(txtFwAtSea == ''){ txtFwAtSea = "0"; }
			if(txtPremi == ''){ txtPremi = "0"; }
			if(txtOtherCost == ''){ txtOtherCost = "0"; }
			if(txtCommission == ''){ txtCommission = "0"; }
			if(txtPph == ''){ txtPph = "0"; }
			if(txtPerizinan == ''){ txtPerizinan = "0"; }

			totalFresh = parseFloat(txtFwInPort) + parseFloat(txtFwAtSea);
			$('#lblInput_60').text(totalFresh.toLocaleString(2));

			total = (parseFloat(totalAll) + parseFloat(txtShipUnloader) + parseFloat(txtCleaningHolds) + parseFloat(txtPremi) + parseFloat(txtOtherCost) + parseFloat(txtPph) + parseFloat(txtPerizinan) + parseFloat(txtFwInPort) + parseFloat(txtFwAtSea)) - parseFloat(totalDemmurage);

			const format2blkgKoma = new Intl.NumberFormat(undefined, {minimumFractionDigits: 2});
			total = format2blkgKoma.format(total);

			$('#lblInput_66').text(total);
		}
		function clearFormModalBunker()
		{
			$("#txtIdEditModalBunker").val('');
			$("#slcModalBunkerPeriod").val('');
			$("#txtModalBunkerPeriod").val('');
			$("#slModalBunkerCurrMFO").val('idr');
			$("#txtModalBunkerPriceMFO").val('');
			$("#slcModalBunkerCurrMGO").val('idr');
			$("#txtModalBunkerPriceMGO").val('');
			$("#txtModalBunkerRemark").val('');
		}
		function addRowData()
		{
			var rowNya = $("#txtRowData").val();
			var NewRow = parseFloat(rowNya) + 1;

			$("#txtRowData").val(NewRow);

			var html = "";

			html += '<tr id="idTR_'+NewRow+'">';
				html += '<input type="hidden" id="txtIdEditReportVoyage_'+NewRow+'" value="">';
				html += '<td id="erningIDR_'+NewRow+'" style="text-align:center;"></td>';
				html += '<td id="erningUSD_'+NewRow+'" style="text-align:center;"></td>';
				html += '<td>';
					html += '<input type="text" class="form-control input-sm" id="txtFreight_'+NewRow+'" value="0" oninput="hitungEstVoyReport('+"'"+NewRow+"'"+');">';
				html += '</td>';
				html += '<td id="tce_'+NewRow+'" style="text-align:center;"></td>';
				html += '<td id="freightBaseConv_'+NewRow+'" style="text-align:center;"></td>';
				html += '<td id="grossProfit_'+NewRow+'" style="text-align:center;"></td>';
				html += '<td id="addComm_'+NewRow+'" style="text-align:center;"></td>';
				html += '<td align="center">';
					html += '<button class="btn btn-primary btn-xs" onclick=\"addRowData();\"><i class="fa fa-plus"></i></button>&nbsp&nbsp';
					html += '<button class="btn btn-danger btn-xs" onclick=\"delRowData('+NewRow+');\"><i class="fa fa-minus"></i></button>';
				html += '</td>';
			html += '</tr>';

			$("#idBodyFreightForm").append(html);
		}
		function delRowData(idRow)
		{
			$("#idTR_"+idRow).empty();
		}
		function parseFloatNya(str,val) 
		{
		    str = str.toString();
		    str = str.slice(0, (str.indexOf(".")) + val + 1); 
		    return Number(str);   
		}
		function backPage()
		{
			var idEditVoyEst = $("#txtIdEditVoyEst").val();
			getDataFreight(idEditVoyEst);
		}
		function reloadPage(uKey)
		{
			window.location = "<?php echo base_url('shipCommercial/getVoyageEstNew');?>";
		}
	</script>
</head>
<body>
	<section id="container">
		<section id="main-content">
			<section class="wrapper site-min-height" style="min-height:400px;">
				<h3>
					<i class="fa fa-angle-right"></i> Voyage Estimator
					<span style="padding-left:20px;display:none;" id="idLoading">
						<img src="<?php echo base_url('assets/img/loading.gif'); ?>" >
					</span>
				</h3>
				<div class="form-panel" id="idDataTable" style="display:;">
					<div class="row" id="idData1">
						<div id="idFormSearch">
							<dir class="col-md-1 col-xs-12">
								<button type="button" id="idBtnAdd" class="btn btn-primary btn-sm btn-block" title="Add"><i class="fa fa-plus-square" style="margin-right: 5px;"></i> Add</button>
							</dir>							
							<dir class="col-md-3 col-xs-12" id="idtxtSearch" style="display: ">
								<input placeholder="Title" type="text" class="form-control input-sm" id="txtSearchName" name="txtSearchName" value="">
							</dir>
							<dir class="col-md-2 col-xs-6" id="idSearch" style="display: ">
								<button type="button" id="idbtnSearch" class="btn btn-info btn-sm btn-block" title="Search"><i class="fa fa-search" style="margin-right: 5px;"></i> Search</button>
							</dir>
							<dir class="col-md-2 col-xs-6">
								<button type="button" id="idBtnRefresh" onclick="reloadPage();" class="btn btn-success btn-sm btn-block" title="Refresh"><i class="glyphicon glyphicon-refresh"></i> Refresh</button>
							</dir>
							<dir class="col-md-2 col-xs-12">
								<button type="button" id="idBtnAddModalBunkerRegional" class="btn btn-primary btn-sm btn-block" title="Bunker Regional"><i class="glyphicon glyphicon-eye-open" style="margin-right: 5px;"></i> Bunker Regional</button>
							</dir>
						</div>
						<div class="col-md-12 col-xs-12">
							<div class="table-responsive">
								<table class="table table-hover table-border table-bordered table-condensed table-advance">
									<thead>
										<tr style="background-color: #ba5500;color: #FFF;">
											<th style="vertical-align:middle;width:5%;text-align:center;padding:5px;">No</th>
											<th style="vertical-align:middle;width:75%;text-align:center;">Title</th>
											<th style="vertical-align:middle;text-align:center;" colspan="4">Action</th>
										</tr>
									</thead>
									<tbody id="idBody">
										<?php echo $trNya; ?>
									</tbody>
								</table>
							</div>
							<div id="idPageNya">
								<?php //echo $pageNya; ?>
							</div>
						</div>
					</div>
				</div>
				<div id="idForm" style="display:none;">
					<div class="form-panel">
						<div class="row">
							<div class="col-md-12 col-xs-12">
								<div class="form-group">
									<label for="txtBunkerTitle" style="font-size:12px;color:#0030FF;">Bunker Title :</label>
									<input type="text" class="form-control input-sm" id="txtBunkerTitle" value="" placeholder="Title">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtDate_prepared" style="font-size:12px;color:#0030FF;">Date Prepared :</label>
									<input type="text" class="form-control input-sm" id="txtDate_prepared" value="" placeholder="Date">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtCargo" style="font-size:12px;color:#0030FF;">Cargo :</label>
									<input type="text" class="form-control input-sm" id="txtCargo" value="" placeholder="Cargo">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtVslType" style="font-size:12px;color:#0030FF;">Vessel Type :</label>
									<input type="text" class="form-control input-sm" id="txtVslType" value="" placeholder="type">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtCargoShipment" style="font-size:12px;color:#0030FF;">Cargo/Shipment <span style="font-size:10px;color:#f00;">(MT/Voy)</span> :</label>
									<input type="number" class="form-control input-sm" id="txtCargoShipment" value="" placeholder="0" onkeypress="return isNumberKey(event);">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtLoadPort" style="font-size:12px;color:#0030FF;">Load Port (L/P) :</label>
									<input type="text" class="form-control input-sm" id="txtLoadPort" value="" placeholder="Loading Port">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtDischPort" style="font-size:12px;color:#0030FF;">Discharge Port (D/P) :</label>
									<input type="text" class="form-control input-sm" id="txtDischPort" value="" placeholder="Discharge Port">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtAllowLoad" style="font-size:12px;color:#0030FF;">Allow. Load Rate <span style="font-size:10px;color:#f00;">(MT/Day)</span> :</label>
									<input type="number" class="form-control input-sm" id="txtAllowLoad" value="" placeholder="0" onkeypress="return isNumberKey(event);" oninput="hitungForm('allowLPDays');">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtAllowDisch" style="font-size:12px;color:#0030FF;">Allow.&nbspDisch&nbspRate&nbsp<span style="font-size:10px;color:#f00;">(MT/Day)</span>&nbsp:</label>
									<input type="number" class="form-control input-sm" id="txtAllowDisch" value="" placeholder="0" onkeypress="return isNumberKey(event);" oninput="hitungForm('allowDPDays');">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtActualLoad" style="font-size:12px;color:#0030FF;">Actual&nbspLoad&nbspRate&nbsp<span style="font-size:10px;color:#f00;">(MT/Day)</span>&nbsp:</label>
									<input type="number" class="form-control input-sm" id="txtActualLoad" value="" placeholder="0" onkeypress="return isNumberKey(event);" oninput="hitungForm('actualLPDays');">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtActualDisch" style="font-size:12px;color:#0030FF;">Actual&nbspDisch&nbspRate&nbsp<span style="font-size:10px;color:#f00;">(MT/Day)</span>&nbsp:</label>
									<input type="number" class="form-control input-sm" id="txtActualDisch" value="" placeholder="0" onkeypress="return isNumberKey(event);" oninput="hitungForm('actualDPDays');">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtAllowTTLP" style="font-size:12px;color:#0030FF;">Allowable TT at L/P <span style="font-size:10px;color:#f00;">(Day)</span> :</label>
									<input type="number" class="form-control input-sm" id="txtAllowTTLP" value="" placeholder="0" onkeypress="return isNumberKey(event);" oninput="hitungForm('allowLPDays');">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtAllowTTDP" style="font-size:12px;color:#0030FF;">Allowable TT at D/P <span style="font-size:10px;color:#f00;">(Day)</span> :</label>
									<input type="number" class="form-control input-sm" id="txtAllowTTDP" value="" placeholder="0" onkeypress="return isNumberKey(event);" oninput="hitungForm('allowDPDays');">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtActualTTLP" style="font-size:12px;color:#0030FF;">Actual TT at L/P <span style="font-size:10px;color:#f00;">(Day)</span> :</label>
									<input type="number" class="form-control input-sm" id="txtActualTTLP" value="" placeholder="0" onkeypress="return isNumberKey(event);" oninput="hitungForm('actualLPDays');">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtActualTTDP" style="font-size:12px;color:#0030FF;">Actual TT at D/P <span style="font-size:10px;color:#f00;">(Day)</span> :</label>
									<input type="number" class="form-control input-sm" id="txtActualTTDP" value="" placeholder="0" onkeypress="return isNumberKey(event);" oninput="hitungForm('actualDPDays');">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtWaitingLP" style="font-size:12px;color:#0030FF;">Waiting at L/P <span style="font-size:10px;color:#f00;">(Day)</span> :</label>
									<input type="number" class="form-control input-sm" id="txtWaitingLP" value="" placeholder="0" onkeypress="return isNumberKey(event);" oninput="hitungForm('actualLPDays');">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtWaitingDP" style="font-size:12px;color:#0030FF;">Waiting at D/P <span style="font-size:10px;color:#f00;">(Day)</span> :</label>
									<input type="number" class="form-control input-sm" id="txtWaitingDP" value="" placeholder="0" onkeypress="return isNumberKey(event);" oninput="hitungForm('actualDPDays');">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtDemmurage" style="font-size:12px;color:#0030FF;">Demmurage :</label>
									<div class="row">
										<div class="col-md-6 col-xs-12" style="margin-bottom:5px;">
											<select class="form-control" id="slcDemmurage">
												<option value="idr">Rp</option>
												<option value="usd">USD</option>
											</select>
										</div>
										<div class="col-md-6 col-xs-12" style="padding-left:0px;">
											<input type="text" class="form-control input-sm" id="txtDemmurage" value="" placeholder="0" onkeypress="return isNumberKey(event);">
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtDespatch" style="font-size:12px;color:#0030FF;">Despatch :</label>
									<div class="row">
										<div class="col-md-6 col-xs-12" style="margin-bottom:5px;">
											<select class="form-control" id="slcDespatch">
												<option value="idr">Rp</option>
												<option value="usd">USD</option>
											</select>
										</div>
										<div class="col-md-6 col-xs-12" style="padding-left:0px;">
											<input type="text" class="form-control input-sm" id="txtDespatch" value="" placeholder="0" onkeypress="return isNumberKey(event);">
										</div>
									</div>								
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtDistanceLaden" style="font-size:12px;color:#0030FF;">Distance Laden <span style="font-size:10px;color:#f00;">(nm)</span> :</label>
									<input type="number" class="form-control input-sm" id="txtDistanceLaden" value="" placeholder="0" onkeypress="return isNumberKey(event);" oninput="hitungForm('sailingladendays');">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtDistanceBallast" style="font-size:12px;color:#0030FF;">Distance Ballast <span style="font-size:10px;color:#f00;">(nm)</span> :</label>
									<input type="number" class="form-control input-sm" id="txtDistanceBallast" value="" placeholder="0" onkeypress="return isNumberKey(event);" oninput="hitungForm('sailingBallastdays');">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtSeaSpeedLaden" style="font-size:12px;color:#0030FF;">Sea&nbspSpeed&nbspLaden&nbsp<span style="font-size:10px;color:#f00;">(Knots/Hr)</span>&nbsp:</label>
									<input type="number" class="form-control input-sm" id="txtSeaSpeedLaden" value="" placeholder="0" onkeypress="return isNumberKey(event);" oninput="hitungForm('sailingladendays');">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtSeaSpeedBallast" style="font-size:12px;color:#0030FF;">Sea&nbspSpeed&nbspBallast&nbsp<span style="font-size:10px;color:#f00;">(Knots/Hr)</span>&nbsp:</label>
									<input type="number" class="form-control input-sm" id="txtSeaSpeedBallast" value="" placeholder="0" onkeypress="return isNumberKey(event);" oninput="hitungForm('sailingBallastdays');">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtSailingLaden" style="font-size:11px;">Sailing&nbspLaden&nbspDays&nbsp<span style="font-size:10px;color:#f00;">(Days/Voy)</span>&nbsp:</label>
									<input type="text" class="form-control input-sm" id="txtSailingLaden" value="" placeholder="0" disabled="disabled">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtSailingBallast" style="font-size:11px;">Sailing&nbspBallast&nbspDays&nbsp<span style="font-size:10px;color:#f00;">(Days/Voy)</span>&nbsp:</label>
									<input type="text" class="form-control input-sm" id="txtSailingBallast" value="" placeholder="0" disabled="disabled">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtTotalSailingDays" style="font-size:11px;">Total&nbspsailing&nbspDays&nbsp<span style="font-size:10px;color:#f00;">(Days/Voy)</span>&nbsp:</label>
									<input type="text" class="form-control input-sm" id="txtTotalSailingDays" value="" placeholder="0" disabled="disabled">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtAllowLPDays" style="font-size:11px;">Allow L/P Days <span style="font-size:10px;color:#f00;">(Days/Voy)</span> :</label>
									<input type="text" class="form-control input-sm" id="txtAllowLPDays" value="" placeholder="0" disabled="disabled">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtAllowDPDays" style="font-size:11px;">Allow D/P Days <span style="font-size:10px;color:#f00;">(Days/Voy)</span> :</label>
									<input type="text" class="form-control input-sm" id="txtAllowDPDays" value="" placeholder="0" disabled="disabled">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtAllowPortDays" style="font-size:11px;">Allow Port Days <span style="font-size:10px;color:#f00;">(Days/Voy)</span> :</label>
									<input type="text" class="form-control input-sm" id="txtAllowPortDays" value="" placeholder="0" disabled="disabled">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtActualLPDays" style="font-size:11px;">Actual L/P Days <span style="font-size:10px;color:#f00;">(Days/Voy)</span> :</label>
									<input type="text" class="form-control input-sm" id="txtActualLPDays" value="" placeholder="0" disabled="disabled">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtActualDPDays" style="font-size:11px;">Actual D/P Days <span style="font-size:10px;color:#f00;">(Days/Voy)</span> :</label>
									<input type="text" class="form-control input-sm" id="txtActualDPDays" value="" placeholder="0" disabled="disabled">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtActualPortDays" style="font-size:11px;">Actual Port Days <span style="font-size:10px;color:#f00;">(Days/Voy)</span> :</label>
									<input type="text" class="form-control input-sm" id="txtActualPortDays" value="" placeholder="0" disabled="disabled">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtAllowRVDays" style="font-size:11px;">Allow RV Days <span style="font-size:10px;color:#f00;">(Days/Voy)</span> :</label>
									<input type="text" class="form-control input-sm" id="txtAllowRVDays" value="" placeholder="0" disabled="disabled">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtActualRVDays" style="font-size:11px;">Actual RV Days <span style="font-size:10px;color:#f00;">(Days/Voy)</span> :</label>
									<input type="text" class="form-control input-sm" id="txtActualRVDays" value="" placeholder="0" disabled="disabled">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="slcBunkerPricePeriod" style="font-size:11px;color:#0030FF;">Bunker&nbspPrice&nbspPeriod&nbsp<span style="font-size:10px;color:#f00;">(Days/Voy)</span>&nbsp:</label>
									<select class="form-control input-sm" id="slcBunkerPricePeriod" onchange="getBunkerPrice($(this).val());">
										<?php echo $optPeriode; ?>
									</select>
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtIFOprice" style="font-size:11px;">IFO Price/ltr <span style="font-size:10px;color:#f00;">(ltr)</span> :</label>
									<input type="text" class="form-control input-sm" id="txtIFOprice" value="" placeholder="0" disabled="disabled">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtMGOPrice" style="font-size:11px;">MGO Price/ltr <span style="font-size:10px;color:#f00;">(ltr)</span> :</label>
									<input type="text" class="form-control input-sm" id="txtMGOPrice" value="" placeholder="0" disabled="disabled">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtDiscOnIFO" style="font-size:11px;color:#0030FF;">Discount On IFO <span style="font-size:10px;color:#f00;">(%)</span> :</label>
									<input type="text" class="form-control input-sm" id="txtDiscOnIFO" value="" placeholder="0" onkeypress="return isNumberKey(event);" oninput="hitungDiscBunker();">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtDiscOnMFO" style="font-size:11px;color:#0030FF;">Discount On MGO <span style="font-size:10px;color:#f00;">(%)</span> :</label>
									<input type="text" class="form-control input-sm" id="txtDiscOnMFO" value="" placeholder="0" onkeypress="return isNumberKey(event);" oninput="hitungDiscBunker();">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtIFOPriceAfterDisc" style="font-size:11px;">IFO Price after disc. <span style="font-size:10px;color:#f00;">(ltr)</span> :</label>
									<input type="text" class="form-control input-sm" id="txtIFOPriceAfterDisc" value="" placeholder="0" disabled="disabled">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtMGOPriceAfterDisc" style="font-size:11px;">MGO Price after disc.<span style="font-size:10px;color:#f00;">(ltr)</span> :</label>
									<input type="text" class="form-control input-sm" id="txtMGOPriceAfterDisc" value="" placeholder="0" disabled="disabled">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtIFOConsSeaLdn" style="font-size:11px;color:#0030FF;">IFO&nbspcons&nbspat&nbspSea&nbspLdn&nbsp<span style="font-size:10px;color:#f00;">(MT/Day)</span>&nbsp:</label>
									<input type="text" class="form-control input-sm" id="txtIFOConsSeaLdn" value="" placeholder="0" onkeypress="return isNumberKey(event);">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtIFOConsSeaBllst" style="font-size:11px;color:#0030FF;">IFO&nbspcons&nbspat&nbspSea&nbspBllst&nbsp<span style="font-size:10px;color:#f00;">(MT/Day)</span>&nbsp:</label>
									<input type="text" class="form-control input-sm" id="txtIFOConsSeaBllst" value="" placeholder="0" onkeypress="return isNumberKey(event);">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtMGOConsSeaLdn" style="font-size:11px;color:#0030FF;">MGO&nbspcons&nbspat&nbspSea&nbspLdn&nbsp<span style="font-size:10px;color:#f00;">(MT/Day)</span>&nbsp:</label>
									<input type="text" class="form-control input-sm" id="txtMGOConsSeaLdn" value="" placeholder="0" onkeypress="return isNumberKey(event);">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtMGOConsSeaBllst" style="font-size:11px;color:#0030FF;">MGO&nbspcons&nbspat&nbspSea&nbspBllst&nbsp<span style="font-size:10px;color:#f00;">(MT/Day)</span>&nbsp:</label>
									<input type="text" class="form-control input-sm" id="txtMGOConsSeaBllst" value="" placeholder="0" onkeypress="return isNumberKey(event);">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtIFOConsPortIdle" style="font-size:11px;color:#0030FF;">IFO&nbspcons&nbspat&nbspport&nbspidle&nbsp<span style="font-size:10px;color:#f00;">(MT/Day)</span>&nbsp:</label>
									<input type="text" class="form-control input-sm" id="txtIFOConsPortIdle" value="" placeholder="0">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtIFOConsPortWrkg" style="font-size:11px;color:#0030FF;">IFO&nbspcons&nbspat&nbspport&nbspwrkg&nbsp<span style="font-size:10px;color:#f00;">(MT/Day)</span>&nbsp:</label>
									<input type="text" class="form-control input-sm" id="txtIFOConsPortWrkg" value="" placeholder="0">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtMGOConsPortIdle" style="font-size:11px;color:#0030FF;">MGO&nbspcons&nbspat&nbspport&nbspidle&nbsp<span style="font-size:10px;color:#f00;">(MT/Day)</span>&nbsp:</label>
									<input type="text" class="form-control input-sm" id="txtMGOConsPortIdle" value="" placeholder="0">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtMGOConsPortWrkg" style="font-size:11px;color:#0030FF;">MGO&nbspcons&nbspat&nbspport&nbspwrkg&nbsp<span style="font-size:10px;color:#f00;">(MT/Day)</span>&nbsp:</label>
									<input type="text" class="form-control input-sm" id="txtMGOConsPortWrkg" value="" placeholder="0">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3 col-xs-12">
								<div class="form-group">
									<label for="txtPDALP" style="font-size:12px;color:#0030FF;">PDA L/P :</label>
									<div class="row">
										<div class="col-md-5 col-xs-12" style="margin-bottom:5px;">
											<select class="form-control" id="slcPDALP">
												<option value="idr">Rp</option>
												<option value="usd">USD</option>
											</select>
										</div>
										<div class="col-md-7 col-xs-12" style="padding-left:0px;">
											<input type="text" class="form-control input-sm" id="txtPDALP" value="" placeholder="0" onkeypress="return isNumberKey(event);">
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-xs-12">
								<div class="form-group">
									<label for="txtPDADP" style="font-size:11px;color:#0030FF;">PDA D/P :</label>
									<div class="row">
										<div class="col-md-5 col-xs-12" style="margin-bottom:5px;">
											<select class="form-control" id="slcPDADP">
												<option value="idr">Rp</option>
												<option value="usd">USD</option>
											</select>
										</div>
										<div class="col-md-7 col-xs-12" style="padding-left:0px;">
											<input type="text" class="form-control input-sm" id="txtPDADP" value="" placeholder="0" onkeypress="return isNumberKey(event);">
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtNumberShips" style="font-size:11px;color:#0030FF;">Number of Ship(s) :</label>
									<input type="text" class="form-control input-sm" id="txtNumberShips" value="" placeholder="0">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtMaxCgoQty" style="font-size:11px;color:#0030FF;">Max Cgo Qty/Year :</label>
									<input type="text" class="form-control input-sm" id="txtMaxCgoQty" value="" placeholder="0">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtAddCommPMT" style="font-size:11px;color:#0030FF;">Addcomm PMT/Voy <span style="font-size:10px;color:#f00;">(%)</span> :</label>
									<input type="text" class="form-control input-sm" id="txtAddCommPMT" value="" placeholder="0">
								</div>
							</div>							
						</div>
						<div class="row">
							<div class="col-md-3 col-xs-12">
								<div class="form-group">
									<label for="txtOtherCostPMT" style="font-size:12px;color:#0030FF;">Other Cost PMT/Voy :</label>
									<div class="row">
										<div class="col-md-5 col-xs-12" style="margin-bottom:5px;">
											<select class="form-control" id="slcOtherCostPMT">
												<option value="idr">Rp</option>
												<option value="usd">USD</option>
											</select>
										</div>
										<div class="col-md-7 col-xs-12" style="padding-left:0px;">
											<input type="text" class="form-control input-sm" id="txtOtherCostPMT" value="" placeholder="0" onkeypress="return isNumberKey(event);">
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-xs-12">
								<div class="form-group">
									<label for="slcFXRpUsd" style="font-size:12px;color:#0030FF;">Fx Rp/ USD 1 :</label>
									<div class="row">
										<div class="col-md-5 col-xs-12" style="margin-bottom:5px;">
											<select class="form-control" id="slcFXRpUsd">
												<option value="idr">Rp</option>
												<option value="usd">USD</option>
											</select>
										</div>
										<div class="col-md-7 col-xs-12" style="padding-left:0px;">
											<input type="text" class="form-control input-sm" id="txtFXRpUsd" value="" placeholder="0" onkeypress="return isNumberKey(event);">
										</div>
									</div>
								</div>
							</div>
						</div>						
					</div>					
					<div class="form-panel">
						<legend style="font-size:12px;text-align:center;">Other Cost PMT/Voy</legend>
						<div class="row">
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtFloatingCrane" style="font-size:11px;color:#0030FF;">Floating Crane :</label>
									<input type="text" class="form-control input-sm" id="txtFloatingCrane" value="" placeholder="0" onkeypress="return isNumberKey(event);" oninput="hitungOtherCost('floating');">
									<label style="margin-top:5px;margin-left:5px;" id="lblFloatingCrane"></label>
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtAdditif" style="font-size:11px;color:#0030FF;">Additif :</label>
									<input type="text" class="form-control input-sm" id="txtAdditif" value="" placeholder="0" onkeypress="return isNumberKey(event);" oninput="hitungOtherCost('additif');">
									<label style="margin-top:5px;margin-left:5px;" id="lblAdditif"></label>
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtOther" style="font-size:11px;color:#0030FF;">Other :</label>
									<input type="text" class="form-control input-sm" id="txtOther" value="" placeholder="0" onkeypress="return isNumberKey(event);" oninput="hitungOtherCost('other');">
									<label style="margin-top:5px;margin-left:5px;" id="lblOther"></label>
								</div>
							</div>
							<div class="col-md-3 col-xs-12">
								<div class="row">
									<div class="col-md-12 col-xs-12">
										<label style="font-size:11px;color:#0030FF;">Total Floating Crane :</label>
										<label style="font-size:11px;float:right;" id="lblTtlFloatingCrane"></label>
									</div>
									<div class="col-md-12 col-xs-12">
										<label style="font-size:11px;color:#0030FF;">Total Additif :</label>
										<label style="font-size:11px;float:right;" id="lblTtlAdditif"></label>
									</div>
									<div class="col-md-12 col-xs-12">
										<label style="font-size:11px;color:#0030FF;">Total Other :</label>
										<label style="font-size:11px;float:right;" id="lblTtlOther"></label>
									</div>
									<div class="col-md-12 col-xs-12">
										<label style="font-size:11px;color:#0030FF;">Total Cost PMT :</label>
										<label style="font-size:11px;float:right;" id="lblTtlCostPMT"></label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="form-panel">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<input type="hidden" name="" id="txtIdEditEstimate" value="">
									<div class="col-md-6 col-xs-6">
										<button id="btnSave" class="btn btn-primary btn-xs btn-block" title="Save" onclick="saveData();">
										<i class="fa fa-check-square-o"></i> Save</button>
									</div>
									<div class="col-md-6 col-xs-6">
										<button id="btnCancel" onclick="reloadPage();" class="btn btn-danger btn-xs btn-block" name="btnCancel" title="Cancel"><i class="fa fa-ban"></i> Cancel</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div  id="idFormPanelDataFreight" style="display:none;">
					<div class="form-panel">
						<div id="idDataTableFreight">
							<div class="row" style="margin-bottom:5px;">
								<div class="col-md-1 col-xs-12">
									<button type="button" class="btn btn-primary btn-sm btn-block" title="Back" onclick="reloadPage();"><i class="fa fa-mail-reply-all" style="margin-right:5px;"></i> Back</button>
								</div>
								<div class="col-md-1 col-xs-12">
									<button type="button" id="idBtnAddFormVoyEst" class="btn btn-info btn-sm btn-block" title="Add"><i class="fa fa-plus-square" style="margin-right:5px;"></i> Add</button>
								</div>
								<div class="col-md-2 col-xs-6">
									<button type="button" id="idBtnRefresh" onclick="backPage();" class="btn btn-success btn-sm btn-block" title="Refresh"><i class="glyphicon glyphicon-refresh"></i> Refresh</button>
								</div>
							</div>
							<div class="row">
								<div class="col-md-9 col-xs-12">
									<div class="table-responsive">
										<table class="table table-hover table-border table-bordered table-condensed table-advance" style="font-size:12px;">
											<thead>
												<tr style="background-color: #ba5500;color: #FFF;">
													<th style="vertical-align:middle;text-align:center;width:10%" rowspan="2" colspan="2">No</th>
													<th style="vertical-align:middle;text-align:center;" colspan="2">Total Earning Per Voyage</th>
													<th style="vertical-align:middle;text-align:center;">Freight Based</th>
													<th style="vertical-align:middle;text-align:center;">Expected TCE / Shipment</th>
													<th style="vertical-align:middle;text-align:center;">Freight Based</th>
													<th style="vertical-align:middle;text-align:center;">Bottom Lines / Gross Profit</th>
													<th style="vertical-align:middle;text-align:center;">Add Comm / MDI</th>
												</tr>
												<tr style="background-color: #5C2F02;color: #FFF;">											
													<th style="vertical-align:middle;text-align:center;width:14%">IDR</th>
													<th style="vertical-align:middle;text-align:center;width:13%">USD</th>
													<th style="vertical-align:middle;text-align:center;width:14%">IDR / Ton</th>
													<th style="vertical-align:middle;text-align:center;width:13%">USD / Day</th>
													<th style="vertical-align:middle;text-align:center;width:13%">USD / Ton</th>
													<th style="vertical-align:middle;text-align:center;width:14%">IDR / Ton</th>
													<th style="vertical-align:middle;text-align:center;width:14%">USD / Shipment</th>
												</tr>
											</thead>
											<tbody id="idBodyDataTableFreight">
											</tbody>
										</table>
									</div>
								</div>
								<div class="col-md-3 col-xs-12">
									<div class="pre-scrollable">
										<div class="table-responsive">
											<table class="table table-hover table-border table-bordered table-condensed table-advance" style="font-size:11px;">
												<thead>
													<tr style="background-color: #ba5500;color: #FFF;">
														<th style="vertical-align:middle;text-align:center;" colspan="2" id="titleTblAnalis"></th>
													</tr>
													<tr style="background-color: #5C2F02;color: #FFF;">
														<th style="vertical-align:middle;text-align:center;width:60%;">ANALISA VOYAGE</th>
														<th style="vertical-align:middle;text-align:center;width:40%;">Total</th>
													</tr>
												</thead>
												<tbody id="idBodyAnalisaVoy">
												</tbody>
												<input type="hidden" id="totalRowTableAnalisVoyage" value="">
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div id="idFormFreight" style="display:none;">
							<div class="row">
								<div class="col-md-1 col-xs-12">
									<button type="button" class="btn btn-primary btn-xs btn-block" title="Back" onclick="backPage();"><i class="fa fa-mail-reply-all" style="margin-right:5px;"></i> Back</button>
								</div>
								<div class="col-md-11 col-xs-12" align="right">
									<legend><i>:: Form Data ::</i></legend>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-xs-12">
									<div class="table-responsive">
										<table class="table table-hover table-border table-bordered table-condensed table-advance" style="font-size:12px;">
											<thead>
												<tr style="background-color: #ba5500;color: #FFF;">													
													<th style="vertical-align:middle;text-align:center;" colspan="2">Total Earning Per Voyage</th>
													<th style="vertical-align:middle;text-align:center;">Freight Based</th>
													<th style="vertical-align:middle;text-align:center;">Expected TCE / Shipment</th>
													<th style="vertical-align:middle;text-align:center;">Freight Based</th>
													<th style="vertical-align:middle;text-align:center;">Bottom Lines / Gross Profit</th>
													<th style="vertical-align:middle;text-align:center;">Add Comm/ MDI</th>
													<th style="vertical-align:middle;text-align:center;width:5%" rowspan="2">#</th>
												</tr>
												<tr style="background-color: #5C2F02;color: #FFF;">											
													<th style="vertical-align:middle;text-align:center;width:14%">IDR</th>
													<th style="vertical-align:middle;text-align:center;width:13%">USD</th>
													<th style="vertical-align:middle;text-align:center;width:14%">IDR / Ton</th>
													<th style="vertical-align:middle;text-align:center;width:13%">USD / Day</th>
													<th style="vertical-align:middle;text-align:center;width:13%">USD / Ton</th>
													<th style="vertical-align:middle;text-align:center;width:14%">IDR / Ton</th>
													<th style="vertical-align:middle;text-align:center;width:14%">IDR / Shipment</th>
												</tr>
											</thead>
											<tbody id="idBodyFreightForm">
												<tr id="idTR_1">
													<input type="hidden" id="txtIdEditReportVoyage_1" value="">
													<td id="erningIDR_1" style="text-align:center;"></td>
													<td id="erningUSD_1" style="text-align:center;"></td>
													<td>
														<input type="text" class="form-control input-sm" id="txtFreight_1" value="0" oninput="hitungEstVoyReport('1');">
													</td>
													<td id="tce_1" style="text-align:center;"></td>
													<td id="freightBaseConv_1" style="text-align:center;"></td>
													<td id="grossProfit_1" style="text-align:center;"></td>
													<td id="addComm_1" style="text-align:center;"></td>													
													<td align="center">
														<button class="btn btn-primary btn-xs" onclick="addRowData();"><i class="fa fa-plus"></i></button>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 col-xs-6">
									<input type="hidden" id="txtIdEditVoyEst" value="">
									<input type="hidden" id="txtRowData" value="1">
									<input type="hidden" id="txtTotalCargoForm" value="">
									<input type="hidden" id="txtTotalFXForm" value="">
									<input type="hidden" id="txtTotalAddCommForm" value="">
									<input type="hidden" id="txtTotalActRVForm" value="">
									<input type="hidden" id="txtTotalOptCost" value="">
									<button class="btn btn-primary btn-xs btn-block" onclick="saveDataReportVoyEst();"><i class="fa fa-save"></i> Save</button>
								</div>
								<div class="col-md-6 col-xs-6">
									<button class="btn btn-danger btn-xs btn-block" onclick="backPage();"><i class="fa fa-ban"></i> Cancel</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</section>
	</section>
	<div class="modal fade" id="idModalShowVoyEstNew" role="dialog">
		<div class="modal-gd modal-dialog">
			<div class="modal-content">
		        <div class="modal-header" style="background-color:#d56b03;">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title" style="text-align:left;font-weight:bold;"><i>:: View Voyage Estimator ::</i></h4>
		        </div>
		        <div class="modal-body">
		          <div class="row">
		          	<div class="col-md-1 col-xs-12">
						<button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					      Export
					      <span class="caret"></span>
					    </button>
					    <ul class="dropdown-menu">
					      <li><a style="cursor:pointer;" id="btnModalExportPDF">PDF</a></li>
					    </ul>
		          	</div>
		          	<div class="col-md-10 col-xs-12" align="center">
		          		<label id="lblModalBunkerTitle" style="font-size:20px;text-align:center;font-weight:bold;"></label>
		          	</div>
		          </div>
		          <div class="row">
		          	<div class="col-md-4 col-xs-12">
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Date Prepared</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalDatePrepared"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Cargo</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalCargo"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Vessel Type</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalVesselType"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Cargo / Shipment</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalTotalCargo"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Load Port (L/P)</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalLoadPortLP"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Discharge Port (D/P)</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalDischPortDP"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Allowable Load Rate</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalAllowLoadRate"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Allowable&nbspDisch.&nbspRate</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalAllowDischRate"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Actual Load Rate</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalActualLoadRate"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Actual Disch. Rate</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalActualDischRate"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Allowable TT at L/P</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalAllowTTatLP"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Allowable TT at D/P</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalAllowTTatDP"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Actual TT at L/P</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalActualTTatLP"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Actual TT at D/P</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalActualTTatDP"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Waiting at L/P</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalWaitatLP"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Waiting at D/P</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalWaitatDP"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Demmurage</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalDemmurage"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Despatch</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalDespatch"></label>
		          			</div>
		          		</div>
		          	</div>
		          	<div class="col-md-4 col-xs-12">
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Distance Laden</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalDistanceLaden"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Distance Ballast</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalDistanceBallast"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Sea Speed Laden</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalSeaSpeedLaden"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Sea Speed Ballast</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalSeaSpeedBallast"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Sailing Laden Days</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalSailingLaden"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Sailing Ballast Days</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalSailingBallast"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Total sailing Days</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalTotalSailing"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Allowable L/P Days</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalAllowLP"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Allowable D/P Days</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalAllowDP"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Allowable Port Days</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalAllowPort"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Actual L/P Days</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalActualLP"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Actual D/P Days</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalActualDP"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Actual Port Days</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalActualPort"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Allowable RV Days</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalAllowRV"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Actual RV Days</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalActualRV"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Bunker Price Period</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalBunkerPricePeriod"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>IFO Price/ltr</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalIFOPrice"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>MGO Price/ltr</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalMGOPrice"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Discount On IFO</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalDiscIFO"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Discount On MGO</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalDiscMGO"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>IFO Price after disc.</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalIFOAfterDisc"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>MGO&nbspPrice&nbspafter&nbspdisc.</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalMGOAfterDisc"></label>
		          			</div>
		          		</div>
		          	</div>
		          	<div class="col-md-4 col-xs-12">
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>IFO cons at Sea Ldn</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalIFOConsAtSeaLdn"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>IFO cons at Sea Bllst</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalIFOConsAtSeaBllst"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>MGO cons at Sea Ldn</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalMGOConsAtSeaLdn"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>MGO&nbspcons&nbspat&nbspSea&nbspBllst</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalMGOConsAtSeaBllst"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>IFO cons at port idle</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalIFOConsAtPortIdle"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>IFO&nbspcons&nbspat&nbspport&nbspwrkg</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalIFOConsAtPortWrkg"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>MGO&nbspcons&nbspat&nbspport&nbspidle</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalMGOConsAtPortIdle"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>MGO&nbspcons&nbspat&nbspport&nbspwrkg</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalMGOConsAtPortWrkg"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>PDA L/P</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalPDALP"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>PDA D/P</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalPDADP"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Number of Ship(s)</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalNoShip"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Max Cgo Qty/Year</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalMaxCgoQtyYear"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Addcomm PMT/Voy</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalAddCommPMT"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Other Cost PMT/Voy</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalOtherCostPMT"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-4 col-xs-5">
		          				<label>Fx Rp/ USD 1</label>
		          			</div>
		          			<div class="col-md-1 col-xs-1">
		          				<label style="font-weight:bold;">:</label>
		          			</div>
		          			<div class="col-md-7 col-xs-6" style="padding-left:0px;">
		          				<label id="lblModalFixRpUsd"></label>
		          			</div>
		          		</div>
		          		<div class="row">
		          			<div class="col-md-12 col-xs-12">
		          				<div align="center" style="background-color:#d56b03;vertical-align:middle;">
			          				<label style="font-size:16px;font-weight:bold;color:#FFFFFF;">Other Cost PMT/Voy</label>
			          			</div>
			          			<div style="background-color:#FFDCBA;padding:10px;">
				          			<div class="row">
					          			<div class="col-md-5 col-xs-5">
					          				<label>Floating Crane</label><label id="lblModalIFOConsAtSeaLdnTitle" style="font-weight:bold;"></label>
					          			</div>
					          			<div class="col-md-1 col-xs-1">
					          				<label style="font-weight:bold;">:</label>
					          			</div>
					          			<div class="col-md-6 col-xs-6" style="text-align:right;">
					          				<label id="lblModalFloatingCranePMT"></label>
					          			</div>
					          		</div>
					          		<div class="row">
					          			<div class="col-md-5 col-xs-5">
					          				<label>Additif</label><label id="lblModalAdditifPMTTitle" style="font-weight:bold;"></label>
					          			</div>
					          			<div class="col-md-1 col-xs-1">
					          				<label style="font-weight:bold;">:</label>
					          			</div>
					          			<div class="col-md-6 col-xs-6" style="text-align:right;">
					          				<label id="lblModalAdditifPMT"></label>
					          			</div>
					          		</div>
					          		<div class="row">
					          			<div class="col-md-5 col-xs-5">
					          				<label>Other</label><label id="lblModalOtherPMTTitle" style="font-weight:bold;"></label>
					          			</div>
					          			<div class="col-md-1 col-xs-1">
					          				<label style="font-weight:bold;">:</label>
					          			</div>
					          			<div class="col-md-6 col-xs-6" style="text-align:right;">
					          				<label id="lblModalOtherPMT"></label>
					          			</div>
					          		</div>
					          		<div class="row">
					          			<div class="col-md-6 col-xs-1" align="right">
					          				<label style="font-weight:bold;font-size:14px;">Total</label>
					          			</div>
					          			<div class="col-md-6 col-xs-12" style="text-align:right;font-weight:bold;font-size:14px;">
					          				<label id="lblModalTotalPMT"></label>
					          			</div>
					          		</div>
					          	</div>
		          			</div>
		          		</div>
		          	</div>
		          </div>
		        </div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="idModalBunkerRegional" role="dialog">
		<div class="modal-gd modal-dialog">
			<div class="modal-content">
		        <div class="modal-header" style="background-color:#d56b03;">
		          <button type="button" class="close" data-dismiss="modal">&times;</button>
		          <h4 class="modal-title" style="text-align:left;font-weight:bold;">		          	
		          	<i>:: Bunker Regional :: </i>
		          	<img id="idLoadingModalBunker" src="<?php echo base_url('assets/img/loading.gif'); ?>" style="display:none;">
		          </h4>
		        </div>
		        <div class="modal-body">
			    	<div class="form-panel" id="idModalBunkerDataTable" style="display:;">
						<div class="row" style="margin-bottom:5px;">
							<div class="col-md-1 col-xs-12">
								<button type="button" class="btn btn-primary btn-sm btn-block" title="Back" onclick="reloadPage();">
									<i class="fa fa-mail-reply-all" style="margin-right: 5px;"></i> Back</button>
							</div>
							<div class="col-md-1 col-xs-12">
								<button type="button" id="idBtnAddModalForm" class="btn btn-info btn-sm btn-block" title="Add"><i class="fa fa-plus-square" style="margin-right: 5px;"></i> Add</button>
							</div>
							<div class="col-md-3 col-xs-12" id="idtxtSearch" style="display: ">
								<input placeholder="Period" type="text" class="form-control input-sm" id="txtSearchModalBunker" value="">
							</div>
							<div class="col-md-2 col-xs-6" id="idSearch" style="display: ">
								<button type="button" id="idbtnModalSearchBunker" class="btn btn-warning btn-sm btn-block" title="Search" onclick="searchDataBunkerRegional();">
									<i class="fa fa-search" style="margin-right: 5px;"></i> Search
								</button>
							</div>
							<div class="col-md-2 col-xs-6">
								<button type="button" id="idBtnRefresh" onclick="getDataBunkerRegional();" class="btn btn-success btn-sm btn-block" title="Refresh"><i class="glyphicon glyphicon-refresh"></i> Refresh</button>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-xs-12">
								<div class="table-responsive">
									<table class="table table-hover table-border table-bordered table-condensed table-advance">
										<thead>
											<tr style="background-color: #ba5500;color: #FFF;">
												<th style="vertical-align:middle;width:5%;text-align:center;padding:5px;" rowspan="2">No</th>
												<th style="vertical-align:middle;width:30%;text-align:center;" rowspan="2">Period</th>
												<th style="vertical-align:middle;text-align:center;" colspan="2">Bunker Price</th>
												<th style="vertical-align:middle;width:30%;text-align:center;" rowspan="2">Remark</th>
												<th style="vertical-align:middle;width:5%;text-align:center;" rowspan="2">Action</th>
											</tr>
											<tr style="background-color: #ba5500;color: #FFF;">
												<th style="vertical-align:middle;width:15%;text-align:center;">MFO</th>
												<th style="vertical-align:middle;width:15%;text-align:center;">MGO</th>
											</tr>
										</thead>
										<tbody id="idBodyModalBunker">
										</tbody>
									</table>
								</div>
								<div id="idPageNya">
									<?php //echo $pageNya; ?>
								</div>
							</div>
						</div>
					</div>
					<div class="form-panel" id="idModalBunkerForm" style="display:none;">
						<div class="row">
							<div class="col-md-12 col-xs-12" align="right">
								<h4><b><i>:: Form Data ::</i></b></h4>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="slcModalBunkerPeriodMonth" style="font-size:12px;color:#0030FF;">Period Month:</label>
									<select id="slcModalBunkerPeriodMonth" class="form-control select-sm">
										<option value="">- Select -</option>
										<option value="1">January</option>
										<option value="2">February</option>
										<option value="3">March</option>
										<option value="4">April</option>
										<option value="5">May</option>
										<option value="6">June</option>
										<option value="7">July</option>
										<option value="8">August</option>
										<option value="9">September</option>
										<option value="10">October</option>
										<option value="11">November</option>
										<option value="12">December</option>
									</select>
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtModalBunkerPeriod" style="font-size:12px;color:#0030FF;">Period Name :</label>
									<input type="text" class="form-control input-sm" id="txtModalBunkerPeriod" value="" placeholder="Period">
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtModalBunkerPriceMFO" style="font-size:12px;color:#0030FF;">Price MFO :</label>
									<div class="row">
										<div class="col-md-6 col-xs-12">
											<select id="slModalBunkerCurrMFO" class="form-control select-sm">
												<option value="idr">IDR</option>
												<option value="usd">USD</option>
											</select>
										</div>
										<div class="col-md-6 col-xs-12">
											<input type="text" class="form-control input-sm" id="txtModalBunkerPriceMFO" value="" placeholder="0" style="text-align:right;">
										</div>
									</div>									
								</div>
							</div>
							<div class="col-md-2 col-xs-12">
								<div class="form-group">
									<label for="txtModalBunkerPriceMGO" style="font-size:12px;color:#0030FF;">Price MGO :</label>
									<div class="row">
										<div class="col-md-6 col-xs-12">
											<select id="slcModalBunkerCurrMGO" class="form-control select-sm">
												<option value="idr">IDR</option>
												<option value="usd">USD</option>
											</select>
										</div>
										<div class="col-md-6 col-xs-12">
											<input type="text" class="form-control input-sm" id="txtModalBunkerPriceMGO" value="" placeholder="0" style="text-align:right;">
										</div>
									</div>									
								</div>
							</div>
							<div class="col-md-4 col-xs-12">
								<div class="form-group">
									<label for="txtModalBunkerRemark" style="font-size:12px;color:#0030FF;">Remark :</label>
									<textarea class="form-control input-sm" id="txtModalBunkerRemark"></textarea>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 col-xs-12">
								<input type="hidden" id="txtIdEditModalBunker" value="">
								<button id="btnSaveModalBunker" class="btn btn-primary btn-xs btn-block" title="Save" onclick="saveDataModalBunker();">
									<i class="fa fa-check-square-o"></i> Save</button>
							</div>
							<div class="col-md-6 col-xs-12">
								<button id="btnCancelModalBunker" onclick="getDataBunkerRegional();" class="btn btn-danger btn-xs btn-block" name="btnCancel" title="Cancel">
									<i class="fa fa-ban"></i> Cancel</button>
							</div>
						</div>
					</div>
		        </div>
			</div>
		</div>
	</div>
</body>
</html>

