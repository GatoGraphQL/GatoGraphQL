<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointConfigurationFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\BlockCategories\BlockCategoryInterface;
use GatoGraphQL\GatoGraphQL\Services\BlockCategories\EndpointBlockCategory;
use GatoGraphQL\GatoGraphQL\Services\Helpers\BlockRenderingHelpers;
use GatoGraphQL\GatoGraphQL\Services\Helpers\CPTUtils;

/**
 * SchemaConfiguration block
 */
class EndpointSchemaConfigurationBlock extends AbstractBlock implements PersistedQueryEndpointEditorBlockServiceTagInterface, EndpointEditorBlockServiceTagInterface
{
    use MainPluginBlockTrait;

    public final const ATTRIBUTE_NAME_SCHEMA_CONFIGURATION = 'schemaConfiguration';
    /**
     * These consts must be integer!
     */
    public final const ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT = 0;
    public final const ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_NONE = -1;
    public final const ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_INHERIT = -2;

    private ?BlockRenderingHelpers $blockRenderingHelpers = null;
    private ?CPTUtils $cptUtils = null;
    private ?EndpointBlockCategory $endpointBlockCategory = null;

    final public function setBlockRenderingHelpers(BlockRenderingHelpers $blockRenderingHelpers): void
    {
        $this->blockRenderingHelpers = $blockRenderingHelpers;
    }
    final protected function getBlockRenderingHelpers(): BlockRenderingHelpers
    {
        if ($this->blockRenderingHelpers === null) {
            /** @var BlockRenderingHelpers */
            $blockRenderingHelpers = $this->instanceManager->getInstance(BlockRenderingHelpers::class);
            $this->blockRenderingHelpers = $blockRenderingHelpers;
        }
        return $this->blockRenderingHelpers;
    }
    final public function setCPTUtils(CPTUtils $cptUtils): void
    {
        $this->cptUtils = $cptUtils;
    }
    final protected function getCPTUtils(): CPTUtils
    {
        if ($this->cptUtils === null) {
            /** @var CPTUtils */
            $cptUtils = $this->instanceManager->getInstance(CPTUtils::class);
            $this->cptUtils = $cptUtils;
        }
        return $this->cptUtils;
    }
    final public function setEndpointBlockCategory(EndpointBlockCategory $endpointBlockCategory): void
    {
        $this->endpointBlockCategory = $endpointBlockCategory;
    }
    final protected function getEndpointBlockCategory(): EndpointBlockCategory
    {
        if ($this->endpointBlockCategory === null) {
            /** @var EndpointBlockCategory */
            $endpointBlockCategory = $this->instanceManager->getInstance(EndpointBlockCategory::class);
            $this->endpointBlockCategory = $endpointBlockCategory;
        }
        return $this->endpointBlockCategory;
    }

    protected function getBlockName(): string
    {
        return 'schema-configuration';
    }

    public function getBlockPriority(): int
    {
        return 180;
    }

    protected function getBlockCategory(): ?BlockCategoryInterface
    {
        return $this->getEndpointBlockCategory();
    }

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    /**
     * Pass localized data to the block
     *
     * @return array<string,mixed>
     */
    protected function getLocalizedData(): array
    {
        return array_merge(
            parent::getLocalizedData(),
            [
                'isAPIHierarchyEnabled' => $this->getModuleRegistry()->isModuleEnabled(EndpointConfigurationFunctionalityModuleResolver::API_HIERARCHY),
            ]
        );
    }

    /**
     * @param array<string,mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        /**
         * Print the list of all the contained Access Control blocks
         */
        $blockContentPlaceholder = <<<HTML
            <div class="%s">
                <h3 class="%s">%s</strong></h3>
                %s
            </div>
        HTML;
        $schemaConfigurationContent = '';
        $schemaConfigurationID = $attributes[self::ATTRIBUTE_NAME_SCHEMA_CONFIGURATION] ?? null;
        if ($schemaConfigurationID === self::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT) {
            $schemaConfigurationContent = \__('Default', 'gato-graphql');
        } elseif ($schemaConfigurationID === self::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_NONE) {
            $schemaConfigurationContent = \__('None', 'gato-graphql');
        } elseif ($schemaConfigurationID === self::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_INHERIT) {
            $schemaConfigurationContent = \__('Inherit from parent', 'gato-graphql');
        } elseif ($schemaConfigurationID > 0) {
            $schemaConfigurationObject = \get_post($schemaConfigurationID);
            if (!is_null($schemaConfigurationObject)) {
                $schemaConfigurationDescription = $this->getCPTUtils()->getCustomPostDescription($schemaConfigurationObject);
                $permalink = \get_permalink($schemaConfigurationObject->ID);
                $schemaConfigurationContent = ($permalink ?
                    \sprintf(
                        '<code><a href="%s">%s</a></code>',
                        $permalink,
                        $this->getBlockRenderingHelpers()->getCustomPostTitle($schemaConfigurationObject)
                    ) :
                    \sprintf(
                        '<code>%s</code>',
                        $this->getBlockRenderingHelpers()->getCustomPostTitle($schemaConfigurationObject)
                    )
                ) . ($schemaConfigurationDescription ?
                    '<br/><small>' . $schemaConfigurationDescription . '</small>'
                    : ''
                );
            }
        }
        $className = $this->getBlockClassName();
        return sprintf(
            $blockContentPlaceholder,
            $className,
            $className . '-front',
            \__('Schema Configuration', 'gato-graphql'),
            $schemaConfigurationContent
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
