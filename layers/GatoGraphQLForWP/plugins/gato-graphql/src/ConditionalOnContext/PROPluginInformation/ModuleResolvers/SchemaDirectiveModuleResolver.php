<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\AbstractModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaDirectiveModuleResolverTrait;
use GatoGraphQL\GatoGraphQL\Plugin;

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
            self::CACHE_DIRECTIVE => \__('Cache Directive', 'gato-graphql'),
            self::APPLY_FIELD_DIRECTIVE => \__('Apply Field Directive', 'gato-graphql'),
            self::META_DIRECTIVES => \__('Meta Directives', 'gato-graphql'),
            self::PASS_ONWARDS_DIRECTIVE => \__('Pass Onwards Directive', 'gato-graphql'),
            self::REMOVE_DIRECTIVE => \__('Remove Output Directive', 'gato-graphql'),
            self::DEFAULT_DIRECTIVE => \__('Default Directive', 'gato-graphql'),
            self::FUNCTION_DIRECTIVES => \__('Function Directives', 'gato-graphql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::CACHE_DIRECTIVE:
                return \__('Addition of <code>@cache</code> directive, to cache expensive operations to disk', 'gato-graphql');
            case self::APPLY_FIELD_DIRECTIVE:
                return \__('Addition of <code>@applyField</code> directive, to execute a certain field on the resolved field\'s value', 'gato-graphql');
            case self::META_DIRECTIVES:
                return \__('Addition of <code>@forEach</code>, <code>@underArrayItem</code> and <code>@underJSONObjectProperty</code> directives, to add meta-scripting capabilities that modify the behavior of other directives', 'gato-graphql');
            case self::PASS_ONWARDS_DIRECTIVE:
                return \__('Addition of <code>@passOnwards</code> directive, to make the field\'s resolved value available to subsequent directives via a dynamic variable', 'gato-graphql');
            case self::REMOVE_DIRECTIVE:
                return \__('Addition of <code>@remove</code> directive, to remove an output from the response', 'gato-graphql');
            case self::DEFAULT_DIRECTIVE:
                return sprintf(
                    \__('Directive <code>@%s</code>, to set a value to null or empty fields', 'gato-graphql'),
                    /**
                     * Same names as in UseDefaultValueIfConditionFieldDirectiveResolver->getDirectiveName()
                     */
                    'default'
                );
            case self::FUNCTION_DIRECTIVES:
                return \__('Manipulate the field output using standard programming language functions (provided via special directives)', 'gato-graphql');
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
