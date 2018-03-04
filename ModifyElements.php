<?php

/*
    
*/
class ModifyElements {

    private $pattern  = '/(\\w+)\s*=\\s*("[^"]*"|\'[^\']*\'|[^"\'\\s>]*)/';
    private $attrs    = array();
    private $pathsep  = "/";

    public $validtags = array('img');

    private $reporaw;
    private $owner;
    private $repo;
    private $branch;

    function __construct($mdcfg)
    {
        $this->reporaw  = $mdcfg->reporaw;
        $this->owner     = $mdcfg->owner;
        $this->repo      = $mdcfg->repo;
        $this->branch    = $mdcfg->branch;
    }

    public function imgModify(array $imgBlock) 
    {
        // one of two types were passed to us, an element or
        // an inline.
        if(isset($imgBlock['name'])) {
            // verify the block name in case the caller didn't
            if((strtolower($imgBlock['name']) === 'img') &&(isset($imgBlock['markup']))) {
                if($this->getAttributes($imgBlock['markup']) !== false) {
                    // rebuild the "src" attribute
                    $src = $this->reporaw . $this->owner . $this->pathsep . $this->repo . $this->pathsep . $this->branch . $this->pathsep;
                    // remove leading "./" from $this->attrs['src']
                    $src = str_replace("./", "", ($src . $this->attrs['src']));
                    // rebuild the markup
                    $this->attrs['src'] = $src;
                    $imgBlock['markup'] = $this->rebuildElement($imgBlock['name']);
                }
            }
        } else {
            if(isset($imgBlock['element'])) {
                $src = $this->reporaw . $this->owner . $this->pathsep . $this->repo . $this->pathsep . $this->branch . $this->pathsep;
                // remove leading "./" from $this->attrs['src']
                $src = str_replace("./", "", ($src . $imgBlock['element']['attributes']['src']));
                $imgBlock['element']['attributes']['src'] = $src;
                $imgBlock['element']['attributes']['alt'] = "Image, absolute path";
            }
        }
        return $imgBlock;
    }

    /*
        Add more ????Modify() functions as needed.
    */

    /* **********************************************************************
        getAttributes() - Get the attributes of a specified element and save
        them in this class's array named $attrs.

        Returns - 
            > 0 : Attributes were found
            false : None were found
    */
    protected function getAttributes($element)
    {
        $matches = array();

        $ret = preg_match_all($this->pattern, $element, $matches, PREG_SET_ORDER);

        if(($ret > 0) && ($ret !== false)) {
            foreach($matches as $match) {
    
                if (($match[2][0] == '"' || $match[2][0] == "'") && $match[2][0] == $match[2][strlen($match[2])-1]) {
                    $match[2] = substr($match[2], 1, -1);
                }
            
                $name = strtolower($match[1]);
                $value = html_entity_decode($match[2]);
                $this->attrs[$name] = $value;
    
                //$this->attrs[strtolower($match[1])] = html_entity_decode($match[2]);
            }
        } else $ret = false;

        return $ret;
    }

    /* **********************************************************************
        rebuildElement() - Rebuilds the element with the rr.

        Returns - The HTML element rebuilt from the tag and
        its attributes.

    */
    protected function rebuildElement($tagname)
    {
        $markup = "<$tagname ";
        foreach($this->attrs as $key => $value)
        {
            $markup = $markup . "$key=\"$value\" ";
        }
        $markup = $markup . ">";
        return $markup;
    }
}

?>