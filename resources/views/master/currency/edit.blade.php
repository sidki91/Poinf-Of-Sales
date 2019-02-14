<form class="form-horizontal" role="form">
<input type="hidden" id="id_currency" value="{{$row->id}}"/>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Currency Name</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="currency_name"  value="{{$row->currency_name}}">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
</form>
