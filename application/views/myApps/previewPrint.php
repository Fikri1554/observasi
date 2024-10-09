<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ICT Tools and Equipment Request</title>
    <script src="<?php echo base_url();?>assets/js/jquery-1.8.3.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
    function acknowledgeData(idForm) {
        $.ajax({
            url: '<?php echo base_url('form/acknowledgeData'); ?>',
            type: "POST",
            data: {
                id: idForm
            },
            success: function(response) {
                const res = JSON.parse(response);
                if (res.status === 'success') {
                    const statusElement = document.getElementById("status_" +
                        idForm);
                    if (statusElement) {
                        statusElement.innerHTML = "Waiting Approval";
                        alert("Status successfully updated to waiting Approval");
                        reloadPage();
                    }

                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    function approveData(idForm) {
        $.ajax({
            url: '<?php echo base_url('form/approveData'); ?>',
            type: "POST",
            data: {
                id: idForm
            },
            success: function(response) {
                const res = JSON.parse(response);
                if (res.status === 'success') {
                    const statusElement = document.getElementById("status_" +
                        idForm);
                    if (statusElement) {
                        statusElement.innerHTML = "Approve Success";
                        alert("Request has been approve!");
                        reloadPage();
                    }

                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    function reloadPage() {
        window.location = "<?php echo base_url('form/getDataForm');?>";
    }
    </script>
    <style>
    @media print {
        body {
            width: 210mm;
            height: 297mm;
            margin: 0;
            padding: 10mm;
            box-sizing: border-box;
        }

        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }

        .approval td {
            height: 150px;
            width: 200px;
        }

        .signature-box {
            border: 1px solid black;
            height: 100px;
            width: 100px;
        }
    }


    .title {
        text-align: right;
        font-size: 8px;
        margin-top: 10px;
    }

    table {
        width: 100%;
        max-width: 100%;
        margin-top: 10px;
        border-collapse: collapse;
    }

    .detail {
        width: 100%;
        max-width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }

    th,
    td {
        border: 1px solid black;
        padding: 3px;
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
        width: 200px;
    }

    .footer .note {
        margin-bottom: 10px;
        height: 100px;
    }

    .footer .approval {
        margin-top: 10px;
    }

    .note-box {
        border: 1px solid black;
        padding: 5px;
        min-height: 50px;
        margin-bottom: 10px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
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
    </table>

    <table class="detail">
        <thead>
            <tr>
                <th>DESKRIPSI</th>
                <th>TYPE / BRAND</th>
                <th>QTY</th>
                <th>REASON / ALASAN</th>
                <th>REQUIRED DATE</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($form_details as $detail): ?>
            <tr>
                <td><?php echo isset($detail->description) ? $detail->description : ''; ?></td>
                <td><?php echo isset($detail->type) ? $detail->type : ''; ?></td>
                <td><?php echo isset($detail->quantity) ? $detail->quantity : ''; ?></td>
                <td><?php echo isset($detail->reason) ? $detail->reason : ''; ?></td>
                <td><?php echo isset($detail->required_date) && $detail->required_date != '0000-00-00' ? $detail->required_date : ''; ?>
                </td>
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
                        <?php echo isset($form->request_name) ? $form->request_name : 'N/A'; ?>
                    </td>
                    <td>Acknowledge by<br>
                        <div class="signature-box">
                            <?php echo $kadept; ?>
                        </div>
                        <?php echo isset($nameKadept) ? $nameKadept : 'N/A'; ?>
                    </td>
                    <td>Approved by<br>
                        <div class="signature-box">
                            <?php echo $kadiv; ?>
                        </div>
                        <?php echo isset($nameKadiv) ? $nameKadiv : 'N/A'; ?>
                    </td>
                </tr>
            </table>
        </div>
        <!-- Buttons for Acknowledge and Approve -->
        <div style="margin-top: 20px;">
            <button onclick="acknowledgeData(<?php echo $form->id; ?>);" class="btn btn-primary btn-xs" type="button"
                style="margin: 5px;">
                <i class="fa fa-print"></i> Acknowledge
            </button>
            <button onclick="approveData(<?php echo $form->id; ?>);" class="btn btn-success btn-xs" type="button"
                style="margin: 5px;">
                <i class="fa fa-check"></i> Approve
            </button>
        </div>

    </div>
</body>

</html>