<form class="form-horizontal" role="form">
<input type="hidden" id="tax_id" value="{{$row->tax_id}}"/>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Tax Name</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="tax_name"  value="{{$row->tax_name}}">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Tax Value</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="tax_value"  value="{{$row->tax_value}}">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
</form>
