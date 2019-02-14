<form class="form-horizontal" role="form">
<input type="hidden" id="id_division" value="{{$row->division_code}}"/>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Division</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="division"  value="{{$row->division_name}}">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
</form>
