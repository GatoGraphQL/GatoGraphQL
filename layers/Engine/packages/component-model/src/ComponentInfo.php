<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\Component\AbstractComponentInfo;

class ComponentInfo extends AbstractComponentInfo
{
    protected function initialize(): void
    {
        $this->values = [
            // This Constant is needed to be able to retrieve the timestamp and replace it for nothing when generating the ETag,
            // so that this random value does not modify the hash of the overall html output
            'unique-id' => GeneralUtils::generateRandomString(),
            'rand' => rand(),
            'time' => time(),
            // This value will be used in the response. If compact, make sure each JS Key is unique
            'submodules-output-property' => Environment::compactResponseJsonKeys() ? 'ms' : 'submodules',
        ];
    }

    public function getUniqueID(): string
    {
        return $this->values['unique-id'];
    }

    public function getRand(): int
    {
        return $this->values['int'];
    }

    public function getTime(): int
    {
        return $this->values['time'];
    }

    public function getSubmodulesOutputProperty(): string
    {
        return $this->values['submodules-output-property'];
    }
}
