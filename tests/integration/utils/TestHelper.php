<?php


class TestHelper
{
    public function initMagento()
    {
        if (!class_exists('Mage', false)) {
            $this->requireMageFile();
            $this->requireIntegrationTestUtils();
        }
        $_SESSION = [];
        Mage::setIsDeveloperMode(true);
        Mage::app('', 'store', [
            'config_model' => Integration_Test_Config::class
        ])->setResponse(new Integration_Test_Http_Response());
        $this->fixMagentoAutoLoader();
    }

    public function resetMagento()
    {
        Mage::reset();
        unset($_SERVER['REQUEST_METHOD']);
        $this->initMagento();
    }

    private function fixMagentoAutoLoader()
    {
        $mageHandler = set_error_handler(function () {
            return false;
        });
        set_error_handler(function ($errno, $errstr, $errfile, $errline) use ($mageHandler) {
            if (E_WARNING === $errno
                && 0 === strpos($errstr, 'include(')
                && substr($errfile, -19) == 'Varien/Autoload.php'
            ) {
                return null;
            }
            return call_user_func($mageHandler, $errno, $errstr, $errfile, $errline);
        });
    }

    private function getMagePathIfModmanIsInMagentoBaseDir()
    {
        return __DIR__ . '/../../../../../app/Mage.php';
    }

    private function getMagePathIfModmanIsAboveMagentoBaseDir()
    {
        return __DIR__ . '/../../../../../*/app/Mage.php';
    }

    /**
     * @throws RuntimeException
     */
    private function requireMageFile()
    {
        if (file_exists($this->getMagePathIfModmanIsInMagentoBaseDir())) {
            require $this->getMagePathIfModmanIsInMagentoBaseDir();
        } else {
            if ($matches = glob($this->getMagePathIfModmanIsAboveMagentoBaseDir())) {
                require $matches[0];
            } else {
                throw new RuntimeException('Unable to find the file app/Mage.php');
            }
        }
    }
    
    private function requireIntegrationTestUtils()
    {
        require __DIR__ . '/Response.php';
        require __DIR__ . '/Config.php';
    }
} 
