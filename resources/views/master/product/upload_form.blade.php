<script type="text/javascript">
$(document).ready(function(){
        var url;
           $('#upload_excel').on("submit", function(e){
             e.preventDefault(); //form will not submitted
             swal({
                     title: "Info System",
                     text: "Apakah anda yakin ingin mengupload data ?",
                     icon: "warning",
                     buttons: true,
                     dangerMode: true,
                 })
                 .then((willDelete) => {
                    if (willDelete) {
                $.ajax({

                     url:'product/import_excel',
                     method:"POST",
                     data:new FormData(this),
                     contentType:false,          // The content type used when sending data to the server.
                     cache:false,                // To unable request pages to be cached
                     processData:false,
                     dataType: "JSON",
                     beforeSend: function() {
                         $('#content_msg').html('<center><img src="{{ URL::asset("public/assets/images/831.gif")}}" width="30px" height="30px"/> <img src="{{ URL::asset("public/assets/images/332.gif")}}"/></center>');
                         $("#content_msg").hide();
                         $("#content_msg").fadeIn("slow");
                     },
                     success: function(data){
                          if(data.status=='success')
                          {
                                $("#content_msg").html('');
                                $("#uploadModal").modal("hide");
                                bootbox.alert(data.msg);
                                list_data();
                          }
                          else
                          {
                                $("#content_msg").html('');
                                bootbox.alert(data.msg);

                          }
                     }
                })
              } else {
                  return false;
              }
          });
           });

      });
</script>
<form id="upload_excel" class="form-horizontal" method="post" enctype="multipart/form-data">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <div class="col-md-12">
      <div class="form-group">
          <label for="inputEmail3" class="col-sm-4 control-label">Excel File</label>
          <div class="col-sm-8">
              <input type="file" class="form-control" id="import_file" name="import_file">
          </div>
      </div>
      <div class="row"></div>
      <br/>
      <div class="col-md-6">
          <button type="submit" name="upload" id="upload" class="btn btn-success" style="margin-left: 174px;margin-top:-2px"><i class="fa fa-upload"></i> Upload </button>
      </div>
      <div class="col-md-6">
          <div style="margin-left:5px" id="content_msg"></div>
      </div>
</div>
</form>
<br/>
<br/>
