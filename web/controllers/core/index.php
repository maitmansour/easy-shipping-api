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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\User;

/**
 * Login route, test : send a JSON object with properties : _username:admin & _password:foo
 * 
 */
$app->post('/login', function(Request $request) use ($app){
    $vars = json_decode($request->getContent(), true);

    try {
        if (empty($vars['_username']) || empty($vars['_password'])) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $vars['_username']));
        }

        /**
         * @var $user User
         */
        $user = $app['users']->loadUserByUsername($vars['_username']);

        if (! $app['security.encoder.digest']->isPasswordValid($user->getPassword(), $vars['_password'], '')) {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $vars['_username']));
        } else {
            $response = [
                'success' => true,
                'token' => $app['security.jwt.encoder']->encode(['name' => $user->getUsername()]),
            ];
        }
    } catch (UsernameNotFoundException $e) {
        $response = [
            'success' => false,
            'error' => 'Invalid credentials',
        ];
    }

    return $app->json($response, ($response['success'] == true ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST));
});



$app->get('/protected', function() use ($app){
    $jwt = 'no';
    $token = $app['security.token_storage']->getToken();
    if ($token instanceof Silex\Component\Security\Http\Token\JWTToken) {
        $jwt = 'yes';
    }
    $granted = 'no';
    if($app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
        $granted = 'yes';
    }
    $granted_user = 'no';
    if($app['security.authorization_checker']->isGranted('ROLE_USER')) {
        $granted_user = 'yes';
    }
    $granted_super = 'no';
    if($app['security.authorization_checker']->isGranted('ROLE_SUPER_ADMIN')) {
        $granted_super = 'yes';
    }
    $user = $token->getUser();
    return $app->json([
        'hello' => $token->getUsername(),
        'username' => $user->getUsername(),
        'auth' => $jwt,
        'granted' => $granted,
        'granted_user' => $granted_user,
        'granted_super' => $granted_super,
    ]);
});
