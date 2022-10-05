<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\DirectiveResolvers\AbstractFieldDirectiveResolver as UpstreamAbstractFieldDirectiveResolver;
use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Directives\FieldDirectiveBehaviors;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\SuperRootObjectTypeResolver;

/**
 * The GraphQL server resolves only FieldDirectiveResolvers
 * via the directive pipeline.
 *
 * FieldDirectiveResolvers can also handle Operation Directives,
 * by having these be duplicated into the SuperRoot type fields.
 */
abstract class AbstractFieldDirectiveResolver extends UpstreamAbstractFieldDirectiveResolver
{
    private ?SuperRootObjectTypeResolver $superRootObjectTypeResolver = null;

    final public function setSuperRootObjectTypeResolver(SuperRootObjectTypeResolver $superRootObjectTypeResolver): void
    {
        $this->superRootObjectTypeResolver = $superRootObjectTypeResolver;
    }
    final protected function getSuperRootObjectTypeResolver(): SuperRootObjectTypeResolver
    {
        /** @var SuperRootObjectTypeResolver */
        return $this->superRootObjectTypeResolver ??= $this->instanceManager->getInstance(SuperRootObjectTypeResolver::class);
    }

    /**
     * For a FieldDirectiveResolver to only behave as a
     * OperationDirectiveResolver, it must only support
     * the SuperRoot type.
     *
     * @return array<class-string<ConcreteTypeResolverInterface>>|null
     */
    protected function getSupportedFieldTypeResolverClasses(): ?array
    {
        $fieldDirectiveBehavior = $this->getFieldDirectiveBehavior();
        if ($fieldDirectiveBehavior === FieldDirectiveBehaviors::OPERATION) {
            return [
                SuperRootObjectTypeResolver::class,
            ];
        }

        return parent::getSupportedFieldTypeResolverClasses();
    }

    /**
     * Print what types does the directive support, or `null`
     * to mean it supports them all.
     *
     * It can be a name, such as `String`, or a description,
     * such as `Any type implementing the CustomPost interface`.
     *
     * @return string[]|null
     */
    protected function getSupportedFieldTypeNamesOrDescriptions(): ?array
    {
        $concreteTypeResolvers = $this->getSupportedConcreteTypeResolvers();
        if ($concreteTypeResolvers === null) {
            return null;
        }

        return array_map(
            function (ConcreteTypeResolverInterface $typeResolver): string {
                if ($typeResolver === $this->getSuperRootObjectTypeResolver()) {
                    return $this->__('Operation (`query` and `mutation`)', 'engine');
                }
                return $typeResolver->getMaybeNamespacedTypeName();
            },
            $concreteTypeResolvers
        );
    }

    /**
     * For a FieldDirectiveResolver to not also behave as a
     * OperationDirectiveResolver, it must be excluded from
     * the SuperRoot type.
     *
     * Watch out: System directives (like @resolveValueAndMerge)
     * must always be allowed, it's only the "query-type" and
     * "schema-type" directives that must be excluded.
     *
     * @return array<class-string<ConcreteTypeResolverInterface>>|null
     */
    protected function getExcludedFieldTypeResolverClasses(): ?array
    {
        $fieldDirectiveBehavior = $this->getFieldDirectiveBehavior();
        if (
            $fieldDirectiveBehavior === FieldDirectiveBehaviors::FIELD
            && (
                $this->isQueryTypeDirective()
                || $this->getDirectiveKind() === DirectiveKinds::SCHEMA
            )
        ) {
            return [
                SuperRootObjectTypeResolver::class,
            ];
        }

        return parent::getExcludedFieldTypeResolverClasses();
    }
}
