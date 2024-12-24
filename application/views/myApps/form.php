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
        $("#txtRequiredDate").datepicker({
            dateFormat: 'yy-mm-dd',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
        });
        $("#txtRequiredDateEdit").datepicker({
            dateFormat: 'yy-mm-dd',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
        });
        $('#saveFormRequest').click(function() {
            var projectReference = $('#txtprojectReference').val();
            var purpose = $('#txtpurpose').val();
            var company = $("#slcCompany").val();
            var location = $("#txtlocation").val();
            var divisi = $("#slcDivisi").val();
            var department = $("#slcDepartment").val();
            var requiredDate = $("#txtRequiredDate").val();
            var acknowledge = $("#slcAcknowledge").val();
            var approve = $("#slcApprove").val();
            var txtIdForm = $("#txtIdForm").val();

            if (!projectReference || !purpose) {
                alert("Field Project Reference and Purpose are required.");
                return;
            }


            var formData = new FormData();
            formData.append('txtprojectReference', projectReference);
            formData.append('txtpurpose', purpose);
            formData.append('slcCompany', company);
            formData.append('slcCompanyText', $("#slcCompany option:selected").text());
            formData.append('txtlocation', location);
            formData.append('slcDivisi', divisi);
            formData.append('slcDepartment', department);
            formData.append('txtRequiredDate', requiredDate);
            formData.append('slcAcknowledgeText', $("#slcAcknowledge option:selected").text());
            formData.append('slcAcknowledge', acknowledge);
            formData.append('slcApprove', approve);
            formData.append('slcApproveText', $("#slcApprove option:selected").text());
            formData.append('txtIdForm', txtIdForm);

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
            appendData('notes', "input[name^='txtnote']");

            $.ajax({
                type: 'POST',
                url: '<?php echo base_url('form/saveFormRequestWithDetail'); ?>',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.status === "Insert Success..!!" || response.status ===
                        "Update Success") {
                        alert(response.status);
                        var statusText = '';

                        if (response.st_submit === 'Y' && response.st_acknowledge === 'N') {
                            statusText =
                                "Waiting Acknowledge <i class='fa fa-clock-o'></i>";
                        } else if (response.st_acknowledge === 'Y' && response
                            .st_approval === 'N') {
                            statusText = "Waiting Approval <i class='fa fa-clock-o'></i>";
                        } else if (response.st_approval === 'Y') {
                            statusText = "Approve Success <i class='fa fa-check'></i>";
                        }

                        var newRow = "<tr>";
                        newRow += "<td align='center'>" + ($("#idTbody tr").length + 1) +
                            "</td>";
                        newRow += "<td align='center'><button onclick=\"editData('" +
                            response.id +
                            "');\" title=\"Edit Detail\" class=\"btn btn-warning btn-xs\" id=\"btnEdit\" type=\"button\"><i class=\"glyphicon glyphicon-edit\"></i></button></td>";
                        newRow += "<td align='center'>" + response.project_reference +
                            "</td>";
                        newRow += "<td align='center'>" + response.purpose + "</td>";
                        newRow += "<td align='left'>" + response.company + "</td>";
                        newRow += "<td align='center'>" + response.location + "</td>";
                        newRow += "<td align='left'>" + response.divisi + "</td>";
                        newRow += "<td align='left'>" + statusText + "</td>";
                        newRow += "<td align='center'><button onclick=\"ViewPrint('" +
                            response.id +
                            "', 'request');\" class=\"btn btn-success btn-xs\" type=\"button\" title=\"View\"><i class=\"fa fa-eye\"></i> View</button>" +
                            "<button onclick=\"delData('" + response.id +
                            "');\" class=\"btn btn-danger btn-xs\" type=\"button\" title=\"Delete\"><i class=\"fa fa-trash-o\"></i> Delete</button></td>";
                        newRow += "</tr>";

                        $('#idTbody').prepend(newRow);
                        reindexTable();
                        $('#idFormModal').modal('hide');

                        $('input[name^="txt"]').val('');
                        $('#slcCompany').prop('selectedIndex', 0);
                        $('#slcDivisi').prop('selectedIndex', 0);
                        $('#slcDepartment').prop('selectedIndex', 0);
                        $('#txtIdForm').val('');
                    } else if (response.status.startsWith("Failed")) {
                        alert("Gagal: " + response.message);
                    } else {
                        alert("Data berhasil disimpan! " + response.detail_status);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan: ' + error);
                }
            });
        });
    });


    $(document).ready(function() {
        const departmentMapping = {
            "BOD / BOC": ["NON DEPARTMENT", "PA"],
            "CORPORATE FINANCE, STRATEGY & COMPLIANCE": ["NON DEPARTMENT"],
            "DRY BULK COMMERCIAL , OPERATION & AGENCY": ["OPERATION", "COMMERCIAL & CHARTERING", "AGENCY"],
            "FINANCE": ["FINANCE", "ACCOUNTING", "TAX", "NON DEPARTMENT", "FINANCE & CONTROL",
                "ACCOUNTING & REPORTING"
            ],
            "HUMAN CAPITAL & GA": ["HR", "GA"],
            "NON DIVISION": ["SECRETARY", "NON DEPARTMENT"],
            "OFFICE OPERATION": ["IT", "LEGAL", "PROCUREMENT", "AGENCY & BRANCH"],
            "OIL & GAS COMMERCIAL & OPERATION": ["COMMERCIAL", "OPERATION"],
            "SHIP MANAGEMENT": ["OWNER SUPERINTENDENT (TECHNICAL)", "CREWING", "QHSE", "AGENCY & BRANCH"]
        };
        $('#slcDivisiEdit').change(function() {
            var selectedDivision = $(this).val();
            var departmentSelect = $('#slcDepartmentEdit');

            departmentSelect.empty();
            departmentSelect.append('<option value="">- Select Department -</option>');

            if (departmentMapping[selectedDivision]) {
                departmentMapping[selectedDivision].forEach(function(department) {
                    departmentSelect.append('<option value="' + department + '">' + department +
                        '</option>');
                });
            }
        });
    });

    function editData(id) {
        $("#idLoading").show();;

        $.ajax({
            url: '<?php echo base_url('form/getEditForm'); ?>',
            type: 'POST',
            data: {
                id: id,
                id_form: id
            },
            dataType: 'json',
            success: function(response) {

                $("#idLoading").hide();
                $('#idFormEditModal').modal('show');

                if (response.status === 'success') {

                    const formData = response.formData[0];
                    console.log(formData);

                    $("#txtprojectReferenceEdit").val(formData.project_reference);
                    $("#txtpurposeEdit").val(formData.purpose);
                    $("#txtlocationEdit").val(formData.location);
                    $("#slcCompanyEdit").val(formData.company);
                    $("#slcDivisiEdit").val(formData.divisi);

                    setTimeout(() => {
                        $("#slcDepartmentEdit").val(formData.department);
                    }, 100);

                    $("#slcAcknowledgeEdit").val(formData.userid_acknowledge);
                    $("#slcApproveEdit").val(formData.userid_approve);
                    $("#txtRequiredDateEdit").val(formData.required_date);

                    $('#slcDivisiEdit').trigger('change');


                    var detailContent = '';
                    response.details.forEach(function(detail, index) {
                        const showRemoveButton = response.details.length > 1 ? '' :
                            'style="display:none;"';
                        detailContent += createDetailRow(index, detail, showRemoveButton);
                    });

                    $("#idFieldDetailEdit .detailRowEdit").html(detailContent);

                    $(".detailRowEdit").off("click", ".btnAddRowEdit").on("click", ".btnAddRowEdit",
                        function() {
                            const newIndex = $(".detailRowEdit .row").length;
                            const newRow = createDetailRow(newIndex, {},
                                '');
                            $(".detailRowEdit").append(newRow);
                            updateRemoveButtons();
                        });

                    $(".detailRowEdit").off("click", ".btnRemoveRowEdit").on("click", ".btnRemoveRowEdit",
                        function() {
                            const row = $(this).closest(".row");
                            row.find("input, select").each(function() {
                                $(this).prop("disabled",
                                    true);
                            });
                            row.hide();
                            row.data("isDeleted", true);
                        });

                    updateRemoveButtons();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error: " + error);
                alert("An error occurred while retrieving data.");
                $("#idLoading").hide();
            }
        });
    }

    function createDetailRow(index, detail = {}, showRemoveButton = '') {
        return '<div class="row" style="margin-bottom: 15px;">' +
            '<div class="col-md-12">' +
            '<div class="form-row">' +
            '<input type="hidden" id="txtIdDetail_' + index + '" name="txtIdDetail[]" value="' + (detail.id || '') +
            '">' +
            '<input type="hidden" id="txtIdEditForm" name="txtIdEditForm" value="' + (detail.id_form || '') + '">' +
            '<div class="col-md-2 col-xs-12">' +
            '<div class="form-group">' +
            '<label for="txtdescription_' + index + '"><u>Description:</u></label>' +
            '<input type="text" name="txtdescriptionEdit[]" class="form-control input-sm" id="txtdescriptionEdit_' +
            index + '" value="' + (detail.description || '') + '" placeholder="Description">' +
            '</div>' +
            '</div>' +
            '<div class="col-md-2 col-xs-12">' +
            '<div class="form-group">' +
            '<label for="txttype_' + index + '"><u>Type/Merk:</u></label>' +
            '<input type="text" name="txttypeEdit[]" class="form-control input-sm" id="txttypeEdit_' + index +
            '" value="' + (detail.type || '') + '" placeholder="Type/Merk">' +
            '</div>' +
            '</div>' +
            '<div class="col-md-2 col-xs-12">' +
            '<div class="form-group">' +
            '<label for="txtreason_' + index + '"><u>Reason:</u></label>' +
            '<input type="text" name="txtreasonEdit[]" class="form-control input-sm" id="txtreasonEdit_' + index +
            '" value="' + (detail.reason || '') + '" placeholder="Reason">' +
            '</div>' +
            '</div>' +
            '<div class="col-md-1 col-xs-12">' +
            '<div class="form-group">' +
            '<label for="txtquantity_' + index + '"><u>Quantity:</u></label>' +
            '<input type="text" name="txtquantityEdit[]" class="form-control input-sm" id="txtquantityEdit_' + index +
            '" value="' + (detail.quantity || '') + '" onkeypress="return isNumber(event)">' +
            '</div>' +
            '</div>' +
            '<div class="col-md-3 col-xs-12">' +
            '<div class="form-group">' +
            '<label for="txtnote_' + index + '"><u>Note:</u></label>' +
            '<input type="text" name="txtnoteEdit[]" class="form-control input-sm" id="txtnoteEdit_' + index +
            '" value="' + (detail.note || '') + '">' +
            '</div>' +
            '</div>' +
            '<div class="col-md-2 col-xs-2" style="flex: 1 1 auto;">' +
            '<button type="button" class="btn btn-primary btn-xs btnAddRowEdit" style="margin-top: 25px;">' +
            '<i class="glyphicon glyphicon-plus"></i>' +
            '</button>' +
            '<button type="button" class="btn btn-danger btn-xs btnRemoveRowEdit" ' + showRemoveButton +
            ' style="margin-top: 25px;">' +
            '<i class="glyphicon glyphicon-minus"></i>' +
            '</button>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';
    }

    function updateRemoveButtons() {
        const rows = $(".detailRowEdit .row");
        if (rows.length > 1) {
            rows.find(".btnRemoveRowEdit").show();
        } else {
            rows.find(".btnRemoveRowEdit").hide();
        }
    }

    $(document).ready(function() {
        $("#btnSearch").click(function() {
            var idSlcType = $("#idSlcType").val();
            var valSearch = $("#txtSearch").val();

            if (valSearch == "") {
                alert("Search Empty..!!");
                return false;
            }
            $("#idLoading").show();
            $.post('<?php echo base_url("form/getDataForm"); ?>/search/', {
                    valSearch: valSearch,
                    idSlcType: idSlcType
                },
                function(data) {
                    $("#idTbody").empty();
                    $("#idTbody").append(data.tr);
                    $("#idPage").empty();
                    $("#idLoading").hide();
                },
                "json"
            );
        });
    })

    $(document).ready(function() {
        $("#saveEditFormRequest").click(function(e) {
            e.preventDefault();

            var formData = new FormData();

            formData.append('txtIdEditForm', $('#txtIdEditForm').val());
            formData.append('txtprojectReferenceEdit', $('#txtprojectReferenceEdit').val());
            formData.append('txtpurposeEdit', $('#txtpurposeEdit').val());
            formData.append('slcCompanyEdit', $("#slcCompanyEdit").val());
            formData.append('slcCompanyText', $("#slcCompanyEdit option:selected").text());
            formData.append('txtlocationEdit', $("#txtlocationEdit").val());
            formData.append('slcDivisiEdit', $("#slcDivisiEdit").val());
            formData.append('slcDepartmentEdit', $("#slcDepartmentEdit").val());
            formData.append('txtRequiredDateEdit', $("#txtRequiredDateEdit").val());
            formData.append('slcAcknowledgeEdit', $("#slcAcknowledgeEdit").val());
            formData.append('slcAcknowledgeText', $("#slcAcknowledgeEdit option:selected").text());
            formData.append('slcApproveEdit', $("#slcApproveEdit").val());
            formData.append('slcApproveText', $("#slcApproveEdit option:selected").text());

            function appendData(fieldName, selector) {
                $(selector).each(function() {
                    const isDeleted = $(this).closest(".row").data("isDeleted") || false;
                    formData.append(fieldName + '[]', $(this).val());
                    formData.append(fieldName + '_isDeleted[]', isDeleted);
                });
            }
            appendData('txtdescriptionEdit', "input[name='txtdescriptionEdit[]']");
            appendData('txttypeEdit', "input[name='txttypeEdit[]']");
            appendData('txtreasonEdit', "input[name='txtreasonEdit[]']");
            appendData('txtquantityEdit', "input[name='txtquantityEdit[]']");
            appendData('txtnoteEdit', "input[name='txtnoteEdit[]']");
            appendData('txtIdDetail', "input[name='txtIdDetail[]']");

            $.ajax({
                url: '<?php echo base_url('form/saveEditFormRequest'); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === "success") {
                        alert("Data berhasil diperbarui!");
                        $("#idFormEditModal").modal('hide');

                        $("#idTbody").load(
                            '<?php echo base_url('form/getDataForm'); ?> #idTbody > *');

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

    function ViewPrint(id, typeView) {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url('form/previewPrint'); ?>",
            data: {
                id: id,
                typeView: typeView
            },
            success: function(response) {
                var data = JSON.parse(response);

                $('#ictRequestModal .modal-bodyPreview .table-bordered td').html('');
                $('#ictRequestModal .modal-bodyPreview .table-striped tbody').html('');
                $('#ictRequestModal .modal-bodyPreview .note-box').empty();
                $('#ictRequestModal .modal-bodyPreview .approval td').not(':has(div)')
                    .html('');
                $('#ictRequestModal .modal-bodyPreview .signature-box').empty();
                $('.name-wrapper').empty();

                $('#ictRequestModal .modal-bodyPreview .table-bordered tr:eq(0) td')
                    .html(data.form
                        .project_reference || 'N/A');
                $('#ictRequestModal .modal-bodyPreview .table-bordered tr:eq(1) td')
                    .html(data.form
                        .purpose || 'N/A');
                $('#ictRequestModal .modal-bodyPreview .table-bordered tr:eq(2) td')
                    .html(data.form
                        .divisi || 'N/A');
                $('#ictRequestModal .modal-bodyPreview .table-bordered tr:eq(3) td')
                    .html(data.form
                        .department || 'N/A');
                $('#ictRequestModal .modal-bodyPreview .table-bordered tr:eq(4) td')
                    .html(data.form
                        .company || 'N/A');
                $('#ictRequestModal .modal-bodyPreview .table-bordered tr:eq(5) td')
                    .html(data.form
                        .location || 'N/A');
                $('#ictRequestModal .modal-bodyPreview .table-bordered tr:eq(6) td')
                    .html(data.form
                        .required_date || 'N/A');

                var detailHtml = '';
                if (data.form_details && data.form_details.length > 0) {
                    data.form_details.forEach(function(detail) {
                        detailHtml +=
                            '<tr>' +
                            '<td>' + (detail.description || '') + '</td>' +
                            '<td>' + (detail.type || '') + '</td>' +
                            '<td>' + (detail.quantity || '') + '</td>' +
                            '<td>' + (detail.reason || '') + '</td>' +
                            '<td>' + (detail.note || '') + '</td>' +
                            '</tr>';

                    });
                } else {
                    detailHtml = '<tr><td colspan="5">No details available</td></tr>';
                }
                $('#ictRequestModal .modal-bodyPreview .table-striped tbody').html(
                    detailHtml);

                $('#ictRequestModal .modal-bodyPreview .signature-box').eq(0).html(data
                    .qrCode);
                $('#ictRequestModal .modal-bodyPreview .signature-box').eq(1).html(data
                    .qrCodeAcknowledge);
                $('#ictRequestModal .modal-bodyPreview .signature-box').eq(2).html(data
                    .qrCodeApprove);

                $('.reqName').html(data.form.request_name);
                $('.nameKadept').html(data.nameKadept);
                $('.nameKadiv').html(data.nameKadiv);

                $('.footer-buttonAcknowledge').html(data.buttonKadept || '');
                $('.footer-buttonApprove').html(data.buttonKadiv || '');

                if (data.button) {
                    $('#ictRequestModal .modal-bodyPreview .footer-buttonSend').html(
                        data.button);
                }

                $('#ictRequestModal').modal('show');
            },
            error: function() {
                alert('Error loading data');
            }
        });
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
        var acknowledgeEmail = $("#slcAcknowledge option:selected").data("email");
        var approveEmail = $("#slcApprove option:selected").data("email");

        if (!acknowledgeEmail || !approveEmail) {
            alert("Please select both Acknowledge and Approve users.");
            return;
        }

        $.ajax({
            url: "<?php echo base_url('form/updateSubmitStatus'); ?>",
            type: "POST",
            data: {
                id: idForm,
                acknowledgeEmail: acknowledgeEmail,
                approveEmail: approveEmail
            },
            success: function(response) {
                var res = JSON.parse(response);
                if (res.status === 'success') {
                    alert(res.message);
                    $("#ictRequestModal").modal('hide');
                    $("#idTbody").load("<?php echo base_url('form/getDataForm'); ?> #idTbody > *");
                } else if (res.status === 'failed') {
                    alert("Error: " + res.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    function acknowledgeData(idForm) {
        $.ajax({
            url: '<?php echo base_url('form/acknowledgeData'); ?>',
            type: "POST",
            data: {
                id: idForm
            },
            success: function(response) {
                try {
                    var res = JSON.parse(response);
                    if (res === 'success') {
                        alert("The data has been acknowledged and sent to approve! 🕒");
                        $("#ictRequestModal").modal('hide');

                        $("#idTbodyAcknowledge").load(
                            "<?php echo base_url('form/getDataForm'); ?> #idTbodyAcknowledge > *"
                        );
                    } else {
                        console.error("Acknowledgement failed: ", res);
                        alert("Acknowledgement failed. Please try again.");
                    }
                } catch (e) {
                    console.error("Error parsing response:", e);
                    alert("Unexpected server response. Please try again.");
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert("Failed to send acknowledgment. Please check your connection.");
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
                try {
                    var res = JSON.parse(response); // Pastikan response JSON dapat diparsing
                    if (res === 'success') { // PHP hanya mengembalikan "success" sebagai string
                        const statusElement = document.getElementById("status_" + idForm);
                        if (statusElement) {
                            statusElement.innerHTML = "Approve Success";
                        }
                        alert("Request has been approved! 👍");
                        $("#ictRequestModal").modal('hide');

                        $("#idTbodyApproval").load(
                            "<?php echo base_url('form/getDataForm'); ?> #idTbodyApproval > *"
                        );
                    } else {
                        console.error("Approval failed: ", res);
                        alert("Approval failed. Please try again.");
                    }
                } catch (e) {
                    console.error("Error parsing response:", e);
                    alert("Unexpected server response. Please try again.");
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert("Failed to send approval. Please check your connection.");
            }
        });
    }


    function downloadPdf(id) {
        window.open("<?php echo base_url('form/printPdf/'); ?>" + '/' + id, '_blank');
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
                    var data = response.data;
                    var tbody = $('#idTbodyAcknowledge');
                    tbody.empty();
                    var no = 1;

                    if (Array.isArray(data) && data.length) {
                        data.forEach(function(item) {
                            var row =
                                '<tr id="row_' + item.id + '">' +
                                '<td style="text-align:center;">' + (no++) + '</td>' +
                                '<td style="text-align:center;">' + item.project_reference +
                                '</td>' +
                                '<td style="text-align:center;">' + item.purpose + '</td>' +
                                '<td style="text-align:center;">' + item.company + '</td>' +
                                '<td style="text-align:center;">' + item.location + '</td>' +
                                '<td style="text-align:center;">' + item.divisi + '</td>' +
                                '<td style="text-align:center;">' +
                                '<button onclick="ViewPrint(' + item.id + ', \'' + type +
                                '\');" class="btn btn-success btn-xs" type="button">' +
                                '<i class="fa fa-eye"></i> View' +
                                '</button>' +
                                '</td>' +
                                '</tr>';

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
                    var data = response.data;
                    var tbody = $('#idTbodyApproval');
                    tbody.empty();
                    var no = 1;

                    if (Array.isArray(data) && data.length) {
                        data.forEach(function(item) {
                            var row =
                                '<tr>' +
                                '<td style="text-align:center;">' + (no++) + '</td>' +
                                '<td style="text-align:center;">' + item.project_reference +
                                '</td>' +
                                '<td style="text-align:center;">' + item.purpose + '</td>' +
                                '<td style="text-align:center;">' + item.company + '</td>' +
                                '<td style="text-align:center;">' + item.location + '</td>' +
                                '<td style="text-align:center;">' + item.divisi + '</td>' +
                                '<td style="text-align:center;">' +
                                '<button onclick="ViewPrint(' + item.id + ', \'' + type +
                                '\');" class="btn btn-primary btn-xs" type="button">' +
                                '<i class="fa fa-eye"></i> View' +
                                '</button>' +
                                '</td>' +
                                '</tr>';

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
            "BOD / BOC": ["NON DEPARTMENT", "PA"],
            "CORPORATE FINANCE, STRATEGY & COMPLIANCE": ["NON DEPARTMENT"],
            "DRY BULK COMMERCIAL , OPERATION & AGENCY": ["OPERATION",
                "COMMERCIAL & CHARTERING",
                "AGENCY"
            ],
            "FINANCE": ["FINANCE", "ACCOUNTING", "TAX", "NON DEPARTMENT",
                "FINANCE & CONTROL",
                "ACCOUNTING & REPORTING"
            ],
            "HUMAN CAPITAL & GA": ["HR", "GA"],
            "NON DIVISION": ["SECRETARY", "NON DEPARTMENT"],
            "OFFICE OPERATION": ["IT", "LEGAL", "PROCUREMENT", "AGENCY & BRANCH"],
            "OIL & GAS COMMERCIAL & OPERATION": ["COMMERCIAL", "OPERATION"],
            "SHIP MANAGEMENT": ["OWNER SUPERINTENDENT (TECHNICAL)", "CREWING", "QHSE",
                "AGENCY & BRANCH"
            ]
        };
        $('#slcDivisi').change(function() {
            var selectedDivision = $(this).val();
            console.log("Selected Division: " + selectedDivision);
            var departmentSelect = $('#slcDepartment');

            departmentSelect.empty();
            departmentSelect.append('<option value="">- Select Department -</option>');

            if (departmentMapping[selectedDivision]) {
                departmentMapping[selectedDivision].forEach(function(department) {
                    console.log("Adding department: " + department);
                    departmentSelect.append('<option value="' + department +
                        '">' +
                        department +
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
            $clone.find('input').each(function() {
                $(this).removeAttr('id');
            });
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

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("idPage").addEventListener("click", function(event) {

            if (event.target.tagName === "A" && event.target.classList.contains("page-link")) {
                event.preventDefault();

                var url = event.target.getAttribute("href");

                fetch(url, {
                        method: "GET",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById("idTbody").innerHTML = data.tr;
                        document.getElementById("idPage").innerHTML = data.listPage;
                    })
                    .catch(error => console.error("Error:", error));
            }
        });
    });
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
                            <button class="btn btn-primary btn-block" onclick="changeBtnNavigation('request')">
                                <label>Request</label>
                            </button>
                        </div>
                        <?php if(isset($buttonAck)) echo $buttonAck; ?>
                        <?php if(isset($buttonApp)) echo $buttonApp; ?>
                    </div>
                </div>

                <div class="form-panel" id="DataTableRequest" style="display: none;">
                    <h3>Request</h3>
                    <div class="row">
                        <div class="modal fade bd-example-modal-lg" id="idFormModal" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header"
                                        style="background-color:#D46D16;border-bottom:1px solid #e7e7e7">
                                        <h4 class="modal-title">Add Request</h4>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form Utama -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="requestContainer">
                                                    <div class="row requestRow">
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="txtprojectReference"><b><u>Project Ref
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
                                                    </div>

                                                    <div class="row requestRow">
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
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="slcJenisPerangkat"><b><u>Jenis Perangkat
                                                                            :</u></b></label>
                                                                <select id="slcJenisPerangkat"
                                                                    class="form-control input-sm">
                                                                    <?php echo $getOptJenisPerangkat; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="txtRequiredDate"><u>Required
                                                                        Date:</u></label>
                                                                <input type="text" name="txtrequired_date[]"
                                                                    class="form-control input-sm" id="txtRequiredDate"
                                                                    autocomplete="off">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="slcAcknowledge"><b><u>Acknowledge
                                                                            By:</u></b></label>
                                                                <select id="slcAcknowledge"
                                                                    class="form-control input-sm">
                                                                    <?php echo $getOptAcknowledge; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 col-xs-12">
                                                            <div class="form-group">
                                                                <label for="slcApprove"><b><u>Approve
                                                                            By:</u></b></label>
                                                                <select id="slcApprove" class="form-control input-sm">
                                                                    <?php echo $getOptApprove; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Form Detail -->
                                        <div id="idFieldDetail">
                                            <legend><label id="lblForm">Add Request Detail</label></legend>
                                            <div class="detailRow" style="display: flex; flex-wrap: wrap;">
                                                <!-- Detail fields -->
                                                <div class="col-md-2 col-xs-12"
                                                    style="padding-right: 10px; padding-left: 10px; flex: 1 1 auto;">
                                                    <div class="form-group">
                                                        <label for="txtdescription"><u>Description:</u></label>
                                                        <input type="text" name="txtdescription[]"
                                                            class="form-control input-sm txtdescription"
                                                            placeholder="Description" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-xs-12"
                                                    style="padding-right: 10px; padding-left: 10px; flex: 1 1 auto;">
                                                    <div class="form-group">
                                                        <label for="txttype"><u>Type:</u></label>
                                                        <input type="text" name="txttype[]"
                                                            class="form-control input-sm txttype" placeholder="Type"
                                                            autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-xs-12"
                                                    style="padding-right: 10px; padding-left: 10px; flex: 1 1 auto;">
                                                    <div class="form-group">
                                                        <label for="txtreason"><u>Reason:</u></label>
                                                        <input type="text" name="txtreason[]"
                                                            class="form-control input-sm txtreason" placeholder="Reason"
                                                            autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-md-1 col-xs-12"
                                                    style="padding-right: 10px; padding-left: 10px; flex: 1 1 auto;">
                                                    <div class="form-group">
                                                        <label for="txtquantity"><u>Quantity:</u></label>
                                                        <input type="text" name="txtquantity[]"
                                                            class="form-control input-sm txtquantity" value="0"
                                                            onkeypress="return isNumber(event)" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-xs-12"
                                                    style="padding-right: 10px; padding-left: 10px; flex: 1 1 auto;">
                                                    <div class="form-group">
                                                        <label for="txtnote"><u>Note:</u></label>
                                                        <input type="text" name="txtnote[]"
                                                            class="form-control input-sm txtnote" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-md-1 col-xs-2" style="flex: 1 1 auto;">
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
                        <!-- <div class="col-md-2">
                            <select class="form-control input-sm" id="idSlcType">
                                <option value="projectreference">Project Refference</option>
                                <option value="purpose">Purpose</option>
                                <option value="company">Company</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control input-sm" id="txtSearch" value=""
                                placeholder="Search Text" autocomplete="off">
                        </div>
                        <div class="col-md-2">
                            <button type="button" id="btnSearch" class="btn btn-warning btn-sm btn-block" title="Add"><i
                                    class="fa fa-search"></i> Search</button>
                        </div> -->
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
                                                Jenis Perangkat
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
                            <div id="idPage"><?php echo $listPage; ?></div>
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

                <div class="modal fade" id="idFormEditModal" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color:#D46D16;border-bottom:1px solid #e7e7e7">
                                <h4 class="modal-title">Edit Request</h4>
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
                                                            id="txtprojectReferenceEdit" name="txtprojectReference[]"
                                                            value="">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="txtpurpose"><b><u>Purpose :</u></b></label>
                                                        <input type="text" class="form-control input-sm"
                                                            id="txtpurposeEdit" name="txtpurpose">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="slcCompany"><b><u>Company:</u></b></label>
                                                        <select id="slcCompanyEdit" class="form-control input-sm">
                                                            <?php echo $getOptCompany; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="txtlocation"><b><u>Location :</u></b></label>
                                                        <input type="text" class="form-control input-sm"
                                                            id="txtlocationEdit" name="txtlocation">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="slcDivisi"><b><u>Divisi:</u></b></label>
                                                        <select id="slcDivisiEdit" class="form-control input-sm">
                                                            <?php echo $getOptMstDivisi; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="slcDepartment"><b><u>Department :</u></b></label>
                                                        <select id="slcDepartmentEdit" class="form-control input-sm">
                                                            <option value="">- Select Department -</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-xs-12"
                                                    style="padding-right: 10px; padding-left: 10px;">
                                                    <div class="form-group">
                                                        <label for="txtRequiredDate"><u>Required Date:</u></label>
                                                        <input type="date" name="txtrequired_date[]"
                                                            class="form-control input-sm" id="txtRequiredDateEdit"
                                                            autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-md-5 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="slcAcknowledge"><b><u>Acknowledge
                                                                    By:</u></b></label>
                                                        <select id="slcAcknowledgeEdit" class="form-control input-sm">
                                                            <?php echo $getOptAcknowledge; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-5 col-xs-12">
                                                    <div class="form-group">
                                                        <label for="slcApprove"><b><u>Approve
                                                                    By:</u></b></label>
                                                        <select id="slcApproveEdit" class="form-control input-sm">
                                                            <?php echo $getOptApprove; ?>
                                                            <option value="00172"
                                                                data-email="adhitya.ilham@andhika.com"></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="idFieldDetailEdit">
                                    <legend><label id="lblForm">Edit Request Detail</label></legend>
                                    <div class="detailRowEdit" style="display: flex; flex-wrap: wrap;"></div>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" id="txtIdForm" value="">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="saveEditFormRequest">Save
                                    changes</button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="modal fade bd-example-modal-lg" id="ictRequestModal" tabindex="-1" role="dialog"
                    aria-labelledby="ictRequestModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color:#D46D16;border-bottom:1px solid #e7e7e7">
                                <h5 class="modal-title" id="ictRequestModalLabel">ICT Tools and Equipment Request</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-bodyPreview">
                                <div>
                                    <?php echo isset($imageLogo) ? $imageLogo : ''; ?>
                                </div>
                                <div class="title text-center">
                                    <h1>ICT TOOLS AND EQUIPMENT REQUEST</h1>
                                    <h2>PERMINTAAN ALAT DAN PERLENGKAPAN ICT</h2>
                                </div>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Project Reference / Referensi Proyek</th>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Purpose / Kebutuhan</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Divisi / Divisi</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Departement / Departement</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Company / Perusahaan</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Location / Lokasi</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Required Date</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Device Type / Jenis Perangkat</th>
                                        <td></td>
                                    </tr>
                                </table>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>DESKRIPSI</th>
                                            <th>TYPE / BRAND</th>
                                            <th>QTY</th>
                                            <th>REASON / ALASAN</th>
                                            <th>NOTE</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>

                                <div class="footer">
                                    <div class="approval">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td style="text-align: center;">Proposed by<br>
                                                    <div class="signature-box"
                                                        style="text-align: center; margin-bottom: 5px;">

                                                    </div>
                                                    <div class="name-wrapper reqName"
                                                        style="text-align: center; margin-top: 5px; font-weight: bold; font-size: 12px;">

                                                    </div>
                                                </td>
                                                <td style="text-align: center;">Acknowledge by<br>
                                                    <div class="signature-box"
                                                        style="text-align: center; margin-bottom: 5px;">

                                                    </div>
                                                    <div class="name-wrapper nameKadept"
                                                        style="text-align: center; margin-top: 5px; font-weight: bold; font-size: 12px;">

                                                    </div>
                                                </td>
                                                <td style="text-align: center;">Approved by<br>
                                                    <div class="signature-box"
                                                        style="text-align: center; margin-bottom: 5px;">

                                                    </div>
                                                    <div class="name-wrapper nameKadiv"
                                                        style="text-align: center; margin-top: 5px; font-weight: bold; font-size: 12px;">

                                                    </div>
                                                </td>

                                            </tr>
                                        </table>
                                    </div>
                                    <div class="footer-buttonSend">

                                    </div>
                                    <div class="footer-buttonAcknowledge">

                                    </div>
                                    <div class="footer-buttonApprove">

                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
        </section>
    </section>
</body>

</html>