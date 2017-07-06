<?php
session_start();


include_once("include/DBFunctions.php");
include_once("include/BootstrapDisplay.php");
include_once("include/BootstrapForm.php");
include_once("include/BootstrapNav.php");

/*
Function	checkImage
Purpose		Checks the image if its suitable for upload
Params		$file - reference to the file
			$fileName - the name to give to the file
Return		$flag - 1 represents good file
					2 represents error of file size larger than allowed
					3 represents error of the file extension/type not being supported
*/
function checkImage($file)
{
    $flag = 0;
	
    $name = $_FILES[$file]['name'];
    $type = $_FILES[$file]['type'];
    $size = $_FILES[$file]['size'];
    $max_size = 2097152;
    $extension = strtolower(substr($name, strpos($name,'.')+1));

	
    if( ($extension=='jpg' ||$extension=='png')&& 
                ($type == 'image/jpeg' || $type == 'image/png')
                && $size <= $max_size)
    {
        $flag = 1;
    }
    else 
    {
        if($size > $max_size)
        {
            $flag = 2;
        }
        else if( !(($extension=='jpg' ||$extension=='png')) && 
                !($type == 'image/jpeg' || $type == 'image/png'))
        {
            $flag = 3;
        }
    }
	
    return $flag;
}

function display($error="")
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

	$form = new BootstrapForm("create","","POST","enctype='multipart/form-data'");
	$form->addBasicFormControl("Title", "title", "text");
	$form->addTextArea("Post", "post");
	$form->addBasicFormControl("Image", "blogImg", "file");



	//$nav->addNavBarRight($items);
	$body="<h1 class='heading'>Create Post</h1>\n";
	$display->nav($nav->getNav());
	if($error!="")
    {
        $body .= $error."\n";
    }
	$body.=$form->getForm();
	$display->mainBody($body);
	echo $display->displayPage();
}





if(isset($_POST["title"]) && !empty($_POST["title"]))
{
	$db=new DBFunctions();
    $userid = $_SESSION['user'];
    $title=$db->sanitize("title"); 
    $post=$db->sanitize("post"); 
	$imageLink = "";
    
    $msgDisplay = "<p class='successMsg'>You have created an"
                    . " blog post</p>";
    
    $imgUploaded = 0;
	$now = strtotime("now");
    $date = date("o-d-m",$now);
	$imageLink=" ";
	
    if(is_uploaded_file($_FILES['blogImg']['tmp_name'])) 
    {
		//tries to upload image, will return 1 for success, 2 for invalid image size, 3 for invalid extension
		$imgUploaded = checkImage('blogImg');
        if($imgUploaded == 1)
        {
            $tmp_name = $_FILES['blogImg']['tmp_name'];
            $name = $_FILES['blogImg']['name'];
            $extension = strtolower(substr($name, strpos($name,'.')));
            $nameNoExt = substr($name, 0, strpos($name,'.'));
			$imageLink = $nameNoExt."-".$userid."-".$date.$extension;
			$fileLocation = "pictures/".$imageLink;
            
			
            move_uploaded_file($tmp_name, $fileLocation);
			
             
        }
        else if($imgUploaded == 2)
        {
            $msgDisplay = "<p class='errorMsg'>Please upload a smaller image</p>";

        }
        else if($imgUploaded == 3)
        {
            $msgDisplay = "<p class='errorMsg'>Please upload a .jpg or .png file</p>";

        }
        else if($imgUploaded == 0)
        {
            $msgDisplay = "<p class='errorMsg'>Something went wrong with file upload. Please try again</p>";
        }
		else
        {
            $msgDisplay = "<p class='errorMsg'>Something went wrong with file upload. Please try again</p>";
        }
    } 

	$sqlInsertItem = "insert into posts (title, post, userid, postdate, imageLink) "
			. "values ('$title', '$post', $userid, '$date', '$imageLink')";

	$db->doQuery($sqlInsertItem);
    display($msgDisplay);
}
else
{
	display();
}