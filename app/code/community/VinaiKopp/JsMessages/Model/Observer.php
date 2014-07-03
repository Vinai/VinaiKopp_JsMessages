<?php


class VinaiKopp_JsMessages_Model_Observer
{
    public function controllerActionPredispatch(Varien_Event_Observer $event)
    {
        // Frontend only block rewrite
        if (! Mage::app()->getStore()->isAdmin()) {
            Mage::getConfig()->setNode('global/blocks/core/rewrite/messages', 'VinaiKopp_JsMessages_Block_Core_Messages');
        }
    }
}