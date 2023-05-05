<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use PoP\Root\App;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\Constants\BlockAttributeNames;

abstract class AbstractDefaultEnableDisableFunctionalitySchemaConfigBlock extends AbstractSchemaConfigBlock
{
    use OptionsBlockTrait;

    abstract protected function getBlockLabel(): string;
    abstract protected function getBlockTitle(): string;

    /**
     * @param array<string,mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName() . '-front';

        $blockContentPlaceholder = '<p><strong>%s</strong></p><p>%s</p>';

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $enabledDisabledLabels = $this->getEnabledDisabledLabels();
        $blockContent = sprintf(
            $blockContentPlaceholder,
            $this->getBlockLabel(),
            $enabledDisabledLabels[$attributes[BlockAttributeNames::ENABLED_CONST] ?? ''] ?? $moduleConfiguration->getSettingsValueLabel()
        );

        $blockContentPlaceholder = <<<EOT
            <div class="%s">
                <h3 class="%s">%s</h3>
                %s
            </div>
        EOT;
        return sprintf(
            $blockContentPlaceholder,
            $className . ' ' . $this->getAlignClassName(),
            $className . '__title',
            $this->getBlockTitle(),
            $blockContent
        );
    }

    /**
     * Register style-index.css
     */
    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }
}
