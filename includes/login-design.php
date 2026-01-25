<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', 'twg_add_login_design_menu');

function twg_add_login_design_menu() {
    add_submenu_page(
        'twg-tools',
        'Login Design',
        'Login Design',
        'manage_options',
        'twg-login-design',
        'twg_login_design_page'
    );
}

add_action('admin_init', 'twg_register_login_design_settings');

function twg_register_login_design_settings() {
    register_setting(
        'twg_login_design_group',
        'twg_login_design_settings'
    );
}

function twg_login_design_page() {
    $options = get_option('twg_login_design_settings');
    ?>
    <div class="wrap">
        <h1>Login Page Design</h1>

        <form method="post" action="options.php">
            <?php
            settings_fields('twg_login_design_group');
            ?>

            <table class="form-table">

                <!-- Logo -->
                <tr>
                    <th>Logo Image</th>
                    <td>
                        <input type="text" id="twg_login_logo" name="twg_login_design_settings[logo]" value="<?php echo esc_attr($options['logo'] ?? ''); ?>" />
                        <button type="button" class="button twg-upload">Upload</button>
                    </td>
                </tr>

                <tr>
                    <th>Logo Width (px)</th>
                    <td>
                        <input type="number" name="twg_login_design_settings[logo_width]" value="<?php echo esc_attr($options['logo_width'] ?? 242); ?>">
                    </td>
                </tr>

                <tr>
                    <th>Logo Height (px)</th>
                    <td>
                        <input type="number" name="twg_login_design_settings[logo_height]" value="<?php echo esc_attr($options['logo_height'] ?? 80); ?>">
                    </td>
                </tr>

                <!-- Background -->
                <tr>
                    <th>Container Background Color</th>
                    <td>
                        <input type="text" class="twg-color" name="twg_login_design_settings[bg_color]" value="<?php echo esc_attr($options['bg_color'] ?? '#ffffff'); ?>">
                    </td>
                </tr>

                <tr>
                    <th>Right Side Image</th>
                    <td>
                        <input type="text" id="twg_login_side_image" name="twg_login_design_settings[side_image]" value="<?php echo esc_attr($options['side_image'] ?? ''); ?>" />
                        <button type="button" class="button twg-upload">Upload</button>
                    </td>
                </tr>

                <tr>
                    <th>Login Button Color</th>
                    <td>
                        <input type="text" class="twg-color" name="twg_login_design_settings[button_color]" value="<?php echo esc_attr($options['button_color'] ?? '#FFCC1A'); ?>">
                    </td>
                </tr>

            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

add_action('admin_enqueue_scripts', 'twg_login_design_assets');

function twg_login_design_assets($hook) {
    if ($hook !== 'twg-tools_page_twg-login-design') {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script(
        'twg-login-design-js',
        plugin_dir_url(__FILE__) . '../assets/login-design.js',
        ['jquery', 'wp-color-picker'],
        null,
        true
    );
}

add_action('login_head', 'twg_custom_login_css');

function twg_custom_login_css() {

    $o = get_option('twg_login_design_settings');

    if (!$o) {
        return;
    }

    $logo        = esc_url($o['logo'] ?? '');
    $logo_w      = intval($o['logo_width'] ?? 242);
    $logo_h      = intval($o['logo_height'] ?? 80);
    $bg_color    = esc_attr($o['bg_color'] ?? '#fff');
    $side_image  = esc_url($o['side_image'] ?? '');
    $btn_color   = esc_attr($o['button_color'] ?? '#FFCC1A');
    ?>
    <style>
        body.login {
            background-color: <?php echo $bg_color; ?>;
            width: 50vw;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        <?php if ($side_image): ?>
        body.login:after {
            content: "";
            width: 50vw;
            height: 100%;
            position: absolute;
            right: -50vw;
            top: 0;
            background-image: url('<?php echo $side_image; ?>');
            background-size: cover;
            background-position: center;
        }
        <?php endif; ?>

        body.login #login h1 a {
            background-image: url('<?php echo $logo; ?>');
            width: <?php echo $logo_w; ?>px;
            height: <?php echo $logo_h; ?>px;
            background-size: contain;
        }

        body.login form input[type="submit"] {
            background-color: <?php echo $btn_color; ?>;
            border-color: <?php echo $btn_color; ?>;
        }

        @media (max-width: 767px) {
            body.login {
                width: 100%;
            }
            body.login:after {
                display: none;
            }
        }
    </style>
    <?php
}