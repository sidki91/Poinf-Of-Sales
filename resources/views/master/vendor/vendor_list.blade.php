<div style="margin-top: -37px;"></div>
<div class="table-responsive">
<table class="table table-hover" style="font-size:13px">
  <thead>
    <th style="text-align:center">No</th>
    <th>Vendor ID</th>
    <th>Class</th>
    <th>Vendor Name</th>
    <th>Phone</th>
    <th>City</th>
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
      <td>{{$row->vendor_id}}</td>
      <td>{{$row->classVendor->vendor_class}} </td>
      <td>{{$row->vendor_name}}</td>
      <td>{{$row->phone1}}</td>
      <td>{{$row->city}}</td>
      <td>{{$row->created_at}}</td>
      <td>
        <button class="btn btn-vimeo btn-sm" onclick="edit('{{$row->vendor_id}}')">
        <i class="icon-pencil-1"></i>
        Edit
        </button>
        <button class="btn btn-youtube btn-sm" onclick="delete_data('{{$row->vendor_id}}')">
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
