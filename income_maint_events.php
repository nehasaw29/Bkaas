<?php
//BindEvents Method @1-0F3BE83B
function BindEvents()
{
    global $income;
    $income->upload_type->CCSEvents["BeforeShow"] = "income_upload_type_BeforeShow";
    $income->inc_success->CCSEvents["BeforeShow"] = "income_inc_success_BeforeShow";
    $income->txn_date->CCSEvents["BeforeShow"] = "income_txn_date_BeforeShow";
    $income->ds->CCSEvents["AfterExecuteInsert"] = "income_ds_AfterExecuteInsert";
    $income->ds->CCSEvents["AfterExecuteUpdate"] = "income_ds_AfterExecuteUpdate";

}
//End BindEvents Method

//DEL  // -------------------------
//DEL      // Write your own code here.
//DEL  // -------------------------
//DEL  /*if ($income->ds->Where <> "") {
//DEL      $income->ds->Where .= " AND ";
//DEL    }
//DEL  
//DEL    $income->ds->Where .= " id_coa in (select id_coa from coa where account_type in )";*/
//DEL  echo 'Where '.$income->ds->Where;

//income_upload_type_BeforeShow @27-3295B74E
function income_inc_success_BeforeShow(& $sender)
{
    $income_inc_success_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $income; //Compatibility
//End income_inc_success_BeforeShow

//Custom Code @101-2A29BDB7
// -------------------------
    // Write your own code here.
// -------------------------
//displaying success message to user after record added successfully with help of query param 'msg'
//checking if the msg is inserted type or not 1-insert,2-update
if(CCGetParam('msg')==1)
$Component->SetValue("<span style='color:green;'>Record Added Successfully</span>");
elseif(CCGetParam('msg')==2)
    $Component->SetValue("<span style='color:green;'>Record Updated Successfully</span>");
//End Custom Code

//Close income_inc_success_BeforeShow @100-247B7F75
    return $income_inc_success_BeforeShow;
}


function income_ds_AfterExecuteInsert(& $sender)
{
    $income_ds_AfterExecuteInsert = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $income; //Compatibility
//End income_ds_AfterExecuteInsert

//Custom Code @99-2A29BDB7
// -------------------------
    // Write your own code here.
// -------------------------
global $DBbkaas;
global $Redirect;
    $db = new clsDBbkaas();
//getting last inserted id and adding it to url
//select max(id_income) from income

 //redirecting to the same page by adding query params newly inserted expense id and a msg(1-success)
$Redirect = "income_maint.php?msg=1";
//$Redirect = "income_maint.php?id_upload=$uid&upload_type=$ut&id_income=$id_invoice&msg=1";
//End Custom Code

//Close income_ds_AfterExecuteInsert @47-854453D5
    return $income_ds_AfterExecuteInsert;
}


function income_ds_AfterExecuteUpdate(& $sender)
{
    $income_ds_AfterExecuteUpdate = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $income; //Compatibility
//End income_ds_AfterExecuteUpdate

//Custom Code @109-2A29BDB7
// -------------------------
    // Write your own code here.
// -------------------------
global $Redirect;
$id_income = CCGetParam('id_income');
 $uid=CCGetParam('id_upload');
 $ut=CCGetParam('upload_type');
 //redirecting to the same page by adding query params newly inserted expense id and a msg(1-success)
$Redirect = "income_maint.php?ccsForm=income&msg=2";
//End Custom Code

//Close income_ds_AfterExecuteUpdate @47-4A6D925A
    return $income_ds_AfterExecuteUpdate;
}


function income_upload_type_BeforeShow(& $sender)
{
    $income_upload_type_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $income; //Compatibility
//End income_upload_type_BeforeShow

//Custom Code @28-2A29BDB7
// -------------------------
    // Write your own code here.
// -------------------------
if($income->EditMode) {
	global $DBbkaas;
	$Component->Visible = true;
	$Component->Value = 'Trns type';
	$Component->Value = CCDLookUp("upload_type", "refuploadtype", "id_refuploadtype=4", $Page->Connections["bkaas"]);
   // echo $Component->Value;
	//echo '<pre>';
	//print_r($income->DataSource->Record['id_income']);
	//exit();
	$result = $DBbkaas->query('SELECT upload_type FROM refuploadtype WHERE id_refuploadtype in (select upload_type from upload where id_upload = '.$income->DataSource->Record['id_upload'].')');
	//$DBbkaas->query($SQL);
  //  print_r($DBbkaas->query($SQL));exit();
    $result = $DBbkaas->next_record();
    if ($result) {
      $Component->Value = $DBbkaas->f("upload_type");     


    }
    else 
    	$Component->Value = '#';
}
//End Custom Code

//Close income_upload_type_BeforeShow @27-74D3D4D7
    return $income_upload_type_BeforeShow;
}
//End Close income_upload_type_BeforeShow

//income_txn_date_BeforeShow @11-6CD240F1
function income_txn_date_BeforeShow(& $sender)
{
    $income_txn_date_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $income; //Compatibility
//End income_txn_date_BeforeShow

//Close income_txn_date_BeforeShow @11-B16B405E
    return $income_txn_date_BeforeShow;
}
//End Close income_txn_date_BeforeShow


?>
