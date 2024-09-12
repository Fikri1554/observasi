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

    h1,
    h2 {
        text-align: right;
        font-size: 16px;
        margin-bottom: 5px;
    }

    table {
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
        height: 50px;
    }

    .footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
    }

    .footer .note {
        margin-bottom: 10px;
    }

    .footer .approval {
        margin-top: 10px;
    }

    .note-box {
        border: 1px solid black;
        padding: 10px;
        min-height: 50px;
        margin-bottom: 10px;
    }

    /* Add layout for the logo and header */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    </style>
</head>

<body>
    <div class="header">
        <!-- Display the company logo on the left -->
        <div>
            <img src="<?php echo base_url('application/assets/img/' . $company_logo); ?>" alt="Company Logo"
                height="100">
        </div>

        <!-- Titles on the right -->
        <div>
            <h1>ICT TOOLS AND EQUIPMENT REQUEST</h1>
            <h2>PERMINTAAN ALAT DAN PERLENGKAPAN ICT</h2>
        </div>
    </div>

    <!-- Main Table -->
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
            <th>Required Date / Tanggal Dibutuhkan</th>
            <td><?php echo isset($form->required_date) ? $form->required_date : 'N/A'; ?></td>
        </tr>
        <tr>
            <th>Company / Perusahaan</th>
            <td><?php echo isset($form->company) ? $form->company : 'N/A'; ?></td>
        </tr>
        <tr>
            <th>Location / Lokasi</th>
            <td><?php echo isset($form->location) ? $form->location : 'N/A'; ?></td>
        </tr>
    </table>

    <!-- Details Table -->
    <table>
        <thead>
            <tr>
                <th>DESKRIPSI</th>
                <th>TYPE / BRAND</th>
                <th>QTY</th>
                <th>REASON / ALASAN</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($form_details as $detail): ?>
            <tr>
                <td><?php echo isset($detail->description) ? $detail->description : 'N/A'; ?></td>
                <td><?php echo isset($detail->type) ? $detail->type : 'N/A'; ?></td>
                <td><?php echo isset($detail->quantity) ? $detail->quantity : 'N/A'; ?></td>
                <td><?php echo isset($detail->reason) ? $detail->reason : 'N/A'; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Footer Section -->
    <div class="footer">
        <div class="note">
            <strong>Note:</strong>
            <div class="note-box">
                <?php echo isset($form->note) ? $form->note : 'N/A'; ?>
            </div>
        </div>

        <div class="approval">
            <table>
                <tr>
                    <td>Proposed by<br>........................</td>
                    <td>Acknowledge by<br>........................</td>
                    <td>Approved by<br>........................</td>
                </tr>
                <tr>
                    <td>Date<br>........................</td>
                    <td>Date<br>........................</td>
                    <td>Date<br>........................</td>
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