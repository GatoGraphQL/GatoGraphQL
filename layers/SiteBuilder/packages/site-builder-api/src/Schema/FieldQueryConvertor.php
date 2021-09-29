<?php

declare(strict_types=1);

namespace PoP\SiteBuilderAPI\Schema;

use PoP\API\Schema\FieldQueryConvertor as UpstreamFieldQueryConvertor;
use PoP\ConfigurationComponentModel\Constants\Params;

class FieldQueryConvertor extends UpstreamFieldQueryConvertor
{
    protected function getForbiddenFragmentNames(): array
    {
        return array_merge(
            parent::getForbiddenFragmentNames(),
            [
                Params::STRATUM,
            ]
        );
    }
}
