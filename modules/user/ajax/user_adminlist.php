<?php
/*
    * 
    *  Developed By        :   Love Kumawat
    *  Date Created        :   Sep 12, 2016
    *  Last Modified       :   Sep 16, 2016
    *  Last Modified By    :   Ishwar Lal Ghiya
    *  Last Modification   :   Admin User Listing
    * 
 */

$obj_user = new users();
extract($_POST);

//Columns to fetch from database
$aColumns = array('user_id', 'CONCAT(first_name," ",last_name) as name', 'username', 'email', 'phone_number', 'company_name', 'company_code', 'no_of_client', 'payment_status', 'status');
$sIndexColumn = "user_id";

/* DB table to use */
$uTable = $obj_user->getTableName('user');

/*
 * Paging
 */
$uLimit = "";
if (isset($_POST['iDisplayStart']) && $_POST['iDisplayLength'] != '-1') {
    $uLimit = "LIMIT " . $obj_user->escape($_POST['iDisplayStart']) . ", " . $obj_user->escape($_POST['iDisplayLength']);
}

/*
 * Ordering
 */
$uOrder = "";
if (isset($_POST['iSortCol_0'])) {

    $uOrder = "ORDER BY ";
    for ($i = 0; $i < intval($_POST['iSortingCols']); $i++) {
        if ($_POST['bSortable_' . intval($_POST['iSortCol_' . $i])] == "true") {
            $uOrder .= $aColumns[intval($_POST['iSortCol_' . $i])] . " " .$obj_user->escape($_POST['sSortDir_' . $i]) . ", ";
        }
    }
    if ($uOrder == "ORDER BY ") {
        $uOrder = "ORDER BY user_id DESC";
    }
}

/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */

$uWhere = " where is_deleted='0' AND user_group = '2'";
if (isset($_POST['sSearch']) && $_POST['sSearch'] != "") {
    for ($i = 0; $i < count($aColumns1); $i++) {
        $uWhere .= $aColumns1[$i] . " LIKE '%" . utf8_encode(htmlentities($_POST['sSearch'],ENT_COMPAT,'utf-8')) . "%' OR ";
    }
    $uWhere = substr_replace($uWhere, "", -3);
    $uWhere .= ')';
}

/* Individual column filtering */
for ($i = 0; $i < count($aColumns); $i++) {
    if (isset($_POST['bSearchable_' . $i]))
        if ((isset($_POST['bSearchable_' . $i]) && $_POST['bSearchable_' . $i] == "true") && (isset($_POST['sSearch_' . $i]) && $_POST['sSearch_' . $i] != '')) {
            $uWhere .= " AND ";
            $uWhere .= $aColumns[$i] . " LIKE '%" . $obj_user->escape($_POST['sSearch_' . $i]) . "%' ";
        }
}

/*
 * SQL queries
 * Get data to display
 */
$uWhere = trim(trim($uWhere), 'AND');
$uQuery = " SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
            FROM $uTable
            $uWhere
            $uOrder
            $uLimit
	";
//echo $uQuery; die;
$rResult = $obj_user->get_results($uQuery);

/* Data set length after filtering */
$uQuery = "SELECT FOUND_ROWS() as rows";
$iFilteredTotal = $obj_user->get_row($uQuery);
$iFilteredTotal = $iFilteredTotal->rows;

/* Total data set length */
$uQuery = "SELECT COUNT(" . $sIndexColumn . ") as count FROM $uTable";
//echo $sQuery;
$iTotal = $obj_user->get_row($uQuery);
$iTotal = $iTotal->count;

/*
 * Output
 */
$output = array(
    "sEcho" => intval($_POST['sEcho']),
    "iTotalRecords" => $iTotal,
    "iTotalDisplayRecords" => $iFilteredTotal,
    "aaData" => array()
);

$temp_x=1;
if(isset($rResult) && !empty($rResult))
{
foreach($rResult as $aRow) {
    
    $row = array();
    $status = '';
    
    if($aRow->status == '0'){
        $status = '<span class="inactive">InActive<span>';
    }elseif($aRow->status == '1'){
        $status = '<span class="active">Active<span>';
    }
    
    $payment_status = '';
    if($aRow->payment_status == '0'){
        $payment_status = '<span class="pending">Pending<span>';
    }elseif($aRow->payment_status == '1'){
        $payment_status = '<span class="success">Success<span>';
    }elseif($aRow->payment_status == '2'){
        $payment_status = '<span class="fraud">Mark As Fraud<span>';
    }elseif($aRow->payment_status == '3'){
        $payment_status = '<span class="rejected">Rejected<span>';
    }elseif($aRow->payment_status == '4'){
        $payment_status = '<span class="refund">Refund<span>';
    }
    
    $row[] = $temp_x;
    $row[] = utf8_decode($aRow->name);
    $row[] = utf8_decode($aRow->username);
    $row[] = utf8_decode($aRow->email);
    $row[] = utf8_decode($aRow->phone_number);
    $row[] = utf8_decode($aRow->company_name);
    $row[] = utf8_decode($aRow->company_code);
    $row[] = utf8_decode($aRow->no_of_client);
    $row[] = $status;
    $row[] = $payment_status;
    $row[] = '<a href="'.PROJECT_URL.'/?page=user_adminupdate&action=editAdmin&id='.$aRow->user_id.'" class="iconedit hint--bottom" data-hint="Edit" ><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;<a href="'.PROJECT_URL.'/?page=user_adminlist&action=deleteAdmin&id='.$aRow->user_id.'" class="iconedit hint--bottom" data-hint="Delete" ><i class="fa fa-trash"></i></a>';
    $output['aaData'][] = $row;
    $temp_x++;
}
}

echo json_encode($output);
?>