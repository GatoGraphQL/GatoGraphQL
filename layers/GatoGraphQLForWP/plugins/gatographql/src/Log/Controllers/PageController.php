<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Log\Controllers;

use GatoGraphQL\GatoGraphQL\Services\MenuPages\AbstractPluginMenuPage;

abstract class PageController extends AbstractPluginMenuPage
{
	abstract public function get_query_params( array $param_keys = array() ): array;
    abstract public function get_logs_tab_url(): string;
}
