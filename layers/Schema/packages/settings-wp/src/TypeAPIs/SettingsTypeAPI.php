<?php

declare(strict_types=1);

namespace PoPSchema\SettingsWP\TypeAPIs;

use PoPSchema\Settings\TypeAPIs\SettingsTypeAPIInterface;

class SettingsTypeAPI implements SettingsTypeAPIInterface
{
    public function getOption(string $name): mixed
    {
        return \get_option($name);
    }
}
