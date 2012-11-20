<?php

namespace Test\All\Fs;
use Europa\Fs\Loader;
use Europa\Fs\Locator;
use Europa\Fs\LocatorArray;
use Testes\Test\UnitAbstract;

class LoaderTest extends UnitAbstract
{
    private $loader;
    
    public function setUp()
    {
        $this->loader = new Loader;
        $this->loader->register();
        $this->loader->setLocator(new LocatorArray);
        $this->loader->getLocator()->add(new Locator);
        $this->loader->getLocator()->add(function($file) {
            return realpath(dirname(__FILE__) . '/../../' . $file . '.php');
        });
    }
    
    public function testRegisterAutoload()
    {
        foreach (spl_autoload_functions() as $func) {
            if (is_array($func) && $func[0] instanceof Loader && $func[1] === '__invoke') {
                return;
            }
        }

        $this->assert(false, 'Unable to register autoloading.');
    }
    
    public function testLoadClass()
    {
        $this->assert(
            $this->loader->__invoke('Europa\Request\Http'),
            'Unable to load class.'
        );
    }
    
    public function testLoadOldStyleNamespacedClass()
    {
        $this->assert(
            $this->loader->__invoke('Test_Provider_Fs_TestClass'),
            'Unable to load old style namespaced class.'
        );
    }
}