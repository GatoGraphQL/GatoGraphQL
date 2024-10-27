<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema\ModuleResolvers;

use GatoGraphQL\TestingSchema\GatoGraphQLExtension;
use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\AbstractModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaTypeModuleResolverTrait;

class SchemaTypeModuleResolver extends AbstractModuleResolver
{
    use ModuleResolverTrait;
    use SchemaTypeModuleResolverTrait;

    public final const SCHEMA_TESTING = GatoGraphQLExtension::NAMESPACE . '\schema-testing';

    private ?MarkdownContentParserInterface $markdownContentParser = null;

    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        if ($this->markdownContentParser === null) {
            /** @var MarkdownContentParserInterface */
            $markdownContentParser = $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
            $this->markdownContentParser = $markdownContentParser;
        }
        return $this->markdownContentParser;
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::SCHEMA_TESTING,
        ];
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::SCHEMA_TESTING => \__('Schema Testing', 'gatographql-testing-schema'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::SCHEMA_TESTING => \__('Addition of elements to the GraphQL schema to test the Gato GraphQL plugin', 'gatographql-testing-schema'),
            default => parent::getDescription($module),
        };
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            self::SCHEMA_TESTING => true,
            default => parent::isHidden($module),
        };
    }

    public function hasDocumentation(string $module): bool
    {
        return match ($module) {
            self::SCHEMA_TESTING => false,
            default => parent::hasDocumentation($module),
        };
    }
}
