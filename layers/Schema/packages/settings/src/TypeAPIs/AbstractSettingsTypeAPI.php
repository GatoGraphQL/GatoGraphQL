<?php

declare(strict_types=1);

namespace PoPSchema\Settings\TypeAPIs;

use PoPSchema\Settings\TypeAPIs\SettingsTypeAPIInterface;

abstract class AbstractSettingsTypeAPI implements SettingsTypeAPIInterface
{
    final public function getOption(string $name): mixed
    {
        return $this->doGetOption($name);
    }

    abstract protected function doGetOption(string $name): mixed;
}
