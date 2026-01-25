<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * IMAGE OPTIMIZATION SETTINGS
 */
add_action( 'admin_init', 'twg_register_image_settings' );

function twg_register_image_settings() {

    register_setting(
        'twg_image_settings',
        'twg_max_image_dimension',
        [
            'type'              => 'integer',
            'sanitize_callback' => 'absint',
            'default'           => 2000,
        ]
    );
}

/**
 * GLOBAL CONTACT INFO SETTINGS
 */
add_action( 'admin_init', 'twg_register_contact_settings' );

function twg_register_contact_settings() {

    register_setting(
        'twg_contact_settings',
        'twg_global_phone_number',
        [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );

    register_setting(
        'twg_contact_settings',
        'twg_global_email_address',
        [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_email',
        ]
    );
}
