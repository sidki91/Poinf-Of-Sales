<form class="form-horizontal" role="form">
  <input type="hidden" id="wh_loc_id" value="{{$row->wh_loc_id}}"/>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Warehouse</label>
        <div class="col-sm-8">
            <select class="form-control" id="wh_id">
              @foreach($warehouse as $row_item)
              <?php if($row->wh_id==$row_item->wh_code)
                    {
                        $selected ="selected='selected'";
                    }
                    else
                    {
                        $selected = "";
                    }

               ?>
              <option value="{{$row_item->wh_code}}" {{$selected}}>{{$row_item->wh_description}}</option>
              @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Warehouse Location</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="wh_loc" value="{{$row->location}}">
        </div>
    </div>

</form>
