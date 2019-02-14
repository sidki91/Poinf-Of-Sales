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
            <label for="inputEmail3" class="col-sm-4 control-label">Vendor Class <span style="color:red">*</span></label>
            <div class="col-sm-8">
                <select class="form-control" id="vendor_class">
                    @foreach($vendor_class as $row_class)
                    <option value="{{$row_class->vendor_class_id}}">{{$row_class->vendor_class}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Vendor Name <span style="color:red">*</span></label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="vendor_name">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Bussiness Name</label>
            <div class="col-sm-8">
                <input type="text" id="bussiness_name" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Attention <span style="color:red">*</span></label>
            <div class="col-sm-8">
                <input type="text" id="attention" class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Email </label>
            <div class="col-sm-8">
                <input type="text" id="email" class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Website</label>
            <div class="col-sm-8">
                <input type="text" id="website" class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Phone</label>
            <div class="col-sm-8">
                <input type="text" id="phone" class="form-control"/>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-4 control-label">Address <span style="color:red">*</span></label>
            <div class="col-sm-8">
                <textarea id="address" rows="2" cols="80" class="form-control"></textarea>
            </div>
        </div>

    </div>

    <div class="col-md-6">

        <div class="form-group">
            <label for="inputEmail3" class="col-sm-5 control-label">City <span style="color:red">*</span></label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="city"/>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-5 control-label">Postal Code </label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="postal_code"/>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-5 control-label">Payment Method <span style="color:red">*</span></label>
            <div class="col-sm-7">
              <select class="form-control" id="payment_method">
                <option value="">Choose</option>
                @foreach($payment as $row_payment)
                  <option value="{{$row_payment->payment_id}}">{{$row_payment->payment_method}}</option>
                @endforeach
              </select>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-5 control-label">Terms Payment <span style="color:red">*</span></label>
            <div class="col-sm-7">
              <select class="form-control" id="terms_payment">
                <option value="">Choose</option>
                @foreach($terms_payment as $row_terms)
                  <option value="{{$row_terms->terms_id}}">{{$row_terms->terms_description}}</option>
                @endforeach
              </select>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-5 control-label">Currency <span style="color:red">*</span></label>
            <div class="col-sm-7">
              <select class="form-control" id="currency">
                  <option value="">Choose</option>
                  @foreach($currency as $row_currency)
                  <option value="{{$row_currency->id}}">{{$row_currency->currency_name}}</option>
                  @endforeach
              </select>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-5 control-label">Tax Reg </label>
            <div class="col-sm-7">
              <input type="text" class="form-control" id="tax_reg"/>
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-5 control-label">Tax Type </label>
            <div class="col-sm-5">
            <select class="form-control" id="tax_type">
              <option value="">Choose</option>
              <option value="PPh">PPh</option>
              <option value="PBB">PBB</option>
              <option value="BM">BM</option>
              <option value="PPN">PPN</option>
            </select>
            </div>
        </div>
    </div>
</form>
