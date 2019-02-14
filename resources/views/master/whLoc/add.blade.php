<form class="form-horizontal" role="form">
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Warehouse</label>
        <div class="col-sm-8">
            <select class="form-control" id="wh_id">
              @foreach($warehouse as $row)
              <option value="{{$row->wh_code}}">{{$row->wh_description}}</option>
              @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Warehouse Location</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="wh_loc">
        </div>
    </div>

</form>
