<?php
$obj_master = new master();
?>
<div class="admincontainer greybg">
    <div class="formcontainer">
        <div>
            <a class='addnew' href='<?php echo PROJECT_URL;?>/?page=master_state_update'>Add New</a>
        </div>
        <h1>State</h1>
        <hr class="headingborder">
        <h2 class="greyheading">State Listing</h2>
        <div class="adminformbx">
            <?php $obj_master->showErrorMessage(); ?>
            <?php $obj_master->showSuccessMessge(); ?>
            <?php $obj_master->unsetMessage(); ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablecontent" id="mainTable">
                <thead>
                    <tr>
                        <th align='left' width="72">Sr</th>
                        <th align='left'>State</th>
                        <th align='left'>State Code</th>
                        <th align='left'>Status</th>
                        <th width="72">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        TableManaged.init();
        $('#submit').click(function () {
            var mesg = {};
            if (vali.validate(mesg,'form')) {
                return true;
            }
            return false;
        });
    });
    var TableManaged = function () {
        return {
            init: function () {
                if (!jQuery().dataTable) {
                    return;
                }
                var sgHREF = window.location.pathname;
                $.ajaxSetup({'type': 'POST', 'url': sgHREF, 'dataType': 'json'});
                $.extend($.fn.dataTable.defaults, {'sServerMethod': 'POST'});
                $('#mainTable').dataTable({
                    "aoColumns": [
                        {"bSortable": false},
                        {"bSortable": false},
                        {"bSortable": false},
                        {"bSortable": false},
                        {"bSortable": false}
                    ],
                    "sDom": "lfrtip",
                    "aLengthMenu": [
                        [10, 20, 50, 100, 500],
                        [10, 20, 50, 100, 500],
                    ],
                    "bProcessing": true,
                    "bServerSide": true,
                    "bStateSave": false,
                    "bDestroy": true,
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=master_state",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 50
                });
            }
        };
    }();
</script>