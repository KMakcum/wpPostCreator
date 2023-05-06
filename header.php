<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, height=device-height initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="developed" content="Development by OnePix">

	<!-- Preloading fonts -->
	<link rel="preload" href="<?= DIST_URI ?>/fonts/Roboto/Roboto-Regular.woff2" as="font" type="font/woff2" crossorigin="anonymous">

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <header class="header">
        <div class="header__content container">   
            <a href="/" class="header__logo">
                <?php if (false):?>
                <svg>
                    <use xlink:href="<?= DIST_URI . '/images/icons/svg-sprite.svg#logo'; ?>"></use>
                </svg>
                <?php endif;?>
                <img src="<?= DIST_URI . '/images/icons/logo.png'; ?>" alt="PostCreator-logo">
            </a>
        </div>
    </header>

    <div class="wrapper">
        <main class="main">
