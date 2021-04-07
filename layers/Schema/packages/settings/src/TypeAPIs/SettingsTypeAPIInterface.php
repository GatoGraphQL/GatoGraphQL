<?php

declare(strict_types=1);

namespace PoPSchema\Settings\TypeAPIs;

interface SettingsTypeAPIInterface
{
    public function getOption(string $name): mixed;
}
