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

        $this->_clearOriginalMessageCollection();

        $this->_rewriteMessageBlock();

        $this->_rewriteMessageCollection();

        $this->rewritten = true;
    }

    public function controllerFrontSendResponseBefore(Varien_Event_Observer $event)
    {
        // Do not process unless message block unless rewrite was successful
        if (!$this->rewritten) {
            return;
        }
        
        /** @var Mage_Core_Controller_Varien_Front $front */
        $front = $event->getFront();
        if (!$front instanceof Mage_Core_Controller_Varien_Front) {
            return;
        }
        
        Mage::helper('vinaikopp_jsmessages/response')->moveMessagesToJsMessagesCookie($front->getRequest());
    }

    public function controllerFrontSendResponseAfter(Varien_Event_Observer $event)
    {
        // Do not process unless message block unless rewrite was successful
        if (!$this->rewritten) {
            return;
        }
        
        $this->clearSessionMessageStorages();
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

    /**
     * Upon initial deployment ensure that old message collection
     * that was serialised to session isn't used.
     */
    private function _clearOriginalMessageCollection()
    {
        foreach($this->getSessionMessageStorages() as $sessionType) {
            $session = Mage::getSingleton($sessionType);
            $messages = $session->getData('messages');
            if (get_class($messages) == 'Mage_Core_Model_Message_Collection') {
                $session->unsetData('messages');
            }
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

    private function clearSessionMessageStorages()
    {
        foreach ($this->getSessionMessageStorages() as $classAlias) {
            /** @var Mage_Core_Model_Session_Abstract $session */
            if ($session = Mage::getSingleton($classAlias)) {
                $session->getMessages(true);
            }
        }
    }
}
