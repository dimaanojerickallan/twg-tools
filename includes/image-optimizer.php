<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_filter( 'wp_handle_upload', 'twg_optimize_image_on_upload' );

function twg_optimize_image_on_upload( $upload ) {

    if ( ! isset( $upload['type'] ) || strpos( $upload['type'], 'image/' ) !== 0 ) {
        return $upload;
    }

    if ( $upload['type'] === 'image/svg+xml' ) {
        return $upload;
    }

    if ( isset( $_REQUEST['context'] ) && $_REQUEST['context'] === 'site-icon' ) {
        return $upload;
    }

    $file_path = $upload['file'];
    $is_webp   = ( $upload['type'] === 'image/webp' || str_ends_with( $file_path, '.webp' ) );

    $editor = wp_get_image_editor( $file_path );
    if ( is_wp_error( $editor ) ) {
        return $upload;
    }
    
    /** FIX ORIENTATION BEFORE RESIZE */
    if ( method_exists( $editor, 'maybe_exif_rotate' ) ) {
        $editor->maybe_exif_rotate();
    }

    $editor->set_quality( 85 );

    $max_dimension = absint( get_option( 'twg_max_image_dimension', 2000 ) );
    if ( $max_dimension <= 0 ) {
        $max_dimension = 2000;
    }

    $max_dimension = apply_filters( 'twg_max_image_dimension', $max_dimension );

    $size = $editor->get_size();
    if ( $size && ( $size['width'] > $max_dimension || $size['height'] > $max_dimension ) ) {
        $editor->resize( $max_dimension, $max_dimension, false );
    }

    // Already WebP → save resized version only
    if ( $is_webp ) {
        $editor->save( $file_path, 'image/webp' );
        return $upload;
    }

    // Convert JPG/PNG → WebP with unique filename
    $dir      = pathinfo( $file_path, PATHINFO_DIRNAME );
    $filename = pathinfo( $file_path, PATHINFO_FILENAME );

    $unique_webp = wp_unique_filename( $dir, $filename . '.webp' );
    $webp_path   = trailingslashit( $dir ) . $unique_webp;

    $saved = $editor->save( $webp_path, 'image/webp' );
    if ( is_wp_error( $saved ) ) {
        return $upload;
    }

    @unlink( $file_path );

    $upload['file'] = $webp_path;
    $upload['url']  = trailingslashit( dirname( $upload['url'] ) ) . $unique_webp;
    $upload['type'] = 'image/webp';

    return $upload;
}
