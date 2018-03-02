<?php
require_once "Parsedown.php";

class PageConfig {

    private $cfg;
    private $pathsep  = "/";

    public $mdfile;

    public $repohome;
    public $owner;
    public $repo;
    public $branch;
    public $pagetitle;

    function __construct($cfgfile)
    {
        if(!file_exists($cfgfile)) $cfgfile = "./default.json";
        $this->cfg = json_decode(file_get_contents($cfgfile));

        // create the path to the file that we will render
        $this->mdfile = $this->cfg->repohome . $this->cfg->owner . $this->pathsep . $this->cfg->repo . $this->pathsep . $this->cfg->branch . $this->pathsep . $this->cfg->mdfile;

        // reduce the depth at which clients will need to
        // reach into this object
        $this->pagetitle = $this->cfg->pagetitle;
        $this->repohome  = $this->cfg->repohome;
        $this->owner     = $this->cfg->owner;
        $this->repo      = $this->cfg->repo;
        $this->branch    = $this->cfg->branch;
    }
}

/*
    
*/
class ModifyElements {

    private $pattern  = '/(\\w+)\s*=\\s*("[^"]*"|\'[^\']*\'|[^"\'\\s>]*)/';
    private $attrs    = array();
    private $pathsep  = "/";

    private $repohome;
    private $owner;
    private $repo;
    private $branch;

    function __construct($mdcfg)
    {
        $this->repohome  = $mdcfg->repohome;
        $this->owner     = $mdcfg->owner;
        $this->repo      = $mdcfg->repo;
        $this->branch    = $mdcfg->branch;
    }

    public function imgModify(array $imgBlock) 
    {
        // verify the block name in case the caller didn't
        if((strtolower($imgBlock['name']) === 'img') &&(isset($imgBlock['markup']))) {
            if($this->getAttributes($imgBlock['markup']) !== false) {
                // rebuild the "src" attribute
                $src = $this->repohome . $this->owner . $this->pathsep . $this->repo . $this->pathsep . $this->branch . $this->pathsep;
                // remove leading "./" from $this->attrs['src']
                $src = str_replace("./", "", ($src . $this->attrs['src']));
                // rebuild the markup
                $this->attrs['src'] = $src;
                $imgBlock['markup'] = $this->rebuildElement($imgBlock['name']);
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

class ParsedownModifyVoid extends Parsedown {

    private $pdModTagObj;

    function __construct($modtagobj)
    {
        $this->pdModTagObj = $modtagobj;
    }

    protected function modifyVoid(array $block) 
    {
        $Block = array();

        if(isset($block['name']) && isset($block['markup'])) {
            if(isset($this->pdModTagObj)) {
                $Block = $this->pdModTagObj->{strtolower($block['name']).'Modify'}($block);
            } else $Block = $block;
        } else $Block = $block;

        // Return result using modified values and allow the
        // parent to complete any necessary additional steps.
        return parent::modifyVoid($Block);
    }
}

$pageconfig = new PageConfig("./ESP8266-RCWL0516.json");
$ElementModifier = new ModifyElements($pageconfig);
$ModifyParsedown = new ParsedownModifyVoid($ElementModifier);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageconfig->pagetitle; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.7/cyborg/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,500,700">

    <link rel="stylesheet" href="./assets/css/document.css">
    <link rel="stylesheet" href="./assets/css/totop.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<!--
<body class="page-nocopy">
-->
<body>
    <!-- Document -->
    <div class="row doc-body">
        <div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1">
<?php
$file = file_get_contents($pageconfig->mdfile, true);
echo $ModifyParsedown->text($file);
?>
        </div>
        <button id="gototop" class="gototop" onclick="jumpToTop()" title="Go to top of page">&#9650;<br>top</button> 
        <br>
        <br>
    </div>
    <!-- ^Document -->
    <!-- Page Footer -->
    <footer class="navbar navbar-default navbar-fixed-bottom">
        <div class="container text-center">
            <p class="navbar-text col-lg-12 col-md-12 col-sm-12 col-xs-12">
                2017 &copy; James Motyl
            </p>
        </div>
    </footer>
    <!-- ^Page Footer -->
</body>
<script src="./assets/js/totop.js"></script>
</html>
