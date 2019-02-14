<form class="form-horizontal" role="form">
<input type="hidden" id="id_category" value="{{$row->id}}"/>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Category Name</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="description" name="description" value="{{$row->description}}">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
</form>
