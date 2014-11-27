<?php


/**
 * Class VinaiKopp_JsMessages_Block_Core_Messages
 */
class VinaiKopp_JsMessages_Block_Core_Messages extends Mage_Core_Block_Messages
{
    private static $_isRendered = false;

    public function _prepareLayout()
    {
        $this->setTemplate('vinaikopp/jsmessages/messages.phtml');
        return Mage_Core_Block_Template::_prepareLayout();
    }

    public function getGroupedHtml()
    {
        // Avoid rendering global_messages AND messages - with JsMessages only one is needed
        if (self::$_isRendered) {
            return '';
        }
        self::$_isRendered = true;

        return Mage_Core_Block_Template::_toHtml();
    }

    /**
     * Add messages to display
     *
     * @param Mage_Core_Model_Message_Collection $messages
     *
     * @return Mage_Core_Block_Messages
     */
    public function addMessages(Mage_Core_Model_Message_Collection $messages)
    {
        /** @var Mage_Core_Model_Session $session */
        $session = Mage::getSingleton('core/session');
        foreach ($messages->getItems() as $message) {
            $session->addMessage($message);
        }
        return $this;
    }

    /**
     * Adding new message to message collection
     *
     * @param   Mage_Core_Model_Message_Abstract $message
     *
     * @return  Mage_Core_Block_Messages
     */
    public function addMessage(Mage_Core_Model_Message_Abstract $message)
    {
        /** @var Mage_Core_Model_Session $session */
        $session = Mage::getSingleton('core/session');
        $session->addMessage($message);

        return $this;
    }
}
