<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPosts\Module;
use PoPCMSSchema\CustomPosts\ModuleConfiguration;
use PoP\ComponentModel\App;

trait CustomPostObjectTypeResolverPickerTrait
{
    abstract public function getCustomPostType(): string;

    public function isServiceEnabled(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $customPostTypes = $moduleConfiguration->getGenericCustomPostTypes();
        return in_array($this->getCustomPostType(), $customPostTypes);
    }
}
