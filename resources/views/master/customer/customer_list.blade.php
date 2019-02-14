<div style="margin-top: -37px;"></div>
<div class="table-responsive">
<table class="table table-hover" style="font-size:13px">
  <thead>
    <th style="text-align:center">No</th>
    <th>Customer ID</th>
    <th>Class</th>
    <th>Customer Name</th>
    <th>Gender</th>
    <th>Date Join</th>
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
      <td>{{$row->customer_id}}</td>
      <td>{{$row->custclass->customer_class}} </td>
      <td>{{$row->customer_name}}</td>
      <td>{{$row->gender}}</td>
      <td>{{$row->date_join}}</td>
      <td>{{$row->created_at}}</td>
      <td>
        <button class="btn btn-vimeo btn-sm" onclick="edit('{{$row->customer_id}}')">
        <i class="icon-pencil-1"></i>
        Edit
        </button>
        <button class="btn btn-youtube btn-sm" onclick="delete_data('{{$row->customer_id}}')">
        <i class="fa  fa-trash-o"></i>
        Delete
        </button>
      </td>
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
