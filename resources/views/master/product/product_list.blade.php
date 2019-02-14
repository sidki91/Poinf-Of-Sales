<div style="margin-top: -37px;"></div>
<div class="table-responsive">
<table class="table table-hover" style="display: block;
        overflow-x: auto;
        white-space: nowrap;">
  <thead>
    <th style="text-align:center">No</th>
    <th>Barcode</th>
    <th>Product Name</th>
    <th>Category</th>
    <th>UOM</th>
    <th>Purchase</th>
    <th>Sales</th>
    <th>QR Code</th>
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
      <td>{{$row->barcode_id}}</td>
      <td>{{$row->product_name}}</td>
      <td>{{$row->category->description}}</td>
      <td>{{$row->uom->uom_name}}</td>
      <td>{{number_format($row->purchase_price)}}</td>
      <td>{{number_format($row->sales_total_price)}}</td>
      <td><?php echo DNS2D::getBarcodeHTML($row->barcode_id, "QRCODE",1.7,1.7);?></td>
      <td>

        <button class="btn btn-vimeo btn-sm" onclick="edit('{{$row->product_id}}')">
        <i class="icon-pencil-1"></i>
        Edit
        </button>
        <button class="btn btn-youtube btn-sm" onclick="delete_data('{{$row->product_id}}')">
        <i class="fa  fa-trash-o"></i>
        Delete
        </button>
      </td>
    </tr>
<?php $no++; ?>

    @endforeach
    @else
    <tr>
      <td colspan="9">
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
