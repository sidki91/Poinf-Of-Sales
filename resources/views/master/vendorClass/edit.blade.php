<form class="form-horizontal" role="form">
<input type="hidden" id="vendor_class_id" value="{{$row->vendor_class_id}}"/>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Vendor Class</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="vendor_class"  value="{{$row->vendor_class}}">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
</form>
