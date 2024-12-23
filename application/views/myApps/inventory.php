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
                'company', 'init_cmp', 'idname', 'divisi', 'txtlocation', 'jenisperangkat',
                'ram', 'harddisk', 'windows', 'winserial', 'user', 'tanggalbeli',
                'historyuser', 'po', 'status', 'brand', 'port', 'size', 'txtIdInventory'
            ];

            fields.forEach(function(field) {
                var value = $("#" + field).val();
                formData.append(field, value || '');
            });


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
        const slcCompany = document.getElementById("slcCompany");
        const selectedOption = slcCompany.options[slcCompany.selectedIndex];
        const cmpcode = selectedOption.getAttribute("data-cmpcode");
        const idnameInput = document.getElementById("idname");

        if (cmpcode) {
            const randomNumber = Math.floor(10 + Math.random() * 200);
            const idName = `${cmpcode}-${randomNumber}`;
            idnameInput.value = idName;
        } else {
            idnameInput.value = "";
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const slcJenisPerangkat = document.getElementById("slcJenisPerangkat");
        const inputsToToggle = {
            pc: ["brand", "port", "size"], // PC SERVER, PC DESKTOP, LAPTOP
            network: ["ram", "harddisk", "windows", "winserial", "user", "historyuser",
                "status"
            ], // ROUTER, SWITCH, ACCESS POINT
            storage: ["ram", "harddisk", "windows", "winserial", "user", "historyuser",
                "status"
            ] // HARDDISK, MEMORY
        };

        slcJenisPerangkat.addEventListener("change", () => {
            const selectedValue = slcJenisPerangkat.value.toUpperCase();


            const allInputs = [
                "ram", "harddisk", "windows", "winserial", "user", "historyuser", "status",
                "brand", "port", "size"
            ];

            // Tentukan input yang harus disembunyikan berdasarkan pilihan
            let inputsToHide = [];
            if (["PC SERVER", "PC DESKTOP", "LAPTOP"].includes(selectedValue)) {
                inputsToHide = inputsToToggle.pc;
            } else if (["ROUTER", "SWITCH", "ACCESS POINT"].includes(selectedValue)) {
                inputsToHide = inputsToToggle.network;
            } else if (["HARDDISK", "MEMORY"].includes(selectedValue)) {
                inputsToHide = inputsToToggle.storage;
            }

            // Tentukan input yang harus ditampilkan
            const inputsToShow = allInputs.filter(input => !inputsToHide.includes(input));

            // Sembunyikan input yang sesuai
            inputsToHide.forEach(id => {
                const input = document.getElementById(id);
                const label = document.querySelector(`label[for='${id}']`);
                if (input) input.style.display = "none";
                if (label) label.style.display = "none";
            });

            // Tampilkan input yang sesuai
            inputsToShow.forEach(id => {
                const input = document.getElementById(id);
                const label = document.querySelector(`label[for='${id}']`);
                if (input) input.style.display = "block";
                if (label) label.style.display = "block";
            });
        });
    });
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
                                                                    <option value="">Select Divisi</option>
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
                                                                <label for="ram"><b><u>RAM :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="ram" name="ram[]">
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
                                                        <div class="col-md-6">
                                                            <div class="inventory-group">
                                                                <label for="size"><b><u>Size :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="size" name="size[]">
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
                                <table class="table table-border table-striped table-bordered"
                                    style="border-collapse: collapse; width: 100%; font-size: 12px;">
                                    <thead>
                                        <tr style="background-color: #ba5500; color: #FFF;">
                                            <th
                                                style="vertical-align: middle; text-align: center; padding: 8px; border: 1px solid #ddd;">
                                                No</th>
                                            <th
                                                style="vertical-align: middle; text-align: center; padding: 8px; border: 1px solid #ddd;">
                                                ID Name</th>
                                            <th
                                                style="vertical-align: middle; text-align: center; padding: 8px; border: 1px solid #ddd;">
                                                Jenis Perangkat</th>
                                            <th
                                                style="vertical-align: middle; text-align: center; padding: 8px; border: 1px solid #ddd;">
                                                RAM</th>
                                            <th
                                                style="vertical-align: middle; text-align: center; padding: 8px; border: 1px solid #ddd;">
                                                Company</th>
                                            <th
                                                style="vertical-align: middle; text-align: center; padding: 8px; border: 1px solid #ddd;">
                                                Divisi</th>
                                            <th
                                                style="vertical-align: middle; text-align: center; padding: 8px; border: 1px solid #ddd;">
                                                Location</th>
                                            <th
                                                style="vertical-align: middle; text-align: center; padding: 8px; border: 1px solid #ddd;">
                                                Harddisk</th>
                                            <th
                                                style="vertical-align: middle; text-align: center; padding: 8px; border: 1px solid #ddd;">
                                                Windows</th>
                                            <th
                                                style="vertical-align: middle; text-align: center; padding: 8px; border: 1px solid #ddd;">
                                                Win Serial</th>
                                            <th
                                                style="vertical-align: middle; text-align: center; padding: 8px; border: 1px solid #ddd;">
                                                User</th>
                                            <th
                                                style="vertical-align: middle; text-align: center; padding: 8px; border: 1px solid #ddd;">
                                                Tanggal Beli</th>
                                            <th
                                                style="vertical-align: middle; text-align: center; padding: 8px; border: 1px solid #ddd;">
                                                History User</th>
                                            <th
                                                style="vertical-align: middle; text-align: center; padding: 8px; border: 1px solid #ddd;">
                                                PO</th>
                                            <th
                                                style="vertical-align: middle; text-align: center; padding: 8px; border: 1px solid #ddd;">
                                                Status</th>
                                            <th
                                                style="vertical-align: middle; text-align: center; padding: 8px; border: 1px solid #ddd;">
                                                Action</th>
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