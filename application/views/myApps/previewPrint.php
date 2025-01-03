<?php
$nama_dokumen = "ICT_Tools_Request";
require("pdf/mpdf60/mpdf.php");
$mpdf = new mPDF('utf-8', 'A4');
ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ICT Tools and Equipment Request</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
    }

    .title {
        text-align: right;
        font-size: 8px;
        margin-top: 10px;
    }

    table {
        width: 100%;
        margin-top: 10px;
        border-collapse: collapse;
    }

    .detail {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }

    th,
    td {
        border: 1px solid black;
        padding: 5px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    .approval {
        text-align: center;
    }

    .approval td {
        height: 150px;
        width: 300px;
        vertical-align: top;
    }

    .footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
    }

    .footer .note {
        margin-bottom: 10px;
        height: 100px;
    }

    .footer .approval {
        margin-top: 100px;
    }

    .note-box {
        border: 1px solid black;
        padding: 10px;
        min-height: 50px;
        margin-bottom: 10px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }


    .signature-box {
        border: 1px solid black;
        height: 100px;
        width: 200px;
        display: block;
        margin: 0 auto;
    }

    .name-acknowledge,
    .name-approved {
        margin-bottom: 150px;
        height: 100px;
        font-size: 14px;
        text-align: center;
    }
    </style>
</head>

<body>
    <div class="header">
        <div>
            <?php echo $imageLogo; ?>
        </div>
        <div class="title">
            <h1>ICT TOOLS AND EQUIPMENT REQUEST</h1>
            <h2>PERMINTAAN ALAT DAN PERLENGKAPAN ICT</h2>
        </div>
    </div>

    <table>
        <tr>
            <th>Project Reference / Referensi Proyek</th>
            <td><?php echo isset($form->project_reference) ? $form->project_reference : 'N/A'; ?></td>
        </tr>
        <tr>
            <th>Purpose / Kebutuhan</th>
            <td><?php echo isset($form->purpose) ? $form->purpose : 'N/A'; ?></td>
        </tr>
        <tr>
            <th>Divisi / Divisi</th>
            <td><?php echo isset($form->divisi) ? $form->divisi : 'N/A'; ?></td>
        </tr>
        <tr>
            <th>Departement / Departement</th>
            <td><?php echo isset($form->department) ? $form->department : 'N/A'; ?></td>
        </tr>
        <tr>
            <th>Company / Perusahaan</th>
            <td><?php echo isset($form->company) ? $form->company : 'N/A'; ?></td>
        </tr>
        <tr>
            <th>Location / Lokasi</th>
            <td><?php echo isset($form->location) ? $form->location : 'N/A'; ?></td>
        </tr>
        <tr>
            <th>Required Date</th>
            <td><?php echo isset($form->required_date) ? $form->required_date : 'N/A'; ?></td>
        </tr>
    </table>

    <table class="detail">
        <thead>
            <tr>
                <th>DESKRIPSI</th>
                <th>TYPE / BRAND</th>
                <th>QUANTITY</th>
                <th>REASON / ALASAN</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($form_details as $detail): ?>
            <tr>
                <td><?php echo isset($detail->description) ? $detail->description : ''; ?></td>
                <td><?php echo isset($detail->type) ? $detail->type : ''; ?></td>
                <td><?php echo isset($detail->quantity) ? $detail->quantity : ''; ?></td>
                <td><?php echo isset($detail->reason) ? $detail->reason : ''; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        <div class="note">
            <strong>Note:</strong>
            <div class="note-box">
                <?php echo isset($detail->note) ? $detail->note : ''; ?>
            </div>
        </div>

        <div class="approval">
            <table>
                <tr>
                    <td>Proposed by<br>
                        <div class="signature-box">
                            <?php echo $qrCode; ?>
                        </div>
                        <div class="name">
                            <?php echo isset($form->request_name) ? $form->request_name : 'N/A'; ?>
                        </div>
                    </td>
                    <td>Acknowledge by<br>
                        <div class="signature-box">
                            <?php echo $qrCodeAcknowledge; ?>
                        </div>
                        <div class="name-acknowledge">
                            <?php echo isset($nameKadept) ? $nameKadept : 'N/A'; ?>
                        </div>
                    </td>
                    <td>Approved by<br>
                        <div class="signature-box">
                            <?php echo $qrCodeApprove; ?>
                        </div>
                        <div class="name-approved">
                            <?php echo isset($nameKadiv) ? $nameKadiv : 'N/A'; ?>
                        </div>
                    </td>

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
$mpdf->Output($nama_dokumen . ".pdf", 'I');
exit;
?>