<?php

declare(strict_types=1);

namespace PoP\Root\Routing;

use PoP\Root\App;
use PoP\Root\Services\BasicServiceTrait;

class RoutingHelperService implements RoutingHelperServiceInterface
{
    use BasicServiceTrait;

    public function getRequestURI(): ?string
    {
        if (!App::isHTTPRequest()) {
            return null;
        }

        // Allow to remove the language information from qTranslate (https://domain.com/en/...)
        return App::applyFilters(
            HookNames::REQUEST_URI,
            App::server('REQUEST_URI')
        );
    }

    public function getRequestURIPath(): ?string
    {
        if (!App::isHTTPRequest()) {
            return null;
        }

        $route = $this->getRequestURI();
        $params_pos = strpos($route, '?');
        if ($params_pos !== false) {
            $route = substr($route, 0, $params_pos);
        }
        return trim($route, '/');
    }
}
