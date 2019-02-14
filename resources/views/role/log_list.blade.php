<div style="margin-top: -37px;"></div>
<div class="table-responsive">
<table class="table table-hover" style="font-size:13px">
  <thead>
    <th style="text-align:center">No</th>
    <th>Module</th>
    <th>Activity</th>
    <th>User</th>
    <th>Log Date</th>
    <th>Desc</th>
    <th>Browser</th>
    <th>Platform</th>
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
      <td>{{$row->modules}}</td>
      <td>{{$row->activity}}</td>
      <td>{{$row->name}}</td>
      <td>{{$row->log_date}}</td>
      <td>{{$row->desc_log}}</td>
      <td>{{$row->browser}}</td>
      <td>{{$row->platform}}</td>
    </tr>
<?php $no++; ?>

    @endforeach
    @else
    <tr>
      <td colspan="8">
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
