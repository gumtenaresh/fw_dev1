<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * Ex: $errorsObj = new Errors($xmlFilePath)
 */

/**
 * Description of Errors
 * DOMElement->getElementsByTagName() -- Gets elements by tagname
 * nodeValue : The value of this node, depending on its type.
 * Load XML File. You can use loadXML if you wish to load XML data from a string
 * @author satyam
 * @return value
 */
class Errors {

    private $xmlFilePath = null;
    private $objDOM = null;

    /**
     * Creates dom object and loads file
     * @param <string> $filePath
     */
    function __construct($xmlFilePath) {

        $this->xmlFilePath = $xmlFilePath;
        $this->objDOM = new DOMDocument();
        $this->objDOM->load($this->xmlFilePath);
    }

    /**
     * Gives node value
     * @param <string> $errorName
     * @return <string> value
     */
    function getErrorMessage($errorName) {
        $child = $this->objDOM->getElementsByTagName($errorName);
        return $child->item(0)->nodeValue;
    }

    function __destruct() {

        unset($this->xmlFilePath);
        unset($this->objDOM);
        unset($this->objDOM);
    }

}

?>
