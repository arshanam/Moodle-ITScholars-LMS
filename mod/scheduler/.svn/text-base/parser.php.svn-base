<?php

/**
 *
 * Array 2 XML class
 * Convert an array or multi-dimentional array to XML
 *
 * @author Kevin Waterson
 * @copyright 2009 PHPRO.ORG
 *
 */
class array2xml extends DomDocument
{

    public $nodeName;

    private $xpath;

    private $root;

    private $node_name;


    /**
    * Constructor, duh
    *
    * Set up the DOM environment
    *
    * @param    string    $root        The name of the root node
    * @param    string    $nod_name    The name numeric keys are called
    *
    */
    public function __construct($root='root', $node_name='node')
    {
        parent::__construct();

        /*** set the encoding ***/
        $this->encoding = "ISO-8859-1";

        /*** format the output ***/
        $this->formatOutput = true;

        /*** set the node names ***/
        $this->node_name = $node_name;

        /*** create the root element ***/
        $this->root = $this->appendChild($this->createElement( $root ));

        $this->xpath = new DomXPath($this);
    }

    /*
    * creates the XML representation of the array
    *
    * @access    public
    * @param    array    $arr    The array to convert
    * @aparam    string    $node    The name given to child nodes when recursing
    *
    */
    public function createNode( $arr, $node = null)
    {
        if (is_null($node))
        {
            $node = $this->root;
        }
        foreach($arr as $element => $value) 
        {
            $element = is_numeric( $element ) ? $this->node_name : $element;

            $child = $this->createElement($element, (is_array($value) ? null : $value));
            $node->appendChild($child);

            if (is_array($value))
            {
                self::createNode($value, $child);
            }
        }
    }
    /*
    * Return the generated XML as a string
    *
    * @access    public
    * @return    string
    *
    */
    public function __toString()
    {
        return $this->saveXML();
    }

    /*
    * array2xml::query() - perform an XPath query on the XML representation of the array
    * @param str $query - query to perform
    * @return mixed
    */
    public function query($query)
    {
        return $this->xpath->evaluate($query);
    }

} // end of class


/**
 *
 * Array 2 json class
 * Convert an array or multi-dimentional array to a json object
 *
 *
 */

function array2json($arr) {
    if(function_exists('json_encode')) return json_encode($arr); //Lastest versions of PHP already has this functionality.
    $parts = array();
    $is_list = false;

    //Find out if the given array is a numerical array
    $keys = array_keys($arr);
    $max_length = count($arr)-1;
    if(($keys[0] == 0) and ($keys[$max_length] == $max_length)) {//See if the first key is 0 and last key is length - 1
        $is_list = true;
        for($i=0; $i<count($keys); $i++) { //See if each key correspondes to its position
            if($i != $keys[$i]) { //A key fails at position check.
                $is_list = false; //It is an associative array.
                break;
            }
        }
    }

    foreach($arr as $key=>$value) {
        if(is_array($value)) { //Custom handling for arrays
            if($is_list) $parts[] = array2json($value); /* :RECURSION: */
            else $parts[] = '"' . $key . '":' . array2json($value); /* :RECURSION: */
        } else {
            $str = '';
            if(!$is_list) $str = '"' . $key . '":';

            //Custom handling for multiple data types
            if(is_numeric($value)) $str .= $value; //Numbers
            elseif($value === false) $str .= 'false'; //The booleans
            elseif($value === true) $str .= 'true';
            else $str .= '"' . addslashes($value) . '"'; //All other things
            // :TODO: Is there any more datatype we should be in the lookout for? (Object?)

            $parts[] = $str;
        }
    }
    $json = implode(',',$parts);
    
    if($is_list) return '[' . $json . ']';//Return numerical JSON
    return '{' . $json . '}';//Return associative JSON
} 

?>
