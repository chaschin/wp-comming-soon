<?php

/**
 * @package CommingSoon
 *
 *
 * Plugin Name: WP Comming Soon
 * Plugin URI: https://github.com/chaschin/wp-comming-soon
 * Description: Add Comming Soon page for non autorized users
 * Version: 1.0
 * Author: Alexey Chaschin
 * Author URI: https://github.com/chaschin
 * Text Domain: comming_soon
 *
 */
 
if ( ! function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}


define( 'COMMING_SOON__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'COMMING_SOON__PLUGIN_URL', plugin_dir_url( __FILE__ ) );


function coming_soon_redirect() {
    global $pagenow;
    
    $show_comming_soon_page    = get_field( 'show_comming_soon_page', 'options' );
    if ( ! is_user_logged_in() && ! is_page( 'login' ) && $page_now != 'wp-login.php' && $show_comming_soon_page ) {
        header( 'Retry-After: ' . DAY_IN_SECONDS );
        header( 'Expires: 0' );
        header( 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0' );
        header( 'Cache-Control: post-check=0, pre-check=0', false );
        header( 'Pragma: no-cache' );
        // readfile( COMMING_SOON__PLUGIN_DIR . 'comming-soon.html' );
        ob_start();
        include( COMMING_SOON__PLUGIN_DIR . 'comming-soon.html' );
        $comming_soon_page = ob_get_contents();
        ob_end_clean();
        $assets_url = COMMING_SOON__PLUGIN_URL . 'assets/';
        $comming_soon_text         = get_field( 'comming_soon_text', 'options' );
        $comming_soon_show_footer  = get_field( 'comming_soon_show_footer', 'options' );
        $social_links = '';
        if ( $comming_soon_show_footer ) {
            $comming_soon_social_links = get_field( 'comming_soon_social_links', 'options' );
            if ( $comming_soon_social_links ) {
                $social_links .= '<div class="social-footer">';
                foreach ( $comming_soon_social_links as $social_link ) {
                    $social_links .= '<a href="' . $social_link['url'] . '" target="_blank"><i class="fa ' . $social_link['icon'] . '" aria-hidden="true"></i></a>';
                }
                $social_links .= '</div>';
            }
        }
        $comming_soon_page = str_replace( '{SOCIAL_FOOTER}', $social_links, $comming_soon_page );
        $comming_soon_page = str_replace( '{ASSETS_URL}', $assets_url, $comming_soon_page );
        $comming_soon_page = str_replace( '{TEXT}', $comming_soon_text, $comming_soon_page );
        echo $comming_soon_page;
        exit;
    }
}
add_action( 'template_redirect', 'coming_soon_redirect' );


function comming_soon_settings() {
    if ( function_exists( 'acf_add_options_page' ) ) {
        acf_add_options_page( [
            'page_title' => 'Comming Soon Page Settings',
            'menu_title' => 'Comming Soon',
            'menu_slug'  => 'comming-soon-page-settings',
            'capability' => 'edit_posts',
            'redirect'   => false
        ] );
    }
    if (function_exists('acf_add_local_field_group')) :

        acf_add_local_field_group(array(
            'key' => 'group_60527fe449afe',
            'title' => 'Comming Soon Settings',
            'fields' => array(
                array(
                    'key' => 'field_60527ff1d1d7c',
                    'label' => 'Show Comming Soon Page',
                    'name' => 'show_comming_soon_page',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 0,
                    'ui' => 1,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                ),
                array(
                    'key' => 'field_6052812798570',
                    'label' => 'Text',
                    'name' => 'comming_soon_text',
                    'type' => 'wysiwyg',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_60527ff1d1d7c',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'tabs' => 'all',
                    'toolbar' => 'full',
                    'media_upload' => 0,
                    'delay' => 0,
                ),
                array(
                    'key' => 'field_6052819942972',
                    'label' => 'Show footer with social links',
                    'name' => 'comming_soon_show_footer',
                    'type' => 'true_false',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_60527ff1d1d7c',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 0,
                    'ui' => 1,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                ),
                array(
                    'key' => 'field_605281f07f001',
                    'label' => 'Social links',
                    'name' => 'comming_soon_social_links',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_60527ff1d1d7c',
                                'operator' => '==',
                                'value' => '1',
                            ),
                            array(
                                'field' => 'field_6052819942972',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'collapsed' => '',
                    'min' => 0,
                    'max' => 0,
                    'layout' => 'row',
                    'button_label' => 'Add Link',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_605282717f002',
                            'label' => 'Icon',
                            'name' => 'icon',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                        array(
                            'key' => 'field_6052828d7f003',
                            'label' => 'Url',
                            'name' => 'url',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'comming-soon-page-settings',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
        ));

    endif;
}
add_action( 'init', 'comming_soon_settings' );