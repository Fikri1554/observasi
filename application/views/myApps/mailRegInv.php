<?php $this->load->view('myApps/menu'); ?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <script type="text/javascript">
    $(document).ready(function() {
        $("#idLoading").hide();
        $("#btnUnrec").attr("disabled", "disabled");
        $("#txtStartDate").datepicker({
            dateFormat: 'yymmdd',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
        });
        $("#txtEndDate").datepicker({
            dateFormat: 'yymmdd',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
        });
        $("#btnSearchOpenForm").click(function() {
            $(this).hide(100);
            $("#idFormSearch").show();
        });
        $("#btnCancelSearch").click(function() {
            reloadPage();
        });
        $("#btnUnrec").click(function() {
            var sDateSearch = $("#txtStartDate").val();
            var eDateSearch = $("#txtEndDate").val();
            var dtChecked = [];
            $(':checkbox:checked').each(function(i) {
                dtChecked[i] = $(this).val();
            });
            $.post('<?php echo base_url("myapps/unReceive"); ?>', {
                    dtChecked: dtChecked
                },
                function(data) {
                    alert(data);
                    if (sDateSearch == "" & eDateSearch == "") {
                        reloadPage();
                    } else {
                        searchData();
                        $("#txtIdMailModal").val('');
                        $('#idModalAccept').modal("hide");
                    }
                },
                "json"
            );
        });
    });

    function searchData() {
        var searchUnit = $("#slcSearchUnit").val();
        var searchData = $("#txtSearchData").val();
        var sDateSearch = $("#txtStartDate").val();
        var eDateSearch = $("#txtEndDate").val();

        if (searchUnit == "") {
            alert("Unit Empty..!!");
            return false;
        }

        if (sDateSearch == "" & eDateSearch == "") {
            alert("Date Search Empty..!!");
            return false;
        }
        if (sDateSearch == "" & eDateSearch != "") {
            alert("From Date Empty..!!");
            return false;
        } else if (sDateSearch != "" & eDateSearch == "") {
            alert("To Date Empty..!!");
            return false;
        }
        $("#idTbody").empty();
        $("#idLoading").show();
        // return false;
        $.post('<?php echo base_url("myapps/getMailRegInv/search"); ?>', {
                searchUnit: searchUnit,
                searchData: searchData,
                sDateSearch: sDateSearch,
                eDateSearch: eDateSearch
            },
            function(data) {
                var html = data.trNya;
                $('#idTbody').append(html);
                $("#idLoading").hide();
            },
            "json"
        );
    }

    function showModalAccept(id) {
        $("#idLoading").show();
        $("#idChkMoreApprove").attr("checked", false);
        $("#slcAprvMore").val("");
        $("#slcAprvMore").css("display", "none");
        $("#txtTypeDataModal").val("");

        $.post('<?php echo base_url("myapps/cekSesseion"); ?>', {},
            function(data) {
                if (data == "ada") {
                    $("#txtIdMailModal").val('');
                    $("#txtIdMailModal").val(id);
                    $('#idModalAccept').modal("show");
                    $("#idLoading").hide();
                } else {
                    alert("Session Expired, Please Login..!!");
                    reloadPage();
                }
            },
            "json"
        );
    }

    function showModalAcceptAnother(id) {
        $("#idLoading").show();
        $("#idChkMoreApprove").attr("checked", false);
        $("#slcAprvMore").val("");
        $("#idDivFormAnother").css("display", "none");
        $("#txtTypeDataModal").val("another");

        $.post('<?php echo base_url("myapps/cekSesseion"); ?>', {},
            function(data) {
                if (data == "ada") {
                    $("#txtIdMailModal").val(id);
                    $('#idModalAccept').modal("show");
                    $("#idLoading").hide();
                } else {
                    alert("Session Expired, Please Login..!!");
                    reloadPage();
                }
            },
            "json"
        );
    }

    function showModalReject(id) {
        $("#idLoading").show();
        $.post('<?php echo base_url("myapps/cekSesseion"); ?>', {},
            function(data) {
                if (data == "ada") {
                    $("#txtIdMailModalReject").val('');
                    $("#txtIdMailModalReject").val(id);
                    $('#idModalReject').modal("show");
                } else {
                    alert("Session Expired, Please Login..!!");
                    reloadPage();
                }
            },
            "json"
        );
    }

    function acceptNya() {
        var idMail = $("#txtIdMailModal").val();
        var txtReason = $("#txtReason").val();
        var sDateSearch = $("#txtStartDate").val();
        var eDateSearch = $("#txtEndDate").val();
        var txtTypeDataModal = $("#txtTypeDataModal").val();
        var anotherApr = "N";
        var slcAprvUserId = "";
        var slcAprvName = "";

        if (txtReason == "") {
            alert("Reason Empty..!!");
            $("#txtReason").focus();
            return false;
        }

        if ($('#idChkMoreApprove').is(":checked")) {
            anotherApr = "Y";
            slcAprvUserId = $("#slcAprvMore").val();
            slcAprvName = $("#slcAprvMore option:selected").text();
            if (slcAprvUserId == "") {
                alert("Approve Name Empty..!!");
                $("#slcAprvMore").focus();
                return false;
            }
        }

        $.post('<?php echo base_url("myapps/updateDataReceive"); ?>', {
                id: idMail,
                txtReason: txtReason,
                anotherApr: anotherApr,
                slcAprvUserId: slcAprvUserId,
                slcAprvName: slcAprvName,
                txtTypeDataModal: txtTypeDataModal
            },
            function(data) {
                alert(data);
                if (sDateSearch == "" & eDateSearch == "") {
                    window.location = "<?php echo base_url("/myapps/getMailRegInv");?>";
                } else {
                    searchData();
                    $("#txtIdMailModal").val('');
                    $("#txtReason").val('');
                    $('#idModalAccept').modal("hide");
                }
            },
            "json"
        );
    }

    function onlickChecked() {
        if ($('#idChkMoreApprove').is(":checked")) {
            $("#slcAprvMore").css('display', '');
        } else {
            $("#slcAprvMore").css('display', 'none');
        }
    }

    function rejectNya() {
        var idMail = $("#txtIdMailModalReject").val();
        var txtReason = $("#txtReasonReject").val();
        var sDateSearch = $("#txtStartDate").val();
        var eDateSearch = $("#txtEndDate").val();

        if (txtReason == "") {
            alert("Reason Empty..!!");
            return false;
        }

        $.post('<?php echo base_url("myapps/updateDataReject"); ?>', {
                id: idMail,
                txtReason: txtReason
            },
            function(data) {
                alert(data);
                if (sDateSearch == "" & eDateSearch == "") {
                    window.location = "<?php echo base_url("/myapps/getMailRegInv");?>";
                } else {
                    searchData();
                    $("#txtIdMailModalReject").val('');
                    $("#txtReasonReject").val('');
                    $('#idModalReject').modal("hide");
                }
            },
            "json"
        );
    }

    function cekCheck() {
        var cekCheck = [];
        $(':checkbox:checked').each(function(i) {
            cekCheck[i] = $(this).val();
        });
        if (cekCheck.length == 0) {
            $("#btnUnrec").attr("disabled", true);
        } else {
            $("#btnUnrec").attr("disabled", false);
        }
    }

    function exportDataMailInvoiceRegister() {
        var searchUnit = $("#slcSearchUnit").val();
        var sDateSearch = $("#txtStartDate").val();
        var eDateSearch = $("#txtEndDate").val();

        if (!sDateSearch || !eDateSearch) {
            alert("Please select a valid date range!");
            return;
        }

        $("#idLoading").show();

        $.ajax({
            url: "<?php echo base_url('myapps/exportDataMailInvoiceRegister'); ?>",
            type: "POST",
            dataType: "json",
            contentType: "application/json",
            data: JSON.stringify({
                searchUnit: searchUnit,
                sDateSearch: sDateSearch,
                eDateSearch: eDateSearch
            }),
            success: function(data) {
                $("#idLoading").hide();

                if (data.error) {
                    alert(data.error);
                    return;
                }

                if (data.length === 0) {
                    alert("No data found to export!");
                    return;
                }

                // var mailGroup = searchUnit && searchUnit !== "all" ? `Mail Group: ${searchUnit}` :
                "Mail Group: BOD SECRETARY";
                var formattedData = [];
                // formattedData.push([mailGroup, "", "", "", ""]); // Mail group header
                formattedData.push(["No", "Batch No", "Sender (Company) / Remark", "Barcode",
                    "Invoice No / Amount"
                ]); // Table header

                data.forEach((row, index) => {
                    formattedData.push([
                        index + 1,
                        row["Batch No"],
                        row["Sender (Company) / Remark"],
                        row["Barcode"],
                        row["Invoice No / Amount"]
                    ]);
                });

                var worksheet = XLSX.utils.aoa_to_sheet(formattedData);
                var range = XLSX.utils.decode_range(worksheet["!ref"]);

                // Style header
                var mailGroupCell = XLSX.utils.encode_cell({
                    r: 0,
                    c: 0
                });
                if (worksheet[mailGroupCell]) {
                    worksheet[mailGroupCell].s = {
                        font: {
                            bold: true,
                            color: {
                                rgb: "B45F04"
                            },
                            sz: 14
                        },
                        alignment: {
                            horizontal: "left"
                        }
                    };
                }

                for (var C = range.s.c; C <= range.e.c; C++) {
                    var tableHeaderCell = XLSX.utils.encode_cell({
                        r: 1,
                        c: C
                    });
                    if (worksheet[tableHeaderCell]) {
                        worksheet[tableHeaderCell].s = {
                            font: {
                                bold: true,
                                color: {
                                    rgb: "FFFFFF"
                                }
                            },
                            fill: {
                                fgColor: {
                                    rgb: "B45F04"
                                }
                            },
                            alignment: {
                                horizontal: "center",
                                vertical: "center"
                            }
                        };
                    }
                }

                // Style rows
                for (var R = 2; R <= range.e.r; R++) {
                    var senderCell = XLSX.utils.encode_cell({
                        r: R,
                        c: 2
                    });
                    if (worksheet[senderCell]) {
                        worksheet[senderCell].s = {
                            alignment: {
                                wrapText: true,
                                vertical: "top"
                            }
                        };
                    }

                    var amountCell = XLSX.utils.encode_cell({
                        r: R,
                        c: 4
                    });
                    if (worksheet[amountCell]) {
                        worksheet[amountCell].s = {
                            font: {
                                color: {
                                    rgb: "FF0000"
                                }
                            },
                            alignment: {
                                horizontal: "right"
                            }
                        };
                    }
                }

                worksheet["!cols"] = [{
                        wch: 5
                    }, // No
                    {
                        wch: 10
                    }, // Batch No
                    {
                        wch: 100
                    }, // Sender / Remark
                    {
                        wch: 15
                    }, // Barcode
                    {
                        wch: 30
                    } // Invoice No / Amount
                ];

                // Create workbook and export
                var workbook = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(workbook, worksheet, "ExportedData");

                var fileName = `MailInvoiceRegister_${new Date().toISOString().slice(0, 10)}.xlsx`;
                XLSX.writeFile(workbook, fileName);

                alert("Data successfully exported!");
            },
            error: function(xhr, status, error) {
                $("#idLoading").hide();
                console.error("Error exporting data:", error);
                alert("Failed to export data. Please try again.");
            }
        });
    }


    function reloadPage() {
        window.location = "<?php echo site_url('myapps/getMailRegInv');?>";
    }
    </script>
</head>

<body>
    <section id="container">
        <section id="main-content">
            <section class="wrapper site-min-height" style="min-height:400px;">
                <h3>
                    <i class="fa fa-angle-right"></i> Mail Register & Invoice Distribution<span
                        style="padding-left: 20px;" id="idLoading"><img
                            src="<?php echo base_url('assets/img/loading.gif'); ?>"></span>
                </h3>
                <div class="form-panel" id="idDataTable">
                    <div class="row">
                        <div class="col-md-2 col-xs-12" style="margin-top:5px;">
                            <select class="form-control input-sm" id="slcSearchUnit">
                                <?php echo $optUnit; ?>
                            </select>
                        </div>
                        <div class="col-md-2 col-xs-12" style="margin-top:5px;">
                            <input placeholder="Search Data" autocomplete="off" type="text"
                                class="form-control input-sm" id="txtSearchData" name="txtSearchData" value="">
                        </div>
                        <div class="col-md-2 col-xs-12" style="margin-top:5px;">
                            <input placeholder="From Date" autocomplete="off" type="text" class="form-control input-sm"
                                id="txtStartDate" name="txtStartDate" value="">
                        </div>
                        <div class="col-md-2 col-xs-12" style="margin-top:5px;">
                            <input placeholder="To Date" autocomplete="off" type="text" class="form-control input-sm"
                                id="txtEndDate" name="txtEndDate" value="">
                        </div>
                        <div class="col-md-3 col-xs-12" style="margin-top:5px;">
                            <button type="submit" id="btnSearch" onclick="searchData();" class="btn btn-primary btn-sm"
                                title="Add"><i class="fa fa-search"></i> Search</button>
                            <button type="button" id="btnCancelSearch" class="btn btn-danger btn-sm" title="Cancel"><i
                                    class="fa fa-ban"></i> Cancel</button>
                            <button type="button" id="btnCancelSearch" onclick="reloadPage();"
                                class="btn btn-success btn-sm" title="Cancel"><i class="fa fa-ban"></i> Refresh</button>
                            <button type="submit" id="btnSearch" onclick="exportDataMailInvoiceRegister();"
                                class="btn btn-primary btn-sm" title="Add"><i class="fa fa-download"></i>
                                Export</button>
                        </div>
                        <div class="col-md-1 col-xs-12" id="btnNavAtas" style="margin-top:5px;">
                            <button style="float: right;" type="submit" id="btnUnrec" class="btn btn-primary btn-sm"
                                title="Un Accept"><i class="fa fa-check-square-o"></i> Un Accept</button>
                        </div>
                    </div>
                    <div class="row mt" id="idData1">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table
                                    class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
                                    <thead>
                                        <tr style="background-color: #ba5500;color: #FFF;">
                                            <th
                                                style="vertical-align: middle; width:3%;text-align: center;padding: 10px;">
                                                SNo</th>
                                            <th style="vertical-align: middle; width:40%;text-align: center;">Sender /
                                                Remark</th>
                                            <th style="vertical-align: middle; width:8%;text-align: center;">Mail ID
                                            </th>
                                            <th style="vertical-align: middle; width:15%;text-align: center;">Invoice No
                                                / Amount</th>
                                            <th style="vertical-align: middle; width:10%;text-align: center;">Batch No
                                            </th>
                                            <th colspan="2"
                                                style="vertical-align: middle; width:15%;text-align: center;">Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="idTbody">
                                        <?php echo $trNya; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </section>
    <div class="modal fade" id="idModalAccept" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#d56b03;">
                    <button type="button" class="close" data-dismiss="modal" style="color:#FFF;">&times;</button>
                    <h4 class="modal-title" id="idTtitleModal"><i>:: Accept Mail & Invoice ::</i></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <input type="hidden" value="" id="txtIdMailModal">
                            <input type="hidden" value="" id="txtTypeDataModal">
                            <label for="txtReason">Reason :</label>
                            <img id="idLoadingModal" src="<?php echo base_url('assets/img/loading.gif'); ?>"
                                style="display:none;">
                            <textarea class="form-control input-sm" id="txtReason"></textarea>
                        </div>
                    </div>
                    <div class="row" style="margin-top:5px;" id="idDivFormAnother">
                        <div class="col-md-7 col-xs-12">
                            <input type="checkbox" value="" id="idChkMoreApprove" onclick="onlickChecked();"> <label
                                for="idChkMoreApprove">Require another Approval</label>
                        </div>
                        <div class="col-md-5 col-xs-12">
                            <select class="form-control input-sm" id="slcAprvMore" style="display:none;">
                                <option value="">- Select Name -</option>
                                <?php echo $optName; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px;">
                        <div class="col-md-6 col-xs-12">
                            <button type="button" id="btnAcceptModal" class="btn btn-primary btn-xs btn-block"
                                onclick="acceptNya();" title="Accept"><i class="fa fa-check-square-o"></i>
                                Accept</button>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <button type="button" id="btnCancelModal" class="btn btn-danger btn-xs btn-block"
                                onclick="reloadPage();" title="Cancel"><i class="fa fa-times"></i> Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="idModalReject" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#d56b03;">
                    <button type="button" class="close" data-dismiss="modal" style="color:#FFF;">&times;</button>
                    <h4 class="modal-title" id="idTtitleModal"><i>:: Reject Mail & Invoice ::</i></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <input type="hidden" value="" id="txtIdMailModalReject">
                            <label for="txtReasonReject">Reason :</label>
                            <textarea class="form-control input-sm" id="txtReasonReject"></textarea>
                        </div>
                    </div>
                    <div class="row" style="margin-top:10px;">
                        <div class="col-md-6 col-xs-12">
                            <button type="button" id="btnRejectModal" class="btn btn-primary btn-xs btn-block"
                                onclick="rejectNya();" title="Reject"><i class="fa fa-check-square-o"></i>
                                Reject</button>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <button type="button" id="btnCancelModalReject" class="btn btn-danger btn-xs btn-block"
                                onclick="reloadPage();" title="Cancel"><i class="fa fa-times"></i> Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>