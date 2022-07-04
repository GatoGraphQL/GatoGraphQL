<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Container\ServiceTags\MandatoryDirectiveServiceTagInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;
use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

/**
 * Set-up default arguments in the Field, and apply other customizations.
 *
 * Please notice that @prepareField will normally be called only once per field,
 * but in it could be also called more than once, as when the
 * field is inside a fragment, which gets called for different entities
 * (and even types) across the GraphQL query.
 *
 * In that case, since the same field will be applied on different objects
 * on different Engine iterations, the $idFieldSet (for different $ids)
 * will contain the same direct $field, and this $field will then be
 * "prepared" more than once.
 *
 * For instance, field '_kind69x5_name70x5_ofType71x5_isTypeOrImplementsAll___Type_: isTypeOrImplementsAll(typesOrInterfaces: ["__Type"])'
 * is called more than once in the Introspection Query, as it is created on runtime
 * within the fragment `TypeRef`, which is invoked several times:
 *
 *   ```graphql
 *   query IntrospectionQuery {
 *       __schema {
 *           types {
 *               ...FullType
 *           }
 *           directives {
 *               args {
 *                   ...InputValue
 *               }
 *           }
 *       }
 *   }
 *   
 *   fragment FullType on __Type {
 *       fields(includeDeprecated: true) {
 *           args {
 *               ...InputValue
 *           }
 *           type {
 *               ...TypeRef
 *           }
 *       }
 *       inputFields {
 *           ...InputValue
 *       }
 *       interfaces {
 *           ...TypeRef
 *       }
 *       possibleTypes {
 *           ...TypeRef
 *       }
 *   }
 *   
 *   fragment InputValue on __InputValue {
 *       type {
 *           ...TypeRef
 *       }
 *   }
 *   
 *   fragment TypeRef on __Type {
 *       kind
 *       name
 *       # other fields
 *   }
 *   ```
 *
 * This circumstance should not produce issues, as long as @prepareField
 * executes an action only if/when is needed.
 *
 * For instance, setting default Arguments is done only when the corresponding
 * Arguent is not provided in the query. Then, the first execution of `@prepareField`
 * on the query will check if the Argument is missing and, then, add it, and the
 * second one will check if the Argument is missing and, since it is not (as it was
 * added by the first iteration) then it will do nothing.
 */
final class PrepareFieldDirectiveResolver extends AbstractGlobalDirectiveResolver implements MandatoryDirectiveServiceTagInterface
{
    public function getDirectiveName(): string
    {
        return 'prepareField';
    }

    /**
     * This is a system directive
     */
    public function getDirectiveKind(): string
    {
        return DirectiveKinds::SYSTEM;
    }

    /**
     * Execute only once
     */
    public function isRepeatable(): bool
    {
        return false;
    }

    /**
     * This directive must be the first one of the group at the middle
     */
    public function getPipelinePosition(): string
    {
        return PipelinePositions::AFTER_PREPARE_BEFORE_VALIDATE;
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        array $succeedingPipelineDirectiveResolvers,
        array $idObjects,
        array $unionTypeOutputKeyIDs,
        array $previouslyResolvedIDFieldValues,
        array &$succeedingPipelineIDFieldSet,
        array &$resolvedIDFieldValues,
        array &$variables,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        $fields = [];
        foreach ($idFieldSet as $id => $fieldSet) {
            $fields = array_merge(
                $fields,
                $fieldSet->fields
            );
        }
        $fields = array_values(array_unique($fields));

        foreach ($fields as $field) {
            $this->prepareField(
                $relationalTypeResolver,
                $field,
            );
        }
    }

    protected function prepareField(
        RelationalTypeResolverInterface $relationalTypeResolver,
        FieldInterface $field,
    ): void {
        /**
         * Because the UnionTypeResolver doesn't know yet which TypeResolver will be used
         * (that depends on each object), it can't resolve this functionality
         */
        if ($relationalTypeResolver instanceof UnionTypeResolverInterface) {
            return;
        }

        /** @var ObjectTypeResolverInterface */
        $objectTypeResolver = $relationalTypeResolver;

        $objectTypeResolver->prepareField($field);
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('It prepares the Field AST object, adding the default arguments not provided in the query, and needed customizations', 'component-model');
    }
}
