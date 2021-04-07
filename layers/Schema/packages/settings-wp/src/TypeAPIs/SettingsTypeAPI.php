<?php

declare(strict_types=1);

namespace PoPSchema\SettingsWP\TypeAPIs;

use PoPSchema\Settings\TypeAPIs\AbstractSettingsTypeAPI;

class SettingsTypeAPI extends AbstractSettingsTypeAPI
{
    protected function doGetOption(string $name): mixed
    {
        return \get_option($name);
    }
}
