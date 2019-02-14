<div class="form">
    <div class="form-group">
        <div class="col-md-10">
            <input type="text" class="form-control" id="customer_name_search" placeholder="Enter Customer" onkeypress="search_enter(event)">
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

<script type="text/javascript">
    function search_enter(e) {
        var key = e.keyCode || e.which;
        if (key == 13) {
            list_data();
        }

    }

    function list_data() {
        var page = 1;
        var pagination = '';
        var customer_name = $("#customer_name_search").val();
        $.ajax({
            type: "POST",
            url: 'pos/list_data_customer',
            data: {
                '_token': $('input[name=_token]').val(),
                'page': page,
                'customer_name': customer_name
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
        var page = id;
        var urutan = 1;
        var pagination = '';
        var customer_name = $("#customer_name_search").val();
        document.getElementById('temp_view_table').style.visibility = 'visible';
        $.ajax({
            type: "POST",
            url: 'pos/list_data_customer',
            data: {
                '_token': $('input[name=_token]').val(),
                'page': page,
                'customer_class': customer_class
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
