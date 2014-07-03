<?php


class VinaiKopp_JsMessages_Helper_Data
    extends Mage_Core_Helper_Abstract
{
    public function getInitJson()
    {
        return json_encode(array(
            'domain' => Mage::getSingleton('core/cookie')->getDomain()
        ));
    }
} 