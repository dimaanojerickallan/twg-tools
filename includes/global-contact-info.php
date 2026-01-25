<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Phone number (text)
 */
add_shortcode( 'phone-number', function () {
    return esc_html( get_option( 'twg_global_phone_number', '' ) );
});

/**
 * Phone number (tel link)
 */
add_shortcode( 'phone-number-link', function () {
    $phone = get_option( 'twg_global_phone_number', '' );
    return esc_url( 'tel:' . preg_replace( '/\s+/', '', $phone ) );
});

/**
 * Email address (text)
 */
add_shortcode( 'email-address', function () {
    return esc_html( get_option( 'twg_global_email_address', '' ) );
});

/**
 * Email address (mailto link)
 */
add_shortcode( 'email-address-link', function () {
    $email = get_option( 'twg_global_email_address', '' );
    return esc_url( 'mailto:' . $email );
});
