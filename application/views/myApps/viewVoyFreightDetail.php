<?php
$nama_dokumen = $fileName;
require("pdf/mpdf60/mpdf.php");
$mpdf = new mPDF('utf-8', 'A4');
ob_start(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
	<title>Export PDF</title>
</head>
<body>
	<div style="width:100%;min-height:500px;">		
		<div class="reportPDF" style="width:100%;min-height:0px;">
			<div align="center" style="padding-top: -20px;">
				<img width="7%;" src="<?php echo base_url('assets/img/andhika.gif'); ?>">
			</div>
			<div align="center" style="margin-bottom: 10px;"><?php echo $lblYoyage ?></div>
			<table style="width: 100%;font-size: 11px;border-style: groove;border-width: 1px;">
				<tr>
					<td colspan="5">
						<label><?php echo $vessel; ?></label>
					</td>
					<td align="right" colspan="6">
						<label><?php echo $cargoType; ?></label>
					</td>
				</tr>
				<tr>
					<td colspan="10">
						<label><?php echo $lPort; ?></label> TO <label><?php echo $dPort; ?></label>
					</td>
				</tr>
			</table>
			<table style="margin-top: 2px;width: 100%;font-size: 11px;border-style: groove;border-width: 1px;">
				<tr>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">VESSEL DWT</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">VESSEL Drft</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">TPC</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">SPEED L</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">SPEED B</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">FO LDN</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">FO BLST</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">STEM DO</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">PORT DO</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">PORT FO</td>
				</tr>
				<tr>
					<td align="center"><?php echo $vslDwt; ?></td>
					<td align="center"><?php echo $vslDrft; ?></td>
					<td align="center"><?php echo $tpc; ?></td>
					<td align="center"><?php echo $speedL; ?></td>
					<td align="center"><?php echo $speedB; ?></td>
					<td align="center"><?php echo $foLdn; ?></td>
					<td align="center"><?php echo $foBlst; ?></td>
					<td align="center"><?php echo $stemDo; ?></td>
					<td align="center"><?php echo $portDo; ?></td>
					<td align="center"><?php echo $portFo; ?></td>
				</tr>
			</table>
			<table style="margin-top: 2px;width: 100%;font-size: 11px;border-style: groove;border-width: 1px;">
				<tr>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">PORT NAME</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">DIST NM</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">SEA MARGIN</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">SEA DAYS</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">SEA FO</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">SEA DO</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">PRICE FO</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">PRICE DO</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">ETA/ETS</td>
				</tr>
				<tr>
					<td><?php echo $portName; ?></td>
					<td align="center"><?php echo $dist1; ?></td>
					<td align="center"><?php echo $seaMar1; ?> %</td>
					<td align="center"><?php echo $seaDay1; ?></td>
					<td align="center"><?php echo $seaFo1; ?></td>
					<td align="center"><?php echo $seaDo1; ?></td>
					<td align="center"><?php echo $priceFo; ?></td>etaEts
					<td align="center"><?php echo $priceDo; ?></td>
					<td align="center"><?php echo $etaEts; ?></td>
				</tr>
				<tr>
					<td><?php echo $portName2; ?></td>
					<td align="center"><?php echo $dist2; ?></td>
					<td align="center"><?php echo $seaMar2; ?> %</td>
					<td align="center"><?php echo $seaDay2; ?></td>
					<td align="center"><?php echo $seaFo2; ?></td>
					<td align="center"><?php echo $seaDo2; ?></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
				</tr>
				<tr>
					<td colspan="8"><?php echo $portName3; ?></td>
				</tr>
			</table>
			<table style="margin-top: 2px;width: 100%;font-size: 11px;border-style: groove;border-width: 1px;">
				<tr>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">PORT NAME</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">PORT COST</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">DRAFT B SS</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">CGO DAYS</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">WTG DAYS</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">PORT FO</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">PORT DO</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">STEAM FO</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">STEAM DO</td>
				</tr>
				<?php echo $trNya; ?>
			</table>
			<table style="margin-top: 2px;width: 100%;font-size: 11px;border-style: groove;border-width: 1px;">
				<tr>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">LOADING RATE</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">DISCHARGING RATE</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">DEM</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">DES</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">TT L/P</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">TT D/P</td>
					<td align="center" style="background-color:#D55C03;height:25px;color:#FFF;">TERMS</td>
				</tr>
				<tr>
					<td align="center"><?php echo $loadingRate; ?> MT Shinc</td>
					<td align="center"><?php echo $dischRate; ?> MT Shinc</td>
					<td align="center"><?php echo $dmg; ?></td>
					<td align="center"><?php echo $des; ?></td>
					<td align="center"><?php echo $ttlp; ?></td>
					<td align="center"><?php echo $ttdp; ?></td>
					<td align="center"><?php echo $terms; ?></td>
				</tr>
			</table>
			<table style="margin-top: 2px;width: 100%;font-size: 11px;border-style: groove;border-width: 1px;">
				<tr>
					<td colspan="2">Load Port : <?php echo $lPort; ?></td>
					<td colspan="2">Disch. Port : <?php echo $dPort; ?></td>
				</tr>
				<tr>
					<td colspan="2">Cargo Size : <?php echo $carSize; ?> MT Bss Specd</td>
					<td>Frt Rate : <?php echo $frtRate; ?></td>
					<td>Less Comm : <?php echo $lessComm; ?> %</td>
				</tr>
			</table>
			<table style="margin-top: 2px;width: 100%;font-size: 11px;border-style: groove;border-width: 1px;">
				<tr>
					<td>Post Cost : </td>
					<td><?php echo $portCost; ?></td>
					<td>FO Cost : </td>
					<td><?php echo $foCost; ?></td>
				</tr>
				<tr>
					<td>Handling : </td>
					<td><?php echo $handling; ?></td>
					<td>DO Cost : </td>
					<td><?php echo $doCost; ?></td>
				</tr>
				<tr>
					<td>O H & P & I : </td>
					<td><?php echo $ohPI; ?></td>
					<td>Total Fuel Cost : </td>
					<td><?php echo $ttlFuelCost; ?></td>
				</tr>
				<tr>
					<td style="padding-top: 10px;">Laden Day :</td>
					<td style="padding-top: 10px;"><?php echo $ladenDay; ?></td>
					<td style="padding-top: 10px;" colspan="2"><?php echo $ladenDayPcnt; ?> %</td>
				</tr>
				<tr>
					<td>Ballast Days :</td>
					<td><?php echo $ballastDay; ?></td>
					<td colspan="2"><?php echo $ballastDayPcnt; ?> %</td>
				</tr>
				<tr>
					<td>Port Days :</td>
					<td><?php echo $portDays; ?></td>
					<td colspan="2"><?php echo $portDaysPcnt; ?> %</td>
				</tr>
				<tr>
					<td>FO DO :</td>
					<td><?php echo $stemFo; ?></td>
					<td colspan="2"><?php echo $stemDo2; ?></td>
				</tr>
			</table>
			<table style="margin-top: 2px;width: 100%;font-size: 11px;border-style: groove;border-width: 1px;">
				<tr>
					<td>Frt Revenue : </td>
					<td><?php echo $frtRev; ?></td>
					<td>Days Load : </td>
					<td><?php echo $crgDay1; ?></td>
					<td>Days Allowd : </td>
					<td><?php echo $dayAllowdL; ?></td>
					<td>Net Revenue : </td>
					<td><?php echo $netRev; ?></td>
				</tr>
				<tr>
					<td>Dem Murage : </td>
					<td><?php echo $dmg; ?></td>
					<td>Days Disch : </td>
					<td><?php echo $crgDay2; ?></td>
					<td>Days Allowd : </td>
					<td><?php echo $dayAllowdD; ?></td>
					<td>Voyage Cost : </td>
					<td><?php echo $ttlVoyCost; ?></td>
				</tr>
				<tr>
					<td>Despatch Murage : </td>
					<td colspan="5"><?php echo $desPatch; ?></td>
					<td>Voyage Surp : </td>
					<td><?php echo $tceVoy; ?></td>
				</tr>
				<tr>
					<td>Commision : </td>
					<td><?php echo $comm; ?></td>
					<td>FO Load : </td>
					<td><?php echo $foLoad; ?></td>
					<td>FO Disch : </td>
					<td><?php echo $foDisch; ?></td>
				</tr>
				<tr>
					<td>Freight Tax : </td>
					<td><?php echo $frgTax; ?></td>
					<td>DO Load : </td>
					<td><?php echo $doLoad; ?></td>
					<td>DO Disch : </td>
					<td><?php echo $doDisch; ?></td>
					<td>Cost / Ton : </td>
					<td><?php echo $costTon; ?></td>
				</tr>
				<tr>
					<td>Net Revenue : </td>
					<td><?php echo $netRev; ?></td>
					<td>Load Cost : </td>
					<td><?php echo $loadCost; ?></td>
					<td>Disch Cost : </td>
					<td><?php echo $dischCost; ?></td>
				</tr>
			</table>
			<table style="margin-top: 2px;width: 100%;font-size: 11px;border-style: groove;border-width: 1px;">
				<tr>
					<td>Voyage Duration : </td>
					<td><?php echo $voyDur; ?></td>
					<td>Voyage Surplus : </td>
					<td><?php echo $voySurp; ?> p.d</td>
				</tr>
				<tr>
					<td>TCE is Grossed up by : </td>
					<td><?php echo $tceGrosUp; ?> %</td>
					<td>TCE : </td>
					<td><?php echo $tce; ?> p.d</td>
				</tr>
			</table>
			<table style="margin-top: 2px;width: 100%;font-size: 11px;border-style: groove;border-width: 1px;">
				<tr>
					<td align="center"><?php echo $ttlRound; ?> Round Voyages per 365 Consecutive Days(or per year)</td>
				</tr>
				<tr>
					<td align="center">Total Cargo Carried per Year Equal to <?php echo $ttlCargo; ?> MT</td>
				</tr>
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