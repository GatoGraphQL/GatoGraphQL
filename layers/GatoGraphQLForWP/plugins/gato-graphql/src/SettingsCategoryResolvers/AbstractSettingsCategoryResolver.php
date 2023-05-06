<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\SettingsCategoryResolvers;

use PoP\Root\Exception\ImpossibleToHappenException;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractSettingsCategoryResolver implements SettingsCategoryResolverInterface
{
    use BasicServiceTrait;

    final public function getID(string $settingsCategory): string
    {
        return strtolower(str_replace(
            ['-', '\\'],
            '_',
            $settingsCategory
        ));
    }

    public function getName(string $settingsCategory): string
    {
        throw new ImpossibleToHappenException(
            sprintf(
                $this->__('Unsupported Settings Category \'%s\'', 'gato-graphql'),
                $settingsCategory
            )
        );
    }

    public function getDBOptionName(string $settingsCategory): string
    {
        throw new ImpossibleToHappenException(
            sprintf(
                $this->__('Unsupported Settings Category \'%s\'', 'gato-graphql'),
                $settingsCategory
            )
        );
    }

    /**
     * Please notice: the name of the field must be the same as that
     * of the option name in the DB, or otherwise doing `register_setting`
     * in SettingsMenuPage does not work (for some reason, $values is null).
     *
     * @see layers/GatoGraphQLForWP/plugins/gato-graphql/src/Services/MenuPages/SettingsMenuPage.php
     */
    final public function getOptionsFormName(string $settingsCategory): string
    {
        return $this->getDBOptionName($settingsCategory);
    }

    /**
     * When printing the Settings, not all categories
     * need to submit a form. In particular,
     * "Plugin Management" does not.
     */
    public function addOptionsFormSubmitButton(string $settingsCategory): bool
    {
        return true;
    }
}
