<?php

class ParsedownModify extends Parsedown {

    private $pdModTagObj;

    function __construct($modtagobj)
    {
        $this->pdModTagObj = $modtagobj;
    }

    protected function modifyVoid(array $block) 
    {
        $Block = array();

        if(isset($block['name']) && isset($block['markup'])) {
            if(isset($this->pdModTagObj) && in_array($block['name'], $this->pdModTagObj->validtags)) {
                $Block = $this->pdModTagObj->{strtolower($block['name']).'Modify'}($block);
            } else $Block = $block;
        } else $Block = $block;

        // Return result using modified values and allow the
        // parent to complete any necessary additional steps.
        return parent::modifyVoid($Block);
    }

    protected function modifyInline(array $inline)
    {
        $Inline = array();

        if($inline['element']['name'] === 'img') {
            if(isset($this->pdModTagObj)) {
                $Inline = $this->pdModTagObj->imgModify($inline);
            } else $Inline = $inline;
        } else {
            if($inline['element']['name'] === 'a') {
                $Inline = $this->pdModTagObj->linkModify($inline);
            } else $Inline = $inline;
        }
        // Return result using modified values and allow the
        // parent to complete any necessary additional steps.
        return parent::modifyInline($Inline);
    }
}

?>