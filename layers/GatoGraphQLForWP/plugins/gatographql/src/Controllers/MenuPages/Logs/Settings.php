<?php
declare( strict_types=1 );

namespace GatoGraphQL\GatoGraphQL\Controllers\MenuPages\Logs;

use GatoGraphQL\GatoGraphQL\Controllers\Internal\Utilities\FilesystemUtil;
use Exception;
use GatoGraphQL\GatoGraphQL\PluginEnvironment;

class Settings {

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
}
