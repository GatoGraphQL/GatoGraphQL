<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLByPoP\GraphQLServer\Configuration\MutationPayloadTypeOptions;
use PoP\Root\App;

class SchemaConfigPayloadTypesForMutationsBlock extends AbstractSchemaConfigBlock
{
    use MainPluginBlockTrait;
    use OptionsBlockTrait;

    public final const ATTRIBUTE_NAME_USE_PAYLOAD_TYPE = 'usePayloadType';

    protected function getBlockName(): string
    {
        return 'schema-config-payload-types-for-mutations';
    }

    public function getBlockPriority(): int
    {
        return 10130;
    }

    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::MUTATIONS;
    }

    /**
     * @param array<string,mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName() . '-front';

        $blockContentPlaceholder = '<p><strong>%s</strong></p><p>%s</p>';

        $usePayloadTypeLabels = [
            MutationPayloadTypeOptions::USE_PAYLOAD_TYPES_FOR_MUTATIONS => \__('✅ Use payload types for mutations', 'gatographql'),
            MutationPayloadTypeOptions::USE_AND_QUERY_PAYLOAD_TYPES_FOR_MUTATIONS => \__('✳️ Use payload types for mutations, and add fields to query those payload objects', 'gatographql'),
            MutationPayloadTypeOptions::DO_NOT_USE_PAYLOAD_TYPES_FOR_MUTATIONS => \__('❌ Do not use payload types for mutations (i.e. return the mutated entity)', 'gatographql'),
        ];
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $blockContent = sprintf(
            $blockContentPlaceholder,
            \__('Use payload types for mutations in the schema?', 'gatographql'),
            $usePayloadTypeLabels[$attributes[self::ATTRIBUTE_NAME_USE_PAYLOAD_TYPE] ?? ''] ?? $moduleConfiguration->getSettingsValueLabel()
        );

        $blockContentPlaceholder = '<div class="%s"><h3 class="%s">%s</h3>%s</div>';
        return sprintf(
            $blockContentPlaceholder,
            $className . ' ' . $this->getAlignClassName(),
            $className . '__title',
            \__('Payload Types for Mutations', 'gatographql'),
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
