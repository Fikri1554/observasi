<?php $this->load->view('myApps/menu'); ?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="andhika group">
    <link rel="icon" href="<?php echo base_url("assets/img/andhika.gif"); ?>">
    <script type="text/javascript">
    $(document).ready(function() {
        $('#saveFormRequest').click(function() {
            var formData = new FormData();

            $('input[name^="txt"]').each(function() {
                formData.append($(this).attr('name'), $(this).val());
            });

            formData.append('slcCompany', $('#slcCompany').val());
            formData.append('slcCompanyText', $("#slcCompany option:selected").text());
            formData.append('slcDivisi', $('#slcDivisi').val());
            formData.append('slcDepartment', $('#slcDepartment').val());
            formData.append('txtIdForm', $('#txtIdForm').val());

            $.ajax({
                url: '<?php echo base_url("form/saveFormRequest"); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.includes("Insert Success")) {
                        alert(response);
                    } else if (response.startsWith("Failed =>")) {
                        alert("Gagal: " + response);
                    } else {
                        alert("Data berhasil disimpan! " + response);
                    }
                    $('#idFormModal').modal('hide');
                    reloadPage();
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    alert("Terjadi kesalahan saat mengirim data.");
                }
            });
        });
    });

    $(document).ready(function() {
        $("#btnSaveFormDetail").click(function() {
            var formData = new FormData();
            var formId = $("#txtIdForm").val();

            formData.append('id_form', formId);

            function appendData(fieldName, selector) {
                var values = $(selector).map(function() {
                    return $(this).val();
                }).get();
                formData.append(fieldName, values.join('*'));
            }

            appendData('descriptions', "input[name^='txtdescription']");
            appendData('types', "input[name^='txttype']");
            appendData('reasons', "input[name^='txtreason']");
            appendData('quantities', "input[name^='txtquantity']");
            appendData('required_dates', "input[name^='txtrequired_date']");
            appendData('notes', "input[name^='txtnote']");

            if ($("input[name^='txtdescription']").val() === "" ||
                $("input[name^='txttype']").val() === "" ||
                $("input[name^='txtreason']").val() === "" ||
                $("input[name^='txtquantity']").val() <= 0) {
                alert("Description, Type, Reason, and Quantity fields are required.");
                return false;
            }


            $("#idLoading").show();

            $.ajax({
                url: '<?php echo base_url('form/saveFormRequestDetail'); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert(response);
                    $("#idFormDetail").hide();
                    $("#idLoading").hide();
                    reloadPage();
                },
                error: function(xhr, status, error) {
                    console.error("Error: " + error);
                    $("#idLoading").hide();
                },
                dataType: 'json'
            });
        });
    });


    function editData(id) {
        $("#idLoading").show();

        $.ajax({
            url: '<?php echo base_url('form/getFormRequestDetailById'); ?>',
            type: 'POST',
            data: {
                id_form: id
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $("#txtIdEditForm").val(id);

                    $("#idFieldEditDetail").empty();

                    if (Array.isArray(response.details) && response.details.length > 0) {
                        $("#DataTableRequest").hide();
                        $("#idFormEditDetail").show(200);

                        response.details.forEach(function(detail, index) {
                            var detailForm = `
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-md-12">
                                    <legend><label id="lblForm"> Edit Request Detail#${index}</label></legend>
                                    <div class="form-row">
                                        <input type="hidden" name="txtIdDetail[]" value="${detail.id}"> 
                                        <input type="hidden" id="txtIdEditForm" name="txtIdEditForm" value="${detail.id_form}">
                                        <div class="col-md-3 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                            <div class="form-group">
                                                <label for="txtdescription_${index}"><u>Description:</u></label>
                                                <input type="text" name="txtdescription[]" class="form-control input-sm"
                                                    id="txtdescription_${index}" value="${detail.description}" placeholder="Description" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                            <div class="form-group">
                                                <label for="txttype_${index}"><u>Type:</u></label>
                                                <input type="text" name="txttype[]" class="form-control input-sm" id="txttype_${index}"
                                                    value="${detail.type}" placeholder="Type" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                            <div class="form-group">
                                                <label for="txtreason_${index}"><u>Reason:</u></label>
                                                <input type="text" name="txtreason[]" class="form-control input-sm" id="txtreason_${index}"
                                                    value="${detail.reason}" placeholder="Reason" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                            <div class="form-group">
                                                <label for="txtquantity_${index}"><u>Quantity:</u></label>
                                                <input type="text" name="txtquantity[]" class="form-control input-sm"
                                                    id="txtquantity_${index}" value="${detail.quantity}" onkeypress="return isNumber(event)"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                            <div class="form-group">
                                                <label for="txtRequiredDate_${index}"><u>Required Date:</u></label>
                                                <input type="date" name="txtrequired_date[]" class="form-control input-sm"
                                                    id="txtRequiredDate_${index}" value="${detail.required_date}" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                            <div class="form-group">
                                                <label for="txtnote_${index}"><u>Note:</u></label>
                                                <input type="text" name="txtnote[]" class="form-control input-sm"
                                                    id="txtnote_${index}" value="${detail.note}" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                            $("#idFieldEditDetail").append(detailForm);
                        });
                    } else {
                        alert("Data detail tidak ditemukan.");
                    }
                } else {
                    alert(response.message);
                }
                $("#idLoading").hide();
            },
            error: function(xhr, status, error) {
                console.error("Error: " + error);
                alert("Terjadi kesalahan saat mengambil data.");
                $("#idLoading").hide();
            }
        });
    }

    $(document).ready(function() {
        $("#saveEditDetail").click(function(e) {
            e.preventDefault();
            var formData = {
                txtIdEditForm: $("#txtIdEditForm").val(),
                txtdescription: [],
                txttype: [],
                txtreason: [],
                txtquantity: [],
                txtrequired_date: [],
                txtnote: [],
                txtIdDetail: []
            };
            $("#idFieldEditDetail .row").each(function() {
                formData.txtdescription.push($(this).find("input[name^='txtdescription[]']")
                    .val());
                formData.txttype.push($(this).find("input[name^='txttype[]']").val());
                formData.txtreason.push($(this).find("input[name^='txtreason[]']").val());
                formData.txtquantity.push($(this).find("input[name^='txtquantity[]']").val());
                formData.txtrequired_date.push($(this).find("input[name^='txtrequired_date[]']")
                    .val());
                formData.txtnote.push($(this).find("input[name^='txtnote[]']").val());
                formData.txtIdDetail.push($(this).find("input[name='txtIdDetail[]']").val());
            });
            $.ajax({
                url: '<?php echo base_url('form/saveEditDetail'); ?>',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        $("#txtIdEditForm").val(response.idForm);
                        alert("Data berhasil diperbarui!");
                        $("#idFormEditDetail").hide();
                        $("#DataTableRequest").show();
                    } else {
                        alert("Terjadi kesalahan: " + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error: " + error);
                    alert("Terjadi kesalahan saat menyimpan data.");
                }
            });
        });
    });


    $(document).on('click', '#cancelEditDetail', function() {
        $("#idFormEditDetail").hide(200);
        $("#DataTableRequest").show(200);
    });

    $(document).on('click', '#btnCancelFormDetail', function() {
        $("#idFormDetail").hide(200);
        $("#DataTableRequest").show(200);
    });




    function ViewPrint(id = '') {
        window.open('<?php echo base_url('form/previewPrint');?>' + '/' + id,
            '_blank');
    }

    function delData(id) {
        var cfm = confirm("Yakin Hapus..??");
        if (cfm) {
            $.post('<?php echo base_url("form/delData"); ?>', {
                    id: id,
                },
                function(response) {
                    if (response.status === "Delete Success..!!") {
                        alert("Data berhasil dihapus!");
                        $("#row_" + id).remove();
                        reindexTable();
                    } else {
                        alert("Gagal menghapus data: " + response.message);
                    }
                },
                "json"
            ).fail(function() {
                alert("Terjadi kesalahan pada server, coba lagi nanti.");
            });
        }
    }

    function reindexTable() {
        $("#idTbody tr").each(function(index) {
            $(this).find("td:first").text(index + 1);
        });
    }

    function sendData(idForm) {
        $.ajax({
            url: "<?php echo base_url('form/updateSubmitStatus'); ?>",
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
                        statusElement.innerHTML = "Waiting Acknowledge";
                    }
                    alert("Status successfully updated to Waiting Acknowledge.");
                    reloadPage();
                    refreshAcknowledgeTable();
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    function addDetail(id) {
        $("#DataTableRequest").hide();
        $("#idFormDetail").show(200);
        $("#txtIdForm").val(id);
    }

    function changeBtnNavigation(type) {
        if (type == "request") {
            $("#DataTableRequest").show();
            $("#DataTableAcknowledge").hide();
            $("#DataTableApproval").hide();
            $("#idFormEditDetail").hide();
        } else if (type == "acknowledge") {
            $("#DataTableRequest").hide();
            $("#DataTableAcknowledge").show();
            $("#DataTableApproval").hide();
            $("#idFormEditDetail").hide();
            $.ajax({
                url: '<?php echo base_url('form/getAcknowledgeData'); ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Response:', response);
                    let data = response.data;
                    let tbody = $('#idTbodyAcknowledge');
                    tbody.empty();
                    let no = 1;

                    if (Array.isArray(data) && data.length) {
                        data.forEach(function(item) {
                            let row = `
                            <tr id="row_${item.id}">
                                <td style="text-align:center;">${no++}</td>
                                <td style="text-align:center;">${item.project_reference}</td>
                                <td style="text-align:center;">${item.purpose}</td>
                                <td style="text-align:center;">${item.company}</td>
                                <td style="text-align:center;">${item.location}</td>
                                <td style="text-align:center;">${item.divisi}</td>
                                <td style="text-align:center;">
                                    <button onclick="acknowledgeData(${item.id});" class="btn btn-primary btn-xs" type="button" style="margin: 5px;">
                                        <i class="fa fa-print"></i> Acknowledge
                                    </button>
                                    <button onclick="ViewPrint(${item.id});" class="btn btn-success btn-xs" type="button">
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>`;
                            tbody.append(row);
                        });
                    } else {
                        tbody.append(
                            '<tr><td colspan="7" style="text-align:center;">No data available</td></tr>'
                        );
                    }

                },
                error: function(xhr, status, error) {
                    console.log('Error:', error);
                    alert('Error fetching data');
                }
            });
        } else if (type == 'approval') {
            $("#DataTableRequest").hide();
            $("#DataTableAcknowledge").hide();
            $("#DataTableApproval").show();
            $("#idFormEditDetail").hide();
            $.ajax({
                url: '<?php echo base_url('form/getApprovalData'); ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Response:', response);
                    let data = response.data;
                    let tbody = $('#idTbodyApproval');
                    tbody.empty();
                    let no = 1;

                    if (Array.isArray(data) && data.length) {
                        data.forEach(function(item) {
                            let row = `<tr>
                                        <td style="text-align:center;">${no++}</td>
                                        <td style="text-align:center;">${item.project_reference}</td>
                                        <td style="text-align:center;">${item.purpose}</td>
                                        <td style="text-align:center;">${item.company}</td>
                                        <td style="text-align:center;">${item.location}</td>
                                        <td style="text-align:center;">${item.divisi}</td>
                                        <td style="text-align:center;">
                                            <button onclick="ViewPrint(${item.id});" class="btn btn-primary btn-xs" type="button"><i class="fa fa-eye"></i> View</button>
                                        </td>
                                    </tr>`;
                            tbody.append(row);
                        });
                    } else {

                        tbody.append(
                            '<tr><td colspan="7" style="text-align:center;">No data available</td></tr>'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', error);
                    alert('Error fetching data');
                }
            });
        }
    }

    $(document).ready(function() {
        const departmentMapping = {
            "BOD / BOC": ["Non Department", "PA (Personal Assistant)"],
            "CORPORATE FINANCE, STRATEGY & COMPLIANCE": ["Non Department"],
            "DRY BULK COMMERCIAL, OPERATION & AGENCY": ["Operation", "COMMERCIAL & CHARTERING", "Agency"],
            "FINANCE": ["Finance", "Accounting", "Tax"],
            "HUMAN CAPITAL & GA": ["HR", "GA"],
            "NON DIVISION": ["Secretary"],
            "OFFICE OPERATION": ["IT", "Legal", "Procurement", "AGENCY & BRANCH"],
            "OIL & GAS COMMERCIAL & OPERATION": ["Commercial", "Operation"],
            "SHIP MANAGEMENT": ["Owner Superintendent (Technical)", "Crewing", "QHSE", "AGENCY & BRANCH"]
        };

        $('#slcDivisi').change(function() {
            let selectedDivision = $(this).val();
            console.log("Selected Division: " + selectedDivision);
            let departmentSelect = $('#slcDepartment');

            departmentSelect.empty();
            departmentSelect.append('<option value="">- Select Department -</option>');

            if (departmentMapping[selectedDivision]) {
                departmentMapping[selectedDivision].forEach(function(department) {
                    console.log("Adding department: " + department);
                    departmentSelect.append('<option value="' + department + '">' + department +
                        '</option>');
                });
            } else {
                console.log("No departments found for this division");
            }
        });
    });


    $(document).ready(function() {
        function updateButtonVisibility() {
            $('.btnRemoveRow').hide();
            if ($('.detailRow').length > 1) {
                $('.btnRemoveRow').show();
            }
        }

        $('#idFieldDetail').on('click', '.btnAddRow', function() {
            var $clone = $(this).closest('.detailRow').clone();
            $clone.find('input').val('');
            $('#idFieldDetail').append($clone);

            updateButtonVisibility();
        });

        $('#idFieldDetail').on('click', '.btnRemoveRow', function() {
            if ($('.detailRow').length > 1) {
                $(this).closest('.detailRow').remove();
                updateButtonVisibility();
            }
        });

        updateButtonVisibility();
    });



    function reloadPage() {
        window.location = "<?php echo base_url('form/getDataForm');?>";
    }
    </script>
</head>

<body>
    <section id="container">
        <section id="main-content">
            <section class="wrapper site-min-height" style="min-height:400px;">
                <h3>
                    <i class="fa fa-angle-right"></i> Form<span style="padding-left:20px;display:none;"
                        id="idLoading"><img src="<?php echo base_url('assets/img/loading.gif'); ?>"></span>
                </h3>
                <div class="form-panel" id="btnNav">
                    <div class="row">
                        <div class="col-md-4">
                            <button class="btn btn-primary btn btn-block" onclick="changeBtnNavigation('request');">
                                <label>Request</label>
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary btn btn-block" onclick="changeBtnNavigation('acknowledge');">
                                <label>Acknowledge</label>
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary btn btn-block" onclick="changeBtnNavigation('approval');">
                                <label>Approval</label>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="form-panel" id="DataTableRequest" style="display: none;">
                    <h3>Request</h3>
                    <div class="row">
                        <div class="modal fade bd-example-modal-lg" id="idFormModal" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Add Request</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="requestContainer">
                                                    <div class="row requestRow">
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="txtprojectReference"><b><u>Project Reff
                                                                            :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="txtprojectReference" name="txtprojectReference"
                                                                    value="">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="txtpurpose"><b><u>Purpose :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="txtpurpose" name="txtpurpose">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="slcCompany"><b><u>Company :</u></b></label>
                                                                <select id="slcCompany" class="form-control input-sm">
                                                                    <?php echo $getOptCompany; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="txtlocation"><b><u>Location
                                                                            :</u></b></label>
                                                                <input type="text" class="form-control input-sm"
                                                                    id="txtlocation" name="txtlocation">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="slcDivisi"><b><u>Divisi :</u></b></label>
                                                                <select id="slcDivisi" class="form-control input-sm">
                                                                    <?php echo $getOptMstDivisi; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="slcDepartment"><b><u>Department
                                                                            :</u></b></label>
                                                                <select id="slcDepartment"
                                                                    class="form-control input-sm">
                                                                    <option value="">- Select Department -</option>
                                                                </select>
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
                                data-target="#idFormModal">
                                Add Request
                            </button>
                        </div>
                        <div class="col-md-2" style="margin-top:5px;">
                            <button type="button" id="idBtnSearch" class="btn btn-info btn-sm btn-block"
                                title="Search"><i class="glyphicon glyphicon-ok"></i> Search</button>
                        </div>
                        <div class="col-md-2" style="margin-top: 5px;">
                            <button type="button" id="idBtnRefresh" onclick="reloadPage();"
                                class="btn btn-success btn-sm btn-block" title="Refresh"><i
                                    class="glyphicon glyphicon-refresh"></i> Refresh</button>
                        </div>
                    </div>
                    <div class="row mt" id="idData1">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table
                                    class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
                                    <thead>
                                        <tr style="background-color: #ba5500;color: #FFF;">
                                            <th colspan="2"
                                                style="vertical-align: middle; width:3%;text-align:center;padding: 10px;">
                                                No</th>
                                            <th style="vertical-align: middle; width:20%;text-align:center;">
                                                Project Refference
                                            </th>
                                            <th style="vertical-align: middle; width:10%;text-align:center;">
                                                Purpose
                                            </th>
                                            <th style="vertical-align: middle; width:15%;text-align:center;">
                                                Company
                                            </th>
                                            <th style="vertical-align: middle; width:20%;text-align:center;">
                                                Location
                                            </th>
                                            <th style="vertical-align: middle; width:20%;text-align:center;">
                                                Divisi
                                            </th>
                                            <th style="vertical-align: middle; width:20%;text-align:center;">
                                                Status
                                            </th>
                                            <th style="vertical-align: middle; width:20%;text-align:center;">
                                                Action
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

                <div class="form-panel" id="DataTableAcknowledge" style="display: none;">
                    <h3>Acknowledge</h3>
                    <div class="row mt" id="idData1">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table
                                    class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
                                    <thead>
                                        <tr style="background-color: #ba5500;color: #FFF;">
                                            <th
                                                style="vertical-align: middle; width:3%;text-align:center;padding: 10px;">
                                                No</th>
                                            <th style="vertical-align: middle; width:20%;text-align:center;">
                                                Project Refference
                                            </th>
                                            <th style="vertical-align: middle; width:10%;text-align:center;">
                                                Purpose
                                            </th>
                                            <th style="vertical-align: middle; width:15%;text-align:center;">
                                                Company
                                            </th>
                                            <th style="vertical-align: middle; width:20%;text-align:center;">
                                                Location
                                            </th>
                                            <th style="vertical-align: middle; width:20%;text-align:center;">
                                                Divisi
                                            </th>
                                            <th style="vertical-align: middle; width:20%;text-align:center;">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="idTbodyAcknowledge">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-panel" id="DataTableApproval" style="display: none;">
                    <h3>Approval</h3>
                    <div class="row mt" id="idData1">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table
                                    class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
                                    <thead>
                                        <tr style="background-color: #ba5500;color: #FFF;">
                                            <th
                                                style="vertical-align: middle; width:3%;text-align:center;padding: 10px;">
                                                No</th>
                                            <th style="vertical-align: middle; width:20%;text-align:center;">
                                                Project Refference
                                            </th>
                                            <th style="vertical-align: middle; width:10%;text-align:center;">
                                                Purpose
                                            </th>
                                            <th style="vertical-align: middle; width:15%;text-align:center;">
                                                Company
                                            </th>
                                            <th style="vertical-align: middle; width:20%;text-align:center;">
                                                Location
                                            </th>
                                            <th style="vertical-align: middle; width:20%;text-align:center;">
                                                Divisi
                                            </th>
                                            <th style="vertical-align: middle; width:20%;text-align:center;">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="idTbodyApproval">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-panel" id="idFormDetail" style="display:none;">
                    <div id="idFieldDetail">
                        <div class="row" style="margin-bottom: 15px;">
                            <div class="col-md-12">
                                <legend><label id="lblForm"> Add Request Detail</label></legend>
                                <div class="detailRow">
                                    <div class="col-md-2 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                        <div class="form-group">
                                            <label for="txtdescription"><u>Description:</u></label>
                                            <input type="text" name="txtdescription[]" class="form-control input-sm"
                                                id="txtdescription" placeholder="Description" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                        <div class="form-group">
                                            <label for="txttype"><u>Type:</u></label>
                                            <input type="text" name="txttype[]" class="form-control input-sm"
                                                id="txttype" placeholder="Type" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                        <div class="form-group">
                                            <label for="txtreason"><u>Reason:</u></label>
                                            <input type="text" name="txtreason[]" class="form-control input-sm"
                                                id="txtreason" placeholder="Reason" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                        <div class="form-group">
                                            <label for="txtquantity"><u>Quantity:</u></label>
                                            <input type="text" name="txtquantity[]" class="form-control input-sm"
                                                id="txtquantity" value="0" onkeypress="return isNumber(event)"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                        <div class="form-group">
                                            <label for="txtRequiredDate"><u>Required Date:</u></label>
                                            <input type="date" name="txtrequired_date[]" class="form-control input-sm"
                                                id="txtRequiredDate" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                        <div class="form-group">
                                            <label for="txtnote"><u>Note:</u></label>
                                            <input type="text" name="txtnote[]" class="form-control input-sm"
                                                id="txtnote" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-xs-2">
                                        <button type="button" class="btn btn-primary btn-xs btnAddRow"
                                            style="margin-top: 25px;">
                                            <i class="glyphicon glyphicon-plus"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-xs btnRemoveRow"
                                            style="margin-top: 25px; display:none;">
                                            <i class="glyphicon glyphicon-minus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group" align="center">
                                <input type="hidden" name="txtIdForm" id="txtIdForm" value="">
                                <button id="btnSaveFormDetail" class="btn btn-primary btn-sm" name="btnSave"
                                    title="Save">
                                    <i class="fa fa-check-square-o"></i> Save
                                </button>
                                <button id="btnCancelFormDetail" class="btn btn-danger btn-sm" name="btnCancel"
                                    title="Cancel">
                                    <i class="fa fa-ban"></i> Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-panel" id="idFormEditDetail" style="display:none;">
                    <div id="idFieldEditDetail">
                        <div class="row" style="margin-bottom: 15px;">
                            <div class="col-md-12">
                                <legend><label id="lblForm"> Edit Request Detail</label></legend>
                                <div class="form-row">
                                    <input type="hidden" id="txtIdEditForm" name="txtIdEditForm" value="">
                                    <div class="col-md-3 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                        <div class="form-group">
                                            <label for="txtdescription"><u>Description:</u></label>
                                            <input type="text" name="txtdescription" class="form-control input-sm"
                                                id="txtdescription" placeholder="Description" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                        <div class="form-group">
                                            <label for="txttype"><u>Type:</u></label>
                                            <input type="text" name="txttype" class="form-control input-sm" id="txttype"
                                                placeholder="Type" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                        <div class="form-group">
                                            <label for="txtreason"><u>Reason:</u></label>
                                            <input type="text" name="txtreason" class="form-control input-sm"
                                                id="txtreason" placeholder="Reason" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                        <div class="form-group">
                                            <label for="txtquantity"><u>Quantity:</u></label>
                                            <input type="text" name="txtquantity" class="form-control input-sm"
                                                id="txtquantity" value="0" onkeypress="return isNumber(event)"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                        <div class="form-group">
                                            <label for="txtRequiredDate"><u>Required Date:</u></label>
                                            <input type="date" name="txtrequired_date" class="form-control input-sm"
                                                id="txtRequiredDate" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                        <div class="form-group">
                                            <label for="txtnote"><u>Note:</u></label>
                                            <input type="text" name="txtnote" class="form-control input-sm" id="txtnote"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group" align="center">
                                <button id="saveEditDetail" class="btn btn-primary btn-sm" name="btnSave" title="Save">
                                    <i class="fa fa-check-square-o"></i> Save
                                </button>
                                <button id="cancelEditDetail" class="btn btn-danger btn-sm" name="btnCancel"
                                    title="Cancel">
                                    <i class="fa fa-ban"></i> Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>



            </section>
        </section>
    </section>
</body>

</html>