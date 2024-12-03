<?php
    add_theme_support('post-thumbnails');
    // Add support for custom logo
    add_theme_support('custom-logo');
 
    // Add CORS support
    function add_cors_http_header() {
        header("Access-Control-Allow-Origin: *");
    }
    add_action('init', 'add_cors_http_header');
 
    // Enque or Stylesheets - Wordpress not the React Frontend:
    function enqueue_parent_and_custom_styles() {
        // parent theme styles:
        wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
 
        // custom styles:
        wp_enqueue_style('child-style', get_template_directory_uri() . '/custom.css', array('parent-style'));
    }
    add_action('wp_enqueue_scripts', 'enqueue_parent_and_custom_styles');
 
 
 
    // declare the function
    function custom_excerpt_length($length) {
        return 10; // change the number of character for excerpt length
    }
 
    // call the function within the corrrect WP hooks
    add_filter('excerpt_length', 'custom_excerpt_length' , 999 );
 
    // Cutomiser settings:
    function custom_theme_customize_register( $wp_customize ) {
        
        // Register and customizer settings:
        $wp_customize->add_setting('background_color', array(
            'default' => '#fffef2', // default color
            'transport' => 'postMessage',
        ));
 
        // Add a control for the background color
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'background_color', array(
            'label' => __('Background Colour', 'custom-theme'),
            'section' => 'colors',
        )));


            // Font Family
        // Add the Font section
        $wp_customize->add_section('fonts', array(
            'title' => __('Fonts', 'custom-theme'),
            'priority' => 30,
        ));

        // Add the Font setting
        $wp_customize->add_setting('font_family', array(
            'default' => 'Montserrat' ,
            'transport' => 'postMessage',
        ));

        // Add the Control of Fonts
        $wp_customize->add_control('font_family_control', array(
            'label' => 'Font Family',
            'section' => 'fonts',
            'settings' => 'font_family',
            'type' => 'select',
            'choices' => array(
                'Montserrat' => 'Montserrat',
                'Roboto' => 'Roboto',
                'Poppins' => 'Poppins',
                'DotGothic' => 'DotGothic'
            ),
        ));

        // Mobile Menu BG Colour
        $wp_customize->add_setting('mobile_menu_color', array(
            'default' => __('#10381c', 'custom-theme'),
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'mobile_menu_color', array(
            'label' => __('Mobile Menu Colour', 'custom-theme'),
            'section' => 'colors',
        )));

                // Add Primary Button Color to Customizer
        $wp_customize->add_section('button_section', array(
            'title' => __('Button Settings', 'custom-theme'),
            'priority' => 35, // Position it after fonts section
        ));

        $wp_customize->add_setting('primary_button_color', array(
            'default' => '#ffd12e', // Default color for primary button
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_button_color', array(
            'label' => __('Primary Button Colour', 'custom-theme'),
            'section' => 'button_section',
        )));

        // Add Secondary Button Color to Customizer
        $wp_customize->add_setting('secondary_button_color', array(
            'default' => '#249836', // Default color for secondary button
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_button_color', array(
            'label' => __('Secondary Button Colour', 'custom-theme'),
            'section' => 'button_section',
        )));

        // Navbar BG Colour
        $wp_customize->add_setting('navbar_color', array(
            'default' => __('#10381c', 'custom-theme'),
            'transport' => 'postMessage',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'navbar_color', array(
            'label' => __('Navbar Colour', 'custom-theme'),
            'section' => 'colors', 
        )));
    
        // Add a control for the background color
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_color', array(
            'label' => __('Button Colour', 'custom-theme'),
            'section' => 'button color',
        )));
    }
 
    add_action('customize_register', 'custom_theme_customize_register');
 
    function get_customizer_settings() {
        $settings = array(
            'backgroundColor' => get_theme_mod('background_color', '#fffef2'),
            'fontFamily' => get_theme_mod('font_family', 'Montserrat'),
            'mobileMenu' => get_theme_mod('mobile_menu_color', '#10381c'),
            'navbarColor' => get_theme_mod('navbar_color', '#10381c'),
            'primaryButtonColor' => get_theme_mod('primary_button_color', '#000000'),
            'secondaryButtonColor' => get_theme_mod('secondary_button_color', '#f0f0f0'),
        );
    
        return rest_ensure_response($settings);
    }

add_action('rest_api_init', function () {
    register_rest_route('custom-theme/v1', '/customizer-settings', array(
        'methods' => 'GET',
        'callback' => 'get_customizer_settings',
    ));
});


    // Get Nav logo that is set in the admin dashboard:
    function get_nav_logo() {
        $custom_logo_id = get_theme_mod('custom_logo');
        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');

        return $logo;

    }

    add_action('rest_api_init' , function () {
        register_rest_route('custom/v1', 'nav-logo', array(
            'methods' => 'GET',
            'callback' => 'get_nav_logo'
        ));
    });
?>