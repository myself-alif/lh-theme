<?php if (!defined('ABSPATH')) { header('Location: /'); exit; } ?><!doctype html>
<html  <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
    	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5"/>

		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
        <?php wp_body_open(); ?>

		<div id="root">
			<header id="header">
                <div class="lh-navigation">
                    <div class="lh-logo-wrapper">
                        <a href="<?php echo get_bloginfo( 'url' ); ?>" class="lh-desk-logo-container" aria-label="<?php echo get_bloginfo( 'name' ); ?>" style="background-image: url('<?php print LH_HEADER_LOGO_SYMBOL_URL; ?>');">
                            <img
                                id="lh-desk-logo"
                                src="<?php print LH_HEADER_LOGO_URL; ?>"
                                alt="<?php echo get_bloginfo( 'name' ); ?>"
                                title="<?php echo get_bloginfo( 'name' ); ?>"
                                width="107"
                                height="80"/>
                        </a>
                    </div>

                    <div class="lh-menus-wrapper">
                        <div class="lh-search-wrapper lh-desktop">
                            <a
                                href="/?s="
                                class="lh-nav-btn lh-icon-search lh-search-link"
                                title="<?php _e('Search', LH_THEME_SLUG); ?>"
                            ></a>
                        </div>
                        
                        <?php
                            wp_nav_menu(
                                [
                                    'menu_class' 		=> 'menu',
                                    'theme_location' 	=> 'top-nav',
                                    'container'			=> 'nav',
                                    'container_class' 	=> 'lh-links-menu-wrapper',
                                    // 'add_li_class'	    => '',
                                    'add_link_class'	=> 'menu-item-link',
                                    'fallback_cb'		=> false
                                ]
                            );

                            wp_nav_menu(
                                [
                                    'menu_class' 		=> 'menu',
                                    'theme_location' 	=> 'top-nav-buttons',
                                    'container'			=> 'nav',
                                    'container_class' 	=> 'lh-buttons-menu-wrapper',
                                    // 'add_li_class'	    => '',
                                    'add_link_class'	=> 'menu-item-link',
                                    'fallback_cb'		=> false
                                ]
                            );
                        ?>

                        <div class="lh-header-menu-mobile-menu-wrapper">
                            <div class="lh-menu-mobile-menu-header">
                                <div class="lh-logo-wrapper">
                                    <a href="<?php echo get_bloginfo( 'url' ); ?>" class="lh-mobile-logo-container" aria-label="<?php echo get_bloginfo( 'name' ); ?>" style="background-image: url('<?php print LH_HEADER_LOGO_SYMBOL_URL; ?>');">
                                        <img
                                            id="lh-mobile-logo"
                                            src="<?php print LH_HEADER_LOGO_URL; ?>"
                                            alt="<?php echo get_bloginfo( 'name' ); ?>"
                                            title="<?php echo get_bloginfo( 'name' ); ?>"
                                            width="107"
                                            height="80"/>
                                    </a>
                                </div>
                            </div>

                            <div class="lh-menu-mobile-menu-body">
                                <nav class="lh-mobile-menu-wrapper">
                                    <?php
                                        wp_nav_menu(
                                            [
                                                'menu_class' 		=> 'menu',
                                                'theme_location' 	=> 'top-nav',
                                                'container'			=> false,
                                                'container_class' 	=> '',
                                                // 'add_li_class'	    => '',
                                                'add_link_class'	=> 'menu-item-link',
                                                'fallback_cb'		=> false
                                            ]
                                        );

                                        wp_nav_menu(
                                            [
                                                'menu_class' 		=> 'menu',
                                                'theme_location' 	=> 'top-nav-buttons',
                                                'container'			=> false,
                                                'container_class' 	=> '',
                                                // 'add_li_class'	    => '',
                                                'add_link_class'	=> 'menu-item-link',
                                                'fallback_cb'		=> false
                                            ]
                                        );
                                    ?>
                                </nav>
                            </div>

                            <div class="lh-menu-mobile-menu-footer">
                                <button type="button" class="lh-header-menu-close-btn"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>