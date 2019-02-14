<form class="form-horizontal" role="form">
<input type="hidden" id="id_counter" value="{{$row->counter_id}}"/>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Counter</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="counter"  value="{{$row->counter_name}}">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
</form>
