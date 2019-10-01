<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>

    <?php wp_body_open(); ?>

    <header class="header" role="banner">
        <div class="header__main">
            <div class="container">

                <a href="<?= esc_url(home_url()); ?>" class="header__logo">
                    <?php /* <img src="<?= esc_url(get_theme_file_uri('/assets/images/logo.png')); ?>" alt="<?= esc_attr(get_bloginfo('name')); ?> Logo"> */ ?>
                    <?php /* echo file_get_contents(get_theme_file_path('/assets/images/logo.svg')); */ ?>
                    <?php bloginfo('name'); ?>
                </a>

                <div class="header__content">
                    <nav role="navigation" class="navbar">
                        <button type="button" class="navbar__toggle" data-target=".navbar__collapse">
                            Menu
                        </button>
                        <?php wp_nav_menu([
                            'theme_location' => 'primary',
                            'container' => 'div',
                            'container_class' => 'collapse navbar__collapse',
                            'menu_class' => 'navbar__nav',
                            'depth' => 2,
                            'fallback_cb' => '__return_empty_string',
                            'walker' => new \IndigoTree\BootstrapNavWalker\Three\WalkerNavMenu()
                        ]); ?>
                    </nav>
                </div>

            </div>
        </div>
        
    </header>

    <main id="main" class="main" role="main">