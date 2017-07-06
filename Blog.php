<?php
session_start();


include_once("include/DBFunctions.php");
include_once("include/BootstrapDisplay.php");
include_once("include/BootstrapForm.php");
include_once("include/BootstrapNav.php");

function displayBlogPosts()
{
	$html = "\t<div id='postlist'>\n";
	$db=new DBFunctions();
	$db->doQuery("select * from posts");
	$sqlResult = $db->fetchAssocResult();
	foreach ($sqlResult as $row) {
		$classes = "col-sm-8 col-md-8 col-lg-8 col-sm-offset-2 col-md-offset-2 col-lg-offset-2";
		$html.="\t\t<div class='post ".$classes."'>\n";
		$html.="\t\t\t<h2 class='title'><a href='".$_SERVER['REQUEST_URI']."/".$row["id"]."'>".$row["title"]."</a></h2>\n";
		$html.="\t\t\t<p class='posttext'>".$row["post"]."</p>\n";
		
		if($row["imagelink"] != null || $row["imagelink"] != "")
		{
			 $html.="\t\t\t<img class='img' src='pictures/".$row["imagelink"]."'>\n";
		}
		$html.="\t\t\t<p class='date'>".$row["date"]."</p>\n";
		 $html.="\t\t</div>\n";

	}
	$html.="\t</div>\n";
	return $html;
}


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
$body="<h1 class='heading'>Blog Posts</h1>\n";
$display->nav($nav->getNav());
$display->mainBody($body.displayBlogPosts());
echo $display->displayPage();
