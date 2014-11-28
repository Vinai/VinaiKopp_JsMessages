<?php


abstract class JsMessages_Integration_TestCase extends PHPUnit_Framework_TestCase
{
    final protected function dispatch($route, $params = [])
    {
        if ($this->getRequest()->isPost()) {
            $this->getRequest()->setParam('form_key', Mage::getSingleton('core/session')->getFormKey());
            $this->getRequest()->setParam('nocookie', 1);
        }
        array_walk($params, function($value, $key) {
            $this->getRequest()->setParam($key, $value);
        });
        $this->disableRedirectToBaseUrl();
        $this->getRequest()->setPathInfo($route);
        Mage::app()->getFrontController()->dispatch();
    }
    
    final protected function resetMagento()
    {
        (new TestHelper())->resetMagento();
    }
    
    final protected function getResponse()
    {
        return Mage::app()->getResponse();
    }

    /**
     * @return Integration_Test_Config
     */
    final protected function getConfig()
    {
        return Mage::getConfig();
    }
    
    final protected function setModelMock($modelClass, $mock)
    {
        $this->getConfig()->setModelMock($modelClass, $mock);
    }
    
    final protected function setResourceModelMock($modelClass, $mock)
    {
        $this->getConfig()->setResourceModelMock($modelClass, $mock);
    }
    
    final protected function setMockSingleton($modelClass, $mock)
    {
        $key = '_singleton/' . $modelClass;
        Mage::unregister($key);
        Mage::register($key, $mock);
    }
    
    final protected function setMockResourceSingleton($modelClass, $mock)
    {
        $key = '_resource_singleton/' . $modelClass;
        Mage::unregister($key);
        Mage::register($key, $mock);
    }

    /**
     * @return Mage_Core_Controller_Request_Http
     */
    final protected function getRequest()
    {
        return Mage::app()->getRequest();
    }

    final protected function dispatchEventForFrontendClassRewrites()
    {
        // Trigger the frontend only class rewrites
        Mage::app()->loadAreaPart(Mage_Core_Model_App_Area::AREA_FRONTEND, Mage_Core_Model_App_Area::PART_EVENTS);
        Mage::dispatchEvent('controller_action_predispatch', ['controller_action' => new DummyController()]);
    }

    private function disableRedirectToBaseUrl()
    {
        foreach (Mage::app()->getStores(true) as $store) {
            $store->setConfig('web/url/redirect_to_base', 0);
        }
    }

} 
