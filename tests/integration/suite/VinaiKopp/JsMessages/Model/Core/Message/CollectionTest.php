<?php


class VinaiKopp_JsMessages_Model_Core_Message_CollectionTest extends JsMessages_Integration_TestCase
{
    public function tearDown()
    {
        $this->resetMagento();
    }

    /**
     * @test
     */
    public function itShouldRewriteTheMessageCollectionInTheFrontend()
    {
        $this->dispatch('catalog/product/view');

        $this->assertNotEquals(Mage_Core_Model_Store::ADMIN_CODE, Mage::app()->getStore()->getCode());

        $collection = Mage::getModel('core/message_collection');

        $this->assertInstanceOf(VinaiKopp_JsMessages_Model_Core_Message_Collection::class, $collection);
    }

    /**
     * @test
     */
    public function itShouldNotRewriteTheMessageCollectionInTheAdmin()
    {
        $this->dispatch('admin/index/index');

        $this->assertEquals(Mage_Core_Model_Store::ADMIN_CODE, Mage::app()->getStore()->getCode());

        $collection = Mage::getModel('core/message_collection');

        $this->assertInstanceOf(Mage_Core_Model_Message_Collection::class, $collection);
        $this->assertFalse($collection instanceof VinaiKopp_JsMessages_Model_Core_Message_Collection);
    }

    /**
     * @test
     */
    public function itShouldAddAMessageToTheSharedMessageStorage()
    {
        $this->dispatchEventForFrontendClassRewrites();
        
        /** @var VinaiKopp_JsMessages_Model_MessageStorage $sharedStorage */
        $sharedStorage = Mage::getSingleton('vinaikopp_jsmessages/messageStorage');
        $session = Mage::getSingleton('core/session');
        $session->addError('Test Message');
        $messages = $sharedStorage->getAllMessagesByType(Mage_Core_Model_Message::ERROR);
        $this->assertEquals(
            'Test Message',
            $messages[0]->getText()
        );
    }
} 
