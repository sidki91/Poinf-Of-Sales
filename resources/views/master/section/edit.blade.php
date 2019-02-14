<form class="form-horizontal" role="form">
<input type="hidden" id="id_section" value="{{$row->section_code}}"/>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Section</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="section"  value="{{$row->section_name}}">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
</form>
