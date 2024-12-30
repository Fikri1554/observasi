<?php $this->load->view('myApps/menu'); ?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="andhika group">
    <link rel="icon" href="<?php echo base_url("assets/img/andhika.gif"); ?>">
    <script>
    $(document).ready(function() {
        $("#tanggalbeli").datepicker({
            dateFormat: 'yy-mm-dd',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
        });
        $("#saveInventory").click(function() {
            var formData = new FormData();
            var fields = [
                'idname', 'ram', 'harddisk', 'windows', 'winserial', 'user', 'tanggalbeli',
                'historyuser', 'po', 'status', 'processor', 'txtIdInventory'
            ];

            // Ambil nilai dari elemen input biasa
            fields.forEach(function(field) {
                var value = $("#" + field).val();
                formData.append(field, value || '');
            });

            // Ambil nilai yang dipilih dari dropdown menggunakan :selected
            formData.append('company', $('#slcCompany option:selected').val() || '');
            formData.append('divisi', $('#slcDivisi option:selected').val() || '');
            formData.append('location', $('#slcLocation option:selected').val() || '');
            formData.append('jenisperangkat', $('#slcJenisPerangkat option:selected').val() || '');
            formData.append('jenisperangkatkhusus', $('#slcJenisPerangkatKhusus option:selected')
            .val() || '');

            // Kirim data ke server melalui AJAX
            $.ajax({
                url: "<?php echo base_url('inventory/addInventory'); ?>",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert(response);
                    location.reload(); // Reload untuk memperbarui tampilan
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert("Gagal menyimpan data!");
                }
            });
        });

        $('#slcCompany').on('change', function() {
            var selectedCompany = $(this).val();

            $.ajax({
                url: "<?php echo base_url('inventory/getOptDivisiByCompany'); ?>",
                type: "POST",
                data: {
                    company: selectedCompany
                },
                success: function(response) {
                    $('#slcDivisi').html(
                        response);
                },
                error: function(xhr, status, error) {
                    console.error("Error: " + error);
                }
            });
        });

    });

    function generateIDName() {
        var slcCompany = document.getElementById("slcCompany");
        var idnameInput = document.getElementById("idname");

        if (!slcCompany || !idnameInput) {
            console.error("Element slcCompany or idname not found!");
            return;
        }

        var selectedValue = slcCompany.value.trim();

        if (selectedValue) {
            var randomNumber = Math.floor(10 + Math.random() * 200);
            var sanitizedValue = selectedValue.replace(/\s+/g, '-').toUpperCase();
            var idName = `${sanitizedValue}-${randomNumber}`;
            idnameInput.value = idName;
        } else {
            idnameInput.value = "";
            console.warn("No company selected.");
        }
    }
    </script>
</head>

<body>
    <section id="container">
        <section id="main-content">
            <section class="wrapper site-min-height" style="min-height:400px;">
                <h3>
                    <i class="fa fa-angle-right"></i> IT Inventory<span style="padding-left:20px;display:none;"
                        id="idLoading"><img src="<?php echo base_url('assets/img/loading.gif'); ?>"></span>
                </h3>
                <div class="form-panel" id="DataTableInventory">
                    <div class="row">
                        <div class="modal fade bd-example-modal-lg" id="idInventoryModal" role="dialog"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header"
                                        style="background-color:#D46D16;border-bottom:1px solid #e7e7e7">
                                        <h4 class="modal-title">Add Inventory</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="inventoryContainer">
                                                    <div class="row inventoryRow">
                                                        <div class="col-md-6">
                                                            <div class="inventory-group">
                                                                <label for="slcCompany"><b><u>Company :</u></b></label>
                                                                <select id="slcCompany" class="form-control input-sm"
                                                                    onchange="generateIDName()">
                                                                    <?php echo $getOptCompany; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="inventory-group">
                                                                <label for="idname"><b><u>ID Name :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="idname" name="idname[]" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row inventoryRow">
                                                        <div class="col-md-6">
                                                            <div class="inventory-group">
                                                                <label for="slcDivisi"><b><u>Divisi :</u></b></label>
                                                                <select id="slcDivisi" class="form-control input-sm">

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="inventory-group">
                                                                <label for="slcLocation"><b><u>Location
                                                                            :</u></b></label>
                                                                <select id="slcLocation" class="form-control input-sm">
                                                                    <?php echo $getOptLocation; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row inventoryRow">
                                                        <div class="col-md-6">
                                                            <div class="inventory-group">
                                                                <label for="slcJenisPerangkat"><b><u>Jenis Perangkat
                                                                            :</u></b></label>
                                                                <select id="slcJenisPerangkat"
                                                                    class="form-control input-sm">
                                                                    <?php echo $getOptJenisPerangkat; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="inventory-group">
                                                                <label for="slcJenisPerangkatKhusus"><b><u>Jenis
                                                                            Perangkat
                                                                            Khusus
                                                                            :</u></b></label>
                                                                <select id="slcJenisPerangkatKhusus"
                                                                    class="form-control input-sm">
                                                                    <?php echo $getOptJenisPerangkatKhusus; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row inventoryRow">
                                                        <div class="col-md-6">
                                                            <div class="inventory-group">
                                                                <label for="ram"><b><u>RAM :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="ram" name="ram[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="inventory-group">
                                                                <label for="size"><b><u>Processor :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="processor" name="size[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row inventoryRow">
                                                        <div class="col-md-6">
                                                            <div class="inventory-group">
                                                                <label for="harddisk"><b><u>Harddisk :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="harddisk" name="harddisk[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="inventory-group">
                                                                <label for="windows"><b><u>Windows :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="windows" name="windows[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row inventoryRow">
                                                        <div class="col-md-6">
                                                            <div class="inventory-group">
                                                                <label for="winserial"><b><u>Win Serial
                                                                            :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="winserial" name="winserial[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="inventory-group">
                                                                <label for="user"><b><u>User :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="user" name="user[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row inventoryRow">
                                                        <div class="col-md-6">
                                                            <div class="inventory-group">
                                                                <label for="tanggalbeli"><b><u>Tanggal Beli
                                                                            :</u></b></label>
                                                                <input type="text" name="tanggalbeli[]"
                                                                    class="form-control input-sm" id="tanggalbeli"
                                                                    autocomplete="off">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="inventory-group">
                                                                <label for="historyuser"><b><u>History User
                                                                            :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="historyuser" name="historyuser[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row inventoryRow">
                                                        <div class="col-md-6">
                                                            <div class="inventory-group">
                                                                <label for="po"><b><u>PO :</u></b></label>
                                                                <input type="text" class="form-control input-sm" id="po"
                                                                    name="po[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="inventory-group">
                                                                <label for="status"><b><u>Status :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="status" name="status[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="inventory-group">
                                                                <label for="brand"><b><u>Brand :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="brand" name="brand[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="inventory-group">
                                                                <label for="port"><b><u>Port :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="port" name="port[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" id="txtIdInventory" value="">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" id="saveInventory">Save
                                            changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2" style="margin-top: 5px;">
                            <button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="modal"
                                data-target="#idInventoryModal">
                                Add Inventory
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button type="button" id="idBtnRefresh" onclick="reloadPage();"
                                class="btn btn-success btn-sm btn-block" title="Refresh"><i
                                    class="glyphicon glyphicon-refresh"></i> Refresh</button>
                        </div>
                    </div>
                    <div class="row mt" id="idData1">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table
                                    class="table table-border table-striped table-bordered table-condensed table-advance table-hover"
                                    style="border-collapse: collapse; width: 100%; font-size: 12px;">
                                    <thead>
                                        <tr style="background-color: #ba5500; color: #FFF;">
                                            <th colspan="2"
                                                style="vertical-align: middle; text-align: center; padding: 8px;">No
                                            </th>
                                            <th style="vertical-align: middle; text-align: center; padding: 8px;">
                                                Company</th>
                                            <th style="vertical-align: middle; text-align: center; padding: 8px;">
                                                ID Name</th>
                                            <th style="vertical-align: middle; text-align: center; padding: 8px;">Divisi
                                            </th>
                                            <th style="vertical-align: middle; text-align: center; padding: 8px;">
                                                Location</th>
                                            <th style="vertical-align: middle; text-align: center; padding: 8px;">Jenis
                                                Perangkat
                                            </th>
                                            <th style="vertical-align: middle; text-align: center; padding: 8px;">Jenis
                                                Perangkat Khusus
                                            </th>
                                            <th style="vertical-align: middle; text-align: center; padding: 8px;">
                                                RAM</th>
                                            <th style="vertical-align: middle; text-align: center; padding: 8px;">
                                                Processor</th>
                                            <th style="vertical-align: middle; text-align: center; padding: 8px;">
                                                Harddisk</th>
                                            <th style="vertical-align: middle; text-align: center; padding: 8px;">
                                                Windows</th>
                                            <th style="vertical-align: middle; text-align: center; padding: 8px;">Win
                                                Serial
                                            </th>
                                            <th style="vertical-align: middle; text-align: center; padding: 8px;">
                                                User</th>
                                            <th style="vertical-align: middle; text-align: center; padding: 8px;">
                                                Tanggal Beli</th>
                                            <th style="vertical-align: middle; text-align: center; padding: 8px;">
                                                History User
                                            </th>
                                            <th style="vertical-align: middle; text-align: center; padding: 8px;">PO
                                            </th>
                                            <th style="vertical-align: middle; text-align: center; padding: 8px;">Status
                                            </th>
                                            <th style="vertical-align: middle; text-align: center; padding: 8px;">Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="idTbody">
                                        <?php echo $tr; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </section>
        </section>
    </section>
</body>

</html>