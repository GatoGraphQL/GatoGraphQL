<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Services;

use PoP\Hooks\Services\WithHooksAPIServiceTrait;
use PoP\Root\Services\WithInstanceManagerServiceTrait;
use PoP\Translation\Services\WithTranslationAPIServiceTrait;

trait BasicServiceTrait
{
    use WithInstanceManagerServiceTrait;
    use WithTranslationAPIServiceTrait;
    use WithHooksAPIServiceTrait;
}
