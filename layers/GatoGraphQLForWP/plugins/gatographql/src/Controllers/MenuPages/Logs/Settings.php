<?php
declare( strict_types=1 );

namespace GatoGraphQL\GatoGraphQL\Controllers\MenuPages\Logs;

use Automattic\Jetpack\Constants;
use GatoGraphQL\GatoGraphQL\Controllers\Internal\Utilities\FilesystemUtil;
use Exception;
use GatoGraphQL\GatoGraphQL\Controllers\MenuPages\Logs\LogHandlerFileV2;
use GatoGraphQL\GatoGraphQL\PluginEnvironment;
use WC_Log_Levels;

/**
 * Settings class.
 */
class Settings {

	/**
	 * Default values for logging settings.
	 *
	 * @const array
	 */
	private const DEFAULTS = array(
		'logging_enabled'       => true,
		'default_handler'       => LogHandlerFileV2::class,
		'retention_period_days' => 30,
		'level_threshold'       => 'none',
	);

	/**
	 * The prefix for settings keys used in the options table.
	 *
	 * @const string
	 */
	private const PREFIX = 'woocommerce_logs_';

	/**
	 * Get the directory for storing log files.
	 *
	 * The `wp_upload_dir` function takes into account the possibility of multisite, and handles changing
	 * the directory if the context is switched to a different site in the network mid-request.
	 *
	 * @param bool $create_dir Optional. True to attempt to create the log directory if it doesn't exist. Default true.
	 *
	 * @return string The full directory path, with trailing slash.
	 */
	public static function get_log_directory( bool $create_dir = true ): string {
		$dir = PluginEnvironment::getLogsDir();
		$dir = trailingslashit( $dir );

		if ( true === $create_dir ) {
			$realpath = realpath( $dir );
			if ( false === $realpath ) {
				$result = wp_mkdir_p( $dir );

				if ( true === $result ) {
					// Create infrastructure to prevent listing contents of the logs directory.
					try {
						$filesystem = FilesystemUtil::get_wp_filesystem();
						$filesystem->put_contents( $dir . '.htaccess', 'deny from all' );
						$filesystem->put_contents( $dir . 'index.html', '' );
					} catch ( Exception $exception ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch
						// Creation failed.
					}
				}
			}
		}

		return $dir;
	}

	/**
	 * The definition for the default_handler setting.
	 *
	 * @return array
	 */
	private function get_default_handler_setting_definition(): array {
		$handler_options = array(
			LogHandlerFileV2::class  => __( 'File system (default)', 'woocommerce' ),
		);

		/**
		 * Filter the list of logging handlers that can be set as the default handler.
		 *
		 * @param array $handler_options An associative array of class_name => description.
		 *
		 * @since 8.6.0
		 */
		$handler_options = apply_filters( 'woocommerce_logger_handler_options', $handler_options );

		$current_value = $this->get_default_handler();
		if ( ! array_key_exists( $current_value, $handler_options ) ) {
			$handler_options[ $current_value ] = $current_value;
		}

		$desc = array();

		$desc[] = __( 'Note that if this setting is changed, any log entries that have already been recorded will remain stored in their current location, but will not migrate.', 'woocommerce' );

		$hardcoded = ! is_null( Constants::get_constant( 'WC_LOG_HANDLER' ) );
		if ( $hardcoded ) {
			$desc[] = sprintf(
				// translators: %s is the name of a code variable.
				__( 'This setting cannot be changed here because it is defined in the %s constant.', 'woocommerce' ),
				'<code>WC_LOG_HANDLER</code>'
			);
		}

		return array(
			'title'       => __( 'Log storage', 'woocommerce' ),
			'desc_tip'    => __( 'This determines where log entries are saved.', 'woocommerce' ),
			'id'          => self::PREFIX . 'default_handler',
			'type'        => 'radio',
			'value'       => $current_value,
			'default'     => self::DEFAULTS['default_handler'],
			'autoload'    => false,
			'options'     => $handler_options,
			'disabled'    => $hardcoded ? array_keys( $handler_options ) : array(),
			'desc'        => implode( '<br><br>', $desc ),
			'desc_at_end' => true,
		);
	}

	/**
	 * The definition for the retention_period_days setting.
	 *
	 * @return array
	 */
	private function get_retention_period_days_setting_definition(): array {
		$custom_attributes = array(
			'min'  => 1,
			'step' => 1,
		);

		$desc = array();

		$hardcoded = has_filter( 'woocommerce_logger_days_to_retain_logs' );
		if ( $hardcoded ) {
			$custom_attributes['disabled'] = 'true';

			$desc[] = sprintf(
				// translators: %s is the name of a filter hook.
				__( 'This setting cannot be changed here because it is being set by a filter on the %s hook.', 'woocommerce' ),
				'<code>woocommerce_logger_days_to_retain_logs</code>'
			);
		}

		$file_delete_has_filter = LogHandlerFileV2::class === $this->get_default_handler() && has_filter( 'woocommerce_logger_delete_expired_file' );
		if ( $file_delete_has_filter ) {
			$desc[] = sprintf(
				// translators: %s is the name of a filter hook.
				__( 'The %s hook has a filter set, so some log files may have different retention settings.', 'woocommerce' ),
				'<code>woocommerce_logger_delete_expired_file</code>'
			);
		}

		return array(
			'title'             => __( 'Retention period', 'woocommerce' ),
			'desc_tip'          => __( 'This sets how many days log entries will be kept before being auto-deleted.', 'woocommerce' ),
			'id'                => self::PREFIX . 'retention_period_days',
			'type'              => 'number',
			'value'             => $this->get_retention_period(),
			'default'           => self::DEFAULTS['retention_period_days'],
			'autoload'          => false,
			'custom_attributes' => $custom_attributes,
			'css'               => 'width:70px;',
			'row_class'         => 'logs-retention-period-days',
			'suffix'            => sprintf(
				' %s',
				__( 'days', 'woocommerce' ),
			),
			'desc'              => implode( '<br><br>', $desc ),
		);
	}

	/**
	 * The definition for the level_threshold setting.
	 *
	 * @return array
	 */
	private function get_level_threshold_setting_definition(): array {
		$hardcoded = ! is_null( Constants::get_constant( 'WC_LOG_THRESHOLD' ) );
		$desc      = '';
		if ( $hardcoded ) {
			$desc = sprintf(
				// translators: %1$s is the name of a code variable. %2$s is the name of a file.
				__( 'This setting cannot be changed here because it is defined in the %1$s constant, probably in your %2$s file.', 'woocommerce' ),
				'<code>WC_LOG_THRESHOLD</code>',
				'<b>wp-config.php</b>'
			);
		}

		$labels         = WC_Log_Levels::get_all_level_labels();
		$labels['none'] = __( 'None', 'woocommerce' );

		$custom_attributes = array();
		if ( $hardcoded ) {
			$custom_attributes['disabled'] = 'true';
		}

		return array(
			'title'             => __( 'Level threshold', 'woocommerce' ),
			'desc_tip'          => __( 'This sets the minimum severity level of logs that will be stored. Lower severity levels will be ignored. "None" means all logs will be stored.', 'woocommerce' ),
			'id'                => self::PREFIX . 'level_threshold',
			'type'              => 'select',
			'value'             => $this->get_level_threshold(),
			'default'           => self::DEFAULTS['level_threshold'],
			'autoload'          => false,
			'options'           => $labels,
			'custom_attributes' => $custom_attributes,
			'css'               => 'width:auto;',
			'desc'              => $desc,
		);
	}
}
