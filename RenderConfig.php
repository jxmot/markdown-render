<?php

class RenderConfig {

    private $git;
    public $reporaw;
    public $repogit;
    public $repoapi;
    public $accheader = array();

    private $pathsep  = "/";

    private $cfg;
    public $mdfile;
    public $owner;
    public $repo;
    public $branch;
    public $pagetitle;

    public $metakeyw;
    public $metadesc;

    public $genstatic;
    public $statname;

    public $oghead;
    public $ogjson;

    function __construct($cfgfile)
    {
        $this->git = json_decode(file_get_contents("./github.json"));
        $this->reporaw   = $this->git->reporaw;
        $this->repogit   = $this->git->repogit;
        $this->repoapi   = $this->git->repoapi;
        $this->accheader = $this->git->accheader;

        if(!file_exists($cfgfile)) $cfgfile = "./test.json";
        $this->cfg = json_decode(file_get_contents($cfgfile));

        if(isset($this->cfg->mdfilerem) && ($this->cfg->mdfilerem === true)) {
            // create the path to the file that we will render
            $this->mdfile = $this->git->reporaw . $this->cfg->owner . $this->pathsep . $this->cfg->repo . $this->pathsep . $this->cfg->branch . $this->pathsep . $this->cfg->mdfile;
        } else {
            $this->mdfile = $this->cfg->mdfile;
        }

        // reduce the depth at which clients will need to
        // reach into this object
        $this->pagetitle = $this->cfg->pagetitle;
        $this->owner     = $this->cfg->owner;
        $this->repo      = $this->cfg->repo;
        $this->branch    = $this->cfg->branch;

        // optional meta tags, it's not necessary to 
        // include them in the JSON file if not used.
        $this->metaauth  = (isset($this->cfg->metaauth) ? $this->cfg->metaauth : "");
        //
        // To Do : get this info from the owner's 
        // repository via the GitHub API.
        $this->metadesc  = (isset($this->cfg->metadesc) ? $this->cfg->metadesc : "");
        $this->metakeyw  = (isset($this->cfg->metakeyw) ? $this->cfg->metakeyw : "");

        // finish the meta tags...
        $this->configMeta();

        // generate a static file?
        $this->genstatic = $this->cfg->genstatic;
        $this->statname  = ($this->genstatic === true ? $this->cfg->statname : "");

        $this->oghead = $this->cfg->oghead;
        $this->ogjson = ($this->oghead === true ? $this->cfg->ogjson : "");
    }

    private function configMeta()
    {
        $gitresp = null;
        $topics = "";

        if(($this->cfg->gitdesc === true) || ($this->cfg->gittopics === true)) {
            // put together the base URL, 
            $url = $this->git->repoapi . "repos/" . $this->cfg->owner . $this->pathsep . $this->cfg->repo;
        }

        if($this->cfg->gitdesc === true) {
            $this->metadesc = "Document - ";
            if($this->cfg->gittopics === true) {
                // retrieve repo info w/ topics
                $gitresp = $this->getFromGit($this->git->accheader[1], $url);
 
                foreach($gitresp->topics as $topic) {
                    $topics = $topics . $topic . ",";
                }
                $this->metakeyw = $this->metakeyw . trim($topics,",");
            } else {
                // retrieve repo info w/o topics
                $gitresp = $this->getFromGit($this->git->accheader[0], $url);
            }
            $this->metadesc = $this->metadesc . $gitresp->description;
        } else {
            if($this->cfg->gittopics === true) {
                // retrieve repo topics only
                $url = $url . $this->pathsep . "topics";
                $gitresp = $this->getFromGit($this->git->accheader[1], $url);

                foreach($gitresp->names as $topic) {
                    $topics = $topics . $topic . ",";
                }
                $this->metakeyw = $this->metakeyw . trim($topics,",");
            }
        }
    }

    private function getFromGit($accept, $url)
    {
        $opts = array(
            'http' => array(
                'method' => 'GET',
                'header' => "Accept: $accept\r\n" .
                "user-agent: custom\r\n" .
                "Content-Type: application/json; charset=utf-8\r\n" .
                "Content-Encoding: text\r\n"
            )
        );

        $context = stream_context_create($opts);
        $resp = json_decode(file_get_contents($url, true, $context));
        // uncomment and examine for debugging purposes
        //$resphead = $http_response_header;
        return $resp;
    }
}

?>