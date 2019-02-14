<div style="margin-top: -65px;"></div>
<div class="table-responsive">
<table class="table table-hover" style="font-size:13px">
  <thead>
    <th style="text-align:center">No</th>
    <th>Product ID</th>
    <th>Barcode</th>
    <th>Product Name</th>
    <th>Category</th>
    <th>UOM</th>
    <th>Sales</th>
    <th>Add</th>
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
      <td>{{$row->product_id}}</td>
      <td>{{$row->barcode_id}}</td>
      <td>{{$row->product_name}}</td>
      <td>{{$row->category->description}}</td>
      <td>{{$row->uom->uom_name}}</td>
      <td>{{number_format($row->sales_total_price)}}</td>
      <td>
        <button class="btn btn-primary btn-sm" onclick="add_product('{{$row->product_id}}')">
        <i class="fa  fa-pencil"></i>
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
