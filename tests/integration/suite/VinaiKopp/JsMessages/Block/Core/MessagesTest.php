<?php


class MessagesTest extends JsMessages_Integration_TestCase
{
    public function tearDown()
    {
        $this->resetMagento();
    }
    
    /**
     * @test
     */
    public function itShouldRewriteTheMessagesBlockInTheFrontend()
    {
        $this->dispatch('catalog/product/view');
        
        $this->assertNotEquals(Mage_Core_Model_Store::ADMIN_CODE, Mage::app()->getStore()->getCode());
        
        $block = Mage::app()->getLayout()->createBlock('core/messages');
        
        $this->assertInstanceOf(VinaiKopp_JsMessages_Block_Core_Messages::class, $block);
    }

    /**
     * @test
     */
    public function itShouldNotRewriteTheMessagesBlockInTheAdmin()
    {
        $this->dispatch('admin/index/index');

        $this->assertEquals(Mage_Core_Model_Store::ADMIN_CODE, Mage::app()->getStore()->getCode());

        $block = Mage::app()->getLayout()->createBlock('core/messages');

        $this->assertInstanceOf(Mage_Core_Block_Messages::class, $block);
        $this->assertFalse($block instanceof VinaiKopp_JsMessages_Block_Core_Messages);
    }
} 
