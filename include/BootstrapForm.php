<?php

class BootstrapForm
{
    protected $formBody;
    
    /**
     * Constructor for BootStapForm
     * @param STRING $sName
     * @param STRING $sAction
     * @param STRING $sMethod
     * @param STRING $sOptions
     */
    public function __construct($sName,$sAction="",$sMethod="POST",$sOptions="") 
    {
        $this->formBody = "\n<form name='$sName' id='$sName' "
                . "action='$sAction' Method='$sMethod' "
                . "class='form-horizontal' $sOptions>\n";
    }
    /**
     * Function addBasicFormControl
     * Purpose  Adds a bootstap form label and field to the form
     * @param type $sLabel - the label you want displayed
     * @param type $sName - used for both name and id of form field
     * @param type $sType - can be almost all input types except for
     *                      submit/reset, and select boxes
     * @param STRING $sOptions - extra options for an input
     * @param STRING $sErrorMsg -  error message for the field
     * @param STRING $sValue - the value for the field
     * 
     * NOTE: errorMsg and value are used for if there are errors in the field,
     * the value is there so that the value can be displayed, so user can see
     * what they entered 
     */
    public function addBasicFormControl($sLabel, $sName, $sType, 
            $sOptions="", $sErrorMsg="", $sValue="")
    {
        $formControl= "<div class='form-group'>"
            ."<label class='col-sm-offset-1 control-label col-sm-2'>"
                . "$sLabel</label>\n"
            ."<div class='col-sm-6'>\n"
            ."    <input type='$sType' class='form-control' id='$sName' "
                . "name='$sName' $sOptions value='$sValue'>\n";
        if($sErrorMsg!="")
        {
            $formControl.="<span class='error'>$sErrorMsg</span>\n";
        }
        
        $formControl.= "</div>\n"
        ."</div>\n";
        
        $this->formBody .= $formControl;
    }
    /**
     * Function addSelectBox
     * Purpose  Adds select box to form
     * @param STRING $sLabel - the label of the select box
     * @param STRING $sName - used for name and id of select box
     * @param STRING[] $aOptions - associative array of options, Name is
     *                         what is displayed, Value is the actual
     *                          value of the option
     */
    public function addSelectBox($sLabel, $sName, $aOptions)
    {
        $formControl= "<div class='form-group'>"
            ."<label class='col-sm-offset-1 control-label col-sm-2'>"
                . "$sLabel</label>\n"
            ."<div class='col-sm-6'>\n"
                . "<select class='form-control' id='$sName' name='$sName'>";
        foreach ($aOptions as $option) {
            $formControl.="<option value='".$option['Value']."'>".
                    $option['Name']."</option>";
        }
        
        $formControl.= "</select>"
            . "</div>\n"
        ."</div>\n";
        
        $this->formBody .= $formControl;
    }
    /**
     * Function addTextArea
     * Purpose  Adds a text area to the form
     * @param STRING $sLabel - the label of the text area
     * @param STRING $sName - used for name and if of text area
     * @param INT $rows - number of rows of the textarea
     * @param INT $cols - number of columns of the text area
     * @param STRING $sOptions - extra options for the text area
     */
    public function addTextArea($sLabel, $sName, $rows=5, $cols=50, 
            $sOptions="")
    {
        $formControl= "<div class='form-group'>"
                . "<label class='col-sm-offset-1 control-label col-sm-2'>"
                . "$sLabel</label>"
                . "<textarea cols='$cols' rows='$rows'"
                . "id='$sName' name='$sName' $sOptions></textarea>"
                . "</div>";
        
        $this->formBody .= $formControl;
    }
    
    /**
     * Function finishForm  
     * Purpose  Returns a completed form which for example can be a 
     *          parameter for the mainBody function in PageDisplay or
     *          be displayed by iteself
     * @param STRING $sSubmitText - the text for submit button
     * @param STRING $sName - name and id of submit button
     * @param STRING $sResetText -  the text for the reset button
     * @return STRING PageDisplayForm
     */
    public function getForm($sSubmitText = "Submit Form", $sName="submit", 
            $sResetText = "Clear Form")
    {
        $this->formBody .= "<div class='form-group'>\n"
            ."<div class='col-sm-offset-3 col-sm-10'>\n"
                  ."<input type='submit' class='btn btn-default' "
                . "name='$sName' id='$sName' value='$sSubmitText'>\n"
                ."<input type='reset' class='btn btn-default' "
                . "value='$sResetText'>\n"
            ."</div>\n"
        ."</div>\n</form>\n";
        
        return $this->formBody;
    }
}
