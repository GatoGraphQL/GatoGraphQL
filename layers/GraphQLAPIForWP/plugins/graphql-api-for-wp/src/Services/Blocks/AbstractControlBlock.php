<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use PoP\Root\App;
use GraphQLAPI\GraphQLAPI\Module;
use GraphQLAPI\GraphQLAPI\ModuleConfiguration;
use PoP\ComponentModel\Registries\TypeRegistryInterface;

/**
 * Base Control block
 */
abstract class AbstractControlBlock extends AbstractBlock
{
    use WithTypeFieldControlBlockTrait;

    public final const ATTRIBUTE_NAME_TYPE_FIELDS = 'typeFields';
    public final const ATTRIBUTE_NAME_GLOBAL_FIELDS = 'globalFields';
    public final const ATTRIBUTE_NAME_DIRECTIVES = 'directives';

    private ?TypeRegistryInterface $typeRegistry = null;

    final public function setTypeRegistry(TypeRegistryInterface $typeRegistry): void
    {
        $this->typeRegistry = $typeRegistry;
    }
    final protected function getTypeRegistry(): TypeRegistryInterface
    {
        /** @var TypeRegistryInterface */
        return $this->typeRegistry ??= $this->instanceManager->getInstance(TypeRegistryInterface::class);
    }

    protected function isDynamicBlock(): bool
    {
        return true;
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
        $fieldTypeContent = $globalFieldContent = $directiveContent = '';
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($this->enableTypeFields()) {
            $fieldTypeContent = $moduleConfiguration->getEmptyLabel();
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
                            '<strong>%s</strong><ul><li><code>%s</code></li></ul>',
                            $typeName,
                            implode(
                                '</code></li><li><code>',
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
            $globalFieldContent = $moduleConfiguration->getEmptyLabel();
            $globalFields = $attributes[self::ATTRIBUTE_NAME_GLOBAL_FIELDS] ?? [];
            if ($globalFields) {
                $globalFieldContent = sprintf(
                    '<ul><li><code>%s</code></li></ul>',
                    implode('</code></li><li><code>', $globalFields)
                );
            }
        }
        if ($this->enableDirectives()) {
            $directiveContent = $moduleConfiguration->getEmptyLabel();
            $directives = $attributes[self::ATTRIBUTE_NAME_DIRECTIVES] ?? [];
            if ($directives) {
                $directiveContent = sprintf(
                    '<ul><li><code>%s</code></li></ul>',
                    implode('</code></li><li><code>', $directives)
                );
            }
        }
        $blockDataContent = '';
        $blockDataPlaceholder = '<h4>%s</h4>%s';
        if ($this->enableTypeFields()) {
            $blockDataContent .= sprintf(
                $blockDataPlaceholder,
                __('Fields', 'graphql-api'),
                $fieldTypeContent,
            );
        }
        if ($this->enableGlobalFields()) {
            $blockDataContent .= sprintf(
                $blockDataPlaceholder,
                __('Global Fields', 'graphql-api'),
                $globalFieldContent,
            );
        }
        if ($this->enableDirectives()) {
            $blockDataContent .= sprintf(
                $blockDataPlaceholder,
                __('Directives', 'graphql-api'),
                $directiveContent,
            );
        }

        $blockContentPlaceholder = <<<EOT
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
EOT;
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
        return \__('Select fields and directives:', 'graphql-api');
    }
    protected function getBlockContentTitle(): string
    {
        return \__('Configuration:', 'graphql-api');
    }
    /**
     * @param array<string,mixed> $attributes
     */
    abstract protected function getBlockContent(array $attributes, string $content): string;
}
