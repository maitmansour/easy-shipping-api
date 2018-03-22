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


require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../../src/app.php';


require_once __DIR__.'/core/index.php';


$app->match('/', function () use ($app) {

    return new Symfony\Component\HttpFoundation\Response(json_encode(array(0)), 200);
        
})
->bind('index');


$app->run();