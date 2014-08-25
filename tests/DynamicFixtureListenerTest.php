<?php

use Nikoms\DynamicFixture\DynamicFixtureListener;

class DynamicFixtureListenerTest extends \PHPUnit_Framework_TestCase {
    /**
     * @var \ExampleWithListenerTest
     */
    private $testCase;

    public function setUp()
    {
        $this->testCase = new \ExampleWithListenerTest();
    }

    /**
     * @param $testName
     */
    private function callTest($testName)
    {
        $this->testCase->setName($testName);
        $listener = new DynamicFixtureListener();
        $listener->startTest($this->testCase);
    }

    /**
     * @param $attributeName
     * @return ReflectionProperty
     */
    private function getAttributeValue($attributeName)
    {
        $reflection = new ReflectionClass($this->testCase);
        $attribute = $reflection->getProperty($attributeName);
        $attribute->setAccessible(true);
        return $attribute->getValue($this->testCase);
    }

    /**
     * @param string $attributeName
     * @param string $attributeValue
     */
    private function assertAttributeValue($attributeName, $attributeValue)
    {
        $this->assertSame($attributeValue, $this->getAttributeValue($attributeName));
    }

    public function testSetUpName()
    {
        $this->callTest(__FUNCTION__);
        $this->assertAttributeValue('name','Name');
        $this->assertAttributeValue('firstName',null);
    }

    public function testNoSetUp()
    {
        $this->callTest(__FUNCTION__);
         $this->assertAttributeValue('name', null);
        $this->assertAttributeValue('firstName',null);
    }

    public function testSetUpNameAndFirstName()
    {
        $this->callTest(__FUNCTION__);
         $this->assertAttributeValue('name', 'Name');
        $this->assertAttributeValue('firstName','FirstName');
    }

    public function testSetUpWithNonExistentMethod()
    {
        $this->callTest(__FUNCTION__);
         $this->assertAttributeValue('name', null);
        $this->assertAttributeValue('firstName',null);
    }
}