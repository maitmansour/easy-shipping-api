<?php

/*
 * This file is part of the Easy Shipping API project.
 *
 * Author: Mohamed AIT MANSOUR <contact@numidea.com>
 * Web: https://github.com/maitmansour/easy-shipping-api
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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