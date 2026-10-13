<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use PoP\Root\App;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;

class SchemaConfigGraphQLSpecComplianceBlock extends AbstractSchemaConfigBlock
{
    use MainPluginBlockTrait;
    use OptionsBlockTrait;

    public final const ATTRIBUTE_NAME_PROPAGATE_NULL_TO_NEAREST_NULLABLE_PARENT = 'propagateNullToNearestNullableParent';
    public final const ATTRIBUTE_NAME_COERCE_OUTPUT_VALUES = 'coerceOutputValues';
    public final const ATTRIBUTE_NAME_ADD_RESPONSE_PATH_TO_ERRORS = 'addResponsePathToErrors';

    protected function getBlockName(): string
    {
        return 'schema-config-graphql-spec-compliance';
    }

    public function getBlockPriority(): int
    {
        return 10100;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::GRAPHQL_SPEC_COMPLIANCE;
    }

    /**
     * @param array<string,mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName() . '-front';

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $enabledDisabledLabels = $this->getEnabledDisabledLabels();
        $attributeNameLabels = [
            self::ATTRIBUTE_NAME_PROPAGATE_NULL_TO_NEAREST_NULLABLE_PARENT => \__('Pass a null up to the nearest nullable parent?', 'gatographql'),
            self::ATTRIBUTE_NAME_COERCE_OUTPUT_VALUES => \__('Coerce scalar and enum values?', 'gatographql'),
            self::ATTRIBUTE_NAME_ADD_RESPONSE_PATH_TO_ERRORS => \__('Add the response path to errors?', 'gatographql'),
        ];
        $blockContent = '';
        foreach ($attributeNameLabels as $attributeName => $label) {
            $blockContent .= sprintf(
                '<p><strong>%s</strong></p><p>%s</p>',
                $label,
                $enabledDisabledLabels[$attributes[$attributeName] ?? ''] ?? $moduleConfiguration->getSettingsValueLabel()
            );
        }

        $blockContentPlaceholder = '<div class="%s"><h3 class="%s">%s</h3>%s</div>';
        return sprintf(
            $blockContentPlaceholder,
            $className . ' ' . $this->getAlignClassName(),
            $className . '__title',
            \__('GraphQL Spec Compliance', 'gatographql'),
            $blockContent
        );
    }

    /**
     * Add the locale language to the localized data?
     */
    protected function addLocalLanguage(): bool
    {
        return true;
    }

    /**
     * Default language for the script/component's documentation
     */
    protected function getDefaultLanguage(): ?string
    {
        // English
        return 'en';
    }

    /**
     * Register style-index.css
     */
    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }
}
