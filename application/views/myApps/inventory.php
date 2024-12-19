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

    function loadInventoryData() {
        $.ajax({
            url: "<?php echo base_url('inventory/getInventoryData'); ?>",
            type: "GET",
            dataType: "json",
            success: function(data) {
                if (data.length > 0) {
                    const companyDropdown = $("#slcCompany");
                    const divisiDropdown = $("#slcDivisi");
                    const locationInput = $("#txtlocation");

                    companyDropdown.html("<option value=''>- Select -</option>");
                    divisiDropdown.html("<option value=''>- Select -</option>");

                    data.forEach(item => {
                        companyDropdown.append(
                            `<option value="${item.company}" data-cmpcode="${item.company}">${item.company}</option>`
                        );
                        divisiDropdown.append(
                            `<option value="${item.division}">${item.division}</option>`
                        );
                    });

                    if (data[0].location) {
                        locationInput.val(data[0].location);
                    } else {
                        locationInput.val("N/A");
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching inventory data:", error);
            }
        });
    }

    $("[data-target='#idInventoryModal']").on("click", function() {
        loadInventoryData();
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
                                        <!-- Form Utama -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="inventoryContainer">
                                                    <div class="row inventoryRow">
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="inventory-group">
                                                                <label for="slcCompany"><b><u>Company :</u></b></label>
                                                                <select id="slcCompany" class="form-control input-sm"
                                                                    onchange="generateIDName()">
                                                                    <?php echo $getOptCompany; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="inventory-group">
                                                                <label for="idname"><b><u>ID Name :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="idname" name="idname[]" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="inventory-group">
                                                                <label for="jenisperangkat"><b><u>Jenis Perangkat
                                                                            :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="jenisperangkat" name="jenisperangkat[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="inventory-group">
                                                                <label for="ram"><b><u>RAM
                                                                            :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="ram" name="ram[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="inventory-group">
                                                                <label for="slcDivisi"><b><u>Divisi :</u></b></label>
                                                                <select id="slcDivisi" class="form-control input-sm">
                                                                    <?php echo $getOptMstDivisi; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="inventory-group">
                                                                <label for="txtlocation"><b><u>Location
                                                                            :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="txtlocation" name="txtlocation[]" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="inventory-group">
                                                                <label for="harddisk"><b><u>Harddisk
                                                                            :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="harddisk" name="harddisk[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="inventory-group">
                                                                <label for="windows"><b><u>Windows
                                                                            :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="windows" name="windows[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="inventory-group">
                                                                <label for="winserial"><b><u>Win Serial
                                                                            :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="winserial" name="winserial[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="inventory-group">
                                                                <label for="user"><b><u>User
                                                                            :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="user" name="user[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="inventory-group">
                                                                <label for="tanggalbeli"><u>Tanggal
                                                                        Beli:</u></label>
                                                                <input type="text" name="tanggalbeli[]"
                                                                    class="form-control input-sm" id="tanggalbeli"
                                                                    autocomplete="off">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="inventory-group">
                                                                <label for="historyuser"><b><u>History User
                                                                            :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="historyuser" name="historyuser[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="inv entory-group">
                                                                <label for="po"><b><u>PO
                                                                            :</u></b></label>
                                                                <input type="text" class="form-control input-sm" id="po"
                                                                    name="po[]">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="inventory-group">
                                                                <label for="status"><b><u>Status
                                                                            :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="status" name="status[]">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" id="txtIdForm" value="">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" id="saveFormRequest">Save
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