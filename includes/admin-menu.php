<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'admin_menu', 'twg_register_admin_menu' );

function twg_register_admin_menu() {

    /**
     * Top-level menu
     */
    add_menu_page(
        'TWG Tools',
        'TWG Tools',
        'manage_options',
        'twg-tools',
        'twg_image_settings_page',
        'dashicons-admin-tools',
        80
    );

    /**
     * Image Optimization submenu
     */
    add_submenu_page(
        'twg-tools',
        'Image Optimization',
        'Image Optimization',
        'manage_options',
        'twg-tools',
        'twg_image_settings_page'
    );

    /**
     * Contact Info submenu
     */
    add_submenu_page(
        'twg-tools',
        'Contact Info',
        'Contact Info',
        'manage_options',
        'twg-contact-info',
        'twg_contact_info_page'
    );
}

/**
 * IMAGE OPTIMIZATION PAGE
 */
function twg_image_settings_page() {

    $max_dimension = get_option( 'twg_max_image_dimension', 2000 );
    ?>

    <div class="wrap">
        <h1>TWG Tools – Image Optimization</h1>

        <form method="post" action="options.php">
            <?php
                settings_fields( 'twg_image_settings' );
                do_settings_sections( 'twg_image_settings' );
            ?>

            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="twg_max_image_dimension">
                            Max image dimension (px)
                        </label>
                    </th>
                    <td>
                        <input
                            type="number"
                            id="twg_max_image_dimension"
                            name="twg_max_image_dimension"
                            value="<?php echo esc_attr( $max_dimension ); ?>"
                            min="500"
                            step="100"
                        />
                        <p class="description">
                            Images larger than this size (width or height) will be resized and converted to WebP.
                            Default is <strong>2000px</strong>.
                        </p>
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>

    <?php
}

/**
 * CONTACT INFO PAGE
 */
function twg_contact_info_page() {

    $phone = get_option( 'twg_global_phone_number', '' );
    $email = get_option( 'twg_global_email_address', '' );
    ?>

    <div class="wrap">
        <h1>Global Contact Info</h1>

        <form method="post" action="options.php">
            <?php
                settings_fields( 'twg_contact_settings' );
                do_settings_sections( 'twg_contact_settings' );
            ?>

            <table class="form-table">
                <tr>
                    <th scope="row">Phone Number</th>
                    <td>
                        <input
                            type="text"
                            name="twg_global_phone_number"
                            value="<?php echo esc_attr( $phone ); ?>"
                            class="regular-text"
                        />
                    </td>
                </tr>

                <tr>
                    <th scope="row">Email Address</th>
                    <td>
                        <input
                            type="email"
                            name="twg_global_email_address"
                            value="<?php echo esc_attr( $email ); ?>"
                            class="regular-text"
                        />
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>

    <?php
}
