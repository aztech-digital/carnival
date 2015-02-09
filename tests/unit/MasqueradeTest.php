<?php

namespace Aztech\Carnival\Tests;

use Aztech\Carnival\Masquerade;
class MasqueradeTest extends \PHPUnit_Framework_TestCase
{

    public function testBindingAndUsingAClassGeneratesNewClass()
    {
        Masquerade::register();
        Masquerade::bind('\DummyMask', new DummyClass());

        $this->assertTrue(class_exists('\DummyMask'));
    }

    public function testBindingWithANamespaceGeneratesNewNamespacedClass()
    {
        Masquerade::register();
        Masquerade::bind('\Namespaced\DummyMask', new DummyClass());
        Masquerade::bind('Namespaced2\DummyMask', new DummyClass());

        $this->assertTrue(class_exists('\Namespaced\DummyMask'));
        $this->assertTrue(class_exists('Namespaced2\DummyMask'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBindingNonObjectsThrowsException()
    {
        Masquerade::register();
        Masquerade::bind('\FailingBind', '10');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBindingAnExistingClassThrowsException()
    {
        Masquerade::register();
        Masquerade::bind('\Aztech\Carnival\Masquerade', new DummyClass());
    }

    public function testBindingGeneratesASingleton()
    {
        Masquerade::register();
        Masquerade::bind('\DummyMask', new DummyClass());

        $this->assertEquals(1, \DummyMask::getValue());
        $this->assertEquals(2, \DummyMask::getValue());
    }

}

class DummyClass
{

    public $property = 2;

    private $counter = 1;

    public function getValue()
    {
        return $this->counter++;
    }
}