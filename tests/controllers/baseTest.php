<?php
use Silex\Application;
/**
 * base first test
 */
class BaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function should_behave_like_an_array()
    {
        $app = new Application();
        $this->assertFalse(isset($app['foo']));
        $app['foo'] = 'Hello';
        $this->assertTrue(isset($app['foo']));
        $this->assertEquals('Hello', $app['foo']);
        unset($app['foo']);
        $this->assertFalse(isset($app['foo']));
    }
}