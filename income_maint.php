<?php
//Include Common Files @1-3FE60F4C
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "income_maint.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Master Page implementation @1-DDE374F6
include_once(RelativePath . "/Designs/bkaas/MasterPage.php");
//End Master Page implementation

class clsRecordincome { //income Class @2-38BFF6E7

//Variables @2-9E315808

    // Public variables
    public $ComponentType = "Record";
    public $ComponentName;
    public $Parent;
    public $HTMLFormAction;
    public $PressedButton;
    public $Errors;
    public $ErrorBlock;
    public $FormSubmitted;
    public $FormEnctype;
    public $Visible;
    public $IsEmpty;

    public $CCSEvents = "";
    public $CCSEventResult;

    public $RelativePath = "";

    public $InsertAllowed = false;
    public $UpdateAllowed = false;
    public $DeleteAllowed = false;
    public $ReadAllowed   = false;
    public $EditMode      = false;
    public $ds;
    public $DataSource;
    public $ValidatingControls;
    public $Controls;
    public $Attributes;

    // Class variables
//End Variables

//Class_Initialize Event @2-1757C29F
    function clsRecordincome($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record income/Error";
        $this->DataSource = new clsincomeDataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "income";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_Insert = new clsButton("Button_Insert", $Method, $this);
            $this->Button_Update = new clsButton("Button_Update", $Method, $this);
            $this->Button_Delete = new clsButton("Button_Delete", $Method, $this);
            $this->id_subscriber = new clsControl(ccsListBox, "id_subscriber", "Id Subscriber", ccsInteger, "", CCGetRequestParam("id_subscriber", $Method, NULL), $this);
            $this->id_subscriber->DSType = dsTable;
            $this->id_subscriber->DataSource = new clsDBbkaas();
            $this->id_subscriber->ds = & $this->id_subscriber->DataSource;
            $this->id_subscriber->DataSource->SQL = "SELECT * \n" .
"FROM subscriber {SQL_Where} {SQL_OrderBy}";
            list($this->id_subscriber->BoundColumn, $this->id_subscriber->TextColumn, $this->id_subscriber->DBFormat) = array("id_subscriber", "subscriber_name", "");
            $this->id_subscriber->Required = true;
            $this->id_user = new clsControl(ccsListBox, "id_user", "Id User", ccsInteger, "", CCGetRequestParam("id_user", $Method, NULL), $this);
            $this->id_user->DSType = dsTable;
            $this->id_user->DataSource = new clsDBbkaas();
            $this->id_user->ds = & $this->id_user->DataSource;
            $this->id_user->DataSource->SQL = "SELECT * \n" .
"FROM user {SQL_Where} {SQL_OrderBy}";
            list($this->id_user->BoundColumn, $this->id_user->TextColumn, $this->id_user->DBFormat) = array("id_user", "user_name", "");
            $this->id_user->Required = true;
            $this->id_customer = new clsControl(ccsListBox, "id_customer", "Id Customer", ccsInteger, "", CCGetRequestParam("id_customer", $Method, NULL), $this);
            $this->id_customer->DSType = dsTable;
            $this->id_customer->DataSource = new clsDBbkaas();
            $this->id_customer->ds = & $this->id_customer->DataSource;
            $this->id_customer->DataSource->SQL = "SELECT * \n" .
"FROM customer {SQL_Where} {SQL_OrderBy}";
            list($this->id_customer->BoundColumn, $this->id_customer->TextColumn, $this->id_customer->DBFormat) = array("id_customer", "name", "");
            $this->id_customer->Required = true;
            $this->id_coa = new clsControl(ccsListBox, "id_coa", "Id Coa", ccsInteger, "", CCGetRequestParam("id_coa", $Method, NULL), $this);
            $this->id_coa->DSType = dsTable;
            $this->id_coa->DataSource = new clsDBbkaas();
            $this->id_coa->ds = & $this->id_coa->DataSource;
            $this->id_coa->DataSource->SQL = "SELECT coa.id_coa AS coa_id_coa, account_name \n" .
"FROM coa INNER JOIN ref_ac_type ON\n" .
"coa.account_type = ref_ac_type.id_ac_type {SQL_Where} {SQL_OrderBy}";
            $this->id_coa->DataSource->Order = "coa.account_name";
            list($this->id_coa->BoundColumn, $this->id_coa->TextColumn, $this->id_coa->DBFormat) = array("id_coa", "account_name", "");
            $this->id_coa->DataSource->Parameters["expr93"] = 'Income';
            $this->id_coa->DataSource->Parameters["sesSubscriberID"] = CCGetSession("SubscriberID", NULL);
            $this->id_coa->DataSource->wp = new clsSQLParameters();
            $this->id_coa->DataSource->wp->AddParameter("1", "expr93", ccsText, "", "", $this->id_coa->DataSource->Parameters["expr93"], "", false);
            $this->id_coa->DataSource->wp->AddParameter("2", "sesSubscriberID", ccsInteger, "", "", $this->id_coa->DataSource->Parameters["sesSubscriberID"], "", false);
            $this->id_coa->DataSource->wp->Criterion[1] = $this->id_coa->DataSource->wp->Operation(opEqual, "ref_ac_type.account_type", $this->id_coa->DataSource->wp->GetDBValue("1"), $this->id_coa->DataSource->ToSQL($this->id_coa->DataSource->wp->GetDBValue("1"), ccsText),false);
            $this->id_coa->DataSource->wp->Criterion[2] = $this->id_coa->DataSource->wp->Operation(opEqual, "id_subscriber", $this->id_coa->DataSource->wp->GetDBValue("2"), $this->id_coa->DataSource->ToSQL($this->id_coa->DataSource->wp->GetDBValue("2"), ccsInteger),false);
            $this->id_coa->DataSource->Where = $this->id_coa->DataSource->wp->opAND(
                 false, 
                 $this->id_coa->DataSource->wp->Criterion[1], 
                 $this->id_coa->DataSource->wp->Criterion[2]);
            $this->id_coa->DataSource->Order = "coa.account_name";
            $this->rcpt_mode = new clsControl(ccsListBox, "rcpt_mode", "Rcpt Mode", ccsInteger, "", CCGetRequestParam("rcpt_mode", $Method, NULL), $this);
            $this->rcpt_mode->DSType = dsTable;
            $this->rcpt_mode->DataSource = new clsDBbkaas();
            $this->rcpt_mode->ds = & $this->rcpt_mode->DataSource;
            $this->rcpt_mode->DataSource->SQL = "SELECT * \n" .
"FROM ref_pay_method {SQL_Where} {SQL_OrderBy}";
            list($this->rcpt_mode->BoundColumn, $this->rcpt_mode->TextColumn, $this->rcpt_mode->DBFormat) = array("id_pay_method", "name", "");
            $this->rcpt_mode->Required = true;
            $this->rcpt_mode_det = new clsControl(ccsTextBox, "rcpt_mode_det", "Rcpt Mode Det", ccsText, "", CCGetRequestParam("rcpt_mode_det", $Method, NULL), $this);
            $this->txn_desc = new clsControl(ccsTextBox, "txn_desc", "Txn Desc", ccsText, "", CCGetRequestParam("txn_desc", $Method, NULL), $this);
            $this->notes = new clsControl(ccsTextArea, "notes", "Notes", ccsText, "", CCGetRequestParam("notes", $Method, NULL), $this);
            $this->notes->Required = true;
            $this->cancel = new clsButton("cancel", $Method, $this);
         //  $this->upload_type = new clsControl(ccsLabel, "upload_type", "upload_type", ccsText, "", CCGetRequestParam("upload_type", ccsGet, NULL), $this);
            $this->txn_date = new clsControl(ccsTextBox, "txn_date", "Txn Date", ccsDate, array("mm", "/", "dd", "/", "yyyy"), CCGetRequestParam("txn_date", $Method, NULL), $this);
            $this->txn_date->Required = true;
            $this->amount = new clsControl(ccsTextBox, "amount", "Amount", ccsSingle, "", CCGetRequestParam("amount", $Method, NULL), $this);
            $this->amount->Required = true;
            $this->funding_source = new clsControl(ccsListBox, "funding_source", "funding_source", ccsText, "", CCGetRequestParam("funding_source", $Method, NULL), $this);
            $this->funding_source->DSType = dsTable;
            $this->funding_source->DataSource = new clsDBbkaas();
            $this->funding_source->ds = & $this->funding_source->DataSource;
            $this->funding_source->DataSource->SQL = "SELECT id_coa, account_name \n" .
"FROM coa INNER JOIN ref_ac_type ON\n" .
"coa.account_type = ref_ac_type.id_ac_type {SQL_Where} {SQL_OrderBy}";
            $this->funding_source->DataSource->Order = "account_name";
            list($this->funding_source->BoundColumn, $this->funding_source->TextColumn, $this->funding_source->DBFormat) = array("id_coa", "account_name", "");
            $this->funding_source->DataSource->Parameters["expr100"] = 'Asset';
            $this->funding_source->DataSource->Parameters["sesSubscriberID"] = CCGetSession("SubscriberID", NULL);
            $this->funding_source->DataSource->wp = new clsSQLParameters();
            $this->funding_source->DataSource->wp->AddParameter("1", "expr100", ccsText, "", "", $this->funding_source->DataSource->Parameters["expr100"], "", false);
            $this->funding_source->DataSource->wp->AddParameter("2", "sesSubscriberID", ccsInteger, "", "", $this->funding_source->DataSource->Parameters["sesSubscriberID"], "", false);
            $this->funding_source->DataSource->wp->Criterion[1] = $this->funding_source->DataSource->wp->Operation(opEqual, "ref_ac_type.account_type", $this->funding_source->DataSource->wp->GetDBValue("1"), $this->funding_source->DataSource->ToSQL($this->funding_source->DataSource->wp->GetDBValue("1"), ccsText),false);
            $this->funding_source->DataSource->wp->Criterion[2] = $this->funding_source->DataSource->wp->Operation(opEqual, "id_subscriber", $this->funding_source->DataSource->wp->GetDBValue("2"), $this->funding_source->DataSource->ToSQL($this->funding_source->DataSource->wp->GetDBValue("2"), ccsInteger),false);
            $this->funding_source->DataSource->Where = $this->funding_source->DataSource->wp->opAND(
                 false, 
                 $this->funding_source->DataSource->wp->Criterion[1], 
                 $this->funding_source->DataSource->wp->Criterion[2]);
            $this->funding_source->DataSource->Order = "account_name";
            $this->funding_source->Required = true;


            $this->upload_type = new clsControl(ccsListBox, "upload_type", "upload_type", ccsText, "", CCGetRequestParam("upload_type", $Method, NULL), $this);
            $this->upload_type->DSType = dsTable;
            $this->upload_type->DataSource = new clsDBbkaas();
            $this->upload_type->ds = & $this->upload_type->DataSource;
            $this->upload_type->DataSource->SQL = "SELECT id_refuploadtype, upload_type \n" .
"FROM refuploadtype {SQL_Where} {SQL_OrderBy}";
            $this->upload_type->DataSource->Order = "upload_type";
            list($this->upload_type->BoundColumn, $this->upload_type->TextColumn, $this->upload_type->DBFormat) = array("", "", "");
            $this->upload_type->DataSource->Parameters["expr131"] = 1;
            $this->inc_success = new clsControl(ccsLabel, "inc_success", "inc_success", ccsText, "", CCGetRequestParam("inc_success", $Method, NULL), $this);
            $this->inc_success->HTML = true;
            $this->upload_type->DataSource->wp = new clsSQLParameters();
            $this->upload_type->DataSource->wp->AddParameter("1", "expr131", ccsInteger, "", "", $this->upload_type->DataSource->Parameters["expr131"], "", false);
            $this->upload_type->DataSource->wp->Criterion[1] = $this->upload_type->DataSource->wp->Operation(opEqual, "isIncome", $this->upload_type->DataSource->wp->GetDBValue("1"), $this->upload_type->DataSource->ToSQL($this->upload_type->DataSource->wp->GetDBValue("1"), ccsInteger),false);
            $this->upload_type->DataSource->Where = 
                 $this->upload_type->DataSource->wp->Criterion[1];
            $this->upload_type->DataSource->Order = "upload_type";
             
            if(!$this->FormSubmitted) {
                if(!is_array($this->id_subscriber->Value) && !strlen($this->id_subscriber->Value) && $this->id_subscriber->Value !== false)
                    $this->id_subscriber->SetText(CCGetSession('SubscriberID'));
                if(!is_array($this->id_user->Value) && !strlen($this->id_user->Value) && $this->id_user->Value !== false)
                    $this->id_user->SetText(CCGetSession('UserID'));
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @2-BAF081AF
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlid_income"] = CCGetFromGet("id_income", NULL);
        $this->DataSource->Parameters["sesSubscriberID"] = CCGetSession("SubscriberID", NULL);
    }
//End Initialize Method

//Validate Method @2-F2B9FF83
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->id_subscriber->Validate() && $Validation);
        $Validation = ($this->id_user->Validate() && $Validation);
        $Validation = ($this->id_customer->Validate() && $Validation);
        $Validation = ($this->id_coa->Validate() && $Validation);
        $Validation = ($this->rcpt_mode->Validate() && $Validation);
        $Validation = ($this->rcpt_mode_det->Validate() && $Validation);
        $Validation = ($this->txn_desc->Validate() && $Validation);
        $Validation = ($this->notes->Validate() && $Validation);
        $Validation = ($this->txn_date->Validate() && $Validation);
        $Validation = ($this->amount->Validate() && $Validation);
        $Validation = ($this->upload_type->Validate() && $Validation);
        $Validation = ($this->funding_source->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->id_subscriber->Errors->Count() == 0);
        $Validation =  $Validation && ($this->id_user->Errors->Count() == 0);
        $Validation =  $Validation && ($this->id_customer->Errors->Count() == 0);
        $Validation =  $Validation && ($this->id_coa->Errors->Count() == 0);
        $Validation =  $Validation && ($this->rcpt_mode->Errors->Count() == 0);
        $Validation =  $Validation && ($this->rcpt_mode_det->Errors->Count() == 0);
        $Validation =  $Validation && ($this->txn_desc->Errors->Count() == 0);
        $Validation =  $Validation && ($this->notes->Errors->Count() == 0);
        $Validation =  $Validation && ($this->txn_date->Errors->Count() == 0);
        $Validation =  $Validation && ($this->amount->Errors->Count() == 0);
        $Validation =  $Validation && ($this->funding_source->Errors->Count() == 0);
         $Validation =  $Validation && ($this->upload_type->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-713CA49A
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->id_subscriber->Errors->Count());
        $errors = ($errors || $this->id_user->Errors->Count());
        $errors = ($errors || $this->id_customer->Errors->Count());
        $errors = ($errors || $this->id_coa->Errors->Count());
        $errors = ($errors || $this->rcpt_mode->Errors->Count());
        $errors = ($errors || $this->rcpt_mode_det->Errors->Count());
        $errors = ($errors || $this->txn_desc->Errors->Count());
        $errors = ($errors || $this->inc_success->Errors->Count());
        $errors = ($errors || $this->notes->Errors->Count());
        $errors = ($errors || $this->upload_type->Errors->Count());
        $errors = ($errors || $this->txn_date->Errors->Count());
        $errors = ($errors || $this->amount->Errors->Count());
        $errors = ($errors || $this->funding_source->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
     
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//Operation Method @2-368EC694
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->DataSource->Prepare();
        if(!$this->FormSubmitted) {
            $this->EditMode = $this->DataSource->AllParametersSet;
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = $this->EditMode ? "Button_Update" : "Button_Insert";
            if($this->Button_Insert->Pressed) {
                $this->PressedButton = "Button_Insert";
            } else if($this->Button_Update->Pressed) {
                $this->PressedButton = "Button_Update";
            } else if($this->Button_Delete->Pressed) {
                $this->PressedButton = "Button_Delete";
            } else if($this->cancel->Pressed) {
                $this->PressedButton = "cancel";
            }
        }
        $Redirect = "income_list.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick", $this->Button_Delete) || !$this->DeleteRow()) {
                $Redirect = "";
            }
        } else if($this->PressedButton == "cancel") {
            $Redirect = ServerURL . "" . "income_list.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
            if(!CCGetEvent($this->cancel->CCSEvents, "OnClick", $this->cancel)) {
                $Redirect = "";
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "Button_Insert") {
                if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick", $this->Button_Insert) || !$this->InsertRow()) {
                  
                    $Redirect = "";

                }
            } else if($this->PressedButton == "Button_Update") {
                if(!CCGetEvent($this->Button_Update->CCSEvents, "OnClick", $this->Button_Update) || !$this->UpdateRow()) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
        if ($Redirect)
            $this->DataSource->close();
    }
//End Operation Method

//InsertRow Method @2-CD5F038C
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->id_subscriber->SetValue($this->id_subscriber->GetValue(true));
        $this->DataSource->id_user->SetValue($this->id_user->GetValue(true));
        $this->DataSource->id_customer->SetValue($this->id_customer->GetValue(true));
        $this->DataSource->id_coa->SetValue($this->id_coa->GetValue(true));
        $this->DataSource->rcpt_mode->SetValue($this->rcpt_mode->GetValue(true));
        $this->DataSource->rcpt_mode_det->SetValue($this->rcpt_mode_det->GetValue(true));
        $this->DataSource->txn_desc->SetValue($this->txn_desc->GetValue(true));
        $this->DataSource->inc_success->SetValue($this->inc_success->GetValue(true));
        $this->DataSource->notes->SetValue($this->notes->GetValue(true));
        $this->DataSource->upload_type->SetValue($this->upload_type->GetValue(true));
        $this->DataSource->txn_date->SetValue($this->txn_date->GetValue(true));
        $this->DataSource->amount->SetValue($this->amount->GetValue(true));
        $this->DataSource->funding_source->SetValue($this->funding_source->GetValue(true));
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
      //  print_r("done");exit;
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @2-A7C0151B
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->id_subscriber->SetValue($this->id_subscriber->GetValue(true));
        $this->DataSource->id_user->SetValue($this->id_user->GetValue(true));
        $this->DataSource->id_customer->SetValue($this->id_customer->GetValue(true));
        $this->DataSource->id_coa->SetValue($this->id_coa->GetValue(true));
        $this->DataSource->rcpt_mode->SetValue($this->rcpt_mode->GetValue(true));
        $this->DataSource->rcpt_mode_det->SetValue($this->rcpt_mode_det->GetValue(true));
        $this->DataSource->txn_desc->SetValue($this->txn_desc->GetValue(true));
        $this->DataSource->notes->SetValue($this->notes->GetValue(true));
        $this->DataSource->upload_type->SetValue($this->upload_type->GetValue(true));
        $this->DataSource->inc_success->SetValue($this->inc_success->GetValue(true));
        $this->DataSource->txn_date->SetValue($this->txn_date->GetValue(true));
        $this->DataSource->amount->SetValue($this->amount->GetValue(true));
        $this->DataSource->funding_source->SetValue($this->funding_source->GetValue(true));
        $this->DataSource->Update();
        //print_r(expression)
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @2-299D98C3
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete", $this);
        if(!$this->DeleteAllowed) return false;
        $this->DataSource->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete", $this);
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @2-701D2748
    function Show()
    {
        global $CCSUseAmp;
        $Tpl = CCGetTemplate($this);
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);

        $this->id_subscriber->Prepare();
        $this->id_user->Prepare();
        $this->id_customer->Prepare();
        $this->id_coa->Prepare();
         $this->DataSource->Parameters["urlupload_type"] = CCGetFromGet("upload_type", NULL);
        $this->rcpt_mode->Prepare();
        $this->funding_source->Prepare();
          $this->upload_type->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if($this->EditMode) {
            if($this->DataSource->Errors->Count()){
                $this->Errors->AddErrors($this->DataSource->Errors);
                $this->DataSource->Errors->clear();
            }
            $this->DataSource->Open();
            if($this->DataSource->Errors->Count() == 0 && $this->DataSource->next_record()) {
                $this->DataSource->SetValues();
                if(!$this->FormSubmitted){
                    $this->id_subscriber->SetValue($this->DataSource->id_subscriber->GetValue());
                    $this->id_user->SetValue($this->DataSource->id_user->GetValue());
                    $this->id_customer->SetValue($this->DataSource->id_customer->GetValue());
                    $this->id_coa->SetValue($this->DataSource->id_coa->GetValue());
                    $this->rcpt_mode->SetValue($this->DataSource->rcpt_mode->GetValue());
                    $this->rcpt_mode_det->SetValue($this->DataSource->rcpt_mode_det->GetValue());
                    $this->txn_desc->SetValue($this->DataSource->txn_desc->GetValue());
                    $this->notes->SetValue($this->DataSource->notes->GetValue());
                    $this->txn_date->SetValue($this->DataSource->txn_date->GetValue());
                    $this->amount->SetValue($this->DataSource->amount->GetValue());
                    $this->funding_source->SetValue($this->DataSource->funding_source->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->id_subscriber->Errors->ToString());
            $Error = ComposeStrings($Error, $this->id_user->Errors->ToString());
            $Error = ComposeStrings($Error, $this->id_customer->Errors->ToString());
            $Error = ComposeStrings($Error, $this->id_coa->Errors->ToString());
            $Error = ComposeStrings($Error, $this->rcpt_mode->Errors->ToString());
            $Error = ComposeStrings($Error, $this->rcpt_mode_det->Errors->ToString());
            $Error = ComposeStrings($Error, $this->txn_desc->Errors->ToString());
            $Error = ComposeStrings($Error, $this->notes->Errors->ToString());
            $Error = ComposeStrings($Error, $this->inc_success->Errors->ToString());
            $Error = ComposeStrings($Error, $this->upload_type->Errors->ToString());
            $Error = ComposeStrings($Error, $this->txn_date->Errors->ToString());
            $Error = ComposeStrings($Error, $this->amount->Errors->ToString());
            $Error = ComposeStrings($Error, $this->funding_source->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DataSource->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
       /* $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", !$CCSUseAmp ? $this->HTMLFormAction : str_replace("&", "&amp;", $this->HTMLFormAction));
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);
        $this->Button_Insert->Visible = !$this->EditMode && $this->InsertAllowed;
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;
        $this->Button_Delete->Visible = $this->EditMode && $this->DeleteAllowed;
*/
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", !$CCSUseAmp ? $this->HTMLFormAction : str_replace("&", "&amp;", $this->HTMLFormAction));
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);
        $this->Button_Insert->Visible = !$this->EditMode && $this->InsertAllowed;
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;
        $this->Button_Delete->Visible = $this->EditMode && $this->DeleteAllowed;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        $this->Attributes->Show();
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $this->id_subscriber->Show();
        $this->id_user->Show();
        $this->id_customer->Show();
        $this->id_coa->Show();
        $this->rcpt_mode->Show();
        $this->inc_success->Show();
        $this->rcpt_mode_det->Show();
        $this->txn_desc->Show();
        $this->notes->Show();
        $this->cancel->Show();
 // echo  $this->upload_type->Show();exit();  
        $this->upload_type->Show();
        $this->txn_date->Show();
        $this->amount->Show();
        $this->funding_source->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End income Class @2-FCB6E20C

class clsincomeDataSource extends clsDBbkaas {  //incomeDataSource Class @2-77869F68

//DataSource Variables @2-F016D5AC
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $InsertParameters;
    public $UpdateParameters;
    public $DeleteParameters;
    public $wp;
    public $AllParametersSet;

    public $InsertFields = array();
    public $UpdateFields = array();

    // Datasource fields
    public $id_subscriber;
    public $id_user;
    public $id_customer;
    public $id_coa;
    public $rcpt_mode;
    public $rcpt_mode_det;
    public $txn_desc;
    public $notes;
    public $upload_type;
    public $txn_date;
    public $inc_success;
    public $amount;
    public $funding_source;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-D083ABAE
    function clsincomeDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record income/Error";
        $this->Initialize();
        $this->id_subscriber = new clsField("id_subscriber", ccsInteger, "");
        
        $this->id_user = new clsField("id_user", ccsInteger, "");
        
        $this->id_customer = new clsField("id_customer", ccsInteger, "");
        
        $this->id_coa = new clsField("id_coa", ccsInteger, "");
        
        $this->rcpt_mode = new clsField("rcpt_mode", ccsInteger, "");
        
        $this->rcpt_mode_det = new clsField("rcpt_mode_det", ccsText, "");
        
        $this->txn_desc = new clsField("txn_desc", ccsText, "");
        
        $this->notes = new clsField("notes", ccsText, "");
        
          $this->upload_type = new clsField("upload_type", ccsText, "");
        
        $this->txn_date = new clsField("txn_date", ccsDate, array("yyyy", "-", "mm", "-", "dd", " ", "HH", ":", "nn", ":", "ss"));
        
        $this->amount = new clsField("amount", ccsSingle, "");

        $this->inc_success = new clsField("inc_success", ccsText, "");
        
        $this->funding_source = new clsField("funding_source", ccsText, "");
        

        $this->InsertFields["id_subscriber"] = array("Name" => "id_subscriber", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        //$this->InsertFields["upload_type_name"] = array("Name" => "upload_type_name", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["id_user"] = array("Name" => "id_user", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["id_customer"] = array("Name" => "id_customer", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["id_coa"] = array("Name" => "id_coa", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["rcpt_mode"] = array("Name" => "rcpt_mode", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["rcpt_mode_det"] = array("Name" => "rcpt_mode_det", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["txn_desc"] = array("Name" => "txn_desc", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["notes"] = array("Name" => "notes", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["txn_date"] = array("Name" => "txn_date", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["amount"] = array("Name" => "amount", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->InsertFields["funding_source"] = array("Name" => "funding_source", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["id_subscriber"] = array("Name" => "id_subscriber", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["id_user"] = array("Name" => "id_user", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["id_customer"] = array("Name" => "id_customer", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["id_coa"] = array("Name" => "id_coa", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["rcpt_mode"] = array("Name" => "rcpt_mode", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["rcpt_mode_det"] = array("Name" => "rcpt_mode_det", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["txn_desc"] = array("Name" => "txn_desc", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["notes"] = array("Name" => "notes", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["txn_date"] = array("Name" => "txn_date", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["amount"] = array("Name" => "amount", "Value" => "", "DataType" => ccsSingle, "OmitIfEmpty" => 1);
        $this->UpdateFields["funding_source"] = array("Name" => "funding_source", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
    }
//End DataSourceClass_Initialize Event

//Prepare Method @2-1A2C4CA8
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlid_income", ccsInteger, "", "", $this->Parameters["urlid_income"], "", false);
        $this->wp->AddParameter("2", "sesSubscriberID", ccsInteger, "", "", $this->Parameters["sesSubscriberID"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "id_income", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "id_subscriber", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),false);
        $this->Where = $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]);
    }
//End Prepare Method

//Open Method @2-8347614A
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT * \n\n" .
        "FROM income {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @2-969484CB
    function SetValues()
    {
        $this->id_subscriber->SetDBValue(trim($this->f("id_subscriber")));
        $this->id_user->SetDBValue(trim($this->f("id_user")));
        $this->id_customer->SetDBValue(trim($this->f("id_customer")));
        $this->id_coa->SetDBValue(trim($this->f("id_coa")));
        $this->rcpt_mode->SetDBValue(trim($this->f("rcpt_mode")));
        $this->rcpt_mode_det->SetDBValue($this->f("rcpt_mode_det"));
        $this->txn_desc->SetDBValue($this->f("txn_desc"));
        $this->notes->SetDBValue($this->f("notes"));
        $this->txn_date->SetDBValue(trim($this->f("txn_date")));
        $this->amount->SetDBValue(trim($this->f("amount")));
        $this->funding_source->SetDBValue($this->f("funding_source"));
    }
//End SetValues Method

//Insert Method @2-834C7DE4
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->InsertFields["id_subscriber"]["Value"] = $this->id_subscriber->GetDBValue(true);
        $this->InsertFields["id_user"]["Value"] = $this->id_user->GetDBValue(true);
        $this->InsertFields["id_customer"]["Value"] = $this->id_customer->GetDBValue(true);
        $this->InsertFields["id_coa"]["Value"] = $this->id_coa->GetDBValue(true);
        $this->InsertFields["rcpt_mode"]["Value"] = $this->rcpt_mode->GetDBValue(true);
        $this->InsertFields["rcpt_mode_det"]["Value"] = $this->rcpt_mode_det->GetDBValue(true);
        $this->InsertFields["txn_desc"]["Value"] = $this->txn_desc->GetDBValue(true);
        $this->InsertFields["notes"]["Value"] = $this->notes->GetDBValue(true);
        $this->InsertFields["txn_date"]["Value"] = $this->txn_date->GetDBValue(true);
        $this->InsertFields["amount"]["Value"] = $this->amount->GetDBValue(true);
        $this->InsertFields["funding_source"]["Value"] = $this->funding_source->GetDBValue(true);
        $this->SQL = CCBuildInsert("income", $this->InsertFields, $this);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @2-C6A4F25F
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->UpdateFields["id_subscriber"]["Value"] = $this->id_subscriber->GetDBValue(true);
        $this->UpdateFields["id_user"]["Value"] = $this->id_user->GetDBValue(true);
        $this->UpdateFields["id_customer"]["Value"] = $this->id_customer->GetDBValue(true);
        $this->UpdateFields["id_coa"]["Value"] = $this->id_coa->GetDBValue(true);
        $this->UpdateFields["rcpt_mode"]["Value"] = $this->rcpt_mode->GetDBValue(true);
        $this->UpdateFields["rcpt_mode_det"]["Value"] = $this->rcpt_mode_det->GetDBValue(true);
        $this->UpdateFields["txn_desc"]["Value"] = $this->txn_desc->GetDBValue(true);
        $this->UpdateFields["notes"]["Value"] = $this->notes->GetDBValue(true);
        $this->UpdateFields["txn_date"]["Value"] = $this->txn_date->GetDBValue(true);
        $this->UpdateFields["amount"]["Value"] = $this->amount->GetDBValue(true);
        $this->UpdateFields["funding_source"]["Value"] = $this->funding_source->GetDBValue(true);
        $this->SQL = CCBuildUpdate("income", $this->UpdateFields, $this);
        $this->SQL .= strlen($this->Where) ? " WHERE " . $this->Where : $this->Where;
        if (!strlen($this->Where) && $this->Errors->Count() == 0) 
            $this->Errors->addError($CCSLocales->GetText("CCS_CustomOperationError_MissingParameters"));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate", $this->Parent);
        }
    }
//End Update Method

//Delete Method @2-0A2ACE34
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $Where = "";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM income";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        if (!strlen($this->Where) && $this->Errors->Count() == 0) 
            $this->Errors->addError($CCSLocales->GetText("CCS_CustomOperationError_MissingParameters"));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete", $this->Parent);
        }
    }
//End Delete Method

} //End incomeDataSource Class @2-FCB6E20C

//Initialize Page @1-3F8B44D8
// Variables
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";
$Attributes = "";
$PathToCurrentMasterPage = "";
$TemplatePathValue = "";

// Events;
$CCSEvents = "";
$CCSEventResult = "";
$MasterPage = null;
$TemplateSource = "";

$FileName = FileName;
$Redirect = "";
$TemplateFileName = "income_maint.html";
$BlockToParse = "main";
$TemplateEncoding = "UTF-8";
$ContentType = "text/html";
$PathToRoot = "./";
$PathToRootOpt = "";
$Scripts = "|js/jquery/jquery.js|js/jquery/event-manager.js|js/jquery/selectors.js|js/jquery/ui/jquery.ui.core.js|js/jquery/ui/jquery.ui.widget.js|js/jquery/ui/jquery.ui.datepicker.js|js/jquery/datepicker/ccs-date-timepicker.js|";
$Charset = $Charset ? $Charset : "utf-8";
//End Initialize Page

//Authenticate User @1-CDC24717
CCSecurityRedirect("", "login.php");
//End Authenticate User

//Include events file @1-25B7F25C
include_once("./income_maint_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-3518FE4A
$DBbkaas = new clsDBbkaas();
$MainPage->Connections["bkaas"] = & $DBbkaas;
$Attributes = new clsAttributes("page:");
$Attributes->SetValue("pathToRoot", $PathToRoot);
$MainPage->Attributes = & $Attributes;

// Controls
$MasterPage = new clsMasterPage("/Designs/" . $CCProjectDesign . "/", "MasterPage", $MainPage);
$MasterPage->Attributes = $Attributes;
$MasterPage->Initialize();
$Head = new clsPanel("Head", $MainPage);
$Head->PlaceholderName = "Head";
$content = new clsPanel("content", $MainPage);
$content->PlaceholderName = "content";
$income = new clsRecordincome("", $MainPage);
$MainPage->Head = & $Head;
$MainPage->content = & $content;
$MainPage->income = & $income;
$content->AddComponent("income", $income);
$income->Initialize();
$ScriptIncludes = "";
$SList = explode("|", $Scripts);
foreach ($SList as $Script) {
    if ($Script != "") $ScriptIncludes = $ScriptIncludes . "<script src=\"" . $PathToRoot . $Script . "\" type=\"text/javascript\"></script>\n";
}
$Attributes->SetValue("scriptIncludes", $ScriptIncludes);

BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize", $MainPage);

if ($Charset) {
    header("Content-Type: " . $ContentType . "; charset=" . $Charset);
} else {
    header("Content-Type: " . $ContentType);
}
//End Initialize Objects

//Initialize HTML Template @1-85214512
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView", $MainPage);
$Tpl = new clsTemplate($FileEncoding, $TemplateEncoding);
if (strlen($TemplateSource)) {
    $Tpl->LoadTemplateFromStr($TemplateSource, $BlockToParse, "UTF-8");
} else {
    $Tpl->LoadTemplate(PathToCurrentPage . $TemplateFileName, $BlockToParse, "UTF-8");
}
$Tpl->SetVar("CCS_PathToRoot", $PathToRoot);
$Tpl->SetVar("CCS_PathToMasterPage", RelativePath . $PathToCurrentMasterPage);
$Tpl->block_path = "/$BlockToParse";
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow", $MainPage);
$Attributes->SetValue("pathToRoot", "");
$Attributes->Show();
//End Initialize HTML Template

//Execute Components @1-28C282A0
$MasterPage->Operations();
$income->Operation();
//End Execute Components

//Go to destination page @1-0A4C864C
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBbkaas->close();
    header("Location: " . $Redirect);
    unset($income);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-B5B52F5B
$Head->Show();
$content->Show();
$MasterPage->Tpl->SetVar("Head", $Tpl->GetVar("Panel Head"));
$MasterPage->Tpl->SetVar("content", $Tpl->GetVar("Panel content"));
$MasterPage->Show();
if (!isset($main_block)) $main_block = $MasterPage->HTML;
$main_block = CCConvertEncoding($main_block, $FileEncoding, $TemplateEncoding);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-B973F0D6
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBbkaas->close();
unset($MasterPage);
unset($income);
unset($Tpl);
//End Unload Page


?>
