<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICT Tools and Equipment Request</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
    }

    .header {
        font-weight: bold;
        text-align: center;
    }

    .note,
    .approval {
        margin-top: 20px;
    }

    .approval td {
        height: 50px;
        text-align: center;
    }
    </style>
</head>

<body>

    <h1 class="header">ICT TOOLS AND EQUIPMENT REQUEST</h1>
    <h2 class="header">PERMINTAAN ALAT DAN PERLENGKAPAN ICT</h2>

    <table>
        <tr>
            <th>Project Reference<br>Referensi Proyek</th>
            <td><?= isset($form->project_reference) ? $form->project_reference : 'N/A'; ?></td>
        </tr>
        <tr>
            <th>Purpose<br>Kebutuhan</th>
            <td><?= isset($form->purpose) ? $form->purpose : 'N/A'; ?></td>
        </tr>
        <tr>
            <th>Required Date<br>Tanggal Dibutuhkan</th>
            <td><?= isset($form->required_date) ? $form->required_date : 'N/A'; ?></td>
        </tr>
        <tr>
            <th>Department / Company<br>Departemen / Perusahaan</th>
            <td><?= isset($form->company) ? $form->company : 'N/A'; ?></td>
        </tr>
        <tr>
            <th>Location<br>Lokasi</th>
            <td><?= isset($form->location) ? $form->location : 'N/A'; ?></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>[CODE] DESCRIPTION<br>[KODE] DESKRIPSI</th>
                <th>TYPE / BRAND<br>TYPE / MEREK</th>
                <th>QTY</th>
                <th>PURPOSE / REASON<br>KEGUNAAN / ALASAN</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($form_details as $detail): ?>
            <tr>
                <td><?= isset($detail->description) ? $detail->description : 'N/A'; ?></td>
                <td><?= isset($detail->type) ? $detail->type : 'N/A'; ?></td>
                <td><?= isset($detail->quantity) ? $detail->quantity : 'N/A'; ?></td>
                <td><?= isset($detail->reason) ? $detail->reason : 'N/A'; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="note">
        <strong>Note:</strong><br>
        <?= isset($form->note) ? $form->note : 'N/A'; ?>
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

    <button onclick="window.print()">Print</button>

</body>

</html>