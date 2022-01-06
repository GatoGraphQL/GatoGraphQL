<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\GraphQLParser\Component;
use PoP\GraphQLParser\ComponentConfiguration;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Root\App;

abstract class AbstractMetaDirectiveResolver extends AbstractDirectiveResolver implements MetaDirectiveResolverInterface
{
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;

    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }

    public function isServiceEnabled(): bool
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->enableComposableDirectives();
    }

    /**
     * Name for the directive arg to indicate which directives
     * are being nested, by indicating their relative position
     * to the meta-directive.
     *
     * Eg: @foreach(affectDirectivesUnderPos: [1]) @translate
     */
    public function getAffectDirectivesUnderPosArgumentName(): string
    {
        return 'affectDirectivesUnderPos';
    }

    /**
     * This array cannot be empty!
     *
     * @return int[]
     */
    public function getAffectDirectivesUnderPosArgumentDefaultValue(): array
    {
        return [1];
    }

    public function getDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [
            $this->getAffectDirectivesUnderPosArgumentName() => $this->getIntScalarTypeResolver(),
        ];
    }

    public function getDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        return match ($directiveArgName) {
            $this->getAffectDirectivesUnderPosArgumentName() => $this->__('Positions of the directives to be affected, relative from this one (as an array of positive integers)', 'graphql-server'),
            default => parent::getDirectiveArgDescription($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getDirectiveArgDefaultValue(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): mixed
    {
        return match ($directiveArgName) {
            $this->getAffectDirectivesUnderPosArgumentName() => $this->getAffectDirectivesUnderPosArgumentDefaultValue(),
            default => parent::getDirectiveArgDefaultValue($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): int
    {
        return match ($directiveArgName) {
            $this->getAffectDirectivesUnderPosArgumentName() => SchemaTypeModifiers::MANDATORY | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY,
            default => parent::getDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName),
        };
    }
}
