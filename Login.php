<?php
session_start();


include_once("include/DBFunctions.php");
include_once("include/BootstrapDisplay.php");
include_once("include/BootstrapForm.php");
include_once("include/BootstrapNav.php");
/**
 * Function: checkUserName
 * Purpose: Checks to see if the username exists and is equal to username passed in
 *          If a valid user exist, it will return the auction member's id,
 *          otherwise will return a 0
 * @param type $obDB - a reference to the database object
 * @param type $username -  the username entered into the login form
*/
 function checkUserName($obDB, $username)
{
    $validUser = 0;
    $sSQL="select id, username from user where username='".$username."'";
    $obDB->doQuery($sSQL);
    $sqlResult = $obDB->fetchAssocResult();
    if(count($sqlResult)!=0)
    {
        if($sqlResult[0]["username"]==$username)
        {
            $validUser=$sqlResult[0]["id"];
        }
    }
    
    return $validUser;
} 
/**
 * Function: checkPassword
 * Purpose: If the username exists, this method will be called. It will check
 *          to see if the password entered into the form is what exists for
 *          the password for that username in the database
 * @param type $obDB - a reference to the database object
 * @param type $password -  the password entered into the login form
 * @param type $id - the member id
*/
function checkPassword($obDB,$password, $id)
{
    $validPassword = 0;

    $sSQLPassword="SELECT password FROM user where id=" . $id;
    $obDB->doQuery($sSQLPassword);
    $sqlResultPassword = $obDB->fetchAssocResult();
    if(count($sqlResultPassword)!=0)
    {
        if(trim($sqlResultPassword[0]["password"])==trim($password))
        {
            $validPassword =  true;
        }
    }
    
    return $validPassword;
} 
/**
 * Function: display
 * Purpose: To display the form. If the flag is false there was a login error 
 *          so display a message,otherwise just display the username 
 *          and password fields
 * @param type $flag - if false that means there was a login error 
 *                      so display an error message
 */
function display($error="")
{
	$display = new BootstrapDisplay();
	$display->pageHead("Blog", "<link rel='stylesheet' href='include/BootstrapPHPCSS.css'>");

	$nav = new BootstrapNav($otherClasses="",$inverse=true); 
	$aLinks = array(
		array("link"=>"HomePage.php","label"=>"Home"),
		array("link"=>"Blog.php","label"=>"Blog"),
		array("link"=>"CreatePost.php","label"=>"Create Post"),
	);
	$nav->addLinks($aLinks);
	$logInOut = $nav->getLogInOutButton(isset($_SESSION["user"]), "login.php", "logout.php"); 
	$signUp=$nav->getSignupButton("signup.php"); 
	$nav->addNavBarRight($logInOut . ' ' . $signUp);
	
	
	$form = new BootstrapForm("login");
	$form->addBasicFormControl("Username", "username", "text");
	$form->addBasicFormControl("Password", "password", "password");
	$form = $form->getForm();
	
	//$nav->addNavBarRight($items);
	$body="<h1 class='heading'>Login</h1>";
	if($error!="")
    {
        $body .= $error."\n";
    }
	$body.=$form;
	$display->nav($nav->getNav());
	$display->mainBody($body);
	echo $display->displayPage();

}

if(!isset($_POST["username"]) && !isset($_POST["password"]))
{
    $error="";
    if(isset($_GET["AccessDenied"]))
    {
        $error = "<p class='errorMsg'>You need to Login to "
                . "create an post</p>";
    }
    display($error);
}
else if(isset($_POST["username"]) && isset($_POST["password"]))
{
	
    $db = new DBFunctions();
    $username = $db->sanitize("username");
    $password = $db->sanitize("password");

    $id = checkUserName($db, $username);
    $validPassword = false;
    if($id != 0)
    {
        $validPassword = checkPassword($db,$password, $id);
        if($validPassword)
        {
            $_SESSION["user"] = $id;
			header("location:Home.php");
        }
    }
    if($id==0 || !$validPassword)
    {
        display("<p class='errorMsg'>Incorrect Username or Password</p>");
    }
	
}
?>
