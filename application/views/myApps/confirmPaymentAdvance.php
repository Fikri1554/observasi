<?php $this->load->view('myApps/menu'); ?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <script type="text/javascript">
    $(document).ready(function() {
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
        $("#btnCancelSearch").click(function() {
            reloadPage();
        });
    });

    function searchData() {
        var searchUnit = $("#slcSearchUnit").val();
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

        $.post('<?php echo base_url("myapps/getConfirmPaymentAdvance/search"); ?>', {
                searchUnit: searchUnit,
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

    function showModalReject(id) {
        $("#idLoading").show();
        $.post('<?php echo base_url("myapps/cekSesseion"); ?>', {},
            function(data) {
                if (data == "ada") {
                    $("#txtIdModalReject").val(id);
                    $('#idModalReject').modal("show");
                } else {
                    alert("Session Expired, Please Login..!!");
                    reloadPage();
                }
            },
            "json"
        );
    }

    function acceptNya(id) {
        var cfm = confirm("Are you sure..??");
        if (cfm) {
            var sDateSearch = $("#txtStartDate").val();
            var eDateSearch = $("#txtEndDate").val();

            $.post('<?php echo base_url("myapps/updateDataPaymentAdvance"); ?>', {
                    id: id
                },
                function(data) {
                    alert(data);
                    if (sDateSearch == "" & eDateSearch == "") {
                        reloadPage();
                    } else {
                        searchData();
                    }
                },
                "json"
            );
        }
    }

    function rejectNya() {
        var id = $("#txtIdModalReject").val();
        var txtReason = $("#txtReasonReject").val();
        var sDateSearch = $("#txtStartDate").val();
        var eDateSearch = $("#txtEndDate").val();

        if (txtReason == "") {
            alert("Reason Empty..!!");
            return false;
        }

        $.post('<?php echo base_url("myapps/rejectPaymentAdvance"); ?>', {
                id: id,
                txtReason: txtReason
            },
            function(data) {
                alert(data);
                if (sDateSearch == "" & eDateSearch == "") {
                    reloadPage();
                } else {
                    searchData();
                    $("#txtIdModalReject").val('');
                    $("#txtReasonReject").val('');
                    $('#idModalReject').modal("hide");
                }
            },
            "json"
        );
    }

    function exportDataConfirmPaymentAdvance() {
        var searchUnit = $("#slcSearchUnit").val();
        var sDateSearch = $("#txtStartDate").val();
        var eDateSearch = $("#txtEndDate").val();

        if (!sDateSearch || !eDateSearch) {
            alert("Please select a valid date range!");
            return;
        }

        $("#idLoading").show();

        $.ajax({
            url: "<?php echo base_url('myapps/exportDataConfirmPaymentAdvance'); ?>",
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
                var mailGroup = searchUnit && searchUnit !== "all" ? `Mail Group: ${searchUnit}` :
                    "Mail Group: BOD SECRETARY";
                var formattedData = [];
                formattedData.push([mailGroup, "", "", "", ""]); // Mail group header
                formattedData.push(["No", "Batch No", "Sender / Remark", "Barcode",
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
                                } // White text
                            },
                            fill: {
                                fgColor: {
                                    rgb: "B45F04"
                                }
                            }, // Orange background
                            alignment: {
                                horizontal: "center",
                                vertical: "center"
                            }
                        };
                    }
                }
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
                }
                for (var R = 2; R <= range.e.r; R++) {
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

                var fileName = `Data-ConfirmPaymentAdvance_${new Date().toISOString().slice(0, 10)}.xlsx`;
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
        window.location = "<?php echo base_url('myapps/getConfirmPaymentAdvance');?>";
    }
    </script>
</head>

<body>
    <section id="container">
        <section id="main-content">
            <section class="wrapper site-min-height" style="min-height:400px;">
                <h3>
                    <i class="fa fa-angle-right"></i> Confirm Payment & Advance<span
                        style="padding-left:20px;display:none;" id="idLoading"><img
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
                            <input placeholder="From Date" autocomplete="off" type="text" class="form-control input-sm"
                                id="txtStartDate" name="txtStartDate" value="">
                        </div>
                        <div class="col-md-2 col-xs-12" style="margin-top:5px;">
                            <input placeholder="To Date" autocomplete="off" type="text" class="form-control input-sm"
                                id="txtEndDate" name="txtEndDate" value="">
                        </div>
                        <div class="col-md-4 col-xs-12" style="margin-top:5px;">
                            <button type="submit" id="btnSearch" onclick="searchData();" class="btn btn-primary btn-sm"
                                title="Add"><i class="fa fa-search"></i> Search</button>
                            <?php if ($this->session->userdata('userTypeMyApps') === 'admin'): ?>
                            <button type="submit" id="btnExport" onclick="exportDataConfirmPaymentAdvance();"
                                class="btn btn-primary btn-sm" title="Export">
                                <i class="fa fa-download"></i> Export
                            </button>
                            <?php endif; ?>
                            <button type="button" id="btnCancelSearch" class="btn btn-danger btn-sm" title="Cancel"><i
                                    class="fa fa-ban"></i> Cancel</button>
                            <button type="button" id="btnCancelSearch" onclick="reloadPage();"
                                class="btn btn-success btn-sm" title="Cancel"><i class="fa fa-ban"></i> Refresh</button>
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
                                                No</th>
                                            <th style="vertical-align: middle; width:10%;text-align: center;">Batch No
                                            </th>
                                            <th style="vertical-align: middle; width:40%;text-align: center;">Sender /
                                                Remark</th>
                                            <th style="vertical-align: middle; width:10%;text-align: center;">Barcode
                                            </th>
                                            <th style="vertical-align: middle; width:22%;text-align: center;">Invoice No
                                                / Amount</th>
                                            <th style="vertical-align: middle; width:15%;text-align: center;">Status
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
                            <input type="hidden" value="" id="txtIdModalReject">
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