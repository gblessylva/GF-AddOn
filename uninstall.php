<?php
/**
 * Uninstall.php.
 *
 * @since 0.3.1
 *
 * @package gf-helper
 */

namespace SHC\GF_Helper;

defined( 'WP_UNINSTALL_PLUGIN' ) || die;

require __DIR__ . '/vendor/autoload.php';

Plugin::uninstall();
