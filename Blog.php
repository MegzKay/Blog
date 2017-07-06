<?php
session_start();


include_once("include/DBFunctions.php");
include_once("include/BootstrapDisplay.php");
include_once("include/BootstrapForm.php");
include_once("include/BootstrapNav.php");




function displayAllPosts()
{
	$html = "\t<div id='postlist'>\n";
	$db=new DBFunctions();
	$db->doQuery("select * from posts");
	$sqlResult = $db->fetchAssocResult();
	$link = "/PHPFiles/Blog/Blog.php/";
	foreach ($sqlResult as $row) {
		$html.=displaySinglePost($row["title"], $row["post"], $row["postdate"], $row["imagelink"], $link.$row["id"]);
	}
	$html.="\t</div>\n";
	return $html;
}
function displayMain($mainBody="")
{
	$display = new BootstrapDisplay();
	$display->pageHead("Blog", "<link rel='stylesheet' href='/PHPFiles/Blog/include/BootstrapPHPCSS.css'>");

	$nav = new BootstrapNav($otherClasses="",$inverse=true);
	$aLinks = array(
		array("link"=>"/PHPFiles/Blog/HomePage.php","label"=>"Home"),
		array("link"=>"/PHPFiles/Blog/Blog.php","label"=>"Blog"),
		array("link"=>"/PHPFiles/Blog/CreatePost.php","label"=>"Create Post"),
	);
	$nav->addLinks($aLinks);
	$logInOut = $nav->getLogInOutButton(isset($_SESSION["user"]), "login.php", "logout.php"); 
	$signUp=$nav->getSignupButton("signup.php"); 
	$nav->addNavBarRight($logInOut . ' ' . $signUp);
	$body="<h1 class='heading'>Blog Posts</h1>\n";
	$display->nav($nav->getNav());
	$display->mainBody($body.$mainBody);
	echo $display->displayPage();
}

function displaySinglePost($title, $post, $date, $imageLink, $userID="")
{
	$classes = "col-sm-8 col-md-8 col-lg-8 col-sm-offset-2 col-md-offset-2 col-lg-offset-2";
	$html="\t\t<div class='post ".$classes."'>\n";
	$html.="\t\t\t<h2 class='title'><a href='".$userID."'>".$title."</a></h2>\n";
	$html.="\t\t\t<p class='posttext'>".$post."</p>\n";
		
	if($imageLink != null || $imageLink != "")
	{
		$html.="\t\t\t<img class='img' src='/PHPFiles/Blog/pictures/".$imageLink."'>\n";
	}
	$html.="\t\t\t<div class='spacer'></div>\n";
	$html.="\t\t\t<p class='date'>Date Created: ".$date."</p>\n";
	$html.="\t\t</div>\n";
	
	return $html;
}

if(!isset($_SERVER["PATH_INFO"]))
{
	displayMain(displayAllPosts());
}
else
{
	$sPath = $_SERVER["PATH_INFO"];
    $aPathElements = split("/",$sPath);
	$blogid = $aPathElements[1];
	
	
	$db=new DBFunctions();
	$db->doQuery("select * from posts where id=$blogid");
	$sqlResult = $db->fetchAssocResult();
	
	$html = "\t<div id='postlist'>\n";
	$html.=displaySinglePost($sqlResult[0]["title"], $sqlResult[0]["post"], $sqlResult[0]["postdate"], $sqlResult[0]["imagelink"], $sqlResult[0]["id"]);
	$html.="\t</div>\n";
	
	displayMain($html);
}
