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
    <div class="col-md-6">
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Customer Class <span style="color:red">*</span></label>
            <div class="col-sm-8">
                <select class="form-control" id="customer_class">
                    @foreach($customer_class as $row_customer)
                    <option value="{{$row_customer->customer_class_id}}">{{$row_customer->customer_class}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Customer Name <span style="color:red">*</span></label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="customer_name">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Place of birth</label>
            <div class="col-sm-6">
                <input type="text" id="place_of_birth" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Religion</label>
            <div class="col-sm-6">
                <select class="form-control" id="religion">
                    <option value="">Choose</option>
                    @foreach($religion as $row_religion)
                    <option value="{{$row_religion->religion_id}}">{{$row_religion->shortdesc}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Gender <span style="color:red">*</span></label>
            <div class="col-sm-6">
                <select class="form-control" id="gender">
                    <option value="">Choose</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Date of Birth</label>
            <div class="col-sm-5">
                <div class="input-group">
                    <input type="text" id="date_of_birth" class="form-control" value="<?=date('m/d/Y')?>" />
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
                <input type="text" id="email" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Phone <span style="color:red">*</span></label>
            <div class="col-sm-6">
              <input type="text" class="form-control" id="phone"/>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Address <span style="color:red">*</span></label>
            <div class="col-sm-8">
              <textarea id="address" rows="3" cols="80" class="form-control"></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">City <span style="color:red">*</span></label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="city"/>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Date Join <span style="color:red">*</span></label>
            <div class="col-sm-5">
              <div class="input-group">
                  <input type="text" id="date_join" class="form-control" value="<?=date('m/d/Y')?>" />
                  <span class="input-group-addon">
                    <i class="glyphicon glyphicon-calendar" aria-hidden="true">
                    </i>
                  </span>
              </div>
            </div>
        </div>
    </div>
</form>
