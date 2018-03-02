<?php

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

?>