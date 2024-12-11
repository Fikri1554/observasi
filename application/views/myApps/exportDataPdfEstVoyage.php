<?php
$nama_dokumen = "voyageEstimator";
require("pdf/mpdf60/mpdf.php");
$mpdf = new mPDF('utf-8','A4-L');
ob_start(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
	<title>Export PDF</title>
</head>
<body>
	<div style="width:100%;">
		<div class="reportPDF">
			<div align="left" style="padding-top: -50px;">
				<table style="width:100%;">
					<tr>
						<td style="width:100%;vertical-align:top;">
							<img style="width:50%;" src="<?php echo base_url('assets/img/PT. ANDHIKA LINES.png'); ?>">
						</td>
					</tr>
				</table>
			</div>
			<div style="margin-top:-5px;">
				<h3 style="text-align:center;"><u><?php echo strtoupper($data[0]->title); ?></u></h3>
			</div>
			<table style="width:100%;font-size:12px;margin-top:-10px;" border="0">
				<tr>
					<td style="width:250px;vertical-align:top;">
						<table style="width:100%" cellspacing="0">
							<tr>
								<td style="width:120px;">Date Prepared</td>
								<td style="width:130px;border:1px solid;"><?php echo $datePrepared; ?></td>
							</tr>
							<tr>
								<td style="width:120px;">Cargo</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo $data[0]->cargo; ?></td>
							</tr>
							<tr>
								<td style="width:120px;">Vessel Type</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo $data[0]->vessel_type; ?></td>
							</tr>
							<tr>
								<td style="width:120px;">Cargo / Shipment</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->total_cargo,0); ?> MT/Voy</td>
							</tr>
							<tr>
								<td style="width:120px;">Load Port (L/P)</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo $data[0]->load_port; ?></td>
							</tr>
							<tr>
								<td style="width:120px;">Discharge Port (D/P)</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo $data[0]->discharge_port; ?></td>
							</tr>
							<tr>
								<td style="width:120px;">Allowable Load Rate</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->allow_load_rate,0); ?> MT/Day</td>
							</tr>
							<tr>
								<td style="width:120px;">Allowable Disch. Rate</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->allow_disch_rate,0); ?> MT/Day</td>
							</tr>
							<tr>
								<td style="width:120px;">Actual Load Rate</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->actual_load_rate,0); ?> MT/Day</td>
							</tr>
							<tr>
								<td style="width:120px;">Actual Disch. Rate</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->actual_disch_rate,0); ?> MT/Day</td>
							</tr>
							<tr>
								<td style="width:120px;">Allowable TT at L/P</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->allowable_tt_lp,2); ?> Day</td>
							</tr>
							<tr>
								<td style="width:120px;">Allowable TT at D/P</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->allowable_tt_dp,2); ?> Day</td>
							</tr>
							<tr>
								<td style="width:120px;">Actual TT at L/P</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->actual_tt_lp,2); ?> Day</td>
							</tr>
							<tr>
								<td style="width:120px;">Actual TT at D/P</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->actual_tt_dp,2); ?> Day</td>
							</tr>
							<tr>
								<td style="width:120px;">Waiting at L/P</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->waiting_lp,2); ?> Day</td>
							</tr>
							<tr>
								<td style="width:120px;">Waiting at D/P</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->waiting_dp,2); ?> Day</td>
							</tr>
							<tr>
								<td style="width:120px;">Demmurage Load</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo strtoupper($data[0]->demmurage_load_curr)." ".number_format($data[0]->demmurage_load,0); ?> PDPR</td>
							</tr>
							<tr>
								<td style="width:120px;">Despatch Load</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo strtoupper($data[0]->despatch_load_curr)." ".number_format($data[0]->despatch_load,0); ?> PDPR</td>
							</tr>
							<tr>
								<td style="width:120px;">Demmurage Disch.</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo strtoupper($data[0]->demmurage_disch_curr)." ".number_format($data[0]->demmurage_disch,0); ?> PDPR</td>
							</tr>
							<tr>
								<td style="width:120px;">Despatch Disch.</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo strtoupper($data[0]->despatch_disch_curr)." ".number_format($data[0]->despatch_disch,0); ?> PDPR</td>
							</tr>
						</table>
					</td>
					<td style="width:250px;vertical-align:top;">
						<table style="width:100%" cellspacing="0">
							<tr>
								<td style="width:120px;">Distance Laden</td>
								<td style="width:130px;border:1px solid;"><?php echo number_format($data[0]->distance_laden,2); ?> nm</td>
							</tr>
							<tr>
								<td style="width:120px;">Distance Ballast</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->distance_ballast,2); ?> nm</td>
							</tr>
							<tr>
								<td style="width:120px;">Sea Speed Laden</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->sea_speed_laden,2); ?> Knots/Hr</td>
							</tr>
							<tr>
								<td style="width:120px;">Sea Speed Ballast</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->sea_speed_ballast,2); ?> Knots/Hr</td>
							</tr>
							<tr>
								<td style="width:120px;">Sailing Laden Days</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->sailing_laden,2); ?> Days/Voy</td>
							</tr>
							<tr>
								<td style="width:120px;">Sailing Ballast Days</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->sailing_ballast,2); ?> Days/Voy</td>
							</tr>
							<tr>
								<td style="width:120px;">Total sailing Days</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->sailing_total,2); ?> Days/Voy</td>
							</tr>
							<tr>
								<td style="width:120px;">Allowable L/P Days</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->allow_lp_day,2); ?> Days/Voy</td>
							</tr>
							<tr>
								<td style="width:120px;">Allowable D/P Days</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->allow_dp_day,2); ?> Days/Voy</td>
							</tr>
							<tr>
								<td style="width:120px;">Allowable Port Days</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->allow_port_day,2); ?> Days/Voy</td>
							</tr>
							<tr>
								<td style="width:120px;">Actual L/P Days</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->actual_lp_day,2); ?> Days/Voy</td>
							</tr>
							<tr>
								<td style="width:120px;">Actual D/P Days</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->actual_dp_day,2); ?> Days/Voy</td>
							</tr>
							<tr>
								<td style="width:120px;">Actual Port Days</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->actual_port_day,2); ?> Days/Voy</td>
							</tr>
							<tr>
								<td style="width:120px;">Allowable RV Days</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->allow_rv_day,2); ?> Days/Voy</td>
							</tr>
							<tr>
								<td style="width:120px;">Actual RV Days</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->actual_rv_day,2); ?> Days/Voy</td>
							</tr>
							<tr>
								<td style="width:120px;">Bunker Price Period</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo $data[0]->banker_price_period; ?></td>
							</tr>
							<tr>
								<td style="width:120px;">IFO Price/ltr</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->ifo_price,2); ?> /ltr</td>
							</tr>
							<tr>
								<td style="width:120px;">MGO Price/ltr</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->mgo_price,2); ?> /ltr</td>
							</tr>
							<tr>
								<td style="width:120px;">Discount On IFO</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->disc_on_ifo,2); ?> %</td>
							</tr>
							<tr>
								<td style="width:120px;">Discount On MGO</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->disc_on_mgo,2); ?> %</td>
							</tr>
							<tr>
								<td style="width:120px;">IFO Price after disc.</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->ifo_after_disc,2); ?> /ltr</td>
							</tr>
							<tr>
								<td style="width:120px;">MGO Price after disc.</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->mgo_after_disc,2); ?> /ltr</td>
							</tr>
						</table>
					</td>
					<td style="width:250px;vertical-align:top;">
						<table style="width:100%" cellspacing="0">
							<tr>
								<td style="width:120px;">IFO cons at Sea Ldn</td>
								<td style="width:130px;border:1px solid;"><?php echo number_format($data[0]->ifo_cons_seaLadden,2); ?> MT/Day</td>
							</tr>
							<tr>
								<td style="width:120px;">IFO cons at Sea Bllst</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->ifo_cons_seaBallast,2); ?> MT/Day</td>
							</tr>
							<tr>
								<td style="width:120px;">MGO cons at Sea Ldn</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->mgo_cons_seaLaden,2); ?> MT/Day</td>
							</tr>
							<tr>
								<td style="width:120px;">MGO cons at Sea Bllst</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->mgo_cons_seaBallast,2); ?> MT/Day</td>
							</tr>
							<tr>
								<td style="width:120px;">IFO cons at port idle</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->ifo_cons_portIdle,2); ?> MT/Day</td>
							</tr>
							<tr>
								<td style="width:120px;">IFO cons at port wrkg</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->ifo_cons_portWorking,2); ?> MT/Day</td>
							</tr>
							<tr>
								<td style="width:120px;">MGO cons at port idle</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->mgo_cons_portIdle,2); ?> MT/Day</td>
							</tr>
							<tr>
								<td style="width:120px;">MGO cons at port wrkg</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->mgo_cons_portWorking,2); ?> MT/Day</td>
							</tr>
							<tr>
								<td style="width:120px;">PDA L/P</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo strtoupper($data[0]->pda_lp_curr)." ".number_format($data[0]->pda_lp,2); ?></td>
							</tr>
							<tr>
								<td style="width:120px;">PDA D/P</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo strtoupper($data[0]->pda_dp_curr)." ".number_format($data[0]->pda_dp,2); ?></td>
							</tr>
							<tr>
								<td style="width:120px;">Number of Ship(s)</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->no_of_ship,0); ?></td>
							</tr>
							<tr>
								<td style="width:120px;">Max Cgo Qty/Year</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->max_cargo,2); ?></td>
							</tr>
							<tr>
								<td style="width:120px;">Addcomm PMT/Voy</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo number_format($data[0]->addcomm_pmt,2); ?> %</td>
							</tr>
							<tr>
								<td style="width:120px;">Other Cost PMT/Voy</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo strtoupper($data[0]->other_cost_curr)." ".number_format($data[0]->other_cost,2); ?></td>
							</tr>
							<tr>
								<td style="width:120px;">Fx Rp/ USD 1</td>
								<td style="width:130px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;"><?php echo strtoupper($data[0]->fx_curr)." ".number_format($data[0]->fx,2); ?></td>
							</tr>
							<tr><td colspan="2" style="height:10px;"></td></tr>
							<tr style="padding-top: 20px;">
								<td colspan="2" style="background-color:#d56b03;vertical-align:middle;height:20px;color:#FFFFFF;font-weight:bold;" align="center">Other Cost PMT/Voy</td>
							</tr>
							<tr style="background-color:#FFDCBA;">
								<td style="width:120px;height:20px;">Floating Crane (<?php echo number_format($data[0]->floating_crane_pmt,0); ?>)</td>
								<td style="width:130px;text-align:right;padding-right:10px;"><?php echo $ttlFloating; ?></td>
							</tr>
							<tr style="background-color:#FFDCBA;">
								<td style="width:120px;height:20px;">Additif (<?php echo number_format($data[0]->floating_crane_pmt,0); ?>)</td>
								<td style="width:130px;text-align:right;padding-right:10px;"><?php echo $ttlAddt; ?></td>
							</tr>
							<tr style="background-color:#FFDCBA;">
								<td style="width:120px;height:20px;">Other (<?php echo number_format($data[0]->floating_crane_pmt,0); ?>)</td>
								<td style="width:130px;text-align:right;padding-right:10px;"><?php echo $ttlOther; ?></td>
							</tr>
							<tr style="background-color:#FFDCBA;">
								<td style="width:120px;text-align:right;height:20px;font-weight:bold;">Total</td>
								<td style="width:130px;text-align:right;font-weight:bold;padding-right:10px;"><?php echo $ttlCostPMT; ?></td>
							</tr>
							<tr style="background-color:#FFDCBA;">
								<td style="width:120px;height:20px;">Total Operating Cost</td>
								<td style="width:130px;text-align:right;padding-right:10px;"><?php echo $ttlOprCost; ?></td>
							</tr>
							<tr style="background-color:#FFDCBA;">
								<td style="width:120px;height:20px;">Total Demdes</td>
								<td style="width:130px;text-align:right;padding-right:10px;"><?php echo $ttlDemDes; ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table style="width:100%;margin-top:10px;" border="0">
				<thead>
				<tr style="background-color: #ba5500;">
					<th style="vertical-align:middle;text-align:center;color:#FFF;font-size:20px;width:50px;" rowspan="2">No</th>
					<th style="vertical-align:middle;text-align:center;color:#FFF;font-size:20px;height:40px;" colspan="2">Total Earning Per Voyage</th>
					<th style="vertical-align:middle;text-align:center;color:#FFF;font-size:20px;">Freight Based</th>
					<th style="vertical-align:middle;text-align:center;color:#FFF;font-size:20px;">Expected TCE / Shipment</th>
					<th style="vertical-align:middle;text-align:center;color:#FFF;font-size:20px;">Freight Based</th>
					<th style="vertical-align:middle;text-align:center;color:#FFF;font-size:20px;">Bottom Lines / Gross Profit</th>
					<th style="vertical-align:middle;text-align:center;color:#FFF;font-size:20px;">Add Comm / MDI</th>
				</tr>
				<tr style="background-color: #5C2F02;">
					<th style="vertical-align:middle;text-align:center;color:#FFF;font-size:20px;height:40px;width:250px;">IDR</th>
					<th style="vertical-align:middle;text-align:center;color:#FFF;font-size:20px;width:250px;">USD</th>
					<th style="vertical-align:middle;text-align:center;color:#FFF;font-size:20px;width:250px;">IDR / Ton</th>
					<th style="vertical-align:middle;text-align:center;color:#FFF;font-size:20px;width:250px;">USD / Day</th>
					<th style="vertical-align:middle;text-align:center;color:#FFF;font-size:20px;width:250px;">USD / Ton</th>
					<th style="vertical-align:middle;text-align:center;color:#FFF;font-size:20px;width:250px;">IDR / Ton</th>
					<th style="vertical-align:middle;text-align:center;color:#FFF;font-size:20px;width:250px;">IDR / Shipment</th>
				</tr>
				</thead>
				<tbody>
					<?php echo $trNya; ?>
				</tbody>
			</table>
		</div>
	</div>	
</body>
</html>
 
<?php
$html = ob_get_contents();
ob_end_clean();
$mpdf->WriteHTML(utf8_encode($html));
$mpdf->Output($nama_dokumen.".pdf" ,'I');
exit;
?>