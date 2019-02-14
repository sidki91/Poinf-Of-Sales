
<script>
function check_all()
 {
    $(".choose").prop('checked', true);
    $('#action_view').val(1);
    $('#action_create').val(1);
    $('#action_alter').val(1);
    $('#action_drop').val(1);

 }
 function un_check_all()
 {
    $(".choose").prop('checked', false);
    $('#action_view').val(0);
    $('#action_create').val(0);
    $('#action_alter').val(0);
    $('#action_drop').val(0);
 }
$('#action_view').on('change', function(){
   this.value = this.checked ? 1 : 0;
    //alert(this.value);
}).change();
$('#action_create').on('change', function(){
  this.value = this.checked ? 1 : 0;
   //alert(this.value);
}).change();
$('#action_alter').on('change', function(){
  this.value = this.checked ? 1 : 0;
   //alert(this.value);
}).change();
$('#action_drop').on('change', function(){
  this.value = this.checked ? 1 : 0;
   //alert(this.value);
}).change();
 </script>

<form class="form-horizontal" role="form">
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Module ID</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="modid" name="modid">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Module</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="modules" name="modules">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-3 control-label">Alias</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="alias" name="alias">
            <!-- <p class="help-block">Example block-level help text here.</p> -->
        </div>
    </div>
    
</form>
<button class="btn btn-outline btn-success btn-sm" type="button" id="select_all" onclick="check_all()"><i class="fa fa-check"></i> Check All</button>
<button class="btn btn-outline btn-danger btn-sm" type="button" id="un_select_all" onclick="un_check_all()"><i class="fa fa-close"></i> Un Check All</button>
<p></p>
<table class="table table-hover table-bordered">
    <th style="text-align:center">View</th>
    <th style="text-align:center">Create</th>
    <th style="text-align:center">Alter</th>
    <th style="text-align:center">Drop</th>
    <tbody>
        <tr>
            <td style="text-align:center">
                <input type='checkbox' class='ios8-switch choose' id='action_view' name="action_view" value="1">
                <label for='action_view'>&nbsp;</label>
            </td>
            <td style="text-align:center">
                <input type='checkbox' class='ios8-switch choose' id='action_create' name="action_create" >
                <label for='action_create'>&nbsp;</label>
            </td>
            <td style="text-align:center">
                <input type='checkbox' class='ios8-switch choose' id='action_alter' name="action_alter" >
                <label for='action_alter'>&nbsp;</label>
            </td>
            <td style="text-align:center">
                <input type='checkbox' class='ios8-switch choose' id='action_drop' name="action_drop" >
                <label for='action_drop'>&nbsp;</label>
            </td>
        </tr>
    </tbody>
</table>
