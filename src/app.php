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

/**
 * Importing all classes and providers 
 */
use Silex\Application;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Silex\Provider\MonologServiceProvider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\User;

/**
 * Init App and config project
 */
$app = new Application();
ini_set('display_errors', 'E_ALL');
$app['debug']  = true;
$app['projet'] = 'EasyShippingApi';



/**
 * Service registration
 */
/**
 * TODO : Monolog config
 */
//$app->register(new MonologServiceProvider(), ['monolog.logfile' =>'log/development.log','monolog.name' => 'EasyShippingApi']);

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.messages' => array(),
));
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(

        'dbs.options' => array(
            'db' => array(
                'driver'   => 'pdo_mysql',
                'dbname'   => 'DB_NAME',
                'host'     => 'HOST',
                'user'     => 'DB_UNAME',
                'password' => 'DB_PASSWORD',
                'charset'  => 'utf8',
            ),
        )
));
$app->register(new SwiftmailerServiceProvider());

$app['swiftmailer.options'] = array(
    'host' => 'SWIFT_MAILER_HOST',
    'port' => 'PORT',
    'username' => 'SWIFT_MAILER_UNAME',
    'password' => 'SWIFT_MAILER_PSSWD'
);

$app['users'] = function () use ($app) {
    $users = [
        'admin' => array(
            'roles' => array('ROLE_ADMIN'),
            // raw password is foo
            'password' => '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==',
            'enabled' => true
        ),
    ];

    return new InMemoryUserProvider($users);
};

/**
 * JWT security configuration
 */
$app['security.jwt'] = [
    'secret_key' => 'Very_secret_key',
    'life_time'  => 86400,
    'algorithm'  => ['HS256'],
    'options'    => [
        'header_name'  => 'X-Access-Token',
        'token_prefix' => 'Bearer',
    ]
];

$app['security.firewalls'] = array(
    'login' => [
        'pattern' => 'login|register|oauth',
        'anonymous' => true,
    ],
    'secured' => array(
        'pattern' => '^.*$',
        'logout' => array('logout_path' => '/logout'),
        'users' => $app['users'],
        'jwt' => array(
            'use_forward' => true,
            'require_previous_session' => false,
            'stateless' => true,
        )
    ),
);


$app->register(new Silex\Provider\SecurityServiceProvider());
$app->register(new Silex\Provider\SecurityJWTServiceProvider());

return $app;
