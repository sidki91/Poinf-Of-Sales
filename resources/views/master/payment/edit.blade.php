<form class="form-horizontal" role="form">
<input type="hidden" id="id_payment" value="{{$row->payment_id}}"/>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Payment Method</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="payment_method"  value="{{$row->payment_method}}">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
</form>
