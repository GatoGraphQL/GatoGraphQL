<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\Module\AbstractModuleInfo;

class ModuleInfo extends AbstractModuleInfo
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
            'subcomponents-output-property' => Environment::compactResponseJsonKeys() ? 'sc' : 'subcomponents',
        ];
    }

    public function getUniqueID(): string
    {
        return $this->values['unique-id'];
    }

    public function getRand(): int
    {
        return $this->values['rand'];
    }

    public function getTime(): int
    {
        return $this->values['time'];
    }

    public function getSubcomponentsOutputProperty(): string
    {
        return $this->values['subcomponents-output-property'];
    }
}
