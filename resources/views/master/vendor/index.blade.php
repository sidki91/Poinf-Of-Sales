@extends('layouts.app')
@section('content')
@section('title','Vendor')
@section('class_vendor','active subdrop')
@section('class_ul_vendor','display: block')
@section('m_vendor','active')
@section('firstmenu','Master Data')
@section('menu','Vendor Group')
@section('sub_menu','Vendor')
<div class="form-group">
 &nbsp;&nbsp;&nbsp;
<button class="btn btn-facebook add-data"><i class="fa fa-plus"></i> Create Data </button>
<button class="btn btn-github" onclick="list_data()"><i class="fa fa-refresh"></i> Refresh </button>
</div>
<p></p>
<div class="form">
    <div class="form-group">
        <div class="col-md-10">
            <input type="text" class="form-control" id="vendor_search" placeholder="Enter Vendor Name" onkeypress="search_enter(event)">
        </div>
    </div>

    <button class="btn btn-success search-data" onclick="list_data()"><i class="fa fa-search"></i> Search</button>

</div>

<div id="temp_view_table">
    <table class="table table-hover">
        <th style="text-align:center">No</th>
          <th>Vendor ID</th>
          <th>Class</th>
          <th>Vendor Name</th>
          <th>Phone</th>
          <th>City</th>
          <th>Created Date</th>
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

    <script type="text/javascript">
        var save_method;

        function search_enter(e) {
            var key = e.keyCode || e.which;
            if (key == 13) {
                list_data();
            }

        }

        function list_data() {
            var page            = 1;
            var pagination      = '';
            var vendor_name     = $("#vendor_search").val();
            $.ajax({
                type: "POST",
                url: 'vendorList/list_data',
                data: {
                    '_token'           : $('input[name=_token]').val(),
                    'page'             : page,
                    'vendor_name'      : vendor_name
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

        function pagging_click(id) {
            var page          = id;
            var urutan        = 1;
            var pagination    = '';
            var vendor_name     = $("#vendor_search").val();
            document.getElementById('temp_view_table').style.visibility = 'visible';
            $.ajax({
                type: "POST",
                url: 'vendorList/list_data',
                data: {
                    '_token'           : $('input[name=_token]').val(),
                    'page'             : page,
                    'vendor_name'      : vendor_name
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
                url: 'vendorList/add',
                data:
                {
                    '_token': $('input[name=_token]').val()
                },
                success: function(data)
                {
                    if (data.status == 'success')
                    {
                        $("#addModal").modal("show");
                        $('.modal-title').text('Add');
                        $("#HTMLcontent").html(data.html);
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
                url = "vendorList";
            } else {
                url = "vendorList/update";
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    '_token'         : $('input[name=_token]').val(),
                    'vendor_class'   : $('#vendor_class').val(),
                    'vendor_name'    : $("#vendor_name").val(),
                    'bussiness_name' : $("#bussiness_name").val(),
                    'attention'      : $("#attention").val(),
                    'email'          : $("#email").val(),
                    'website'        : $("#website").val(),
                    'phone'          : $("#phone").val(),
                    'address'        : $("#address").val(),
                    'city'           : $("#city").val(),
                    'postal_code'    : $("#postal_code").val(),
                    'payment_method' : $("#payment_method").val(),
                    'terms_payment'  : $("#terms_payment").val(),
                    'currency'       : $("#currency").val(),
                    'tax_reg'        : $("#tax_reg").val(),
                    'tax_type'       : $("#tax_type").val(),
                    'id'             : $('#vendor_id').val()

                },
                success: function(data) {
                    if (data.status == 'error')
                    {
                        if (data.errors.vendor_class)
                        {
                            alertMsg(data.errors.vendor_class, 'error');
                        }
                        if (data.errors.vendor_name)
                        {
                            alertMsg(data.errors.vendor_name, 'error');
                        }
                        if (data.errors.attention)
                        {
                            alertMsg(data.errors.attention, 'error');
                        }
                        if (data.errors.address)
                        {
                            alertMsg(data.errors.address, 'error');
                        }
                        if (data.errors.city)
                        {
                            alertMsg(data.errors.city, 'error');
                        }
                        if (data.errors.payment_method)
                        {
                            alertMsg(data.errors.payment_method, 'error');
                        }
                        if (data.errors.terms_payment)
                        {
                            alertMsg(data.errors.terms_payment, 'error');
                        }
                        if (data.errors.currency)
                        {
                            alertMsg(data.errors.currency, 'error');
                        }

                    }
                    if (data.status == 'success') {
                        $('#addModal').modal('hide');
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
                url: 'vendorList/edit',
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
                            url: 'vendorList/delete',
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
    </script>

    @endsection
