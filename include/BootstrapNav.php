<?php


/**
 * Class BootstrapNav
 * This class is for making a Bootstrap nav.
 * 
 * All functions indicating a return, except for getNav, return an html string to be used for the navigation bar.
 * All functions with no return type, are added to the class variable $nav. 
 * To get the final navigation bar use getNav()
 */
class BootstrapNav
{
    //The navigation bar will be built as a string using this class variable
    protected $nav;
    
    /**
     * Function: constructor
     * @param STRING $otherClasses - An optional value, blank if left defaulted.
     *                              This param is for any additional classes 
     *                              you may want to add the navigation bar
     * @param BOOLEAN $inverse - An optional param. 
     *      If true, will make navigation bar with the class navbar-inverse. 
     *      If false(default) will use navbar-default for class
     */
    public function __construct($otherClasses="",$inverse=false) {
        if(!$inverse)
        {
            $this->nav="<nav class='navbar navbar-default $otherClasses'>\n";
        }
        else
        {
            $this->nav="<nav class='navbar navbar-inverse $otherClasses'>\n";
        }
        
        $this->nav.="<div class='container-fluid'>\n"
                . "<div class='collapse navbar-collapse' id='myNavbar'>\n";
        
    }
    /**
     * Function: getDropDownMenu
     * Purpose: This returns a string that contains the html for a Bootstrap
     *          drop down style menu
     * @param STRING $menuLabel - The label of the link to display
     * @param STRING[] $aLinks - An associative array of links containing labels and links
     *                       Format: array( array("Label"=>"LABEL", "Link"=>"LINK") )
     * @return STRING $sDropdownMenu - A string containing html for a 
     *                                 Bootstrap drop down menu
     */
    public function getDropDownMenu($menuLabel,$aLinks)
    {
        $sDropdownMenu="<li class='dropdown'><a class='dropdown-toggle' "
                            . "data-toggle='dropdown' href='#'>$menuLabel"
                            . "<span class='caret'></span></a>\n".
                        "<ul class='dropdown-menu'>\n".
                            $this->getLinks($aLinks). "". 
                        "</ul>\n".
                    "</li>\n";
        return $sDropdownMenu;
    }
    
    /**
     * Function: addLinks
     * PurposeL Adds links to the navigation bar
     * @param STRING[] $aLinks - An associative array of links containing labels and links
     *                       Format: array( array("Label"=>"LABEL", "Link"=>"LINK") )
     * @param STRING $dropDownMenu - A string containing html for a 
     *                              Bootstrap drop down menu. 
     *                              Use getDropDownMenu() function above
     */
    public function addLinks($aLinks, $dropDownMenu="")
    {
        $links = $this->getLinks($aLinks);
        
        $this->nav.="<ul class='nav navbar-nav'>\n".
                        $links."".
                        $dropDownMenu."".
                    "</ul>\n";
    }
    /**
     * Function: getLinks
     * Purpose: This is a private function which handles taking an associative 
     *          array of links and putting them into an html string
     * @param STRING[] $aLinks - An associative array of links containing labels and links
     *                       Format: array( array("Label"=>"LABEL", "Link"=>"LINK") )
     * @return STRING $sLinks - A string containing html for a single link
     */
    private function getLinks($aLinks)
    {
        $sLinks = "";
        foreach ($aLinks as $link) {
            $sLinks.="<li><a href='".$link["link"]."'>".$link["label"]."</a></li>\n";
        }

        return $sLinks;
    }
    
    /**
     * Function: addHeader
     * Purpose: Adds a header to the navigation bar
     * @param STRING $homeLabel - The label of the home page
     * @param STRING $homeLink - The link to the homepage
     */
    public function addHeader($homeLabel, $homeLink="#")
    {
        $this->nav.="<div class='navbar-header'>\n".
                "<button type='button' class='navbar-toggle' data-toggle="
                        . "'collapse' data-target='#myNavbar'>\n".
                  "<span class='icon-bar'></span>\n".
                  "<span class='icon-bar'></span>\n".
                  "<span class='icon-bar'></span>\n".
                "</button>\n".
                "<a class='navbar-brand' href='$homeLink'>$homeLabel"
                  . "</a>\n".
             "</div>\n";
    }
    
    /**
     * Function: addNavBarRight
     * Purpose: Adds a right adjusted menu to the naviagation bar
     * @param STRING $items - Any items you would like on the right side of the menu.
     *                      Example: getSerchBar, getSignupButton, getLogInOutButton
     */
    public function addNavBarRight($items)
    {
        $this->nav.="<div class='collapse navbar-collapse' id='myNavbar'>\n".
                
                "<ul class='nav navbar-nav navbar-right'>\n"
                    . "$items".
                 "</ul>\n".
              "</div>\n";
    }
    
    /**
     * Function: getSearchBar
     * Purpose: Returns a string of html for a Bootstrap search bar  
     *          to be used elsewhere in the class
     * @param STRING $leftRight - pass in either left or right for adjustment
     * @return STRING sSearchBar - An html string containing code for a Bootstrap searchbar
     */
    public function getSearchBar($leftRight)
    {
        $sSearchBar="<form class='navbar-form navbar-$leftRight'>
                        <div class='input-group'>
                          <input type='text' id='search' name='search' 
                                class='form-control' placeholder='Search'>
                          <div class='input-group-btn'>
                            <button class='btn btn-default' type='submit'>
                              <i class='glyphicon glyphicon-search'></i>
                            </button>
                          </div>
                        </div>
                      </form>";
        return $sSearchBar;
    }
    
    /**
     * Function: getSignUpButton
     * Purpose: To get an html string containing code for a Bootstrap 
     *          sign up button to be used elsewhere in the class
     * @param STRING $signUpLink - The link to the signup page
     * @param STRING $signUpLabel - An Optional Parameter which is the label 
     *                              to be displayed. Default value is Sign Up
     * @return STRING sSignUp - An html string containing code for a Bootstrap 
     *                          sign up button with glyphicon-user
     */
    public function getSignupButton($signUpLink="#", $signUpLabel="Sign Up")
    {
        $sSignUp = "<li><a href='$signUpLink'><span class='glyphicon "
                  . "glyphicon-user'></span> $signUpLabel</a></li>\n";
        
        return $sSignUp;
    }
    
    /**
     * Function: getLogInOutButton
     * Purpose: To get an html string containing code for a Bootstrap 
     *          log in/out button. The log in/out symbol is based on whether 
     *          true or false is passed into the function. 
     *          This is to be used elsewhere in the class
     * @param BOOLEAN $isLoggedIn - A boolean value. If true will display the log 
     *                          in glyphicon, false the logout glyphicon 
     * @param STRING $loginLink - The link to the login page
     * @param STRING $logoutLink - The link to logout
     * @return STRING sSignUp - An html string containing code for a Bootstrap 
     *                          sign up button with glyphicon-user
     */
    public function getLogInOutButton($isLoggedIn, $loginLink="#", $logoutLink="#")
    {
        $loginButton = "";
        if($isLoggedIn)
        {
            $loginButton="<li><a href='$logoutLink'><span "
                    . "class='glyphicon glyphicon-log-in'>"
                    . "</span> Logout</a></li>\n";
        }
        else 
        {
            $loginButton="<li><a href='$loginLink'><span "
                    . "class='glyphicon glyphicon-log-in'>"
                    . "</span> Login</a></li>\n";
        }
        return $loginButton;
    }
    
    /**
     * Function: getNav()
     * @return STRING $nav - The final html for the navigation bar. 
     *                       This needs to be the last function you call for this class
     */
    public function getNav()
    {
        //ends collapse navbar, then ends container, then ends nav
        $this->nav.="</div></div>\n</nav>\n";
        return $this->nav;
    }
}