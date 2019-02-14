<form class="form-horizontal" role="form">
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Name</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="name" name="name">
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Email</label>
        <div class="col-sm-9">
            <input type="email" class="form-control" id="email" name="email">
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Username</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="username" name="username">
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Password</label>
        <div class="col-sm-9">
            <input type="password" class="form-control" id="password" name="password">
        </div>
    </div>

    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Counter</label>
        <div class="col-sm-9">
          <select class="form-control" id="priveleges">
            @foreach($counter as $row_counter)
            <option value="{{$row_counter->counter_id}}">{{$row_counter->counter_name}}</option>
            @endforeach
          </select>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Priveleges</label>
        <div class="col-sm-9">
          <select class="form-control" id="priveleges">
            @foreach($priveleges as $row)
            <option value="{{$row->group_id}}">{{$row->gname}}</option>
            @endforeach
          </select>
        </div>
    </div>
</form>
