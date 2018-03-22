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


require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../src/app.php';

use Symfony\Component\Validator\Constraints as Assert;

$app->match('/core', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
    return new Symfony\Component\HttpFoundation\Response(json_encode(array(1)), 200);
});