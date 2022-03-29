<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Plugin Name: Gravity Forms Helper
 * Description: Misc Gravity Forms Settings/etc Improvements
 * Author: Sylvanus Godbless
 * Author URI: https://www.linkedin.com/in/gblessylva
 * Version: 0.3.1
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package gf-helper
 */

namespace SHC\GF_Helper;

use GFAddOn;
use GFForms;

defined( 'ABSPATH' ) || die;

require __DIR__ . '/vendor/autoload.php';

/**
 * Our main plugin class.
 *
 * @since 0.1.0
 */
class Plugin extends Singleton {
	/**
	 * Our version number.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	const VERSION = '0.3.1';

	/**
	 * The full path to the main plugin file.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	const FILE = __FILE__;

	/**
	 * Add hooks.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	protected function add_hooks() {
		parent::add_hooks();

		add_action( 'gform_loaded', array( $this, 'load_gfaddon' ) );

		return;
	}

	/**
	 * Load our Gravity Forms AddOn.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 *
	 * @action gform_loaded
	 */
	public function load_gfaddon() {
		GFForms::include_addon_framework();
		GFAddOn::register( __NAMESPACE__ . '\AddOn' );

		return;
	}
}

// Instantiate ourself.
Plugin::get_instance();
