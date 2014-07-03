<?php


class VinaiKopp_JsMessages_Model_Core_Message
    extends Mage_Core_Model_Message
{
    const COOKIE_MESSAGES = 'jsmessages';
    
    protected function _factory($code, $type, $class = '', $method = '')
    {
        if (! Mage::app()->getStore()->isAdmin()) {
            $this->_addMessageToCookie($type, $code);
        }
        return parent::_factory($code, $type, $class, $method);
    }

    protected function _addMessageToCookie($type, $message)
    {
        $messages = $this->_getCookieMessages();
        $messages[$type][] = rawurlencode($message);
        $this->_setCookieMessages($messages);
    }
    
    protected function _getCookieMessages()
    {
        $messages = array();
        
        if ($json = Mage::getSingleton('core/cookie')->get(self::COOKIE_MESSAGES)) {
            try {
                $messages = (array) json_decode($messages);
            } catch (Exception $e) {}
        }
        return $messages;
    }

    protected function _setCookieMessages($messages)
    {
        $json = json_encode($messages);
        Mage::getSingleton('core/cookie')->delete(self::COOKIE_MESSAGES);
        Mage::getSingleton('core/cookie')->set(self::COOKIE_MESSAGES, $json, 0);
    }
}