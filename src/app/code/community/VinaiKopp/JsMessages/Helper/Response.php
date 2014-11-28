<?php


class VinaiKopp_JsMessages_Helper_Response extends Mage_Core_Helper_Abstract
{
    public function moveMessagesToJsMessagesCookie(Mage_Core_Controller_Request_Http $request)
    {
        /** @var Mage_Core_Model_Cookie $cookie */
        $cookie = Mage::getSingleton('core/cookie');

        $data = $this->getMessageListFromExistingCookie($request, $cookie);
        $data = $this->mergeNewMessagesIntoList($data);
        $data = array_filter($data);

        if (count($data)) {
            $cookie->set(VinaiKopp_JsMessages_Helper_Data::COOKIE_MESSAGES, rawurlencode(Zend_Json::encode($data)), 0, null, null, null, false);
        }
    }

    /**
     * @param Mage_Core_Controller_Request_Http $request
     * @param Mage_Core_Model_Cookie $cookie
     * @return array
     */
    private function getMessageListFromExistingCookie(
        Mage_Core_Controller_Request_Http $request, Mage_Core_Model_Cookie $cookie
    )
    {
        $messagesList = array();
        $existing = $request->getCookie(VinaiKopp_JsMessages_Helper_Data::COOKIE_MESSAGES);
        if ($this->shouldDeleteExistingCookie($existing)) {
            $cookie->delete(VinaiKopp_JsMessages_Helper_Data::COOKIE_MESSAGES);
        } elseif (!empty($existing)) {
            $messagesList = $this->loadExistingMessagesAndAddToList($existing, $messagesList);
        }
        return $messagesList;
    }

    /**
     * @param string $existing
     * @return bool
     */
    private function shouldDeleteExistingCookie($existing)
    {
        return $existing === '-' || $existing === 'deleted';
    }

    /**
     * @param string $existing
     * @param array $messagesList
     * @return array
     */
    private function loadExistingMessagesAndAddToList($existing, array $messagesList)
    {
        try {
            $existing = Zend_Json::decode(rawurldecode($existing));
            if (is_array($existing)) {
                foreach ($this->getMessageTypes() as $type) {
                    if (isset($existing[$type]) && is_array($existing[$type])) {
                        $messagesList[$type] = $existing[$type];
                    }
                }
            }
        } catch (Exception $e) {
            // NOOP
        }
        return $messagesList;
    }

    private function getMessageTypes()
    {
        return array(
            Mage_Core_Model_Message::ERROR,
            Mage_Core_Model_Message::WARNING,
            Mage_Core_Model_Message::NOTICE,
            Mage_Core_Model_Message::SUCCESS
        );
    }

    private function mergeNewMessagesIntoList($data)
    {
        /** @var VinaiKopp_JsMessages_Model_MessageStorage $sharedMessageStorage */
        $sharedMessageStorage = Mage::getSingleton('vinaikopp_jsmessages/messageStorage');
        foreach ($this->getMessageTypes() as $type) {
            foreach ($sharedMessageStorage->getAllMessagesByType($type) as $message) {
                /** @var Mage_Core_Model_Message_Abstract $message */
                $data[$type][] = $message->getText();
            }
        }
        return $data;
    }
} 
