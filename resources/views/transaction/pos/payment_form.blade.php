<script type="text/javascript">

function pay_format()
{
    $("#pay").maskMoney({
    prefix:'', allowNegative: true, thousands:',', decimal:',', affixesStay: false}
                                );
    var angka = bersihPemisah(bersihPemisah(bersihPemisah(bersihPemisah($("#pay").val()))));
    $("#pay_val").val(angka);
    var pay_value = $("#pay_val").val();
}

function payment_action()
{
    var transaction_number = $("#transaction_number").val();
    var grand_total    = $("#grand_total_val").val();
    var payment_value  = $("#pay_val").val();
    if(payment_value=='')
    {
          swal("Opps !",'Pembayaran ditolak, silahkan isi jumlah pembayaran !', "error");
    }
    else if(payment_value<grand_total)
    {
        swal("Opps !",'Jumlah pembayaran tidak boleh kurang dari total bayar !', "error");
    }
    else
    {
          $.ajax({
          url         	: "pos/payment_action",
          dataType    	: "json",
          type			    : "post",
          data          : {
                        '_token'             : $('input[name=_token]').val(),
                        'transaction_number' : transaction_number,
                        'grand_total'        : grand_total,
                        'payment_value'      : payment_value

                          },

          success       : function (result)
          {
            if(result.status=='success')
            {
                bootbox.hideAll();
                clear();
                table_item();
                bootbox.dialog(
                  {
                      className: "dialogNota",
                      title   : '<div><b>Nota</b></div>',
                      message : (result.html)

                  }
                );
            }
          }
            });

    }

}
</script>
<form role="form">
   <div class="form-group">
        <label for="exampleInputEmail1">Grand Total </label>
            <input type="text" id="grand_total" class="form-control input-sm" placeholder="0" style="height:80px;font-size:40px;text-align:right" value="{{number_format($row->grand_sales_price)}}" />
            <input type="hidden" id="grand_total_val" value="{{$row->grand_sales_price}}"/>
    </div>
    <div class="form-group" style="margin-top:-10px">
        <label for="exampleInputEmail1">Payment</label>
          <input type="text" id="pay" class="form-control input-sm" placeholder="0"  autofocus  style="height:80px;font-size:40px;text-align:right" onkeyup="pay_format()" />
          <input type="hidden" id="pay_val"/>
    </div>
    <div class="form-group">
      <div align="right">
        <button type="button" class="btn btn-success" onclick="payment_action()"><i class="fa fa-check"></i> Payment</button>
      </div>
    </div>

  </form>
