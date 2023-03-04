<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginInformation\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AbstractModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaDirectiveModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\Plugin;

class SchemaDirectiveModuleResolver extends AbstractModuleResolver implements PROPseudoModuleResolverInterface
{
    use ModuleResolverTrait;
    use SchemaDirectiveModuleResolverTrait;

    public final const CACHE_DIRECTIVE = Plugin::NAMESPACE . '\cache-directive';
    public final const APPLY_FIELD_DIRECTIVE = Plugin::NAMESPACE . '\apply-field-directive';
    public final const META_DIRECTIVES = Plugin::NAMESPACE . '\meta-directives';
    public final const PASS_ONWARDS_DIRECTIVE = Plugin::NAMESPACE . '\pass-onwards-directive';
    public final const REMOVE_DIRECTIVE = Plugin::NAMESPACE . '\remove-directive';
    public final const DEFAULT_DIRECTIVE = Plugin::NAMESPACE . '\default-directive';
    public final const FUNCTION_DIRECTIVES = Plugin::NAMESPACE . '\function-directives';

    private ?MarkdownContentParserInterface $markdownContentParser = null;

    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        /** @var MarkdownContentParserInterface */
        return $this->markdownContentParser ??= $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::CACHE_DIRECTIVE,
            self::APPLY_FIELD_DIRECTIVE,
            self::META_DIRECTIVES,
            self::PASS_ONWARDS_DIRECTIVE,
            self::REMOVE_DIRECTIVE,
            self::DEFAULT_DIRECTIVE,
            self::FUNCTION_DIRECTIVES,
        ];
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::CACHE_DIRECTIVE => \__('Cache Directive [PRO]', 'graphql-api-pro'),
            self::APPLY_FIELD_DIRECTIVE => \__('Apply Field Directive [PRO]', 'graphql-api-pro'),
            self::META_DIRECTIVES => \__('Meta Directives [PRO]', 'graphql-api-pro'),
            self::PASS_ONWARDS_DIRECTIVE => \__('Pass Onwards Directive [PRO]', 'graphql-api-pro'),
            self::REMOVE_DIRECTIVE => \__('Remove Output Directive [PRO]', 'graphql-api-pro'),
            self::DEFAULT_DIRECTIVE => \__('Default Directive [PRO]', 'graphql-api-pro'),
            self::FUNCTION_DIRECTIVES => \__('Function Directives [PRO]', 'graphql-api-pro'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::CACHE_DIRECTIVE:
                return \__('Addition of <code>@cache</code> directive, to cache expensive operations to disk', 'graphql-api-pro');
            case self::APPLY_FIELD_DIRECTIVE:
                return \__('Addition of <code>@applyField</code> directive, to execute a certain field on the resolved field\'s value', 'graphql-api-pro');
            case self::META_DIRECTIVES:
                return \__('Addition of <code>@forEach</code>, <code>@underArrayItem</code> and <code>@underJSONObjectProperty</code> directives, to add meta-scripting capabilities that modify the behavior of other directives', 'graphql-api-pro');
            case self::PASS_ONWARDS_DIRECTIVE:
                return \__('Addition of <code>@passOnwards</code> directive, to make the field\'s resolved value available to subsequent directives via a dynamic variable', 'graphql-api-pro');
            case self::REMOVE_DIRECTIVE:
                return \__('Addition of <code>@remove</code> directive, to remove an output from the response', 'graphql-api-pro');
            case self::DEFAULT_DIRECTIVE:
                return sprintf(
                    \__('Directive <code>@%s</code>, to set a value to null or empty fields', 'graphql-api-pro'),
                    /**
                     * Same names as in UseDefaultValueIfConditionFieldDirectiveResolver->getDirectiveName()
                     */
                    'default'
                );
            case self::FUNCTION_DIRECTIVES:
                return \__('Manipulate the field output using standard programming language functions (provided via special directives)', 'graphql-api-pro');
        }
        return parent::getDescription($module);
    }

    /**
     * @return array<string[]>
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::META_DIRECTIVES:
                return [
                    [
                        SchemaConfigurationFunctionalityModuleResolver::COMPOSABLE_DIRECTIVES,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }
}
