<?php

declare(strict_types=1);

namespace PoPSchema\Settings\TypeAPIs;

use InvalidArgumentException;

interface SettingsTypeAPIInterface
{
    /**
     * @throws InvalidArgumentException When the option does not exist, or is not in the allowlist
     */
    public function getOption(string $name): mixed;
}
