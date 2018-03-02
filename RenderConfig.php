<?php

class RenderConfig {

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

        // optional meta tags, it's not necessary to 
        // include them in the JSON file if not used.
        //
        // To Do : get this info from the owner's 
        // repository.
        $this->metadesc  = (isset($this->cfg->metadesc) ? $this->cfg->metadesc : "");
        $this->metakeyw  = (isset($this->cfg->metakeyw) ? $this->cfg->metakeyw : "");
        $this->metaauth  = (isset($this->cfg->metaauth) ? $this->cfg->metaauth : "");
    }
}

?>