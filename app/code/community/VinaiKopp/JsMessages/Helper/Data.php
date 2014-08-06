<?php

class VinaiKopp_JsMessages_Helper_Data extends Mage_Core_Helper_Abstract
{
    const COOKIE_MESSAGES = 'jsmessages';

    public function getInitJson()
    {
        return Zend_Json::encode(
            array(
                'domain' => Mage::getSingleton('core/cookie')->getDomain(),
                'cookie' => self::COOKIE_MESSAGES
            )
        );
    }
}
