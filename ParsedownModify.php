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
        if(isset($this->pdModTagObj)) {
            // decide if this is something we want to modify...
            if(isset($block['name']) && isset($block['markup'])) {
                if(in_array(strtolower($block['name']), $this->pdModTagObj->validvoids)) {
                    $Block = $this->pdModTagObj->{strtolower($block['name']).'Modify'}($block);
                } else $Block = $block;
            } else $Block = $block;
        } else $Block = $block;
        // Return result using modified values and allow the
        // parent to complete any necessary additional steps.
        return parent::modifyVoid($Block);
    }

    protected function modifyInline(array $inline)
    {
        $Inline = array();
        if(isset($this->pdModTagObj)) {
            // decide if this is something we want to modify...
            if(strtolower($inline['element']['name']) === 'img') {
                if(!isset($inline['element']['attributes']['target'])) {
                    $Inline = $this->pdModTagObj->imgModify($inline);
                } else {
                    if(isset($inline['element']['attributes']['data-orig'])) {
                        $inline['element']['attributes']['src'] = $inline['element']['attributes']['data-orig'];
                        unset($inline['element']['attributes']['target']);
                        unset($inline['element']['attributes']['data-orig']);
                        if(isset($inline['element']['attributes']['data-text'])) {
                            $inline['element']['attributes']['title'] = $inline['element']['attributes']['data-text'];
                            unset($inline['element']['attributes']['data-text']);
                        }
                        $Inline = $this->pdModTagObj->imgModify($inline);
                    }
                }
            } else {
                if(strtolower($inline['element']['name']) === 'a') {
                    $Inline = $this->pdModTagObj->linkModify($inline);
                } else $Inline = $inline;
            }
        } else $Inline = $inline;
        // Return result using modified values and allow the
        // parent to complete any necessary additional steps.
        return parent::modifyInline($Inline);
    }
}

?>