<?php

declare(strict_types=1);

namespace GraphQLByPoP\DependsOnOperationsDirective\DirectiveResolvers;

use PoP\ComponentModel\App;
use PoP\ComponentModel\DirectiveResolvers\PureOperationDirectiveResolverTrait;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\DirectiveResolvers\AbstractGlobalFieldDirectiveResolver;
use PoP\GraphQLParser\Module as GraphQLParserModule;
use PoP\GraphQLParser\ModuleConfiguration as GraphQLParserModuleConfiguration;

/**
 * @configureWarningsOnExportingDuplicateVariableName directive, 
 * to either enable or disable showing warnings when exporting
 * a duplicate variable name, as with "someVarName" in:
 * 
 *   ```
 *   {
 *     posts {
 *       id @export(as: "someVarName")
 *       title @export(as: "someVarName")
 *     }
 *   }
 *   ```
 * 
 * Or "hasPostID" in:
 * 
 *   ```
 *   {
 *     transformedContentSource: _echo(value: $contentSource)
 *       @underEachJSONObjectProperty(
 *         passKeyOnwardsAs: "postID"
 *         affectDirectivesUnderPos: [1, 3]
 *       )
 *         @applyField(
 *           name: "_propertyExistsInJSONObject"
 *           arguments: {
 *             object: $someObject
 *             by: { key: $postID }
 *           }
 *           passOnwardsAs: "hasPostID"
 *         )
 *           @if (condition: $hasPostID)
 *             # ... * 
 *
 *         @applyField(
 *           name: "_propertyExistsInJSONObject"
 *           arguments: {
 *             object: $anotherObject
 *             by: { key: $postID }
 *           }
 *           passOnwardsAs: "hasPostID"
 *         )
 *           @if (condition: $hasPostID)
 *             # ...
 *   }
 *   ```
 */
class ConfigureWarningsOnExportingDuplicateVariableNameOperationsFieldDirectiveResolver extends AbstractGlobalFieldDirectiveResolver
{
    use PureOperationDirectiveResolverTrait;

    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;

    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        /** @var BooleanScalarTypeResolver */
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    public function getDirectiveName(): string
    {
        return 'configureWarningsOnExportingDuplicateVariableName';
    }

    public function isDirectiveEnabled(): bool
    {
        /** @var GraphQLParserModuleConfiguration */
        $moduleConfiguration = App::getModule(GraphQLParserModule::class)->getConfiguration();
        if (!$moduleConfiguration->enableDynamicVariables()) {
            return false;
        }

        return parent::isDirectiveEnabled();
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('Enable or disable showing warnings when the same dynamic variable name is defined more than once', 'component-model');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return array_merge(
            parent::getDirectiveArgNameTypeResolvers($relationalTypeResolver),
            [
                'enabled' => $this->getBooleanScalarTypeResolver(),
            ]
        );
    }

    public function getDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        return match ($directiveArgName) {
            'enabled' => $this->__('Raise warnings in the response?', 'component-model'),
            default => parent::getDirectiveArgDescription($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): int
    {
        return match ($directiveArgName) {
            'enabled' => SchemaTypeModifiers::MANDATORY,
            default => parent::getDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getDirectiveArgDefaultValue(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): mixed
    {
        return match ($directiveArgName) {
            'enabled' => true,
            default => parent::getDirectiveArgDefaultValue($relationalTypeResolver, $directiveArgName),
        };
    }

    public function isRepeatable(): bool
    {
        return false;
    }
}
