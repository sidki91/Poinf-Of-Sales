@extends('layouts.app')
@section('content')
@section('title','Customer')
@section('class_customer','active subdrop')
@section('class_ul_customer','display: block')
@section('m_customer','active')
@section('firstmenu','Master Data')
@section('menu','Customer Group')
@section('sub_menu','Customer')
<div class="form-group">
 &nbsp;&nbsp;&nbsp;
<button class="btn btn-facebook add-data"><i class="fa fa-plus"></i> Create Data </button>
<button class="btn btn-github" onclick="list_data()"><i class="fa fa-refresh"></i> Refresh </button>
</div>
<p></p>
<div class="form">
    <div class="form-group">
        <div class="col-md-10">
            <input type="text" class="form-control" id="customer_name" placeholder="Enter Customer" onkeypress="search_enter(event)">
        </div>
    </div>

    <button class="btn btn-success search-data" onclick="list_data()"><i class="fa fa-search"></i> Search</button>

</div>

<div id="temp_view_table">
    <table class="table table-hover">
        <thead>
          <th style="text-align:center">No</th>
            <th>Customer ID</th>
            <th>Class</th>
            <th>Customer Name</th>
            <th>Gender</th>
            <th>Date Join</th>
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
            var customer_name   = $("#customer_name").val();
            $.ajax({
                type: "POST",
                url: 'customer/list_data',
                data: {
                    '_token'           : $('input[name=_token]').val(),
                    'page'             : page,
                    'customer_name'    : customer_name
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
            var page            = id;
            var urutan          = 1;
            var pagination      = '';
            var customer_name   = $("#customer_name").val();
            document.getElementById('temp_view_table').style.visibility = 'visible';
            $.ajax({
                type: "POST",
                url: 'customer/list_data',
                data: {
                    '_token'           : $('input[name=_token]').val(),
                    'page'             : page,
                    'customer_class'   : customer_class
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
                url: 'customer/add',
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
                url = "customer";
            } else {
                url = "customer/update";
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    '_token'         : $('input[name=_token]').val(),
                    'customer_class' : $('#customer_class').val(),
                    'customer_name'  : $("#customer_name").val(),
                    'place_of_birth' : $("#place_of_birth").val(),
                    'religion'       : $("#religion").val(),
                    'gender'         : $("#gender").val(),
                    'date_of_birth'  : $("#date_of_birth").val(),
                    'email'          : $("#email").val(),
                    'phone'          : $("#phone").val(),
                    'address'        : $("#address").val(),
                    'city'           : $("#city").val(),
                    'date_join'      : $("#date_join").val(),
                    'id'             : $('#customer_id').val()

                },
                success: function(data) {
                    if (data.status == 'error')
                    {
                        if (data.errors.customer_class)
                        {
                            alertMsg(data.errors.customer_class, 'error');
                        }
                        if (data.errors.customer_name)
                        {
                            alertMsg(data.errors.customer_name, 'error');
                        }
                        if (data.errors.gender)
                        {
                            alertMsg(data.errors.gender, 'error');
                        }
                        if (data.errors.phone)
                        {
                            alertMsg(data.errors.phone, 'error');
                        }
                        if (data.errors.address)
                        {
                            alertMsg(data.errors.address, 'error');
                        }
                        if (data.errors.city)
                        {
                            alertMsg(data.errors.city, 'error');
                        }
                        if (data.errors.date_join)
                        {
                            alertMsg(data.errors.date_join, 'error');
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
                url: 'customer/edit',
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
                            url: 'customer/delete',
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
