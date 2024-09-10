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
			<table style="width: 100%;font-size: 11px;">
				<tr>
					<td>Vessel</td>
					<td>:</td>
					<td><?php echo $vessel; ?></td>
					<td>Loading/Disch Port</td>
					<td>:</td>
					<td><?php echo $lPort."/".$dPort; ?></td>
				</tr>
				<tr>
					<td>Cargo Type</td>
					<td>:</td>
					<td><?php echo $cargoType; ?></td>
					<td>Loading/Disch Rate</td>
					<td>:</td>
					<td><?php echo number_format($lRate,2)."/".number_format($dRate,2); ?></td>
				</tr>
			</table>
			<table style="width: 100%;font-size: 11px;margin-top:10px;" border="1">
				<thead>
					<tr>
						<td colspan="8" align="center" style="background-color:#d56b03;color:#FFF;height: 30px;">CARGO SIZE</td>
					</tr>
				</thead>
				<tbody id="idBody">
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