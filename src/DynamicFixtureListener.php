<?php

namespace Nikoms\DynamicFixture;

use Exception;
use PHPUnit_Framework_AssertionFailedError;
use PHPUnit_Framework_Test;
use PHPUnit_Framework_TestSuite;

class DynamicFixtureListener implements \PHPUnit_Framework_TestListener
{

    private $annotationName;

    /**
     * @param string $annotationName
     */
    public function __construct($annotationName = 'setUpContext')
    {
        $this->annotationName = $annotationName;
    }

    public function addError(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    }

    public function addFailure(PHPUnit_Framework_Test $test, PHPUnit_Framework_AssertionFailedError $e, $time)
    {
    }

    public function addIncompleteTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    }

    public function addRiskyTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    }

    public function addSkippedTest(PHPUnit_Framework_Test $test, Exception $e, $time)
    {
    }

    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
    }

    public function endTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
    }

    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
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
        $reflectionMethod = new \ReflectionMethod($testCase, $testCase->getName(false));
        $this->callMethods($testCase, $this->getAnnotations($reflectionMethod, $this->annotationName));
    }

    /**
     * @param \ReflectionMethod $reflectionMethod
     * @param $tagName
     * @return mixed
     */
    private function getAnnotations(\ReflectionMethod $reflectionMethod, $tagName)
    {
        preg_match_all("/@$tagName (.*)(\\r\\n|\\r|\\n)/U", $reflectionMethod->getDocComment(), $matches);
        return $matches[1];
    }

    /**
     * @param \PHPUnit_Framework_TestCase $testCase
     * @param array $methods
     */
    private function callMethods(\PHPUnit_Framework_TestCase $testCase, array $methods)
    {
        foreach ($methods as $method) {
            if(!method_exists($testCase, $method)){
                continue;
            }
            $reflectionMethod = new \ReflectionMethod($testCase, $method);
            $reflectionMethod->invoke($testCase);
        }
    }
} 