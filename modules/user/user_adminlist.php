<?php
$obj_user = new users();
if( !isset($_SESSION['user_detail']['user_id']) || $_SESSION['user_detail']['user_id'] == '' ) {
    $obj_user->redirect(PROJECT_URL);
    exit();
}

if( isset($_GET['action']) && $_GET['action'] == 'deleteAdmin' && isset($_GET['id']) && $obj_user->validateId($_GET['id'])) {
    
    $userDetail = $obj_user->getUserDetailsById( $obj_user->sanitize($_GET['id']) );
    if( $userDetail['status'] == "success" ) {

        if($obj_user->deleteUser($userDetail['data']->user_id)){
            $obj_user->redirect(PROJECT_URL."?page=user_adminlist");
        }
        
    } else {
        $obj_user->setError($obj_plan->validationMessage['usernotexist']);
        $obj_user->redirect(PROJECT_URL."?page=user_adminlist");
    }
}
?>

<!--========================sidemenu over=========================-->
<div class="admincontainer greybg">
    <div class="formcontainer">
        <div>
            <a class='addnew' href='<?php echo PROJECT_URL;?>/?page=user_adminupdate'>Add New</a>
        </div>
        <h1>Admin User</h1>
        <hr class="headingborder">
        <h2 class="greyheading">Admin User Listing</h2>
        
        <div class="adminformbx">
            <?php $obj_user->showErrorMessage(); ?>
            <?php $obj_user->showSuccessMessge(); ?>
            <?php $obj_user->unsetMessage(); ?>
        
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablecontent" id="mainTable">
                
                <thead>
                    <tr>
                        <th align='left'>#</th>
                        <th align='left'>Name</th>
                        <th align='left'>Username</th>
                        <th align='left'>Email</th>
                        <th align='left'>Phone Number</th>
                        <th align='left'>Company Name</th>
                        <th align='left'>Company Code</th>
                        <th align='left'>No Of Client</th>                        
                        <th align='left'>Status</th>
                        <th align='left'>Payment Status</th>
                        <th align='left'>Action</th>
                    </tr>
                </thead>

            </table>
        </div>
<!--========================adminformbox over=========================-->    
    </div>
<!--========================admincontainer over=========================-->
</div>
<script>
    $(document).ready(function () {
        TableManaged.init();
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
                        {"bSortable": false},
                        {"bSortable": false},
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
                    "sAjaxSource": "<?php echo PROJECT_URL; ?>/?ajax=user_adminlist",
                    "fnServerParams": function (aoData) {
                    },
                    "iDisplayLength": 50
                });
            }
        };
    }();
</script>