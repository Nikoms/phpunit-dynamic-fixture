<?php

class ExampleWithListenerTest extends PHPUnit_Framework_TestCase {

    private $name;
    private $firstName;

    public function setUpName()
    {
        $this->name = 'Name';
    }

    public function setUpFirstName()
    {
        $this->firstName = 'FirstName';
    }

    /**
     * @setUpContext setUpName
     */
    public function testSetUpName()
    {
        $this->assertSame('Name', $this->name);
        $this->assertNull($this->firstName);
    }

    /**
     *
     */
    public function testNoSetUp()
    {
        $this->assertNull($this->name);
        $this->assertNull($this->firstName);
    }

    /**
     *
     * @setUpContext setUpName
     * @setUpContext setUpFirstName
     */
    public function testSetUpNameAndFirstName()
    {
        $this->assertSame('Name', $this->name);
        $this->assertSame('FirstName', $this->firstName);
    }

    /**
     * @setUpContext undefinedMethod
     */
    public function testSetUpWithNonExistentMethod()
    {
        $this->assertNull($this->name);
        $this->assertNull($this->firstName);
    }
}
