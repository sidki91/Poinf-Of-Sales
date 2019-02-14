<form class="form-horizontal" role="form">
  <input type="hidden" id="id_user" value="{{$row->id}}"/>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Name</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="name" name="name" value="{{$row->name}}">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Email</label>
        <div class="col-sm-9">
            <input type="email" class="form-control" id="email" name="email" value="{{$row->email}}">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Username</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="username" name="username" value="{{$row->username}}">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Password</label>
        <div class="col-sm-9">
            <input type="password" class="form-control" id="username" name="username" value="123456">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Priveleges</label>
        <div class="col-sm-9">
          <select class="form-control" id="priveleges">
            @foreach($priveleges as $item)
            <?php
              if($row->access_id == $item->group_id)
              {
                $selected = "selected='selected'";
              }
              else
              {
                $selected = "";
              }

             ?>


            <option value="{{$item->group_id}}" {{$selected}}>{{$item->gname}}</option>
            @endforeach
          </select>
        </div>
    </div>
</form>
