<?php
$obj_master = new master();
if(isset($_POST['submit']) && $_POST['submit']=='submit')
{
    if($obj_master->addItem())
    {
        $obj_master->redirect(PROJECT_URL."/?page=master_item");
    }
}
if(isset($_POST['submit']) && $_POST['submit']=='update' && isset($_GET['id']))
{
    if($obj_master->updateItem())
    {
        $obj_master->redirect(PROJECT_URL."/?page=master_item");
    }
}
$dataArr = array();
if(isset($_GET['id']))
{
    $dataArr = $obj_master->findAll($obj_master->getTableName('item'),"is_deleted='0' and item_id='".$obj_master->sanitize($_GET['id'])."'");
}


?>
<div class="admincontainer greybg">
    <div class="formcontainer">
        <?php $obj_master->showErrorMessage(); ?>
        <?php $obj_master->showSuccessMessge(); ?>
        <?php $obj_master->unsetMessage(); ?>
        <h1>Item</h1>
        <hr class="headingborder">
        <h2 class="greyheading"><?php echo isset($_GET['id']) ? 'Edit Item' : 'Add New Item'; ?></h2>
        <form method="post" enctype="multipart/form-data" id='form'>
            <div class="adminformbx">
                <div class="kycform">
                    <div class="kycmainbox">
                        <div class="clear"></div>
                        <div class="formcol">
                            <label>Item<span class="starred">*</span></label>
                            <input type="text" placeholder="Item" name='item_name' data-bind="content" class="required" value='<?php if(isset($_POST['item_name'])){ echo $_POST['item_name'];}else if(isset($dataArr[0]->item_name)){ echo $dataArr[0]->item_name;}?>' />
                            <span class="greysmalltxt"></span> </div>
                        <div class="formcol two">
                            <label>HSN Code<span class="starred">*</span></label>
                            <input type="text" placeholder="HSN Code"  name='hsn_code' data-bind="content" class="required" value='<?php if(isset($_POST['hsn_code'])){ echo $_POST['hsn_code'];}else if(isset($dataArr[0]->hsn_code)){ echo $dataArr[0]->hsn_code;}?>'/>
                        </div>
                        <div class="formcol third">
                            <label>Unit Price(Rs.)<span class="starred">*</span></label>
                            <input type="text" placeholder="Unit Price" name='unit_price' data-bind="demical" class="required" value='<?php if(isset($_POST['unit_price'])){ echo $_POST['unit_price'];}else if(isset($dataArr[0]->unit_price)){ echo $dataArr[0]->unit_price;}?>'/>
                        </div>
                        <div class="formcol">
                            <label>IGST Tax Rate(%)<span class="starred">*</span></label>
                            <input type="text" placeholder="IGST Tax Rate" name='igst_tax_rate' data-bind="demical" class="required" id='igst_tax_rate' value='<?php if(isset($_POST['igst_tax_rate'])){ echo $_POST['igst_tax_rate'];}else if(isset($dataArr[0]->igst_tax_rate)){ echo $dataArr[0]->igst_tax_rate;}?>'/>
                        </div>
                        <div class="formcol two">
                            <label>CSGT Tax Rate(%)<span class="starred">*</span></label>
                            <input type="text" placeholder="CSGT Tax Rate" name='csgt_tax_rate' data-bind="demical" class="required" id='csgt_tax_rate' value='<?php if(isset($_POST['csgt_tax_rate'])){ echo $_POST['csgt_tax_rate'];}else if(isset($dataArr[0]->csgt_tax_rate)){ echo $dataArr[0]->csgt_tax_rate;}?>'/>
                        </div>
                        <div class="formcol third">
                            <label>SGST Tax Rate(%)<span class="starred">*</span></label>
                            <input type="text" placeholder="SGST Tax Rate" name='sgst_tax_rate' data-bind="demical" class="required" id='sgst_tax_rate' value='<?php if(isset($_POST['sgst_tax_rate'])){ echo $_POST['sgst_tax_rate'];}else if(isset($dataArr[0]->sgst_tax_rate)){ echo $dataArr[0]->sgst_tax_rate;}?>'/>
                        </div>
                        <div class="formcol">
                            <label>Cess Tax Rate(%)<span class="starred">*</span></label>
                            <input type="text" placeholder="Cess Tax Rate" name='cess_tax_rate' data-bind="demical" class="required" id='cess_tax_rate' value='<?php if(isset($_POST['cess_tax_rate'])){ echo $_POST['cess_tax_rate'];}else if(isset($dataArr[0]->cess_tax_rate)){ echo $dataArr[0]->cess_tax_rate;}?>'/>
                        </div>
                        <div class="formcol two">
                            <label>Status<span class="starred">*</span></label>
                            <select name="status">
                                <option value="1" <?php if(isset($_POST['status']) &&  $_POST['status']==='1'){ echo 'selected';}else if(isset($dataArr[0]->state_code) && $dataArr[0]->state_code==='1'){ echo 'selected';}?>>Active</option>
                                <option value="0" <?php if(isset($_POST['status']) &&  $_POST['status']==='0'){ echo 'selected';}else if(isset($dataArr[0]->state_code) && $dataArr[0]->state_code==='0'){ echo 'selected';}?>>In-Active</option>
                            </select>
                        </div>
                        <div class="clear height30"></div>
                        <div class="adminformbxsubmit" style="width:100%;"> 
                            <div class="tc">
                                <input type='submit' class="btn orangebg" name='submit' value='<?php echo isset($_GET['id']) ? 'update' : 'submit'; ?>' id='submit'>
                                <input type="button" value="<?php echo ucfirst('Back'); ?>" onclick="javascript:window.location.href = '<?php echo PROJECT_URL . "/?page=master_item"; ?>';" class="btn redbg" class="redbtn marlef10"/>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#state").change(function () {
           val1 = $(this).val().split(":");
           $("#state_code").val(val1[1]);
        });
        $('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'form')) {
                return true;
            }
            return false;
        });
    });
</script>
    