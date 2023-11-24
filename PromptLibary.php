<?php

/**
 * Plugin Name: Windesheim Prompt Libary
 * Plugin URI: https://github.com/Windesheim-AI-App/WINsight
 * GitHub Plugin URI: https://github.com/Windesheim-AI-App/WINsight
 * Description: Windesheim Prompt Libary 
 * Author: Windesheim
 * Author URI: https://windesheim.tech/
 * Version: 1.0.1
 * Text Domain: windesheim-prompt-libary
 * Requires at least: 6.2
 * Tested up to: 6.4
 * Requires PHP: 7.1
 * License: GPL-3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package  Windesheim Prompt Libary
 * @category Core
 * @author   Windesheim
 * @version  1.0.1
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

final class WindesheimPromptLibary
{
    /**
     * Instance of the main WPGatsby class
     */
    private static WindesheimPromptLibary|null $instance = null;

    /**
     * Returns instance of the main WPGatsby class
     */
    public static function instance(): WindesheimPromptLibary
    {
        if (self::$instance) {
            return self::$instance;
        }

        self::$instance = new WindesheimPromptLibary();
        self::$instance->setup_constants();
        self::$instance->include();
        self::$instance->init();

        return self::$instance;
    }

    private function setup_constants(): void
    {
        if (!defined('WindesheimPromptLibary_PLUGIN_DIR')) {
            define('WindesheimPromptLibary_PLUGIN_DIR', plugin_dir_path(__FILE__));
        }

        if (!defined('WindesheimPromptLibary_PLUGIN_URL')) {
            define('WindesheimPromptLibary_PLUGIN_URL', plugin_dir_url(__FILE__));
        }

        if (!defined('WindesheimPromptLibary_PLUGIN_VERSION')) {
            define('WindesheimPromptLibary_PLUGIN_VERSION', '1.0.1');
        }

        if (!defined('WindesheimPromptLibary_PLUGIN_FILE')) {
            define('WindesheimPromptLibary_PLUGIN_FILE', __FILE__);
        }

        if (!defined('WindesheimPromptLibary_PLUGIN_BASENAME')) {
            define('WindesheimPromptLibary_PLUGIN_BASENAME', plugin_basename(__FILE__));
        }
    }

    public function include(): void
    {
        require_once WindesheimPromptLibary_PLUGIN_DIR . 'includes/index.php';
        require_once WindesheimPromptLibary_PLUGIN_DIR . 'pages/index.php';
        require_once WindesheimPromptLibary_PLUGIN_DIR . 'utils/index.php';
    }

    /**
     * Initialize plugin functionality
     */
    public function init(): void
    {
        register_activation_hook(__FILE__, array('WinPL_Activator', 'activate'));
        register_deactivation_hook(__FILE__, array('WinPL_Deactivator', 'deactivate'));
        register_uninstall_hook(__FILE__, array('WinPL_Uninstall', 'uninstall'));

        new WinPL_Endpoints();
    }
}

if (!function_exists('windesheim_prompt_libary')) {
    /**
     * Returns instance of the main WingAI class
     *
     * @return WindesheimPromptLibary
     * @throws Exception
     */
    function windesheim_prompt_libary()
    {
        return WindesheimPromptLibary::instance();
    }
}

windesheim_prompt_libary();


function winpl_enqueue_admin_scripts()
{
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');

    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-sortable');
}

add_action('admin_enqueue_scripts', 'winpl_enqueue_admin_scripts');
