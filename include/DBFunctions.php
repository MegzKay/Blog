<?php

include("DBConnectionInfo.php");
define("DATE_TYPE",10);
define("VAR_CHAR_TYPE",253);
/**
 * Class: DBFunctions
 * This class is to make calls to the database
 * 
 * This was a class we used in class for the CST Program. Some methods I have
 * written and some methods have been written by various professors
 */

class DBFunctions {

    protected $mysqliObj;    //Database object we will work with
    protected $mysqliResult;    //Any result that might be returned
    protected $bDebug = 0;  //Debug flag
    protected $aFieldObjects; //array of fields for a table
	
    public function __construct() {
        $this->mysqliObj = new mysqli(HOST,USER, PASSWORD, DB);
        if(mysqli_connect_errno())
        {
            echo "</br>ERROR UPON CONNECTING TO DATABASE";
            echo "</br>ERROR NUMBER: ".mysqli_connect_error();
            echo "</br>ERROR MESSAGE: " . mysqli_connect_errno();
            throw new Exception(mysqli_connect_error(),mysqli_connect_errno());

        }
    }
    
    public function __destruct() {
        $this->mysqliObj->close();
    }
    
    /**
     * Function setDebug
     * Purpose Prints out debug statements if value passed in is true
     * @param BOOLEAN $bValue
     */
    public function setDebug($bValue=true)
    {
        $this->bDebug = $bValue;
    }
    
    /**
     * Function query
     * Purpose Query the database
     * @param STRING $sSQL - the sql statement
     */
    public function doQuery($sSQL)
    {
        if($this->bDebug)
        {
            echo "<br><br>DO QUERY<br>";
            echo "In Query: Running statement<br>$sSQL";
        }
        $this->mysqliResult = $this->mysqliObj->query($sSQL);
    }
	 /**
     * Function fetchAssocResult
     * Purpose Return an associcated array of results after a query
     * @return ASSOCIATIVE_ARRAY aResults - an associative array containing the results of a query in the form:
											Array([0]=>Array(FIELD=>VALUE FIELD=>VALUE)[1]Array(FIELD=>VALUE)...cont...)
     */
    public function fetchAssocResult()
    {
        $aResults = array();
        if($this->bDebug)
        {
            echo "<br>Fetch Assoc Result<br>";
			echo "Rows: ".$this->mysqliObj->affected_rows."<br>";
        }

		$rows = $this->mysqliObj->affected_rows;
        for ($i=0; $i < $rows; $i++)
        {
            array_push($aResults,$this->mysqliResult->fetch_assoc());
        }
		if($this->mysqliObj->affected_rows > 0)
		{
			$this->mysqliResult->data_seek(0);
		}
        
        
        return $aResults;
    }

	/**
     * Function insert
     * Purpose Insert row into the specified table
     * @param STRING=>STRING $aInfo - the field and value you want inserted into the table in the format FIELD=>VALUE
	 *  	  STRING $sTableName - the table to insert row into
     */
    public function doInsert($aInfo, $sTableName) 
    {
        $sInsert = "insert into $sTableName (";
        
        foreach($aInfo as $sKey=>$sValue)
        {
            $sInsert .= $sKey . ",";
        }
        
        $sInsert = rtrim($sInsert, ",");
        $sInsert.= ") values (";

         $this->getTableFieldInfo($sTableName);
        
        if ($this->bDebug)
        {
            $aFields = mysqli_fetch_fields($this->$mysqliResult);
			foreach ($aFields as $ref) {
				echo "Field type is ".$ref->type . "<br>";
			}
        }
        
        
        foreach ($aInfo as $sFldName => $sValue)
        {
            $nFieldType = $this->getFieldType($sFldName);

            if ($nFieldType == VAR_CHAR_TYPE  ||
                    $nFieldType == MYSQLI_TYPE_DATE)
            {
                $sInsert .= "'$sValue',";
            }
            else 
            {
                $sInsert .= $sValue . ",";
            }
            
            
        }
        $sInsert = rtrim("$sInsert" , ",");
        
        $sInsert .= ")";
        
       
        
        $this->query($sInsert);
        
        
    }
	/**
     * Function getFieldType
     * Purpose  This will return the type that is associated with
     *          a particular field name or 0
     * @param STRING sName - name of the field
     */
    protected function getFieldType($sName)
    {
        
        foreach ($this->aFieldObjects as $obElem) {
            if($obElem->name == $sName)
            {
                if($this->bDebug)
                {
                    echo "Looking at " . $sName . " and " . $obElem->name . "<br>";
                }
                
                return $obElem->type;
            }
        }
        
        //this should not happen
        return 0;
    }
	/**
     * Function 
     * Purpose  This function will get all Field Information and put it into aFieldObjects
     * @param STRING $sTableName - name of the table
     */
    protected function getTableFieldInfo($sTableName)
    {
        $this->query("SELECT * from $sTableName");

        $this->aFieldObjects = $this->mysqliResult->fetch_fields();
        
    }
	
   
    /**
     * Function getPrimeKey
     * Purpose Returns the lastest primary key from an insert query
     * @return INT primaryKey - the primary key of the last entry
     */
    public function getPrimeKey()
    {
        if($this->bDebug)
        {
            echo "<br><br>Prime Key<br>";
            echo $this->mysqliObj->insert_id;
        }
        return $this->mysqliObj->insert_id;
    }
	
	/*
	 * Function sanitize
	 * Purpose  This function sanitizes a string before putting into the database
	 * @params  STRING sTarget - the name of the input
	 */
	function sanitize($sTarget)
	{
		$sResult = filter_input(INPUT_POST, $sTarget, FILTER_SANITIZE_MAGIC_QUOTES);
		
		return trim($sResult);
	}
}
