<?php


class VinaiKopp_JsMessages_Model_MessageStorage
{
    private $messages = array();

    /**
     * @param string $section
     * @param Mage_Core_Model_Message_Abstract $message
     */
    public function add($section, Mage_Core_Model_Message_Abstract $message)
    {
        $type = $message->getType();
        $this->messages[$section][$type][] = $message;
    }

    /**
     * @param string $type
     * @return array
     */
    public function getAllMessagesByType($type)
    {
        $result = array();
        foreach ($this->messages as $section => $messages) {
            if (isset($messages[$type])) {
                foreach ($messages[$type] as $message) {
                    $result[] = $message;
                }
            }
        }
        return $result;
    }

    /**
     * @param string $section
     * @param Mage_Core_Model_Message_Abstract $message
     */
    public function removeMessage($section, Mage_Core_Model_Message_Abstract $message)
    {
        $type = $message->getType();
        if (isset($this->messages[$section][$type])) {
            foreach ($this->messages[$section][$type] as $key => $content) {
                if ($content === $message->getText()) {
                    unset($this->messages[$section][$type][$key]);
                    break;
                }
            }
        }
    }
} 
