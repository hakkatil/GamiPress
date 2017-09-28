<?php
/**
 * System Info Tool
 *
 * @package     GamiPress\Admin\Tools\System_Info
 * @since       1.1.7
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Register System Info Tool meta boxes
 *
 * @since  1.1.7
 *
 * @param array $meta_boxes
 *
 * @return array
 */
function gamipress_system_info_tool_meta_boxes( $meta_boxes ) {

    global $wpdb;

    $meta_boxes['server-info'] = array(
        'title' => __( 'Server Info', 'gamipress' ),
        'classes' => 'gamipress-list-table',
        'fields' => apply_filters( 'gamipress_server_info_tool_fields', array(
            'hosting_provider' => array(
                'name' => __( 'Hosting Provider', 'gamipress' ),
                'type' => 'display',
                'value' => gamipress_get_hosting_provider(),
            ),
            'php_version' => array(
                'name' => __( 'PHP Version', 'gamipress' ),
                'type' => 'display',
                'value' => PHP_VERSION,
            ),
            'db_version' => array(
                'name' => __( 'MySQL Version', 'gamipress' ),
                'type' => 'display',
                'value' => $wpdb->db_version(),
            ),
            'server_software' => array(
                'name' => __( 'Webserver Info', 'gamipress' ),
                'type' => 'display',
                'value' => $_SERVER['SERVER_SOFTWARE'],
            ),
            'php_title' => array(
                'name' => __( 'PHP Configuration', 'gamipress' ),
                'type' => 'title',
            ),
            'php_memory_limit' => array(
                'name' => __( 'Memory Limit', 'gamipress' ),
                'type' => 'display',
                'value' => ini_get( 'memory_limit' ),
            ),
            'php_max_execution_time' => array(
                'name' => __( 'Time Limit', 'gamipress' ),
                'type' => 'display',
                'value' => ini_get( 'max_execution_time' ),
            ),
            'php_upload_max_filesize' => array(
                'name' => __( 'Upload Max Size', 'gamipress' ),
                'type' => 'display',
                'value' => ini_get( 'upload_max_filesize' ),
            ),
            'php_post_max_size' => array(
                'name' => __( 'Post Max Size', 'gamipress' ),
                'type' => 'display',
                'value' => ini_get( 'post_max_size' ),
            ),
            'php_max_input_vars' => array(
                'name' => __( 'Max Input Vars', 'gamipress' ),
                'type' => 'display',
                'value' => ini_get( 'max_input_vars' ),
            ),
            'php_display_errors' => array(
                'name' => __( 'Display Errors', 'gamipress' ),
                'type' => 'display',
                'value' => ( ini_get( 'display_errors' ) ? 'On (' . ini_get( 'display_errors' ) . ')' : 'N/A' ),
            ),
        ) )
    );

    $locale = get_locale();

    // Get WordPress Theme info
    $theme_data   = wp_get_theme();
    $theme        = $theme_data->Name . ' (' . $theme_data->Version . ')';
    $parent_theme = $theme_data->Template;

    if ( ! empty( $parent_theme ) ) {
        $parent_theme_data = wp_get_theme( $parent_theme );
        $parent_theme      = $parent_theme_data->Name . ' (' . $parent_theme_data->Version . ')';
    }

    // Retrieve current plugin information
    if( ! function_exists( 'get_plugins' ) ) {
        include ABSPATH . '/wp-admin/includes/plugin.php';
    }

    $plugins = get_plugins();
    $active_plugins = get_option( 'active_plugins', array() );
    $active_plugins_output = '';

    foreach ( $plugins as $plugin_path => $plugin ) {
        // If the plugin isn't active, don't show it.
        if ( ! in_array( $plugin_path, $active_plugins ) )
            continue;

        $active_plugins_output .= $plugin['Name'] . ' (' . $plugin['Version'] . ')' . '<br>';
    }

    $meta_boxes['wordpress-info'] = array(
        'title' => __( 'WordPress Info', 'gamipress' ),
        'classes' => 'gamipress-list-table',
        'fields' => apply_filters( 'gamipress_wordpress_info_tool_fields', array(
            'site_url' => array(
                'name' => __( 'Site URL', 'gamipress' ),
                'type' => 'display',
                'value' => site_url(),
            ),
            'home_url' => array(
                'name' => __( 'Home URL', 'gamipress' ),
                'type' => 'display',
                'value' => home_url(),
            ),
            'multisite' => array(
                'name' => __( 'Multisite', 'gamipress' ),
                'type' => 'display',
                'value' => ( is_multisite() ? 'Yes' : 'No' ),
            ),
            'wp_version' => array(
                'name' => __( 'Version', 'gamipress' ),
                'type' => 'display',
                'value' => get_bloginfo( 'version' ),
            ),
            'wp_locale' => array(
                'name' => __( 'Language', 'gamipress' ),
                'type' => 'display',
                'value' => ( ! empty( $locale ) ? $locale : 'en_US' ),
            ),
            'wp_permalink' => array(
                'name' => __( 'Permalink Structure', 'gamipress' ),
                'type' => 'display',
                'value' => ( get_option( 'permalink_structure' ) ? get_option( 'permalink_structure' ) : 'Default' ),
            ),
            'wp_abspath' => array(
                'name' => __( 'Absolute Path', 'gamipress' ),
                'type' => 'display',
                'value' => ABSPATH,
            ),
            'wp_debug' => array(
                'name' => __( 'Debug', 'gamipress' ),
                'type' => 'display',
                'value' => ( defined( 'WP_DEBUG' ) ? WP_DEBUG ? 'Enabled' : 'Disabled' : 'Not set' ),
            ),
            'wp_memory_limit' => array(
                'name' => __( 'Memory Limit', 'gamipress' ),
                'type' => 'display',
                'value' => WP_MEMORY_LIMIT,
            ),
            'wp_table_prefix' => array(
                'name' => __( 'Table Prefix:', 'gamipress' ),
                'type' => 'display',
                'value' => $wpdb->prefix,
            ),
            'wp_theme' => array(
                'name' => __( 'Active Theme', 'gamipress' ),
                'type' => 'display',
                'value' => $theme,
            ),
            'wp_parent_theme' => array(
                'name' => __( 'Parent Theme', 'gamipress' ),
                'type' => 'display',
                'value' => $parent_theme,
            ),
            'wp_active_plugins' => array(
                'name' => __( 'Active Plugins', 'gamipress' ),
                'type' => 'display',
                'value' => $active_plugins_output,
            ),
        ) )
    );

    $achievement_types = gamipress_get_achievement_types();
    $achievement_types_output = '';

    foreach ( $achievement_types as $achievement_type_slug => $achievement_type ) {
        if ( in_array( $achievement_type_slug, gamipress_get_requirement_types_slugs() ) )
            continue;

        $achievement_types_output .= $achievement_type['singular_name'] . ' - ' . $achievement_type['plural_name'] . ' - ' . $achievement_type_slug . ' (#' . $achievement_type['ID'] . ')' . '<br>';
    }

    $points_types = gamipress_get_points_types();
    $points_types_output = '';

    foreach ( $points_types as $points_type_slug => $points_type ) {

        $points_types_output .= $points_type['singular_name'] . ' - ' . $points_type['plural_name'] . ' - ' . $points_type_slug . ' (#' . $points_type['ID'] . ')' . '<br>';

    }

    if( GamiPress()->settings === null ) {
        GamiPress()->settings = get_option( 'gamipress_settings' );
    }

    $gamipress_settings_output = '';

    foreach ( GamiPress()->settings as $setting_key => $setting_value ) {

        if( is_array( $setting_value ) ) {
            $setting_value = json_encode( $setting_value );
        }

        $gamipress_settings_output .= $setting_key . ': ' . $setting_value . '<br>';

    }

    $meta_boxes['gamipress-info'] = array(
        'title' => __( 'GamiPress Info', 'gamipress' ),
        'classes' => 'gamipress-list-table',
        'fields' => apply_filters( 'gamipress_gamipress_info_tool_fields', array(
            'achievement_types' => array(
                'name' => __( 'Achievement Types', 'gamipress' ),
                'type' => 'display',
                'value' => $achievement_types_output,
            ),
            'points_types' => array(
                'name' => __( 'Points Types', 'gamipress' ),
                'type' => 'display',
                'value' => $points_types_output,
            ),
            'gamipress_settings' => array(
                'name' => __( 'Settings', 'gamipress' ),
                'type' => 'display',
                'value' => $gamipress_settings_output,
            ),
        ) )
    );

    return $meta_boxes;

}
add_filter( 'gamipress_tools_system_meta_boxes', 'gamipress_system_info_tool_meta_boxes' );

/**
 * Register Download System Info Tool meta boxes
 *
 * @since  1.1.7
 *
 * @param array $meta_boxes
 *
 * @return array
 */
function gamipress_download_system_info_tool_meta_boxes( $meta_boxes ) {

    $meta_boxes['download-system-info'] = array(
        'title' => __( 'Download System Info', 'gamipress' ),
        'fields' => apply_filters( 'gamipress_download_system_info_tool_fields', array(
            'download_system_info' => array(
                'label' => __( 'Download System Info File', 'gamipress' ),
                'type' => 'button',
                'button' => 'primary',
                'action' => 'download_system_info',
            ),
        ) )
    );

    return $meta_boxes;

}
add_filter( 'gamipress_tools_system_meta_boxes', 'gamipress_download_system_info_tool_meta_boxes', 9999 );

/**
 * Return the hosting provider this site is using if possible
 *
 * Taken from Easy Digital Downloads
 *
 * @since 1.1.5
 *
 * @return mixed string $host if detected, false otherwise
 */
function gamipress_get_hosting_provider() {
    $host = false;

    if( defined( 'WPE_APIKEY' ) ) {
        $host = 'WP Engine';
    } elseif( defined( 'PAGELYBIN' ) ) {
        $host = 'Pagely';
    } elseif( DB_HOST == 'localhost:/tmp/mysql5.sock' ) {
        $host = 'ICDSoft';
    } elseif( DB_HOST == 'mysqlv5' ) {
        $host = 'NetworkSolutions';
    } elseif( strpos( DB_HOST, 'ipagemysql.com' ) !== false ) {
        $host = 'iPage';
    } elseif( strpos( DB_HOST, 'ipowermysql.com' ) !== false ) {
        $host = 'IPower';
    } elseif( strpos( DB_HOST, '.gridserver.com' ) !== false ) {
        $host = 'MediaTemple Grid';
    } elseif( strpos( DB_HOST, '.pair.com' ) !== false ) {
        $host = 'pair Networks';
    } elseif( strpos( DB_HOST, '.stabletransit.com' ) !== false ) {
        $host = 'Rackspace Cloud';
    } elseif( strpos( DB_HOST, '.sysfix.eu' ) !== false ) {
        $host = 'SysFix.eu Power Hosting';
    } elseif( strpos( $_SERVER['SERVER_NAME'], 'Flywheel' ) !== false ) {
        $host = 'Flywheel';
    } else {
        // Adding a general fallback for data gathering
        $host = 'DBH: ' . DB_HOST . ', SRV: ' . $_SERVER['SERVER_NAME'];
    }

    return $host;
}

/**
 * Download System Info action
 *
 * @since 1.1.5
 */
function gamipress_action_download_system_info() {

    if( ! current_user_can( gamipress_get_manager_capability() ) ) {
        return;
    }

    nocache_headers();

    header( 'Content-Type: text/plain' );
    header( 'Content-Disposition: attachment; filename="gamipress-system-info.txt"' );

    $meta_boxes = array();
    $output = '';

    $meta_boxes = gamipress_system_info_tool_meta_boxes( $meta_boxes );

    $output .= 'GAMIPRESS SYSTEM INFO START';

    foreach( $meta_boxes as $meta_box_id => $meta_box ) {

        if( $meta_box_id === 'download-system-info' ) {
            continue;
        }
        $output .= "\n\n" . '---------------------------------------------' . "\n";
        $output .= $meta_box['title'] . "\n";
        $output .= '---------------------------------------------' . "\n\n";

        if( isset( $meta_box['fields'] ) && ! empty( $meta_box['fields'] ) ) {

            // Loop meta box fields
            foreach( $meta_box['fields'] as $field_id => $field ) {

                if( $field['type'] === 'title' ) {
                    $output .= "\n----- " . $field['name'] . " -----\n\n";
                } else if( $field['type'] === 'display' ) {
                    $output .= str_pad( $field['name'] . ':', 30 ) . $field['value'] . "\n";
                }

            }

        }
    }

    $output .= "\n" . 'GAMIPRESS SYSTEM INFO END';

    $output = str_replace( '<br>', "\n", $output );

    echo $output;
    die();

}
add_action( 'gamipress_action_post_download_system_info', 'gamipress_action_download_system_info' );