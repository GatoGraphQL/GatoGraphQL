<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use PoP\Engine\App;
use GraphQLAPI\GraphQLAPI\Component;
use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use PoP\ComponentModel\Registries\TypeRegistryInterface;

/**
 * Base Control block
 */
abstract class AbstractControlBlock extends AbstractBlock
{
    use WithTypeFieldControlBlockTrait;

    public const ATTRIBUTE_NAME_TYPE_FIELDS = 'typeFields';
    public const ATTRIBUTE_NAME_DIRECTIVES = 'directives';

    private ?TypeRegistryInterface $typeRegistry = null;

    final public function setTypeRegistry(TypeRegistryInterface $typeRegistry): void
    {
        $this->typeRegistry = $typeRegistry;
    }
    final protected function getTypeRegistry(): TypeRegistryInterface
    {
        return $this->typeRegistry ??= $this->instanceManager->getInstance(TypeRegistryInterface::class);
    }

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    protected function disableFields(): bool
    {
        return false;
    }

    protected function disableDirectives(): bool
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
     * @param array<string, mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        // Append "-front" because this style must be used only on the client, not on the admin
        $className = $this->getBlockClassName() . '-front';
        $fieldTypeContent = $directiveContent = '';
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if (!$this->disableFields()) {
            $fieldTypeContent = $componentConfiguration->getEmptyLabel();
            $typeFields = $attributes[self::ATTRIBUTE_NAME_TYPE_FIELDS] ?? [];
            if ($typeFields) {
                $typeFieldsForPrint = $this->getTypeFieldsForPrint($typeFields);
                /**
                 * If $groupFieldsUnderTypeForPrint is true, combine all types under their shared typeName
                 * If $groupFieldsUnderTypeForPrint is false, replace namespacedTypeName for typeName and "." for "/"
                 * */
                $groupFieldsUnderTypeForPrint = $componentConfiguration->groupFieldsUnderTypeForPrint();
                if ($groupFieldsUnderTypeForPrint) {
                    /**
                     * Cast object so PHPStan doesn't throw error
                     * @var array<string,array>
                     */
                    $typeFieldsForPrint = $typeFieldsForPrint;
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
        if (!$this->disableDirectives()) {
            $directiveContent = $componentConfiguration->getEmptyLabel();
            $directives = $attributes[self::ATTRIBUTE_NAME_DIRECTIVES] ?? [];
            if ($directives) {
                // // Notice we are adding the "@" symbol for GraphQL directives
                $directiveContent = sprintf(
                    // '<ul><li><code>@%s</code></li></ul>',
                    // implode('</code></li><li><code>@', $directives)
                    '<ul><li><code>%s</code></li></ul>',
                    implode('</code></li><li><code>', $directives)
                );
            }
        }
        $blockDataContent = '';
        if (!$this->disableFields() && !$this->disableDirectives()) {
            $blockDataPlaceholder = <<<EOT
                <h4>%s</h4>
                %s
                <h4>%s</h4>
                %s
EOT;
            $blockDataContent = sprintf(
                $blockDataPlaceholder,
                __('Fields', 'graphql-api'),
                $fieldTypeContent,
                __('Directives', 'graphql-api'),
                $directiveContent
            );
        } elseif (!$this->disableFields()) {
            $blockDataContent = $fieldTypeContent;
        } elseif (!$this->disableDirectives()) {
            $blockDataContent = $directiveContent;
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
     * @param array<string, mixed> $attributes
     */
    abstract protected function getBlockContent(array $attributes, string $content): string;
}
