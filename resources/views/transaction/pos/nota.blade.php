
<style type="text/css">
  .font_nota {
    font-size: 11px;
    margin:0px;
  }
  .font_nota_header{
    font-size:12px;
    font-weight:bold;
  }
  .margin_bawah{
    padding-bottom:1px;
  }
</style>
<body class="font_nota">
  <div style="min-height: 200px;width:208px !important">
    <table width="250" border="0" style="margin-bottom:5px;margin-top:-15px;font-size: 9px;">
      <tr>
        <td colspan="5">
          <div align="center" class="font_nota_header">KOPERASI YUASA INDONESIA
          </div>
        </td>
      </tr>
      <tr>
        <td colspan="4">
          <div align="center">
            <span class="font_nota">
              {{date('d M Y H:i A', strtotime($head->trans_date))}}
            </span>
          </div>
        </td>
      </tr>
      <tr>
        <td width="47">&nbsp;
        </td>
        <td width="63">
          <div align="right" class="font_nota">
            <div align="left">Trans No
            </div>
          </div>
        </td>
        <td width="8">
          <div align="center" class="font_nota">:
          </div>
        </td>
        <td width="114">
          <span class="font_nota">
            {{$head->trans_id}}
          </span>
        </td>
      </tr>
      <tr>
        <td>&nbsp;
        </td>
        <td>
          <div align="right" class="font_nota">
            <div align="left">Cashier
            </div>
          </div>
        </td>
        <td width="8">
          <div align="center" class="font_nota">:
          </div>
        </td>
        <td>
          <span class="font_nota">
            {{$head->updated_by}}
          </span>
        </td>
      </tr>
    </table>
    <hr align="left" width="250" style="margin-bottom:2px;margin-top:2px" />
    <!--</div>-->
    <table width="250" border="0" class="font_nota">
      <tr>
        <td width="113">
          <div align="left" class="font_nota">Customer Name
          </div>
        </td>
        <td width="10">
          <div align="center" class="font_nota">:
          </div>
        </td>
        <td width="113">
          <div align="left">
            {{$head->cust_name}}
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div align="left" class="font_nota">Payment Method
          </div>
        </td>
        <td width="10">
          <div align="center" class="font_nota">:
          </div>
        </td>
        <td>
          <div align="left">
          {{$head->payment->payment_method}}
          </div>
        </td>
      </tr>
    </table>
    <hr align="left" width="250" style="margin-bottom:4px;margin-top:1px" />
    <!--<div style="height:150px; overflow:auto;">-->
    <table width="250" border="0">
      <?php foreach($line as $key => $row):?>
      <tr class="font_nota">
        <td width="130">
          {{$row->product_name}}
        </td>
        <td width="10">
          <div align="center">
            {{$row->qty}}
          </div>
        </td>
        <td width="22">
          <div align="center">
            {{$row->uom}}
          </div>
        </td>
        <td width="30">
          <div align="right">
            {{number_format($row->sales_price)}}
          </div>
        </td>
        <td width="40">
          <div align="right">
            {{number_format($row->sub_total_sales)}}
          </div>
        </td>
      </tr>
      <?php endforeach;?>
    </table>
    <hr align="left" width="250" style="margin-bottom:2px;margin-top:2px" />
    <table width='250' border="0" class="font_nota">
      <tr>
        <td width="64">&nbsp;
        </td>
        <td width="27">&nbsp;
        </td>
        <td width="42">&nbsp;
        </td>
        <td width="49">
          <div align="left" class="font_nota">Total Qty
          </div>
        </td>
        <td width="46" style="text-align: right;">
          {{number_format($head->grand_qty)}}
        </td>
      </tr>
      <tr>
        <td>&nbsp;
        </td>
        <td>&nbsp;
        </td>
        <td>&nbsp;
        </td>
        <td class="font_nota">SubTotal
        </td>
        <td style="text-align: right;">
          {{number_format($head->grand_sales_price)}}
        </td>
      </tr>
      <tr>
        <td>&nbsp;
        </td>
        <td>&nbsp;
        </td>
        <td>&nbsp;
        </td>
        <td class="font_nota">Diskon
        </td>
        <td style="text-align: right;">
          0
        </td>
      </tr>
      <tr>
        <td>&nbsp;
        </td>
        <td>&nbsp;
        </td>
        <td>&nbsp;
        </td>
        <td class="font_nota">PPN
        </td>
        <td style="text-align: right;">
        0
        </td>
      </tr>
      <tr>
        <td>&nbsp;
        </td>
        <td>&nbsp;
        </td>
        <td>&nbsp;
        </td>
        <td class="font_nota">Grand
        </td>
        <td style="text-align: right;">
            {{number_format($head->grand_sales_price)}}
        </td>
      </tr>

      <tr>
        <td>&nbsp;
        </td>
        <td>&nbsp;
        </td>
        <td>&nbsp;
        </td>
        <td class="font_nota">Tunai
        </td>
        <td style="text-align: right;">
        {{number_format($head->total_bayar)}}
        </td>
      </tr>
      <tr>
        <td>&nbsp;
        </td>
        <td>&nbsp;
        </td>
        <td>&nbsp;
        </td>
        <td class="font_nota">Kembali
        </td>
        <td style="text-align: right;">
        {{number_format($head->kembali)}}
        </td>
      </tr>
    </table>
    <hr align="left" width="250" style="margin-bottom:5px;margin-top:10px" />
    <table width="250" border="0">
      <tr>
        <td>
          <div align="center" class="font_nota">Terima Kasih
          </div>
        </td>
      </tr>
      <tr>
        <td align="right">
            <button type="button" class="btn btn-success btn-sm"><i class="fa fa-print"></i></button>
        </td>
      </tr>
    </table>
  </div>
