@extends('layouts.app')
@section('content')
@section('title','Product')
@section('title_modal','Add Product Category')
@section('class_master','active subdrop')
@section('class_ul','display: block')
@section('m_product','active')
@section('firstmenu','Master Data')
@section('menu','Material Group')
@section('sub_menu','Product')

<div class="form-group">
    &nbsp;&nbsp;&nbsp;
    <button class="btn btn-facebook add-data"><i class="fa fa-plus"></i> Create Data </button>
    <button class="btn btn-success upload-data"><i class="fa fa-upload"></i> Upload Data </button>
    <button class="btn btn-github" onclick="list_data()"><i class="fa fa-refresh"></i> Refresh </button>
</div>
<p></p>
<div class="form">
    <div class="form-group">
        <div class="col-md-3">
            <select class="form-control" id="category_search">
                <option value="">Category Search</option>
                @foreach($category as $row_category)
                <option value="{{$row_category->id}}">{{$row_category->description}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-2" style="margin-left: -20px;width:200px">
            <select class="form-control" id="uom_search">
                <option value="">UOM Search</option>
                @foreach($uom as $row_uom)
                <option value="{{$row_uom->id}}">{{$row_uom->uom_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-3" style="margin-left: -20px;">
            <input type="text" class="form-control" id="product_search" placeholder="Enter Product Name" onkeypress="search_enter(event)">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-2" style="margin-left: -20px;width:150px">
            <select class="form-control" id="output" title="Output">
                <option value="HTML" selected>HTML</option>
                <option value="EXCEL">EXCEL</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-1" style="margin-left: -20px;width:100px">
            <select class="form-control" id="show_data">
                <option value="10" selected>10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>

            </select>
        </div>
    </div>

    <button class="btn btn-success search-data" onclick="list_data()"><i class="fa fa-search"></i> Search</button>

</div>
<hr style=" border: 0; height: 1px; background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));">
<div id="temp_view_table">
    <table class="table table-hover">
        <thead>
            <th style="text-align:center">No</th>
            <th>Barcode</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>UOM</th>
            <th>Purchase</th>
            <th>Sales</th>
            <th>QR Code</th>
            <th>Action</th>
        </thead>
    </table>
</div>
<p></p>
<div id="ajax_list_table"></div>
<p></p>
<div id="pagination" class="pagging">
    <div>
        <a href="#" id="1"></a>
    </div>
</div>
<!-- Modal created -->
<div id="addModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">@yield('title_modal')</h4>
            </div>
            <div class="modal-body">
                <div id="HTMLcontent"></div>
            </div>
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

<!-- Modal Upload -->
<div id="uploadModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">@yield('title_modal')</h4>
            </div>
            <div class="modal-body">
                <div id="HTMLupload"></div>
            </div>
            <div class="modal-footer">
              </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script type="text/javascript">


    var save_method;

    function search_enter(e) {
        var key = e.keyCode || e.which;
        if (key == 13) {
            list_data();
        }

    }

    function list_data() {
        var page = 1;
        var pagination = '';
        var category = $("#category_search").val();
        var uom = $("#uom_search").val();
        var product_name = $("#product_search").val();
        var show_data = $("#show_data").val();
        var output = $("#output").val();
        document.getElementById('temp_view_table').style.visibility='visible';
        $.ajax({
            type: "POST",
            url: 'product/list_data',
            data: {
                '_token': $('input[name=_token]').val(),
                'page': page,
                'product_name': product_name,
                'category': category,
                'uom': uom,
                'show_data': show_data,
                'output': output
            },
            beforeSend: function() {
                $('#ajax_list_table').html('<center><img src="{{ URL::asset("public/assets/images/831.gif")}}" width="30px" height="30px"/> <img src="{{ URL::asset("public/assets/images/332.gif")}}"/></center>');
                $("#ajax_list_table").hide();
                $("#ajax_list_table").fadeIn("slow");
            },
            success: function(data) {
                if (data.output == 'HTML') {
                    document.getElementById('temp_view_table').style.visibility = 'hidden';
                    $('#ajax_list_table').html(data.html);
                    if (page == 1) pagination += '<div class="cell_disabled"><span>First</span></div><div class="cell_disabled"><span>Previous</span></div>';
                    else pagination += '<div class="cell"><a href="#" id="1" onclick="pagging_click(' + urutan + ')" >First</a></div><div class="cell"><a href="#" id="' + (page - 1) + '" onclick="pagging_click(' + (page - 1) + ')" >Previous</span></a></div>';

                    for (var i = parseInt(page) - 3; i <= parseInt(page) + 3; i++) {
                        if (i >= 1 && i <= data.numPage) {
                            pagination += '<div';
                            if (i == page) pagination += ' class="cell_active"><span>' + i + '</span>';
                            else pagination += ' class="cell"><a href="#" id="' + i + '" onclick="pagging_click(' + i + ')" >' + i + '</a>';
                            pagination += '</div>';
                        }
                    }

                    if (page == data.numPage) pagination += '<div class="cell_disabled"><span>Next</span></div><div class="cell_disabled"><span>Last</span></div>';
                    else pagination += '<div class="cell"><a href="#" id="' + (parseInt(page) + 1) + '" onclick="pagging_click(' + (parseInt(page) + 1) + ')"  >Next</a></div><div class="cell"><a href="#" id="' + data.numPage + '" onclick="pagging_click(' + data.numPage + ')">Last</span></a></div>';
                    pagination += '<div class="cell"><a>Total Data : ' + data.numitem + ' Item</a></div>';

                    $('#pagination').html(pagination);

                }
                else
                {
                    document.getElementById('temp_view_table').style.visibility = 'hidden';
                    $('#ajax_list_table').html(data.html);
                    if (page == 1) pagination += '<div class="cell_disabled"><span>First</span></div><div class="cell_disabled"><span>Previous</span></div>';
                    else pagination += '<div class="cell"><a href="#" id="1" onclick="pagging_click(' + urutan + ')" >First</a></div><div class="cell"><a href="#" id="' + (page - 1) + '" onclick="pagging_click(' + (page - 1) + ')" >Previous</span></a></div>';

                    for (var i = parseInt(page) - 3; i <= parseInt(page) + 3; i++) {
                        if (i >= 1 && i <= data.numPage) {
                            pagination += '<div';
                            if (i == page) pagination += ' class="cell_active"><span>' + i + '</span>';
                            else pagination += ' class="cell"><a href="#" id="' + i + '" onclick="pagging_click(' + i + ')" >' + i + '</a>';
                            pagination += '</div>';
                        }
                    }

                    if (page == data.numPage) pagination += '<div class="cell_disabled"><span>Next</span></div><div class="cell_disabled"><span>Last</span></div>';
                    else pagination += '<div class="cell"><a href="#" id="' + (parseInt(page) + 1) + '" onclick="pagging_click(' + (parseInt(page) + 1) + ')"  >Next</a></div><div class="cell"><a href="#" id="' + data.numPage + '" onclick="pagging_click(' + data.numPage + ')">Last</span></a></div>';
                    pagination += '<div class="cell"><a>Total Data : ' + data.numitem + ' Item</a></div>';

                    $('#pagination').html(pagination);
                    if(data.status_link=='ok')
                    {
                        window.open(data.link);
                    }
                    else
                    {
                        alertMsg(data.msg, 'error');
                    }


                }

            },
            error: function() {
                swal("Opps !", 'sorry, an error occurred while displaying data !', "error");
            }
        });
    }

    function pagging_click(id) {
        var page = id;
        var urutan = 1;
        var pagination = '';
        var category = $("#category_search").val();
        var uom = $("#uom_search").val();
        var product_name = $("#product_search").val();
        var show_data = $("#show_data").val();
        var output = $("#output").val();
        document.getElementById('temp_view_table').style.visibility = 'visible';
        $.ajax({
            type: "POST",
            url: 'product/list_data',
            data: {
                '_token': $('input[name=_token]').val(),
                'page': page,
                'product_name': product_name,
                'category': category,
                'uom': uom,
                'show_data': show_data,
                'output': output
            },
            beforeSend: function() {
                $('#ajax_list_table').html('<center><img src="{{ URL::asset("public/assets/images/831.gif")}}" width="30px" height="30px"/> <img src="{{ URL::asset("public/assets/images/332.gif")}}"/></center>');
                $("#ajax_list_table").hide();
                $("#ajax_list_table").fadeIn("slow");
            },
            success: function(data) {

                document.getElementById('temp_view_table').style.visibility = 'hidden';
                $('#ajax_list_table').html(data.html);
                if (page == 1) pagination += '<div class="cell_disabled"><span>First</span></div><div class="cell_disabled"><span>Previous</span></div>';
                else pagination += '<div class="cell"><a href="#" id="1" onclick="pagging_click(' + urutan + ')" >First</a></div><div class="cell"><a href="#" id="' + (page - 1) + '" onclick="pagging_click(' + (page - 1) + ')" >Previous</span></a></div>';

                for (var i = parseInt(page) - 3; i <= parseInt(page) + 3; i++) {
                    if (i >= 1 && i <= data.numPage) {
                        pagination += '<div';
                        if (i == page) pagination += ' class="cell_active"><span>' + i + '</span>';
                        else pagination += ' class="cell"><a href="#" id="' + i + '" onclick="pagging_click(' + i + ')" >' + i + '</a>';
                        pagination += '</div>';
                    }
                }

                if (page == data.numPage) pagination += '<div class="cell_disabled"><span>Next</span></div><div class="cell_disabled"><span>Last</span></div>';
                else pagination += '<div class="cell"><a href="#" id="' + (parseInt(page) + 1) + '" onclick="pagging_click(' + (parseInt(page) + 1) + ')"  >Next</a></div><div class="cell"><a href="#" id="' + data.numPage + '" onclick="pagging_click(' + data.numPage + ')">Last</span></a></div>';
                pagination += '<div class="cell"><a>Total Data : ' + data.numitem + ' Item</a></div>';

                $('#pagination').html(pagination);

            },
            error: function() {
                swal("Opps !", 'sorry, an error occurred while displaying data !', "error");
            }
        });
    }
    $(document).ready(function() {
        list_data();
    });

    $(document).on('click', '.add-data', function() {
        save_method = 'add';
        $.ajax({
            type: 'POST',
            url: 'product/add',
            data: {
                '_token': $('input[name=_token]').val()
            },
            success: function(data) {
                if (data.status == 'success') {
                    $("#addModal").modal("show");
                    $('.modal-title').text('Add');
                    $("#HTMLcontent").html(data.html);
                } else {
                    alertMsg(data.msg, 'error');
                }
            },
        });
    });

    $(document).on('click', '.upload-data', function() {
        save_method = 'add';
        $.ajax({
            type: 'POST',
            url: 'product/upload_form',
            data: {
                '_token': $('input[name=_token]').val()
            },
            success: function(data)
            {
                if (data.status == 'success')
                {
                    $("#uploadModal").modal("show");
                    $('.modal-title').text('Upload Data');
                    $("#HTMLupload").html(data.html);
                }
                else
                {
                    alertMsg(data.msg, 'error');
                }
            },
        });
    });

    $('.modal-footer').on('click', '.add', function() {
        var url;
        if (save_method == 'add') {
            url = "product";
        } else {
            url = "product/update";
        }
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                '_token'                  : $('input[name=_token]').val(),
                'barcode_code'            : $('#barcode_code').val(),
                'product_name'            : $('#product_name').val(),
                'product_category'        : $('#product_category').val(),
                'uom'                     : $('#uom').val(),
                'currency'                : $('#currency').val(),
                'payment'                 : $("#payment").val(),
                'percentage'              : $("#percentage").val(),
                'percentage_value'        : $("#percentage_value").val(),
                'purchase_price'          : $("#purchase_price_value").val(),
                'sales_price'             : $("#sales_price_value").val(),
                'disc'                    : $("#disc").val(),
                'disc_value'              : $("#disc_value").val(),
                'tax'                     : $("#tax").val(),
                'tax_value'               : $("#tax_value").val(),
                'total_sales_price'       : $("#total_sales_price_value").val(),
                'id'                      : $('#product_id').val()

            },
            success: function(data) {
                if (data.status == 'error') {
                    if (data.errors.barcode_code) {
                        alertMsg(data.errors.barcode_code, 'error');
                    }
                    if (data.errors.product_name) {
                        alertMsg(data.errors.product_name, 'error');
                    }
                    if (data.errors.product_category) {
                        alertMsg(data.errors.product_category, 'error');
                    }
                    if (data.errors.uom) {
                        alertMsg(data.errors.uom, 'error');
                    }
                    if (data.errors.currency) {
                        alertMsg(data.errors.currency, 'error');
                    }
                    if (data.errors.payment) {
                        alertMsg(data.errors.payment, 'error');
                    }
                    if (data.errors.percentage) {
                        alertMsg(data.errors.percentage, 'error');
                    }
                    if (data.errors.purchase_price) {
                        alertMsg(data.errors.purchase_price, 'error');
                    }
                    if (data.errors.sales_price) {
                        alertMsg(data.errors.sales_price, 'error');
                    }
                    if (data.errors.total_sales_price) {
                        alertMsg(data.errors.total_sales_price, 'error');
                    }

                }
                if (data.status == 'success') {
                    $('#addModal').modal('hide');
                    // alertMsg(data.msg,'success');
                    swal("Info System", data.msg, "success");
                    list_data();
                }
                if (data.status == 'failed') {
                    alertMsg(data.msg, 'error');
                }

            },
        });
    });

    function edit(id) {
        save_method = 'update';
        $.ajax({
            type: 'POST',
            url: 'product/edit',
            data: {
                '_token': $('input[name=_token]').val(),
                'id': id

            },
            success: function(data) {
                if (data.status == 'success') {
                    $("#addModal").modal("show");
                    $('.modal-title').text('Edit');
                    $("#HTMLcontent").html(data.html);
                } else {
                    alertMsg(data.msg, 'error');
                }
            },
        });

    }

    function delete_data(id) {
        swal({
                title: "Info System",
                text: "Are you sure you want to delete this data ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: 'POST',
                        url: 'product/delete',
                        data: {
                            '_token': $('input[name=_token]').val(),
                            'id': id

                        },
                        success: function(data) {
                            if (data.status == 'success') {
                                swal("Good job!", data.msg, "success");
                                list_data();
                            } else {
                                swal("Not Good !", data.msg, "error");
                                list_data();
                            }
                        }
                    });
                } else {
                    return false;
                }
            });
    }

    function export_data() {

        $.ajax({
            type: 'POST',
            url: 'product/export',
            data: {
                '_token': $('input[name=_token]').val()
            },
            success: function(data) {
                if (data.status == 'success') {
                    $("#addModal").modal("show");
                    $('.modal-title').text('Add');
                    $("#HTMLcontent").html(data.html);
                } else {
                    alertMsg(data.msg, 'error');
                }
            },
        });
    }


</script>

@endsection
