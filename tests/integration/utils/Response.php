<?php


class Integration_Test_Http_Response extends Mage_Core_Controller_Response_Http
{
    private $headersSent = false;

    public function canSendHeaders($throw = false)
    {
        return !$this->headersSent;
    }

    public function sendHeaders()
    {
        $this->headersSent = true;
        return $this;
    }

    public function sendHeadersAndExit()
    {
        $this->sendHeaders();
        return $this;
    }

    public function sendResponse()
    {
        Mage::dispatchEvent('http_response_send_before', array('response'=>$this));
        $this->sendHeaders();
    }
} 
