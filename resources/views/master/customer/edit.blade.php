<style media="screen">
    .modal-body
    {
        max-height: calc(100vh - 210px);
        overflow-y: auto;
    }
    .datepicker{z-index:1151 !important;}

</style>

<script>
$("#date_of_birth").datepicker();
$("#date_join").datepicker();
$(function() {
    $('input').keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });
});
</script>
<form class="form-horizontal" role="form">
  <input type="hidden" id="customer_id" value="{{$row->customer_id}}"/>
    <div class="col-md-6">
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Customer Class <span style="color:red">*</span></label>
            <div class="col-sm-8">
                <select class="form-control" id="customer_class">
                    @foreach($customer_class as $row_customer)\
                    <?php
                      if($row->cust_class_id == $row_customer->customer_class_id)
                      {
                          $selected_class = "selected='selected'";
                      }
                      else
                      {
                          $selected_class = "";
                      }
                    ?>
                    <option value="{{$row_customer->customer_class_id}}" {{$selected_class}}>{{$row_customer->customer_class}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Customer Name <span style="color:red">*</span></label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="customer_name" value="{{$row->customer_name}}" >
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Place of birth</label>
            <div class="col-sm-6">
                <!-- <input type="text" id="place_of_birth" class="form-control" onkeyup="this.value = this.value.toUpperCase()"> -->
                <input type="text" id="place_of_birth" class="form-control" value="{{$row->place_of_birth}}">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Religion</label>
            <div class="col-sm-6">
                <select class="form-control" id="religion">
                    <option value="">Choose</option>
                    @foreach($religion as $row_religion)
                    <?php
                     if($row->religion == $row_religion->religion_id)
                     {
                        $selected_religion = "selected='selected'";
                     }
                     else
                     {
                        $selected_religion = "";
                     }
                     ?>
                    <option value="{{$row_religion->religion_id}}" {{$selected_religion}}>{{$row_religion->shortdesc}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Gender <span style="color:red">*</span></label>
            <div class="col-sm-6">
                <select class="form-control" id="gender">
                    <option value="">Choose</option>
                    @if($row->gender=='Male')
                    <option value="Male" selected>Male</option>
                    <option value="Female">Female</option>
                    @else
                    <option value="Male">Male</option>
                    <option value="Female" selected>Female</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Date of Birth</label>
            <div class="col-sm-5">
                <div class="input-group">
                    <input type="text" id="date_of_birth" class="form-control" value="{{date('m/d/Y',strtotime($row->date_of_birth))}}" />
                    <span class="input-group-addon">
                      <i class="glyphicon glyphicon-calendar" aria-hidden="true">
                      </i>
                    </span>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Email</label>
            <div class="col-sm-8">
                <input type="text" id="email" class="form-control" value="{{$row->email}}">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Phone <span style="color:red">*</span></label>
            <div class="col-sm-6">
              <input type="text" class="form-control" id="phone" value="{{$row->phone}}"/>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Address <span style="color:red">*</span></label>
            <div class="col-sm-8">
              <textarea id="address" rows="3" cols="80" class="form-control">{{$row->address}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">City <span style="color:red">*</span></label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="city" value="{{$row->city}}"/>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Date Join <span style="color:red">*</span></label>
            <div class="col-sm-5">
              <div class="input-group">
                  <input type="text" id="date_join" class="form-control" value="{{date('m/d/Y',strtotime($row->date_join))}}" />
                  <span class="input-group-addon">
                    <i class="glyphicon glyphicon-calendar" aria-hidden="true">
                    </i>
                  </span>
              </div>
            </div>
        </div>
    </div>
</form>
