<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Log\Controllers\FileHandler;

use Exception;
use GatoGraphQL\GatoGraphQL\Log\Controllers\Internal\Utilities\FilesystemUtil;
use GatoGraphQL\GatoGraphQL\PluginApp;
use WP_Error;

/**
 * @phpcs:ignoreFile
 * 
 * FileExport class.
 */
class FileExporter
{
    /**
     * The number of bytes per read while streaming the file.
     *
     * @const int
     */
    private const CHUNK_SIZE = 4 * KB_IN_BYTES;

    /**
     * The absolute path of the file.
     *
     * @var string
     */
    private $path;

    /**
     * A name of the file to send to the browser rather than the filename part of the path.
     *
     * @var string
     */
    private $alternate_filename;

    /**
     * Class FileExporter.
     *
     * @param string $path               The absolute path of the file.
     * @param string $alternate_filename Optional. The name of the file to send to the browser rather than the filename
     *                                   part of the path.
     */
    public function __construct(string $path, string $alternate_filename = '')
    {
        $this->path               = $path;
        $this->alternate_filename = $alternate_filename;
    }

    /**
     * Configure PHP and stream the file to the browser.
     *
     * @return WP_Error|void Only returns something if there is an error.
     */
    public function emit_file()
    {
        try {
            $filesystem  = FilesystemUtil::get_wp_filesystem();
            $is_readable = $filesystem->is_file($this->path) && $filesystem->is_readable($this->path);
        } catch (Exception $exception) {
            $is_readable = false;
        }

        if (! $is_readable) {
            $pluginNamespace = PluginApp::getMainPlugin()->getPluginNamespace();
            return new WP_Error(
                "{$pluginNamespace}_logs_invalid_file",
                __('Could not access file.', 'gatographql')
            );
        }

        // These configuration tweaks are copied from WC_CSV_Exporter::send_headers().
		// phpcs:disable WordPress.PHP.NoSilencedErrors.Discouraged
        if (function_exists('gc_enable')) {
            gc_enable(); // phpcs:ignore PHPCompatibility.FunctionUse.NewFunctions.gc_enableFound
        }
        if (function_exists('apache_setenv')) {
            @apache_setenv('no-gzip', '1'); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_apache_setenv
        }
        @ini_set('zlib.output_compression', 'Off'); // phpcs:ignore WordPress.PHP.IniSet.Risky
        @ini_set('output_buffering', 'Off'); // phpcs:ignore WordPress.PHP.IniSet.Risky
        @ini_set('output_handler', ''); // phpcs:ignore WordPress.PHP.IniSet.Risky
        ignore_user_abort(true);
        @set_time_limit(0);
        @nocache_headers();
		// phpcs:enable WordPress.PHP.NoSilencedErrors.Discouraged

        $this->send_headers();
        $this->send_contents();

        die;
    }

    /**
     * Send HTTP headers at the beginning of a file.
     *
     * Modeled on WC_CSV_Exporter::send_headers().
     *
     * @return void
     */
    private function send_headers(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $this->get_filename());
        header('Pragma: no-cache');
        header('Expires: 0');
    }

    /**
     * Send the contents of the file.
     *
     * @return void
     */
    private function send_contents(): void
    {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen -- No suitable alternative.
        $stream = fopen($this->path, 'rb');

        while (is_resource($stream) && ! feof($stream)) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fread -- No suitable alternative.
            $chunk = fread($stream, self::CHUNK_SIZE);

            if (is_string($chunk)) {
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Outputting to file.
                echo $chunk;
            }
        }

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose -- No suitable alternative.
        fclose($stream);
    }

    /**
     * Get the name of the file that will be sent to the browser.
     *
     * @return string
     */
    private function get_filename(): string
    {
        if ($this->alternate_filename) {
            return $this->alternate_filename;
        }

        return basename($this->path);
    }
}
