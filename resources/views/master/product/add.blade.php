<style media="screen">
.modal-body
{
  max-height: calc(100vh - 210px);
  overflow-y: auto;
}
</style>
<script type="text/javascript">

  function format_purchase_price(action='')
  {

        $("#purchase_price").maskMoney({
        prefix:'', allowNegative: true, thousands:',', decimal:',', affixesStay: false}
                                    );
        var angka = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#purchase_price").val()))));
        $("#purchase_price_value").val(angka);
        var price = $("#purchase_price_value").val();

        $("#sales_price").maskMoney({
        prefix:'', allowNegative: true, thousands:',', decimal:',', affixesStay: false}
                                    );
        var angka_2 = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#sales_price").val()))));
        $("#sales_price_value").val(angka_2);
        var sales_price = $("#sales_price_value").val();

        $("#total_sales_price").maskMoney({
        prefix:'', allowNegative: true, thousands:',', decimal:',', affixesStay: false}
                                    );
        var angka_3 = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#total_sales_price").val()))));
        $("#total_sales_price").val(angka_3);
        var total_sales = $("#total_sales_price").val();

        $.ajax({
            type: 'POST',
            url: 'product/sales_price_value',
            data: {
                '_token'          : $('input[name=_token]').val(),
                'purchase_price'  : price,
                'sales_price'     : sales_price,
                'total_sales'     : total_sales,
                'percentage'      : $("#percentage").val(),
                'disc'            : $("#disc").val(),
                'tax'             : $("#tax").val(),
                'action'          : action

            },
            success: function(data) {
                if (data.status == 'success')
                {
                    $("#percentage_value").val(data.percentage);
                    $("#sales_price").val(data.hjt_format);
                    $("#sales_price_value").val(data.hjt_val);
                    $("#disc_value").val(data.disc);
                    $("#tax_value").val(data.tax);
                    $("#total_sales_price").val(data.total_sales_price);
                    $("#total_sales_price_value").val(data.total_sales_price_val);
                }
                else
                {
                    alertMsg(data.msg, 'error');
                }
            },
        });

  }

</script>


<form class="form-horizontal" role="form">
  <div class="col-md-6">
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Barcode Code</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="barcode_code">
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Product Name</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" id="product_name">
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Product Category</label>
        <div class="col-sm-6">
            <select class="form-control" id="product_category">
              <option value="">Choose</option>
              @foreach($category as $row)
              <option value="{{$row->id}}">{{$row->description}}</option>
              @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">UOM</label>
        <div class="col-sm-6">
          <select class="form-control" id="uom">
            <option value="">Choose</option>
            @foreach($uom as $row_uom)
            <option value="{{$row_uom->id}}">{{$row_uom->uom_name}}</option>
            @endforeach
          </select>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Currency</label>
        <div class="col-sm-6">
          <select class="form-control" id="currency">
            <option value="">Choose</option>
            @foreach($currency as $row_currency)
            <option value="{{$row_currency->id}}">{{$row_currency->currency_name}}</option>
            @endforeach
          </select>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Payment</label>
        <div class="col-sm-6">
          <select class="form-control" id="payment">
            <option value="">Choose</option>
            @foreach($payment as $row_payment)
            <option value="{{$row_payment->payment_id}}">{{$row_payment->payment_method}}</option>
            @endforeach
          </select>
        </div>
    </div>


  </div>

  <div class="col-md-6">
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Percentage</label>
        <div class="col-sm-8">
          <select class="form-control" id="percentage" onchange="format_purchase_price()">
            @foreach($percentage as $row_percentage)
            <option value="{{$row_percentage->percentage}}">{{$row_percentage->percentage}} %</option>
            @endforeach
          </select>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Purchase Price</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="purchase_price" onkeyup="format_purchase_price('1')" value="0" />
          <input type="hidden" id="purchase_price_value"/>
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Sales Price</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="sales_price" onkeyup="format_purchase_price('2')" value="0"/>
          <input type="hidden" id="sales_price_value"/>
          <input type="hidden" id="percentage_value" value="0">
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Disc (%)</label>
        <div class="col-sm-3">
          <input type="text" class="form-control" id="disc" value="0" onkeyup="format_purchase_price('2')"/>
          <input type="hidden" id="disc_value" value="0">
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Tax</label>
        <div class="col-sm-8">
          <select class="form-control" id="tax" onchange="format_purchase_price('2')">
            @foreach($tax as $row_tax)
            <option value="{{$row_tax->tax_value}}">{{$row_tax->tax_value}} %</option>
            @endforeach
          </select>
          <input type="hidden" id="tax_value" value="0">
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-4 control-label">Total Sales Price</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="total_sales_price" value="0" onkeyup="format_purchase_price('3')"/>
          <input type="hidden" id="total_sales_price_value"/>
        </div>
    </div>
  </div>
</form>
