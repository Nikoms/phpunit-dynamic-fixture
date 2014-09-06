<?php

namespace Nikoms\DynamicFixture;

use PHPUnit_Framework_Test;

class DynamicFixtureListener extends \PHPUnit_Framework_BaseTestListener
{

    private $annotationName;

    /**
     * @param string $annotationName
     */
    public function __construct($annotationName = 'setUpContext')
    {
        $this->annotationName = $annotationName;
    }

    /**
     * A test started.
     * @param  PHPUnit_Framework_Test $test
     */
    public function startTest(PHPUnit_Framework_Test $test)
    {
        //That's nasty, but saved my life :)
        if ($test instanceof \PHPUnit_Framework_TestCase) {
            $this->setUpContext($test);
        }
    }

    /**
     * @param \PHPUnit_Framework_TestCase $testCase
     */
    private function setUpContext(\PHPUnit_Framework_TestCase $testCase)
    {
        $annotations = $testCase->getAnnotations();
        if (isset($annotations['method'][$this->annotationName])) {
            $this->callMethods($testCase, $annotations['method'][$this->annotationName]);
        }

    }

    /**
     * @param \PHPUnit_Framework_TestCase $testCase
     * @param array $methods
     */
    private function callMethods(\PHPUnit_Framework_TestCase $testCase, array $methods)
    {
        foreach ($methods as $method) {
            if (!method_exists($testCase, $method)) {
                continue;
            }
            $reflectionMethod = new \ReflectionMethod($testCase, $method);
            $reflectionMethod->invoke($testCase);
        }
    }
}
