<?php
class mySpecialFunction
{
    public function check($variant, $parent, $mode=false, $redirect=false)
    {
        // Redirect to this variant if variant-title is "123Test"
        if($variant->oxarticles__oxtitle == "123Test"){
            return true;
        }

        // Otherwise set inherited redirect
        return $redirect;
    }
}