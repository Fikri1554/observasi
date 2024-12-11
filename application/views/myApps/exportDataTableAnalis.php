<?php
$nama_dokumen = "FreightCostDetail";
require("pdf/mpdf60/mpdf.php");
$mpdf = new mPDF('utf-8','A4');
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
			<table style="width:100%;font-size:12px;margin-top:5px;" border="0" cellspacing="0">
				<thead>
				<tr style="background-color: #ba5500;">
					<th style="vertical-align:middle;text-align:center;color:#FFF;height:20px;" colspan="3"><?php echo strtoupper($titleNya); ?></th>
				</tr>
				<tr style="background-color: #5C2F02;">
					<th style="vertical-align:middle;text-align:center;color:#FFF;height:20px;width:8%;">NO</th>
					<th style="vertical-align:middle;text-align:center;color:#FFF;width:52%;">ANALISA VOYAGE</th>
					<th style="vertical-align:middle;text-align:center;color:#FFF;width:40%;">TOTAL</th>
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