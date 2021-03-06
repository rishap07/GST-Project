<?php
/*
    * 
    *  Developed By        :   Love Kumawat
    *  Date Created        :   Sep 12, 2016
    *  Last Modified       :   Sep 16, 2016
    *  Last Modified By    :   Ishwar Lal Ghiya
    *  Last Modification   :   Plan Category Listing
    * 
 */

$obj_plan = new plan();
extract($_POST);

//Columns to fetch from database
$aColumns = array('id','name','month', 'description', 'status');
$sIndexColumn = "id";

/* DB table to use */
$spcTable = $obj_plan->getTableName('subscriber_plan_category');

/*
 * Paging
 */
$spcLimit = "";
if (isset($_POST['iDisplayStart']) && $_POST['iDisplayLength'] != '-1') {
    $spcLimit = "LIMIT " . $obj_plan->escape($_POST['iDisplayStart']) . ", " . $obj_plan->escape($_POST['iDisplayLength']);
}

/*
 * Ordering
 */
$spcOrder = "";
if (isset($_POST['iSortCol_0'])) {

    $spcOrder = "ORDER BY ";
    for ($i = 0; $i < intval($_POST['iSortingCols']); $i++) {
        if ($_POST['bSortable_' . intval($_POST['iSortCol_' . $i])] == "true") {
            $spcOrder .= $aColumns[intval($_POST['iSortCol_' . $i])] . " " .$obj_plan->escape($_POST['sSortDir_' . $i]) . ", ";
        }
    }
    if ($spcOrder == "ORDER BY ") {
        $spcOrder = "ORDER BY id ASC";
    }
}

/*
 * Filtering
 * NOTE this does not match the built-in DataTables filtering which does it
 * word by word on any field. It's possible to do here, but concerned about efficiency
 * on very large tables, and MySQL's regex functionality is very limited
 */

$spcWhere = " where is_deleted='0'";
if (isset($_POST['sSearch']) && $_POST['sSearch'] != "") {
    for ($i = 0; $i < count($aColumns1); $i++) {
        $spcWhere .= $aColumns1[$i] . " LIKE '%" . utf8_encode(htmlentities($_POST['sSearch'],ENT_COMPAT,'utf-8')) . "%' OR ";
    }
    $spcWhere = substr_replace($spcWhere, "", -3);
    $spcWhere .= ')';
}

/* Individual column filtering */
for ($i = 0; $i < count($aColumns); $i++) {
    if (isset($_POST['bSearchable_' . $i]))
        if ((isset($_POST['bSearchable_' . $i]) && $_POST['bSearchable_' . $i] == "true") && (isset($_POST['sSearch_' . $i]) && $_POST['sSearch_' . $i] != '')) {
            $spcWhere .= " AND ";
            $spcWhere .= $aColumns[$i] . " LIKE '%" . $obj_plan->escape($_POST['sSearch_' . $i]) . "%' ";
        }
}

/*
 * SQL queries
 * Get data to display
 */
$spcWhere = trim(trim($spcWhere), 'AND');
$spcQuery = " SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
            FROM $spcTable
            $spcWhere
            $spcOrder
            $spcLimit
	";
//echo $spcQuery; die;
$rResult = $obj_plan->get_results($spcQuery);

/* Data set length after filtering */
$spcQuery = "SELECT FOUND_ROWS() as rows";
$iFilteredTotal = $obj_plan->get_row($spcQuery);
$iFilteredTotal = $iFilteredTotal->rows;

/* Total data set length */
$spcQuery = "SELECT COUNT(" . $sIndexColumn . ") as count FROM $spcTable";
//echo $sQuery;
$iTotal = $obj_plan->get_row($spcQuery);
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
    
    $row[] = $temp_x;
    $row[] = utf8_decode($aRow->name);
    $row[] = utf8_decode($aRow->month);
    $row[] = utf8_decode($aRow->description);
    $row[] = $status;
    $row[] = '<a href="'.PROJECT_URL.'/?page=plan_editcategory&action=editPlanCategory&id='.$aRow->id.'" class="iconedit hint--bottom" data-hint="Edit" ><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;<a href="'.PROJECT_URL.'/?page=plan_categorylist&action=deletePlanCategory&id='.$aRow->id.'" class="iconedit hint--bottom" data-hint="Delete" ><i class="fa fa-trash"></i></a>';
    $output['aaData'][] = $row;
    $temp_x++;
}
}

echo json_encode($output);
?>