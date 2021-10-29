<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Services;

use PoP\Hooks\Services\WithHooksAPIServiceTrait;

trait BasicServiceTrait
{
    use WithInstanceManagerServiceTrait;
    use WithTranslationAPIServiceTrait;
    use WithHooksAPIServiceTrait;
}
