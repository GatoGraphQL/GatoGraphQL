<?php

declare(strict_types=1);

namespace PoPSchema\Settings\TypeAPIs;

use Symfony\Contracts\Service\Attribute\Required;
use PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface;
use PoPSchema\Settings\ComponentConfiguration;

abstract class AbstractSettingsTypeAPI implements SettingsTypeAPIInterface
{
    protected AllowOrDenySettingsServiceInterface $allowOrDenySettingsService;

    #[Required]
    public function autowireAbstractSettingsTypeAPI(AllowOrDenySettingsServiceInterface $allowOrDenySettingsService): void
    {
        $this->allowOrDenySettingsService = $allowOrDenySettingsService;
    }

    final public function getOption(string $name): mixed
    {
        /**
         * Check if the allow/denylist validation fails
         * Compare for full match or regex
         */
        $settingsEntries = ComponentConfiguration::getSettingsEntries();
        $settingsBehavior = ComponentConfiguration::getSettingsBehavior();
        if (!$this->allowOrDenySettingsService->isEntryAllowed($name, $settingsEntries, $settingsBehavior)) {
            return null;
        }
        return $this->doGetOption($name);
    }

    abstract protected function doGetOption(string $name): mixed;
}
