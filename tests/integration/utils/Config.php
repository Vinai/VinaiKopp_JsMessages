<?php


class Integration_Test_Config extends Mage_Core_Model_Config
{
    private $modelMocks = [];

    private $resourceMocks = [];

    public function getModelInstance($modelClass = '', $constructArguments = array())
    {
        if (isset($this->modelMocks[(string)$modelClass])) {
            return $this->modelMocks[(string)$modelClass];
        }
        return parent::getModelInstance($modelClass, $constructArguments);
    }

    public function getResourceModelInstance($modelClass = '', $constructArguments = array())
    {
        if (isset($this->resourceMocks[(string)$modelClass])) {
            return $this->resourceMocks[(string)$modelClass];
        }
        return parent::getResourceModelInstance($modelClass, $constructArguments);
    }

    final public function setModelMock($modelClass, $mock)
    {
        $this->modelMocks[$modelClass] = $mock;
    }
    
    final public function setResourceModelMock($modelClass, $mock)
    {
        $this->resourceMocks[$modelClass] = $mock;
    }
} 
