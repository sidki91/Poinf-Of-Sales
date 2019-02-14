<form class="form-horizontal" role="form">
<input type="hidden" id="customer_class_id" value="{{$row->customer_class_id}}"/>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Customer Class</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="customer_class"  value="{{$row->customer_class}}">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
</form>
