<?php

/**
 * BootstrapDisplay 
 * Uses bootstap to make a page, which it then returns so it can be displayed
 */
class BootstrapDisplay {
    protected $sBody;
    
    
    /**
     * Constructor for BootstrapDisplay 
     * Takes in no params, just begins the html string
     */
    public function __construct() {
        $this->sBody="<!DOCTYPE html>\n<html>\n";
    }
    
    /**
     * Function: nav
     * Purpose: Makes the navigation for the page. For simplicity of this 
     *          assignment,since every page will have the same navigation, 
     *          pages are hardcoded in
     * @param STRING $nav A string containing html code for a navigation bar.
     *                      Use BootstrapNav->getNav(). 
     */
    public function nav($nav)
    {   
        $this->sBody.=$nav;
    }
    /**
     * Function: pageHead
     * Purpose: Makes the head section for the html page
     * @param STRING $otherLinks - can allow extra links for the html page
     *                              to use
     */
    public function pageHead($title, $otherLinks="")
    {
         $head =  "<head>\n";
		 $head .= "<meta name='viewport' content='width=device-width, "
                 . "initial-scale=1.0'>\n"
            . "<title>$title</title>\n"
            . "<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>\n"
            . "<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>\n"
            . "<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>\n"
            . "$otherLinks";

        $head .= "</head>\n";
        $this->sBody .= $head."<body>\n";

    }
    
    /**
     * Function: mainBody
     * Purpose: sets up the body of the html page
     * @param STRING $sMainBody - the body of the page
     */
    public function mainBody($sMainBody="")
    {
        $this->sBody .= "<div class='container'>\n"
                        .$sMainBody
                    . "</div>\n"
                . "</body>\n</html>\n";
    }
    
    /**
     * Function: displayPage
     * Purpose: Returns the page to be displayed. Need to use echo to display
     * @returns STRING sBody - The entire body for the boot strap page
     */
    public function displayPage()
    {
        return $this->sBody;
    }

}

