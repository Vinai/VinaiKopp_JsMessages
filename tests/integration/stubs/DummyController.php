<?php


class DummyController extends Mage_Core_Controller_Front_Action
{
    public function __construct()
    {
        $request = Mage::app()->getRequest();
        $response = Mage::app()->getResponse();
        parent::__construct($request, $response);
    }

}
