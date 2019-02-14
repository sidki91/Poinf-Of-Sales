<div style="margin-top: -37px;"></div>
<div class="table-responsive">
<table class="table table-hover" style="font-size:13px">
  <thead>
    <th style="text-align:center">No</th>
    <th>Customer ID</th>
    <th>Class</th>
    <th>Customer Name</th>
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
      <td>{{$row->customer_id}}</td>
      <td>{{$row->custclass->customer_class}} </td>
      <td>{{$row->customer_name}}</td>
      <td>
        <button class="btn btn-primary btn-sm" onclick="add_customer('{{$row->customer_id}}')">
        <i class="fa fa-pencil"></i>
        </button>
      </td>
    </tr>
<?php $no++; ?>

    @endforeach
    @else
    <tr>
      <td colspan="5">
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
