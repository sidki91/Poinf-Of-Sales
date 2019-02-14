@if($array['action']=='save')
<button class="btn btn-facebook add-data"><i class="fa fa-plus"></i> Create Data </button>
  <a href="{{URL::to('priveleges')}}" class="btn btn-github"><i class="fa fa-refresh"></i> Refresh </a>
<p></p>
<div style="margin-bottom: 49px;"></div>
@endif
<div style="margin-top: -37px;"></div>
<div class="table-responsive">
<table class="table table-hover">
  <thead>
    <th style="text-align:center">No</th>
    <th>Level Name</th>
    <th>Created Date</th>
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


    <tr>
      <td style="text-align:center">{{$no}}</td>
      <td>{{$row->gname}}</td>
      <td>{{$row->created_at}}</td>
      <td>
        @if(access_level_user('alter','priveleges')=='allow')
        <button class="btn btn-vimeo btn-sm" onclick="edit({{$row->group_id}})">
        <i class="icon-pencil-1"></i>
        Edit
        </button>
        @endif
        @if(access_level_user('drop','priveleges')=='allow')
        <button class="btn btn-youtube btn-sm" onclick="delete_data({{$row->group_id}})">
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
      <td colspan="4">
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
