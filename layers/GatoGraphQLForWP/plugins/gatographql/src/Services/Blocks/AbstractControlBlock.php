<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use PoP\Root\App;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use PoP\ComponentModel\Registries\TypeRegistryInterface;

/**
 * Base Control block
 */
abstract class AbstractControlBlock extends AbstractBlock
{
    use WithTypeFieldControlBlockTrait;

    public final const ATTRIBUTE_NAME_OPERATIONS = 'operations';
    public final const ATTRIBUTE_NAME_TYPE_FIELDS = 'typeFields';
    public final const ATTRIBUTE_NAME_GLOBAL_FIELDS = 'globalFields';
    public final const ATTRIBUTE_NAME_DIRECTIVES = 'directives';

    private ?TypeRegistryInterface $typeRegistry = null;

    final protected function getTypeRegistry(): TypeRegistryInterface
    {
        if ($this->typeRegistry === null) {
            /** @var TypeRegistryInterface */
            $typeRegistry = $this->instanceManager->getInstance(TypeRegistryInterface::class);
            $this->typeRegistry = $typeRegistry;
        }
        return $this->typeRegistry;
    }

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    protected function enableOperations(): bool
    {
        return false;
    }

    protected function enableTypeFields(): bool
    {
        return false;
    }

    protected function enableGlobalFields(): bool
    {
        return false;
    }

    protected function enableDirectives(): bool
    {
        return false;
    }

    /**
     * Register style-index.css
     */
    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }

    /**
     * Register index.css
     */
    protected function registerEditorCSS(): bool
    {
        return true;
    }

    /**
     * Block align class
     */
    public function getAlignClassName(): string
    {
        return 'alignwide';
    }

    /**
     * @param array<string,mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName() . '-front';
        $operationContent = $fieldTypeContent = $globalFieldContent = $directiveContent = '';
        $ulPlaceholder = '<ul><li><code>%s</code></li></ul>';
        $liPlaceholder = '</code></li><li><code>';
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($this->enableOperations()) {
            $operationContent = $moduleConfiguration->getNoItemsSelectedLabel();
            $operations = $attributes[self::ATTRIBUTE_NAME_OPERATIONS] ?? [];
            if ($operations) {
                $operationContent = sprintf(
                    $ulPlaceholder,
                    implode($liPlaceholder, $operations)
                );
            }
        }
        if ($this->enableTypeFields()) {
            $fieldTypeContent = $moduleConfiguration->getNoItemsSelectedLabel();
            $typeFields = $attributes[self::ATTRIBUTE_NAME_TYPE_FIELDS] ?? [];
            if ($typeFields) {
                $typeFieldsForPrint = $this->getTypeFieldsForPrint($typeFields);
                /**
                 * If $groupFieldsUnderTypeForPrint is true, combine all types under their shared typeName
                 * If $groupFieldsUnderTypeForPrint is false, replace namespacedTypeName for typeName and "." for "/"
                 * */
                $groupFieldsUnderTypeForPrint = $moduleConfiguration->groupFieldsUnderTypeForPrint();
                if ($groupFieldsUnderTypeForPrint) {
                    /** @var array<string,string[]> $typeFieldsForPrint */
                    $fieldTypeContent = '';
                    foreach ($typeFieldsForPrint as $typeName => $fields) {
                        $fieldTypeContent .= sprintf(
                            '<em>%s</em>' . $ulPlaceholder,
                            $typeName,
                            implode(
                                $liPlaceholder,
                                $fields
                            )
                        );
                    }
                } else {
                    /**
                     * Cast object so PHPStan doesn't throw error
                     * @var string[];
                     */
                    $typeFieldsForPrint = $typeFieldsForPrint;
                    $fieldTypeContent = sprintf(
                        '<ul><li>%s</li></ul>',
                        implode(
                            '</li><li>',
                            $typeFieldsForPrint
                        )
                    );
                }
            }
        }
        if ($this->enableGlobalFields()) {
            $globalFieldContent = $moduleConfiguration->getNoItemsSelectedLabel();
            $globalFields = $attributes[self::ATTRIBUTE_NAME_GLOBAL_FIELDS] ?? [];
            if ($globalFields) {
                $globalFieldContent = sprintf(
                    $ulPlaceholder,
                    implode($liPlaceholder, $globalFields)
                );
            }
        }
        if ($this->enableDirectives()) {
            $directiveContent = $moduleConfiguration->getNoItemsSelectedLabel();
            $directives = $attributes[self::ATTRIBUTE_NAME_DIRECTIVES] ?? [];
            if ($directives) {
                $directiveContent = sprintf(
                    $ulPlaceholder,
                    implode($liPlaceholder, $directives)
                );
            }
        }
        $blockDataContent = '';
        $blockDataPlaceholder = '<h4>%s</h4>%s';
        if ($this->enableOperations()) {
            $blockDataContent .= sprintf(
                $blockDataPlaceholder,
                __('Operations', 'gatographql'),
                $operationContent,
            );
        }
        if ($this->enableTypeFields()) {
            $blockDataContent .= sprintf(
                $blockDataPlaceholder,
                __('Fields', 'gatographql'),
                $fieldTypeContent,
            );
        }
        if ($this->enableGlobalFields()) {
            $blockDataContent .= sprintf(
                $blockDataPlaceholder,
                __('Global Fields', 'gatographql'),
                $globalFieldContent,
            );
        }
        if ($this->enableDirectives()) {
            $blockDataContent .= sprintf(
                $blockDataPlaceholder,
                __('Directives', 'gatographql'),
                $directiveContent,
            );
        }

        $blockContentPlaceholder = '
        <div class="%s">
            <div class="%s">
                <h3 class="%s">%s</h3>
                %s
            </div>
            <div class="%s">
                <h3 class="%s">%s</h3>
                %s
            </div>
        </div>
';
        return sprintf(
            $blockContentPlaceholder,
            $className . ' ' . $this->getAlignClassName(),
            $className . '__data',
            $className . '__title',
            $this->getBlockDataTitle(),
            $blockDataContent,
            $className . '__content',
            $className . '__title',
            $this->getBlockContentTitle(),
            $this->getBlockContent($attributes, $content)
        );
    }

    protected function getBlockDataTitle(): string
    {
        return \__('Select schema elements', 'gatographql');
    }
    protected function getBlockContentTitle(): string
    {
        return \__('Configuration:', 'gatographql');
    }
    /**
     * @param array<string,mixed> $attributes
     */
    abstract protected function getBlockContent(array $attributes, string $content): string;
}
