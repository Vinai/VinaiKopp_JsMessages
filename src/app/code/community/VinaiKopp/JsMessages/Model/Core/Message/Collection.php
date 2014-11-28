<?php


class VinaiKopp_JsMessages_Model_Core_Message_Collection
    extends Mage_Core_Model_Message_Collection
{
    /**
     * @return VinaiKopp_JsMessages_Model_MessageStorage
     */
    private function getSharedMessageStorage()
    {
        return Mage::getSingleton('vinaikopp_jsmessages/messageStorage');
    }

    /**
     * @return string
     */
    private function getMySectionId()
    {
        return spl_object_hash($this);
    }

    public function addMessage(Mage_Core_Model_Message_Abstract $message)
    {
        $this->getSharedMessageStorage()->add(
            $this->getMySectionId(), $message
        );
        return parent::addMessage($message);
    }

    public function clear()
    {
        foreach ($this->_messages as $type => $messages) {
            foreach ($messages as $id => $message) {
                if (!$message->getIsSticky()) {
                    $this->getSharedMessageStorage()->removeMessage(
                        $this->getMySectionId(), $message
                    );
                }
            }
        }
        return parent::clear();
    }

    public function deleteMessageByIdentifier($identifier)
    {
        $this->getSharedMessageStorage()->removeMessageByIdentifier(
            $this->getMySectionId(), $identifier
        );
        parent::deleteMessageByIdentifier($identifier);
    }

    
} 
