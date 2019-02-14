<div class="col-md-12">
   <div class="table-responsive">
      <div id="scroll">
         <table class="table scroll">
            <thead>
               <th style="text-align:center;">No</th>
               <th style="width:182px">Barcode ID</th>
               <th style="width:196px">Product Name</th>
               <th style="width:85px">UOM</th>
               <th style="width:105px">Price</th>
               <th style="width:50px">QTY</th>
              <th style="width:50px">Disc</th>
                <th style="width:50px">PPN</th>
               <th style="width:115px">Subtotal</th>
               <th>Action</th>
            </thead>
            <tbody class="neworderbody">
               <?php if($data->count()>=1)
                  {
                    $no = 1;

                    foreach($data->get() as $key => $row_item){
                  ?>
               <tr>
                  <td>{{++$key}}
                     <input type="hidden" id="trans_line_id_{{$no}}" value="{{$row_item->trans_line_id}}" />
                  </td>
                  <td>
                     <div class="input-group" style="margin-right:-23px">
                        <input class="form-control input-sm" id="barcode_id_{{$no}}" type="text" style="margin-right: -44px;margin-left:0px;border-color:#ddd" onkeypress="onEnter(event,{{$no}})" value="{{$row_item->product->barcode_id}}">
                        <span class="input-group-addon">
                        <i class="fa fa-search" aria-hidden="true"> </i>
                        </span>
                     </div>
                  </td>
                  <td>
                     <input class="form-control input-sm" id="product_name_{{$no}}" type="text" style="width:190px;margin-right: -24px;" value="{{$row_item->product_name}}">
                  </td>
                  <td>
                     <input class="form-control input-sm" id="uom_{{$no}}" type="text" style="width:79px;margin-right: -22px;margin-left:0px" value="{{$row_item->uom}}">
                  </td>
                  <td>
                     <input class="form-control input-sm" id="price_{{$no}}" type="text" style="width:100px;margin-right: -20px;margin-left:-3px" value="{{number_format($row_item->sales_price)}}">
                     <input type="hidden" id="price_value_{{$no}}" value="{{$row_item->sales_price}}" />
                  </td>
                  <td>
                     <input class="form-control input-sm" id="qty_{{$no}}" type="text" style="width:50px;margin-left: -3px;margin-right:-17px;margin-left:-4px" onkeyup="update('{{$no}}')" value="{{$row_item->qty}}">
                  </td>
                  <td>
                     <input class="form-control input-sm" id="disc_{{$no}}" type="text" style="width:50px;margin-left: -3px;margin-right:-17px;margin-left:-4px" onkeyup="update('{{$no}}')" value="{{$row_item->disc}}">
                  </td>
                  <td>
                     <input class="form-control input-sm" id="ppn_{{$no}}" type="text" style="width:50px;margin-left: -3px;margin-right:-17px;margin-left:-4px" onkeyup="update('{{$no}}')" value="{{$row_item->ppn}}">
                  </td>
                  <td>
                     <input class="form-control input-sm" id="subtotal_{{$no}}" type="text" style="width:105px;margin-right: -24px;margin-left:-7px" value="{{number_format($row_item->sub_total_sales)}}">
                     <input type="hidden" id="sub_total_value_{{$no}}" value="{{$row_item->sub_total_sales}}" />
                  </td>
                  <td>
                     <button class="btn btn-danger btn-sm" type="button" onclick="delete_item('{{$no}}')">
                     <i class="glyphicon glyphicon-trash"></i>
                     </button>
                  </td>
               </tr>
               <?php } ?>
               <?php $no++; } ?>
               <?php for ($i=$data->count()+1; $i<11; $i++){ ?>
               <tr>
                  <td>{{$i}}
                     <input type="hidden" id="trans_line_id_{{$i}}" />
                  </td>
                  <td>
                     <div class="input-group" style="margin-right:-23px">
                        <input class="form-control input-sm" id="barcode_id_{{$i}}" type="text" style="margin-right: -44px;margin-left:0px;border-color:#ddd" onkeypress="onEnter(event,{{$i}})" focus>
                        <span class="input-group-addon" style="cursor:pointer" onclick="product_list()">
                        <i class="fa fa-search" aria-hidden="true"> </i>
                        </span>
                     </div>
                  </td>
                  <td>
                     <input class="form-control input-sm" id="product_name_{{$i}}" type="text" style="width:190px;margin-right: -24px;">
                  </td>
                  <td>
                     <input class="form-control input-sm" id="uom_{{$i}}" type="text" style="width:79px;margin-right: -22px;margin-left:0px">
                  </td>
                  <td>
                     <input class="form-control input-sm" id="price_{{$i}}" type="text" style="width:100px;margin-right: -20px;margin-left:-3px">
                     <input type="hidden" id="price_value_{{$i}}" />
                  </td>
                  <td>
                     <input class="form-control input-sm" id="qty_{{$i}}" type="text" style="width:50px;margin-left: -3px;margin-right:-17px;margin-left:-4px" onkeyup="update('{{$i}}')">
                  </td>
                  <td>
                     <input class="form-control input-sm" id="disc_{{$i}}" type="text" style="width:50px;margin-left: -3px;margin-right:-17px;margin-left:-4px" onkeyup="update('{{$i}}')">
                  </td>
                  <td>
                     <input class="form-control input-sm" id="ppn_{{$i}}" type="text" style="width:50px;margin-left: -3px;margin-right:-17px;margin-left:-4px" onkeyup="update('{{$i}}')">
                  </td>
                  <td>
                     <input class="form-control input-sm" id="subtotal_{{$i}}" type="text" style="width:105px;margin-right: -24px;margin-left:-7px">
                     <input type="hidden" id="sub_total_value_{{$i}}" />
                  </td>
                  <td>
                     <button class="btn btn-danger btn-sm" type="button" onclick="delete_item('{{$i}}')">
                     <i class="glyphicon glyphicon-trash"></i>
                     </button>
                  </td>
               </tr>
               <?php } ?>
            </tbody>
            <tfoot>
               <tr>
                  <td style="text-align:left"><b>Total<b></td>
                    <td style="width:390px"></td>
                    <td style="width:80px"></td>
                    <td style="width:5px"></td>
                    <td style="width:55px"></td>
                  <td style="width:50px">
                     <b>
                        <div id="total_qty"><?=number_format($pos_line_row->qty)?></div>
                     </b>
                  </td>
                  <td style="width:50px"></td>
                  <td style="width:75px"></td>
                  <td style="width:105px">
                     <b>
                        <div id="total_sales"><?=number_format($pos_line_row->sub_total)?></div>
                     </b>
                  </td>
                  <td style="width:1px"></td>
               </tr>
            </tfoot>
         </table>
      </div>
   </div>
</div>
