
<table>
  <thead>
    <tr>
    <th>No</th>
    <th>Product ID</th>
    <th>Barcode</th>
    <th>Product Name</th>
    <th>Product Category</th>
    <th>UOM</th>
    <th>Currency</th>
    <th>Created Date</th>
    </tr>
  </thead>
  <tbody>
  <?php $no = 1; ?>
    @foreach($data as $key => $row)
      <tr>
      <td style="text-align:center">{{$no}}</td>
      <td>{{$row->product_id}}</td>
      <td>{{$row->barcode_id}}</td>
      <td>{{$row->product_name}}</td>
      <td>{{$row->category->description}}</td>
      <td>{{$row->uom->uom_name}}</td>
      <td>{{$row->currency->currency_name}}</td>
      <td>{{date('d M Y H:i A',strtotime($row->created_at))}}</td>
    </tr>
<?php $no++; ?>
    @endforeach
    </tbody>
</table>
</div>
