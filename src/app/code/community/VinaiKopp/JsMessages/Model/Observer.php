<?php


class VinaiKopp_JsMessages_Model_Observer
{
    protected $rewritten = false;

    public function controllerActionPredispatch(Varien_Event_Observer $event)
    {
        // Frontend only block rewrite
        if (Mage::app()->getStore()->isAdmin()) {
            return;
        }

        $this->_rewriteMessageBlock();

        $this->_rewriteMessageCollection();

        $this->rewritten = true;
    }

    public function controllerFrontSendResponseBefore(Varien_Event_Observer $event)
    {
        // Do not process unless message block rewrite was successful
        if (!$this->rewritten) {
            return;
        }

        /** @var Mage_Core_Controller_Varien_Front $front */
        $front = $event->getFront();
        if (!$front instanceof Mage_Core_Controller_Varien_Front) {
            return;
        }

        /** @var Mage_Core_Model_Cookie $cookie */
        $cookie = Mage::getSingleton('core/cookie');
        
        /** @var VinaiKopp_JsMessages_Model_MessageStorage $sharedMessageStorage */
        $sharedMessageStorage = Mage::getSingleton('vinaikopp_jsmessages/messageStorage');
        
        $types = array(
            Mage_Core_Model_Message::ERROR,
            Mage_Core_Model_Message::WARNING,
            Mage_Core_Model_Message::NOTICE,
            Mage_Core_Model_Message::SUCCESS
        );

        $data = array();
        $deleteCookie = false;

        $existing = $front->getRequest()->getCookie(VinaiKopp_JsMessages_Helper_Data::COOKIE_MESSAGES);
        if ($existing === '-' || $existing === 'deleted') {
            $deleteCookie = true;
        } elseif (!empty($existing)) {
            try {
                $existing = Zend_Json::decode(rawurldecode($existing));
                if (is_array($existing)) {
                    foreach ($types as $type) {
                        if (isset($existing[$type]) && is_array($existing[$type])) {
                            $data[$type] = $existing[$type];
                        }
                    }
                }
            } catch (Exception $e) {
                // NOOP
            }
        }

        foreach ($types as $type) {
            foreach ($sharedMessageStorage->getAllMessagesByType($type) as $message) {
                /** @var Mage_Core_Model_Message_Abstract $message */
                $data[$type][] = $message->getText();
            }
        }

        $data = array_filter($data);

        if (count($data)) {
            $cookie->set(VinaiKopp_JsMessages_Helper_Data::COOKIE_MESSAGES, rawurlencode(Zend_Json::encode($data)), 0, null, null, null, false);
        } elseif ($deleteCookie) {
            $cookie->delete(VinaiKopp_JsMessages_Helper_Data::COOKIE_MESSAGES);
        }
    }

    public function controllerFrontSendResponseAfter(Varien_Event_Observer $event)
    {
        foreach ($this->getSessionMessageStorages() as $classAlias) {
            /** @var Mage_Core_Model_Session_Abstract $session */
            if ($session = Mage::getSingleton($classAlias)) {
                $session->getMessages(true);
            }
        }
    }

    private function _rewriteMessageBlock()
    {
        $blockClassName = Mage::getConfig()->getBlockClassName('vinaikopp_jsmessages/core_messages');
        if ($blockClassName) {
            Mage::getConfig()->setNode('global/blocks/core/rewrite/messages', $blockClassName);
        }
    }

    private function _rewriteMessageCollection()
    {
        $collectionClassName = Mage::getConfig()->getModelClassName('vinaikopp_jsmessages/core_message_collection');
        if ($collectionClassName) {
            Mage::getConfig()->setNode('global/models/core/rewrite/message_collection', $collectionClassName);
        }
    }

    private function getSessionMessageStorages()
    {
        return array(
            'catalog/session',
            'catalogsearch/session',
            'checkout/session',
            'core/session',
            'customer/session',
            'review/session',
            'tag/session',
            'wishlist/session',
        );
    }
}
