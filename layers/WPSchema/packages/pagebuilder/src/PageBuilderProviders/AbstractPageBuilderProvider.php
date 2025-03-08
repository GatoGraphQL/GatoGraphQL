<?php

declare(strict_types=1);

namespace PoPWPSchema\PageBuilder\PageBuilderProviders;

use PoP\Root\Services\AbstractBasicService;
use PoP\Root\Services\ActivableServiceTrait;

abstract class AbstractPageBuilderProvider extends AbstractBasicService implements PageBuilderProviderInterface
{
    use ActivableServiceTrait;
}
