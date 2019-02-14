@extends('layouts.app')
@section('content')
@section('title','Point of Sales')
@section('class_pos','active subdrop')
@section('firstmenu','Transaction')
@section('menu','POS')
<style media="screen">
    .scroll {
        width: 100%;
        display: block;
    }

    .scroll tbody,
    .scroll thead {
        display: block;
    }

    .scroll tbody {
        height: 500px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .dialogProduct > .modal-dialog {
        width: 72% !important;
    }

    .dialogPayment > .modal-dialog {
        width: 30% !important;
    }

    .dialogNota > .modal-dialog {
        width: 21% !important;
    }

    .dialogCustomer > .modal-dialog {
        width: 45% !important;
    }
</style>

<script type="text/javascript">
    document.onkeydown = function(e) {
        switch (e.keyCode) {
            case 112: // F1 (Product)
                product_list();
                break;
            case 113: // F2 (Customer)
                customer_list();
                break;
            case 114: // F3 (Payment)
                payment();
                break;
            case 115: // F4 (Product)

                break;
            case 119: // F6 (Pay)
                alert('F6');
                break;
        }
    };

    function clear() {
        $("#transaction_number").val('');
        $("#sub_total").val('0');
        $("#disc").val('0');
        $("#tax").val('0');
        $("#grand_total").val('0');
    }

    function table_item() {
        var transaction_number = $("#transaction_number").val();
        $.ajax({
            url: "pos/table_item",
            dataType: "json",
            type: "post",
            data: {
                '_token': $('input[name=_token]').val(),
                'transaction_number': transaction_number
            },
            beforeSend: function() {
                $('#table_content_item').html('<center><img src="{{ URL::asset("public/assets/images/831.gif")}}" width="30px" height="30px"/> <img src="{{ URL::asset("public/assets/images/332.gif")}}"/></center>');
                $("#table_content_item").hide();
                $("#table_content_item").fadeIn("slow");
            },
            success: function(result) {
                $("#table_content_item").html(result.html);
            },
            error: function() {
                swal("Opps !", 'sorry, an error occurred while displaying data !', "error");
            }
        });
    }

    $(document).ready(function() {
        clear();
        table_item();

    });

    function product_list() {
        $.ajax({
            url: "pos/product",
            dataType: "json",
            type: "post",
            data: {
                '_token': $('input[name=_token]').val()
            },

            success: function(result) {
                bootbox.dialog({
                    className: "dialogProduct",
                    title: '<div><b>Master Product</b></div>',
                    message: (result.list)
                });
            }
        });
    }

    function customer_list() {
        $.ajax({
            url: "pos/customer",
            dataType: "json",
            type: "post",
            data: {
                '_token': $('input[name=_token]').val()
            },

            success: function(result) {
                bootbox.dialog({
                    className: "dialogCustomer",
                    title: '<div><b>Master Customer</b></div>',
                    message: (result.list)
                });
            }
        });
    }

    function add_customer(customer_id) {
        $.ajax({
            url: "pos/add_customer",
            dataType: "json",
            type: "post",
            data: {
                '_token': $('input[name=_token]').val(),
                'customer_id': customer_id
            },

            success: function(result) {
                if (result.status == 'success') {
                    bootbox.hideAll();
                    $("#customer_id").val(result.customer_id);
                    $("#customer_name").val(result.customer_name);
                } else {
                    swal("Opps !", result.msg, "error");
                }
            }
        });
    }

    function payment_list()
    {
        $.ajax({
            url: "pos/payment_list",
            dataType: "json",
            type: "post",
            data: {
                '_token': $('input[name=_token]').val()
            },

            success: function(result) {
                bootbox.dialog({
                    className : "dialogCustomer",
                    title     : '<div><b>Master Payment</b></div>',
                    message   : (result.list)
                });
            }
        });
    }

    function add_payment(payment_id) {
        $.ajax({
            url: "pos/add_payment",
            dataType: "json",
            type: "post",
            data: {
                '_token': $('input[name=_token]').val(),
                'payment_id': payment_id
            },

            success: function(result) {
                if (result.status == 'success') {
                    bootbox.hideAll();
                    $("#payment_id").val(result.payment_id);
                    $("#payment_method").val(result.payment_method);
                } else {
                    swal("Opps !", result.msg, "error");
                }
            }
        });
    }

    function payment() {
        var transaction_number = $("#transaction_number").val();
        if (transaction_number == '') {
            swal("Opps !", 'Pembayaran ditolak, transaksi tidak tersedia !', "error");
        } else {
            $.ajax({
                url: "pos/payment",
                dataType: "json",
                type: "post",
                data: {
                    '_token': $('input[name=_token]').val(),
                    'transaction_number': transaction_number
                },

                success: function(result) {
                    bootbox.dialog({
                        className: "dialogPayment",
                        title: '<div><b>Payment</b></div>',
                        message: (result.html)

                    });

                }
            });
        }

    }

    function new_order(value) {
        var number = value + 1;
        var n = ($('.neworderbody tr').length - 0) + 1;
        var rows = n - 8;
        var tr = '<tr><td style="text-align:center">' + n + '</td>' + '<td><input typye="text" id="product_id_' + n + '" class="form-control input-sm" style="width:160px;margin-right: -24px;margin-left:0px" onkeypress="onEnter(event,' + n + ')"></td>' +
            '<td><input type="text" class="qty form-control input-sm" name="qty[]" style="width:190px;margin-right: -24px;"></td>' +
            '<td><input type="text" class="price form-control input-sm" name="price[]" style="width:79px;margin-right: -22px;margin-left:0px"></td>' +
            '<td><input type="text" class="dis form-control input-sm" style="width:100px;margin-right: -20px;margin-left:-3px"></td>' +
            '<td><input type="text" class="amount form-control input-sm" style="width:50px;margin-left: -3px;margin-right:-17px;margin-left:-4px"></td>' +
            '<td><input class="form-control input-sm" type="text" style="width:105px;margin-right: -24px;margin-left:-7px"></td>' +
            '<td><button class="btn btn-danger btn-sm" type="button"><i class="glyphicon glyphicon-trash"></i></button></td>';
        '</tr>';
        if (value >= 8) {
            $('.neworderbody').append(tr);
        }

        $("#barcode_id_" + number).focus();
    }

    function onEnter(e, value) {
        var key = e.keyCode || e.which;
        if (key == 13) {
            add_product('', value, 'barcode');
        }

    }

    function add_product(product_id = '', urutan = '', key = '') {
        var transaction_number = $("#transaction_number").val();
        var customer_name = $("#customer_name").val();
        var customer_id = $("#customer_id").val();
        var payment_id = $("#payment_id").val();
        var barcode_id = $("#barcode_id_" + urutan).val();
        $.ajax({
            url: "pos/add_product",
            dataType: "json",
            type: "post",
            data: {
                '_token': $('input[name=_token]').val(),
                'product_id': product_id,
                'barcode_id': barcode_id,
                'transaction_no': transaction_number,
                'customer_id': customer_id,
                'customer_name': customer_name,
                'payment_id': payment_id
            },

            success: function(result) {
                if (result.status == 'success') {
                    bootbox.hideAll();
                    $("#transaction_number").val(result.transaction_no);
                    $("#barcode_id_" + result.max_no).val(result.barcode);
                    $("#product_name_" + result.max_no).val(result.product_name);
                    $("#uom_" + result.max_no).val(result.uom_name);
                    $("#price_" + result.max_no).val(result.sales_price);
                    $("#price_value_" + result.max_no).val(result.sales_price_val);
                    $("#qty_" + result.max_no).val(result.qty);
                    $("#subtotal_" + result.max_no).val(result.sub_total);
                    $("#sub_total_value_" + result.max_no).val(result.sub_total_val);
                    $("#total_qty").html(result.total_qty);
                    $("#total_sales").html(result.total_sales);
                    $("#sub_total").val(result.total_sales);
                    $("#grand_total").val(result.total_sales);
                    $("#trans_line_id_" + result.max_no).val(result.trans_line_id);
                    $("#qty_" + result.max_no).focus();
                    $("#disc_"+result.max_no).val(result.disc);
                    $("#ppn_"+result.max_no).val(result.ppn);
                    if (key == 'barcode') {
                        new_order(urutan);
                    }

                } else {
                    swal("Opps !", result.msg, "error");

                }
            }
        });
    }



    function update(key) {
        var trans_id = $("#transaction_number").val();
        var trans_line_id = $("#trans_line_id_" + key).val();
        var qty  = $("#qty_" + key).val();
        var disc = $("#disc_"+key).val();
        var ppn  = $("#ppn_"+key).val();
        if (trans_line_id == '') {
            swal("Opps !", 'Produk item masih kosong, data gagal diubah !', "error");
        } else {
            $.ajax({
                url: "pos/update",
                dataType: "json",
                type: "post",
                data: {
                    '_token'       : $('input[name=_token]').val(),
                    'trans_id'     : trans_id,
                    'trans_line_id': trans_line_id,
                    'qty'          : qty,
                    'disc'         : disc,
                    'ppn'          : ppn
                },
                success: function(result) {
                    if (result.status == 'success') {
                        $("#subtotal_" + key).val(result.subtotal);
                        $("#total_qty").html(result.total_qty);
                        $("#total_sales").html(result.total_sales);
                        $("#sub_total").val(result.total_sales);
                        $("#grand_total").val(result.total_sales);
                    }
                }
            });
        }

    }

    function delete_item(key) {
        var trans_id = $("#transaction_number").val();
        var trans_line_id = $("#trans_line_id_" + key).val();
        if (trans_line_id == '') {
            swal("Opps !", 'Produk item masih kosong, data gagal dihapus !', "error");
        } else {
            swal({
                    title: "Info System",
                    text: "Apakah anda yakin ingin menghapus data ini ?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "pos/delete",
                            dataType: "json",
                            type: "post",
                            data: {
                                '_token': $('input[name=_token]').val(),
                                'trans_id': trans_id,
                                'trans_line_id': trans_line_id,
                            },
                            success: function(result) {
                                if (result.status == 'success') {
                                    table_item();
                                    $("#sub_total").val(result.total_sales);
                                    $("#grand_total").val(result.total_sales);
                                }
                            }
                        });
                    }
                });
        }

    }
</script>
<div class="content-page">
    <!-- ============================================================== -->
    <!-- Start Content here -->
    <!-- ============================================================== -->
    <div class="content">
        <div class="row">
          <div class="form-group">
           &nbsp;&nbsp;&nbsp;
          <button class="btn btn-facebook add-data">Product (F1) </button>
          <button class="btn btn-github">Customer (F2) </button>
          <button class="btn btn-success">Payment (F3) </button>
          <button class="btn btn-info">Return (F4) </button>
            <button class="btn btn-danger">Cancel (ESC) </button>
          </div>
            <div class="col-lg-10 portlets">
                <div id="website-statistics1" class="widget">
                    <div class="widget-header transparent">
                        <h2><i class="icon-chart-line"></i> <strong>@yield('title')</strong></h2>
                        <div class="additional-btn">

                            <a href="#" class="widget-toggle"><i class="icon-down-open-2"></i></a>
                            <a href="#" class="widget-close"><i class="icon-cancel-3"></i></a>
                        </div>
                    </div>
                    <div class="widget-content">
                        <div id="website-statistic" class="statistic-chart">
                            <div class="row stacked">
                                <div class="col-sm-12">
                                    <div class="toolbar">

                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="widget">
                                        <div class="widget-content padding">
                                            <div id="horizontal-form">
                                                <div id="basic-form">
                                                    <div id="table_content_item"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 portlets">
                <div id="website-statistics1" class="widget">
                    <div class="widget-header transparent">
                        <h2><i class="icon-chart-line"></i> <strong>New Order</strong></h2>
                    </div>
                    <div class="widget-content">
                        <div id="website-statistic" class="statistic-chart">
                            <div class="row stacked">
                                <div class="col-sm-12">
                                    <div class="toolbar">

                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="widget">
                                        <div class="widget-content padding">
                                            <div id="horizontal-form">
                                                <div id="basic-form">
                                                    <form role="form">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">Transaction Number</label>
                                                            <div class="input-group">
                                                                <input type="text" id="transaction_number" class="form-control input-sm" style="border-color:#ddd;background-color:white" readonly />
                                                                <span class="input-group-addon">
                                                                      <i class="fa fa-search" aria-hidden="true">
                                                                      </i>
                                                                    </span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group" style="margin-top:-10px">
                                                            <label for="exampleInputEmail1">Transaction Date</label>
                                                            <div class="input-group">
                                                                <input type="text" id="date_of_birth" class="form-control input-sm" value="{{date('d/m/Y')}}" / style="border-color:#ddd;background-color:white" readonly>
                                                                <span class="input-group-addon">
                                                                      <i class="glyphicon glyphicon-calendar" aria-hidden="true">
                                                                      </i>
                                                                    </span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group" style="margin-top:-10px">
                                                            <label for="exampleInputEmail1">Counter</label>
                                                            <div class="input-group">
                                                                <input type="text" id="counter_name" class="form-control input-sm" value="{{$counter->counter_name}}" style="border-color:#ddd;background-color:white" readonly />
                                                                <input type="hidden" id="counter_id" value="{{$counter->counter_id}}" />
                                                                <span class="input-group-addon">
                                                                      <i class="fa fa-search" aria-hidden="true" onclick="customer_list()" style="cursor:pointer" >
                                                                      </i>
                                                                    </span>
                                                            </div>
                                                        </div>

                                                        <div class="form-group" style="margin-top:-10px">
                                                            <label for="exampleInputEmail1">Customer</label>
                                                            <div class="input-group">
                                                                <input type="text" id="customer_name" class="form-control input-sm" value="{{$customer->customer_name}}" style="border-color:#ddd;background-color:white" readonly />
                                                                <input type="hidden" id="customer_id" value="{{$customer->customer_id}}" />
                                                                <span class="input-group-addon">
                                                                      <i class="fa fa-search" aria-hidden="true" onclick="customer_list()" style="cursor:pointer" >
                                                                      </i>
                                                                    </span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group" style="margin-top:-10px">
                                                            <label for="exampleInputEmail1">Payment</label>
                                                            <div class="input-group">
                                                                <input type="text" id="payment_method" class="form-control input-sm" value="{{$payment->payment_method}}" style="border-color:#ddd;background-color:white" readonly/>
                                                                <input type="hidden" id="payment_id" value="{{$payment->payment_id}}" />
                                                                <span class="input-group-addon">
                                                                      <i class="fa fa-search" aria-hidden="true" style="cursor:pointer" onclick="payment_list()" >
                                                                      </i>
                                                                    </span>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="form-group" style="margin-top:-10px">
                                                            <label for="exampleInputEmail1">Sub Total</label>
                                                            <div class="input-group">
                                                                <input type="text" id="sub_total" class="form-control input-sm" value="0" style="border-color:#ddd;background-color:white" readonly />
                                                                <span class="input-group-addon">
                                                                      <i class="fa fa-money" aria-hidden="true" >
                                                                      </i>
                                                                    </span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group" style="margin-top:-10px">
                                                            <label for="exampleInputEmail1">Disc
                                                                <label class="label label-primary">(0 %)</label>
                                                            </label>
                                                            <div class="input-group">
                                                                <input type="text" id="disc" class="form-control input-sm" value="0" style="border-color:#ddd;background-color:white" readonly />
                                                                <span class="input-group-addon">
                                                                      <i class="fa fa-money" aria-hidden="true" >
                                                                      </i>
                                                                    </span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group" style="margin-top:-10px">
                                                            <label for="exampleInputEmail1">Grand Total</label>
                                                            <div class="input-group">
                                                                <input type="text" id="grand_total" class="form-control input-sm" value="0" style="border-color:#ddd;background-color:white" readonly />
                                                                <span class="input-group-addon">
                                                                      <i class="fa fa-money" aria-hidden="true" >
                                                                      </i>
                                                                    </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="col-md-8"></div>
<!-- Modal created -->
<div id="addModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">@yield('title_modal')</h4>
            </div>
            <div class="modal-body">
                <div id="HTMLcontent">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Category Name</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="description" name="description">
                                <!-- <p class="help-block">Example block-level help text here.</p> -->
                            </div>
                        </div>

                    </form>
                </div </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success add"><i class="fa fa-save"></i> Save changes</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-cancel"></i> Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    @endsection
