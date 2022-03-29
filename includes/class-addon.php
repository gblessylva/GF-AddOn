<?php
/**
 * HF_Helper_GFAddOn class.
 *
 * @since 0.1.0
 *
 * @package gf-helper
 */

namespace SHC\GF_Helper;

use GFAddOn;

defined( 'ABSPATH' ) || die;

/**
 * Our main GFAddOn class.
 *
 * @since 0.1.0
 * @since 0.3.0 Class name changed to GF_Helper_GFAddOn.
 * @since 0.3.1 Class name changed to AddOn.
 */
class AddOn extends GFAddOn {
	/**
	 * Our static instance.
	 *
	 * @since 0.1.0
	 *
	 * @var GFAddOn
	 */
	protected static $instance = null;

	/**
	 * Get our static instance.
	 *
	 * @since 0.1.0
	 *
	 * @return GFAddOn
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Gets executed before all init functions.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function pre_init() {
		$this->_version                  = PLUGIN::VERSION;
		$this->_min_gravityforms_version = '2.4';
		$this->_path                     = dirname( Plugin::FILE );
		$this->_slug                     = basename( $this->_path );
		$this->_full_path                = Plugin::FILE;
		$this->_title                    = __( 'GF Helper', 'gf-helper' );
		$this->_short_title              = __( 'GF Helper', 'gf-helper' );

		parent::pre_init();

		return;
	}

	/**
	 * Initialize.
	 *
	 * @since 0.3.1
	 *
	 * @return void
	 */
	public function init() {
		load_plugin_textdomain( 'gf-helper', false, basename( $this->_path ) . '/languages' );

		parent::init();
	}

	/**
	 * Initialization for admin.
	 *
	 * @since 0.2.0
	 * @since 0.3.0 'gform_predefined_choices' is now hooked here, rather than in init().
	 *
	 * @return void
	 */
	public function init_admin() {
		add_filter( 'gform_predefined_choices', array( $this, 'predefined_choices' ) );

		parent::init_admin();

		return;
	}

	/**
	 * Add initialization code (i.e. hooks) for the public (customer facing) site.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function init_frontend() {
		parent::init_frontend();

		add_filter( 'gform_confirmation_anchor', array( $this, 'confirmation_anchor' ), 10, 2 );

		return;
	}

	/**
	 * Add scripts to GF's "noconflict OK" list.
	 *
	 * @since 0.1.0
	 *
	 * @param string[] $scripts An array of script handles to be filtered.
	 * @return string[]
	 *
	 * @filter gform_noconflict_scripts
	 */
	public function register_noconflict_scripts( $scripts ) {
		// The "\r\n" separator is as per the HTML5 spec, and is not to be confused with PHP_EOL.
		$_scripts = array_filter( explode( "\r\n", $this->get_plugin_setting( 'gf_helper_noconflict_scripts' ) ) );
		$scripts  = array_merge( $scripts, $_scripts );

		return $scripts;
	}

	/**
	 * Add styles to GF's "noconflict OK" list.
	 *
	 * @since 0.1.0
	 *
	 * @param string[] $styles An array of style handles to be filtered.
	 * @return string[]
	 *
	 * @filter gform_noconflict_styles
	 */
	public function register_noconflict_styles( $styles ) {
		// The "\r\n" separator is as per the HTML5 spec, and is not to be confused with PHP_EOL.
		$_styles = array_filter( explode( "\r\n", $this->get_plugin_setting( 'gf_helper_noconflict_styles' ) ) );
		$styles  = array_merge( $styles, $_styles );

		$styles[] = 'gf-helper_form_editor';

		return $styles;
	}

	/**
	 * Specify the settings fields to be rendered on the form settings page.
	 *
	 * @since 0.1.0
	 *
	 * @param array $form The current form.
	 * @return array
	 */
	public function form_settings_fields( $form ) {
		$global = $this->get_plugin_setting( 'gf_helper_confirmation_anchor' );

		return array(
			array(
				'title'  => $this->get_short_title(),
				'fields' => array(
					array(
						'type'    => 'select',
						'name'    => 'confirmation_anchor',
						'tooltip' => esc_html__( 'Select whether confirmation anchor is enabled or disabled.', 'gf-helper' ),
						'label'   => esc_html__( 'Confirmation Anchor', 'gf-helper' ),
						'choices' => array(
							array(
								'label' => esc_html( 'enabled' === $global ? __( 'Use Global Setting (cuurently Enabled)', 'gf-helper' ) : __( 'Use Global Setting (currently Disabled)', 'gf-helper' ) ),
								'value' => 'global',
							),
							array(
								'label' => esc_html( __( 'Disabled', 'gf-helper' ) ),
								'value' => 'disabled',
							),
							array(
								'label' => esc_html( __( 'Enabled', 'gf-helper' ) ),
								'value' => 'enabled',
							),
						),
					),
				),
			),
		);
	}

	/**
	 * Specify the settings fields to be rendered on the plugin settings page.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function plugin_settings_fields() {
		return array(
			array(
				'title'       => esc_html__( 'No-Conflict Mode', 'gf-helper' ),
				'description' => esc_html__( 'If Gravity Forms No-Conflict Mode is on, certain other plugin/theme scripts and styles won\'t be enqueued...which may cause those plugins/theme to not work correctly.  If you know that such a script/style is safe to enqueue on Gravity Forms admin screens, you can enter their handles here.', 'gf-helper' ),
				'fields'      => array(
					array(
						'type'    => 'textarea',
						'name'    => 'gf_helper_noconflict_scripts',
						'tooltip' => esc_html__( 'Enter one script handle per line.', 'gf-helper' ),
						'label'   => esc_html__( 'Allowed Scripts', 'gf-helper' ),
						'class'   => 'medium',
					),
					array(
						'type'    => 'textarea',
						'name'    => 'gf_helper_noconflict_styles',
						'tooltip' => esc_html__( 'Enter one style handle per line.', 'gf-helper' ),
						'label'   => esc_html__( 'Allowed Styles', 'gf-helper' ),
						'class'   => 'medium',
					),
				),
			),
			array(
				'title'  => esc_html__( 'Misc', 'gf-helper' ),
				'fields' => array(
					array(
						'type'    => 'select',
						'name'    => 'gf_helper_confirmation_anchor',
						'tooltip' => esc_html__( 'Select whether confirmation anchor is enabled or disabled.  This can be overridden for each form.', 'gf-helper' ),
						'label'   => esc_html__( 'Confirmation Anchor', 'gf-helper' ),
						'choices' => array(
							array(
								'label' => esc_html__( 'Disabled', 'gf-helper' ),
								'value' => 'disabled',
							),
							array(
								'label' => esc_html__( 'Enabled', 'gf-helper' ),
								'value' => 'enabled',
							),
						),
					),
				),
			),
		);
	}

	/**
	 * Add our predefined choices for use in the forms editor.
	 *
	 * @since 0.2.0
	 *
	 * @param array $choices An array with the existing predefined choices to be filtered.
	 *                       It is an associative array where the key is the title and the
	 *                       value is an array containing the choices.
	 * @return array An array with predefined choices.
	 */
	public function predefined_choices( $choices ) {
		// Note: we the array concatenation operator (+) in case the existing
		// choices already contain a 'Yes/No' choice.
		$choices =
			array(
				__( 'Yes/No', 'gf-helper' ) => array(
					__( 'Yes', 'gf-helper' ),
					__( 'No', 'gf-helper' ),
				),
			)
			+
			$choices;

		return $choices;
	}

	/**
	 * Filter whether GF should add a "confirmation anchor" to the form HTML.
	 *
	 * @since 0.1.0
	 *
	 * @param bool|int $anchor Indicates how the anchor will behave.  The default is true when AJAX
	 *                         is enabled or for multi-page non-AJAX forms (anchor present), and
	 *                         false for single page non-AJAX forms (no anchor).
	 * @param array    $form   The current form.
	 * @return bool|int
	 *
	 * @filter gform_confirmation_anchor
	 */
	public function confirmation_anchor( $anchor, $form ) {
		$global = $this->get_plugin_setting( 'gf_helper_confirmation_anchor' );
		$value  = $this->get_form_setting( 'confirmation_anchor', $form );

		if ( 'enabled' === $value ) {
			$anchor = true;
		} elseif ( 'disabled' === $value || 'disabled' === $global ) {
			$anchor = false;
		}

		return $anchor;
	}

	/**
	 * Register our styles with Gravity Forms.
	 *
	 * @since 0.3.0
	 *
	 * @return array
	 */
	public function styles() {
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || isset( $_GET['gform_debug'] ) ? '' : '.min'; 
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$styles = array(
			array(
				'handle'  => 'gf-helper_form_editor',
				'src'     => plugins_url( "assets/css/form_editor{$min}.css", Plugin::FILE ),
				'version' => Plugin::VERSION,
				'enqueue' => array(
					array(
						'admin_page' => array(
							'form_editor',
						),
					),
				),
			),
		);

		return array_merge( parent::styles(), $styles );
	}

	/**
	 * Get form setting.
	 *
	 * Returns the form setting specified by the $setting_name parameter.
	 *
	 * @since 0.1.0
	 *
	 * @param string $setting_name Form setting to be returned.
	 * @param array  $form         The form to get the setting from.
	 * @return mixed Returns the specified form setting or null if the setting doesn't exist.
	 *
	 * @todo open a GF ticket to get this added to GFAddOn.
	 */
	protected function get_form_setting( $setting_name, $form ) {
		$settings = $this->get_form_settings( $form );

		return isset( $settings[ $setting_name ] ) ? $settings[ $setting_name ] : null;

	}
}
