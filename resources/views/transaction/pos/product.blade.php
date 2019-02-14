

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
        <div class="col-md-2" style="margin-left: -20px;width:190px">
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

            <th style="text-align:center">No</th>
            <th>Product ID</th>
            <th>Barcode</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>UOM</th>
            <th>Sales</th>
            <th>Add</th>

    </table>
</div>

<div id="ajax_list_table"></div>
<p></p>
<div id="pagination" class="pagging">
    <div>
        <a href="#" id="1"></a>
    </div>
</div>

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
        document.getElementById('temp_view_table').style.visibility = 'visible';
        $.ajax({
            type: "POST",
            url: 'pos/list_data_product',
            data: {
                '_token'      : $('input[name=_token]').val(),
                'page'        : page,
                'product_name': product_name,
                'category'    : category,
                'uom'         : uom,
                'show_data'   : show_data,
                'output'      : output,
                'source'      : 'POS'
            },
            beforeSend: function() {
                $('#ajax_list_table').html('<center><img src="{{ URL::asset("public/assets/images/831.gif")}}" width="30px" height="30px"/> <img src="{{ URL::asset("public/assets/images/332.gif")}}"/></center>');
                $("#ajax_list_table").hide();
                $("#ajax_list_table").fadeIn("slow");
            },
            success: function(data) {
                if (data.output == 'HTML')
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
            url: 'pos/list_data',
            data: {
                '_token'      : $('input[name=_token]').val(),
                'page'        : page,
                'product_name': product_name,
                'category'    : category,
                'uom'         : uom,
                'show_data'   : show_data,
                'output'      : output,
                'source'      : 'POS'
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






</script>
