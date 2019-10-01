<?php

// Define base paths & uri
//
define('ORIGIN_PATH', untrailingslashit(get_template_directory()));
define('ORIGIN_URI', untrailingslashit(get_template_directory_uri()));

// Require the auto-loader
//
require_once(ORIGIN_PATH . '/vendor/autoload.php');

// Setup IOC container
//
$container = new Illuminate\Container\Container();
$container->instance('origin', $container);

Illuminate\Container\Container::setInstance($container);

// Include misc setup & function files
//
require_once(ORIGIN_PATH . '/theme/theme-functions.php');
require_once(ORIGIN_PATH . '/theme/theme-setup.php');
require_once(ORIGIN_PATH . '/theme/theme-filters.php');
