<?php
session_start();


include_once("include/BootstrapDisplay.php");
include_once("include/BootstrapForm.php");
include_once("include/BootstrapNav.php");


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



//$nav->addNavBarRight($items);
$body="<h1 class='heading'>Blog Site</h1>";
$body.="<p class='welcome'>Welcome to my blog site</p>";
$display->nav($nav->getNav());
$display->mainBody($body);
echo $display->displayPage();