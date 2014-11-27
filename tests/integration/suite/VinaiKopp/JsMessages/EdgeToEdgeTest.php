<?php


class EdgeToEdgeTest extends JsMessages_Integration_TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject|Mage_Core_Model_Cookie
     */
    private $mockCookie;

    public function setUp()
    {
        $this->mockCookie = $this->getMock('Mage_Core_Model_Cookie', [], [], '', false);
        $this->setMockSingleton('core/cookie', $this->mockCookie);
    }
    
    public function tearDown()
    {
        $this->resetMagento();
    }
    
    /**
     * @test
     * @param string $sessionModelAlias
     * @dataProvider sessionModelClassAliasProvider
     */
    public function itShouldSetAMessageAddedToASessionModelAsTheMessagesCookie($sessionModelAlias)
    {
        $this->assertNotEquals(Mage_Core_Model_Store::ADMIN_CODE, Mage::app()->getStore()->getCode());
        $testMessage = 'Test Message';
        $expected = ['success' => [$testMessage]];
        
        $this->mockCookie->expects($this->once())
            ->method('set')
            ->with(VinaiKopp_JsMessages_Helper_Data::COOKIE_MESSAGES, rawurlencode(Zend_Json::encode($expected)));
        
        // Trigger the frontend only class rewrites
        Mage::dispatchEvent('controller_action_predispatch', ['controller_action' => new DummyController()]);
        
        /** @var Mage_Core_Model_Session_Abstract $session */
        $session = Mage::getSingleton($sessionModelAlias);
        $session->addSuccess($testMessage);
        $this->dispatch('/');
    }

    public function sessionModelClassAliasProvider()
    {
        return [
            ['core/session'],
            ['checkout/session'],
            ['catalog/session'],
            ['customer/session']
        ];
    }

    /**
     * @test
     */
    public function itShouldSetAMessageFromAddToCartAsTheMessageCookie()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        
        $mockProduct = $this->getMock(Mage_Catalog_Model_Product::class, ['getId', 'save']);
        $mockProduct->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));
        $this->setModelMock('catalog/product', $mockProduct);

        $expectedMessage = "Please specify the product's option(s).";
        $expected = ['notice' => [$expectedMessage]];

        $this->mockCookie->expects($this->once())
            ->method('set')
            ->with(VinaiKopp_JsMessages_Helper_Data::COOKIE_MESSAGES, rawurlencode(Zend_Json::encode($expected)));
        
        // Will add a notice to the checkout/session messages "Please specify the product's option(s)."
        $this->dispatch('checkout/cart/add', ['product' => 1]);
    }
} 

class DummyController extends Mage_Core_Controller_Front_Action
{
    public function __construct(){}
}
