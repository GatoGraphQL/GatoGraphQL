<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;

use GatoGraphQL\GatoGraphQL\Services\MenuPages\AbstractSettingsMenuPage;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Misc\GeneralUtils;

abstract class AbstractExecuteActionWithCustomSettingsMenuPage extends AbstractSettingsMenuPage
{
    /**
     * The upstream method will print several <form> tags,
     * for the different settings categories.
     * 
     * Combine them all into a single <form> tag.
     */
    public function print(): void
    {
        ob_start();
        parent::print();
        $content = ob_get_clean();

        $content = str_replace(
            [
                '<form method="post" action="options.php">',
                '</form>',
            ],
            '',
            $content
        );

        $bulkActionOriginURL = App::request('bulk_action_origin_url') ?? App::query('bulk_action_origin_url') ?? '';
        if ($bulkActionOriginURL) {
            $bulkActionOriginURL = rawurldecode($bulkActionOriginURL);
        }

        // Convert the custom bulk action URL to the standard bulk action URL
        $bulkActionOriginURL = GeneralUtils::addQueryArgs(
            [
                'action' => 'gatompl-translate',
                'action2' => 'gatompl-translate',
            ],
            $bulkActionOriginURL
        );

        ?>
        <form method="post" action="<?php echo $bulkActionOriginURL; ?>">
            <?php echo $content; ?>
        </form>
        <?php
    }

    /**
     * Get the settings items which have this target
     *
     * @return array<array<string,mixed>>
     */
    protected function getSettingsItems(): array
    {
        $upstreamSettingsItems = parent::getSettingsItems();
        $settingsItems = [];
        foreach ($upstreamSettingsItems as $settingItem) {
            $settingItem['settings'] = array_values(array_filter(
                $settingItem['settings'] ?? [],
                fn (array $item) => array_intersect(
                    $this->getPossibleTargets(),
                    $item[Properties::ADDITIONAL_TARGETS] ?? []
                ) !== []
            ));
            if ($settingItem['settings'] === []) {
                continue;
            }
            $settingsItems[] = $settingItem;
        }
        return $settingsItems;
    }

    /**
    * @return string[]
    */
    abstract protected function getPossibleTargets(): array;

    /**
     * Get the submit button label for a settings category
     */
    protected function getSubmitButtonLabel($settingsCategoryResolver, string $settingsCategory): string
    {
        return $this->getActionName();
    }

    abstract protected function getActionName(): string;
}
