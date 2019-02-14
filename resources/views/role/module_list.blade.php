@include('style')
<div style="margin-top: -37px;"></div>
<div class="table-responsive">
<table class="table table-hover table-responsive">
  <thead>
    <th style="text-align:center">No</th>
    <th>Module ID</th>
    <th>Module</th>
    <th>View</th>
    <th>Create</th>
    <th>Alter</th>
    <th>Drop</th>
    <th>Action</th>
  </thead>
  <tbody>
    <?php
    $bates=$array['perpage'];
    $klik=$array['page'];
    $klik1=$klik-1;
    if ($klik=='1')
    {
    $no = 1;
    }
    else
    {
    $no = ($bates * $klik1)+1;
    }

    ?>
    @if($array['count']>=1)
    @foreach($data as $key => $row)
    <?php
     $checked_enable = '';
     $checked_create = '';
     $checked_alter  = '';
     $checked_drop   = '';
     if($row->enable==1)
     {
       $checked_enable = "checked";
     }
     if($row->r_create==1)
     {
       $checked_create = "checked";
     }
     if($row->r_alter==1)
     {
       $checked_alter = "checked";
     }
     if($row->r_drop==1)
     {
       $checked_drop = "checked";
     }
     ?>

    <tr>
      <td style="text-align:center">{{$no}}</td>
      <td>{{$row->modid}}</td>
      <td>{{$row->modules}}</td>
      <td>
        <input type='checkbox' class='ios8-switch' id='action_view_{{$row->modid}}' {{$checked_enable}} name="action_view" value="{{$row->modid}}">
        <label for='action_view_{{$row->modid}}'>&nbsp;</label>
      </td>
      <td>  <input type="checkbox" class="ios8-switch" id="action_create_{{$row->modid}}"{{$checked_create}} name="action_create" value="{{$row->modid}}"/>
        <label for="action_create_{{$row->modid}}"><span></span></label></td>
        <td>
          <input type="checkbox" class="ios8-switch" id="action_alter_{{$row->modid}}" {{$checked_alter}} name="action_alter" value="{{$row->modid}}"/>
          <label for="action_alter_{{$row->modid}}"><span></span></label>
        </td>

          <td>
            <input type="checkbox" class="ios8-switch" id="action_drop_{{$row->modid}}" {{$checked_drop}} name="action_drop" value="{{$row->modid}}"/>
          <label for="action_drop_{{$row->modid}}"><span></span></label>
        </td>
        <td>
        @if(access_level_user('alter','module')=='allow')
        <button class="btn btn-vimeo btn-sm" onclick="edit({{$row->modid}})">
        <i class="icon-pencil-1"></i>
        Edit
        </button>
        @endif
        @if(access_level_user('drop','module')=='allow')
        <button class="btn btn-youtube btn-sm" onclick="delete_data({{$row->modid}})">
        <i class="fa  fa-trash-o"></i>
        Delete
        </button>
        @endif
      </td>
    </tr>
<?php $no++; ?>

    @endforeach
    @else
    <tr>
      <td colspan="6">
        <div class="alert alert-danger alert-dismissable">
          <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
        Data is not available in the database, please check again.
      </div>
    </td>
    </tr>
    @endif
  </tbody>
</table>
</div>
