<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Log\Controllers;

/**
 * All code in this directory is based on WooCommerce.
 *
 * @see https://github.com/woocommerce/woocommerce/blob/9.8.5/plugins/woocommerce/src/Internal/Admin/Logging/PageController.php
 */
interface PageController
{
	public function get_query_params( array $param_keys = array() ): array;
    public function get_logs_tab_url(): string;
}
