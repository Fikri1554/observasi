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
            formData.append('slcDivisi', $('#slcDivisi').val());
            formData.append('txtIdForm', $('#txtIdForm').val());

            $.ajax({
                url: '<?php echo base_url("form/saveFormRequest"); ?>',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.includes("Update Success")) {
                        alert(response);
                        updateTableRow();
                    } else if (response.includes("Insert Success")) {
                        alert(response);
                        addTableRow(response);
                    } else if (response.startsWith("Failed =>")) {
                        alert("Gagal: " + response);
                    } else {
                        alert("Data berhasil disimpan!" + response);
                    }
                    $('#idFormModal').modal('hide');
                },
                error: function(xhr, status, error) {
                    console.log(error);
                    alert("Terjadi kesalahan saat mengirim data.");
                }
            });
        });

        function addTableRow(id) {
            var rowCount = $('#idTbody tr').length + 1;

            var newRow = `
            <tr data-id="${id}">
                <td align="center" style="font-size:12px;vertical-align:top;">${rowCount}</td>
                <td align="center">
                    <button onclick="addDetail('${id}');" title="Add Detail" class="btn btn-primary btn-xs" type="button">
                        <i class="glyphicon glyphicon-plus"></i>
                    </button>
                </td>
                <td align="center" style="font-size:12px;vertical-align:top;">${$('#txtprojectReference').val()}</td>
                <td align="center" style="font-size:12px;vertical-align:top;">${$('#txtpurpose').val()}</td>
                <td align="left" style="font-size:12px;vertical-align:top;">${$('#slcCompany').val()}</td>
                <td align="center" style="font-size:12px;vertical-align:top;">${$('#txtlocation').val()}</td>
                <td align="left" style="font-size:12px;vertical-align:top;">${$('#slcDivisi').val()}</td>
                <td align="center" style="font-size:12px;vertical-align:top;">
                    <button onclick="editData('${id}');" class="btn btn-info btn-xs" type="button">
                        <i class="fa fa-edit"></i> Edit
                    </button>
                </td>
            </tr>`;

            $('#idTbody').append(newRow);
        }

        function updateTableRow() {
            var id = $('#txtIdForm').val();
            var row = $('tr[data-id="' + id + '"]');

            row.find('td:eq(2)').text($('#txtprojectReference').val());
            row.find('td:eq(3)').text($('#txtpurpose').val());
            row.find('td:eq(4)').text($('#slcCompany').val());
            row.find('td:eq(5)').text($('#txtlocation').val());
            row.find('td:eq(6)').text($('#slcDivisi').val());
        }
    });

    $(document).ready(function() {
        $('#btnSaveFormDetail').click(function(e) {
            e.preventDefault();


            var formData = new FormData();

            formData.append('description', $('#txtdescription').val());
            formData.append('type', $('#txttype').val());
            formData.append('reason', $('#txtreason').val());
            formData.append('quantity', $('#txtquantity').val());
            formData.append('required_date', $('#txtRequiredDate').val());
            formData.append('note', $('#txtnote').val());

            if ($('#txtIdForm').val() === "") {
                alert('Form ID is missing!');
                return;
            }

            formData.append('id_form', $('#txtIdForm').val());

            $.ajax({
                url: '<?php echo base_url("form/saveFormRequestDetail"); ?>',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status.includes('Success')) {
                        alert(response.status);
                        updateTableFormDetail(response.data);
                        $("#idFormDetail").hide();
                    } else {
                        alert('Error: ' + response.status);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error saving data: ' + textStatus);
                }
            });
        });
    });

    function updateTableFormDetail(data) {
        var table = $('.table-responsive table');

        table.find('tbody').remove();

        var tbody = $('<tbody></tbody>');

        var filteredData = data.filter(function(item) {
            return item.sts_delete == 0;
        });

        if (filteredData.length === 0) {
            var emptyRow = $(
                '<tr><td colspan="7" style="text-align:center; padding:10px;">No data available</td></tr>');
            tbody.append(emptyRow);
        } else {
            $.each(filteredData, function(index, item) {
                var row = $('<tr></tr>');

                row.append('<td style="text-align:center; padding:10px;">' + (index + 1) + '</td>');
                row.append('<td style="text-align:center; padding:10px;">' + (item.description || '-') +
                    '</td>');
                row.append('<td style="text-align:center; padding:10px;">' + (item.type || '-') + '</td>');
                row.append('<td style="text-align:center; padding:10px;">' + (item.reason || '-') + '</td>');
                row.append('<td style="text-align:center; padding:10px;">' + (item.quantity || '-') + '</td>');
                row.append('<td style="text-align:center; padding:10px;">' + (item.required_date && item
                    .required_date !== '0000-00-00' ? item.required_date : '-') + '</td>');
                row.append('<td style="text-align:center; padding:10px;">' + (item.note || '-') + '</td>');

                tbody.append(row);
            });
        }

        table.append(tbody);

        $('#tableFormDetail').show();

    }

    function ViewPrint(id = '') {
        window.open('<?php echo base_url('form/previewPrint');?>' + '/' + id, '_blank');
    }


    function addDetail(id) {
        $("#DataTableRequest").hide();
        $("#idFormDetail").show(200);
        $("#txtIdForm").val(id);
    }

    function changeBtnNav(type) {
        if (type == "request") {
            $("#DataTableRequest").show();
        } else if (type == "request") {
            $("#DataTableRequest").show();
        } else {
            $("#DataTableRequest").show();
        }
    }

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
                            <button class="btn btn-primary btn btn-block" onclick="changeBtnNav('request');">
                                <label>Request</label>
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary btn btn-block">
                                <label>Acknowledge</label>
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary btn btn-block">
                                <label>Approval</label>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="form-panel" id="DataTableRequest" style="display: none;">
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
                                                                    <option value="">- Select -</option>
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

                <div class="form-panel" id="idFormDetail" style="display:none;">
                    <div id="idFieldDetail">
                        <div class="row" style="margin-bottom: 15px;">
                            <div class="col-md-12">
                                <legend><label id="lblForm"> Add Request Detail</label></legend>
                                <div class="form-row">
                                    <div class="col-md-3 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                        <div class="form-group">
                                            <label for="txtdescription"><u>Description:</u></label>
                                            <input type="text" name="description" class="form-control input-sm"
                                                id="txtdescription" placeholder="Description" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                        <div class="form-group">
                                            <label for="txttype"><u>Type:</u></label>
                                            <input type="text" name="type" class="form-control input-sm" id="txttype"
                                                placeholder="Type" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                        <div class="form-group">
                                            <label for="txtreason"><u>Reason:</u></label>
                                            <input type="text" name="reason" class="form-control input-sm"
                                                id="txtreason" placeholder="Reason" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                        <div class="form-group">
                                            <label for="txtquantity"><u>Quantity:</u></label>
                                            <input type="text" name="quantity" class="form-control input-sm"
                                                id="txtquantity" value="0" onkeypress="return isNumber(event)"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                        <div class="form-group">
                                            <label for="txtRequiredDate"><u>Required Date:</u></label>
                                            <input type="date" name="required_date" class="form-control input-sm"
                                                id="txtRequiredDate" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                        <div class="form-group">
                                            <label for="txtnote"><u>Note:</u></label>
                                            <input type="text" name="note" class="form-control input-sm" id="txtnote"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-xs-12" style="padding-right: 10px; padding-left: 10px;">
                                        <div class="form-group" style="margin-bottom: 15px;">
                                            <label for="txtTotalReq" style="font-weight: bold;">&nbsp;</label>
                                            <button class="btn btn-primary btn-block btn-xs" title="Add"
                                                id="btnAddField" onclick="addRowDetail();" type="button">
                                                <i class="glyphicon glyphicon-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group" align="center">
                                <input type="hidden" name="txtIdForm" id="txtIdForm" value="">
                                <input type="hidden" name="txtidFormDetail" id="txtidFormDetail" value="">
                                <button id="btnSaveFormDetail" class="btn btn-primary btn-sm" name="btnSave"
                                    title="Save">
                                    <i class="fa fa-check-square-o"></i> Save
                                </button>
                                <button id="btnCancelDetail" class="btn btn-danger btn-sm" name="btnCancel"
                                    onclick="reloadPage();" title="Cancel">
                                    <i class="fa fa-ban"></i> Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-panel" id="tableFormDetail" style="display:none;">
                    <div class="row mt" id="idData1">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover"
                                    style="border: 1px solid #ddd; width: 100%; border-collapse: collapse;">
                                    <thead>
                                        <tr style="background-color: #ba5500; color: #FFF; text-align:center;">
                                            <th style="width:5%; padding: 10px; border: 1px solid #ddd;">No</th>
                                            <th style="width:25%; padding: 10px; border: 1px solid #ddd;">Description
                                            </th>
                                            <th style="width:15%; padding: 10px; border: 1px solid #ddd;">Type</th>
                                            <th style="width:15%; padding: 10px; border: 1px solid #ddd;">Reason</th>
                                            <th style="width:10%; padding: 10px; border: 1px solid #ddd;">Quantity</th>
                                            <th style="width:15%; padding: 10px; border: 1px solid #ddd;">Required Date
                                            </th>
                                            <th style="width:30%; padding: 10px; border: 1px solid #ddd;">Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Table rows will be dynamically added here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- <button class="btn btn-danger btn-sm" name="btnCancel" onclick="backDetail();" title="Cancel">
                        <i class="fa fa-ban"></i> Cancel
                    </button> -->
                    <button type="button" id="btnBack" class="btn btn-danger btn-sm"
                        onclick="reloadPage();">Kembali</button>
                </div>

            </section>
        </section>
    </section>
</body>

</html>