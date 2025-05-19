<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Log\Controllers;

interface PageController
{
	public function get_query_params( array $param_keys = array() ): array;
    public function get_logs_tab_url(): string;
}
