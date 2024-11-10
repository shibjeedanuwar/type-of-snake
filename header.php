<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo esc_attr(get_the_excerpt()); ?>">
    <meta name="keywords" content="Type of snake, venomous snake, Non-Venomous snakes, snake, Snake species">
    <meta name="robots" content="index, follow">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php wp_title('|', true, 'right'); ?>">
    <meta property="og:description" content="<?php echo esc_attr(get_the_excerpt()); ?>">
    <meta property="og:image" content="<?php echo esc_url(get_the_post_thumbnail_url()); ?>">
    <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>">
    <meta property="og:type" content="website">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php wp_title('|', true, 'right'); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr(get_the_excerpt()); ?>">
    <meta name="twitter:image" content="<?php echo esc_url(get_the_post_thumbnail_url()); ?>">

    <?php wp_head(); ?> 
</head>
<body <?php body_class(is_front_page() ? 'min-h-screen bg-gradient-to-br from-slate-900 to-slate-800 text-white' : ''); ?>>
<div class="full-container">
    <div class="row">
        <div class="col">
            <div class="row header">
                <div class="col">
                    <header id="header">
                        <nav class="nav container">
                            <div class="nav__logo"> 
                                <a href="<?php echo esc_url(home_url('/')); ?>">
                                    <img class="nav_logo_img img-fluid" src="<?php echo get_template_directory_uri() . '/assets/images/type-of-snake.png' ?>" alt="logo" style="width:150px; height:auto;"/>
                                </a>
                            </div>

                            <div class="nav__menu" id="nav-menu">
                                <?php 
                                    wp_nav_menu(array(
                                        'theme_location' => 'snake',
                                        'menu_class' => 'nav__list',
                                        'container' => false,
                                        'walker' => new Custom_Walker_Nav_Menu(),
                                    )); 
                                ?>

                                <!-- Close button -->
                                <div class="nav__close" id="nav-close">X</div>

                                <div class="nav__social">
                                    <?php
                                    // Retrieve social media URLs from the Customizer
                                    $facebook_url = get_theme_mod('facebook_url');
                                    $twitter_url = get_theme_mod('twitter_url');
                                    $instagram_url = get_theme_mod('instagram_url');
                                    $pinterest_url = get_theme_mod('pinterest_url');
                                    $reddit_url = get_theme_mod('reddit_url');
                                    ?>
                                    <a href="<?php echo esc_url($instagram_url ? $instagram_url : '#'); ?>" target="_blank" class="nav__social-link" rel="noopener noreferrer" aria-label="Visit our Instagram page">
                                        <i class="ri-instagram-line"></i>
                                    </a>
                                    <a href="<?php echo esc_url($twitter_url ? $twitter_url : '#'); ?>" target="_blank" class="nav__social-link" rel="noopener noreferrer" aria-label="Visit our Twitter page">
                                        <i class="ri-twitter-line"></i>
                                    </a>
                                    <a href="<?php echo esc_url($facebook_url ? $facebook_url : '#'); ?>" target="_blank" class="nav__social-link" rel="noopener noreferrer" aria-label="Visit our Facebook page">
                                        <i class="ri-facebook-line"></i>
                                    </a>
                                    <a href="<?php echo esc_url($pinterest_url ? $pinterest_url : '#'); ?>" target="_blank" class="nav__social-link" rel="noopener noreferrer" aria-label="Visit our Pinterest page">
                                        <i class="ri-pinterest-line"></i>
                                    </a>
                                    <a href="<?php echo esc_url($reddit_url ? $reddit_url : '#'); ?>" target="_blank" class="nav__social-link" rel="noopener noreferrer" aria-label="Visit our Reddit page">
                                        <i class="ri-reddit-line"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- Toggle button -->
                            <div class="nav__toggle" id="nav-toggle">
                                <i class="ri-menu-line"></i>
                            </div>
                        </nav>
                    </header>
                </div>
            </div>
        </div>
    </div>
</div>