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
     * The SuperRoot is reserved for the Operation Directives,
     * so can remove it.
     *
     * @param string[] $supportedFieldTypeResolverContainerServiceIDs
     * @return ConcreteTypeResolverInterface[]|null
     */
    protected function getSupportedConcreteTypeResolvers(array $supportedFieldTypeResolverContainerServiceIDs): ?array
    {
        /** @var ConcreteTypeResolverInterface[] */
        return parent::getSupportedConcreteTypeResolvers(
            array_diff(
                $supportedFieldTypeResolverContainerServiceIDs,
                [
                    SuperRootObjectTypeResolver::class,
                ]
            )
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
