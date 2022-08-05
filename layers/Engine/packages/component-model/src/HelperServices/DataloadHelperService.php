<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;
use PoP\ComponentModel\Feedback\SchemaFeedback;
use PoP\ComponentModel\Feedback\SchemaFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\BasicServiceTrait;

class DataloadHelperService implements DataloadHelperServiceInterface
{
    use BasicServiceTrait;

    private ?FieldQueryInterpreterInterface $fieldQueryInterpreter = null;
    private ?ComponentProcessorManagerInterface $componentProcessorManager = null;

    final public function setFieldQueryInterpreter(FieldQueryInterpreterInterface $fieldQueryInterpreter): void
    {
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
    }
    final protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface
    {
        return $this->fieldQueryInterpreter ??= $this->instanceManager->getInstance(FieldQueryInterpreterInterface::class);
    }
    final public function setComponentProcessorManager(ComponentProcessorManagerInterface $componentProcessorManager): void
    {
        $this->componentProcessorManager = $componentProcessorManager;
    }
    final protected function getComponentProcessorManager(): ComponentProcessorManagerInterface
    {
        return $this->componentProcessorManager ??= $this->instanceManager->getInstance(ComponentProcessorManagerInterface::class);
    }

    /**
     * Accept RelationalTypeResolverInterface as param, instead of the more natural
     * ObjectTypeResolverInterface, to make it easy within the application to check
     * for this result without checking in advance what's the typeResolver.
     *
     * If the FeedbackStore is provided, report errors in the GraphQL query,
     * such as nested fields requested on leaf fields:
     *
     *   `{ id { id } }`
     *
     * This is optional as this method is called in multiple places,
     * but the error needs to be added only once.
     */
    public function getTypeResolverFromSubcomponentField(
        RelationalTypeResolverInterface $relationalTypeResolver,
        FieldInterface $field,
        ?SchemaFeedbackStore $schemaFeedbackStore,
    ): ?RelationalTypeResolverInterface {
        /**
         * Because the UnionTypeResolver doesn't know yet which TypeResolver will be used
         * (that depends on each object), it can't resolve this functionality
         */
        if ($relationalTypeResolver instanceof UnionTypeResolverInterface) {
            return null;
        }
        // By now, the typeResolver must be ObjectType
        /** @var ObjectTypeResolverInterface */
        $objectTypeResolver = $relationalTypeResolver;

        // If this field doesn't have a typeResolver, show a schema error
        $subcomponentFieldNodeTypeResolver = $objectTypeResolver->getFieldTypeResolver($field);
        if (
            $subcomponentFieldNodeTypeResolver === null
            || !($subcomponentFieldNodeTypeResolver instanceof RelationalTypeResolverInterface)
        ) {
            // But if there are no ObjectTypeFieldResolvers, then skip adding an error here, since that error will have been added already
            // Otherwise, there will appear 2 error messages:
            // 1. No ObjectTypeFieldResolver
            // 2. No FieldDefaultTypeDataLoader
            if ($objectTypeResolver->hasObjectTypeFieldResolversForField($field)) {
                if ($schemaFeedbackStore !== null) {
                    // If there is an alias, store the results under this. Otherwise, on the fieldName+fieldArgs
                    $subcomponent_data_field_outputkey = $field->getOutputKey();
                    $schemaFeedbackStore->addError(
                        new SchemaFeedback(
                            new FeedbackItemResolution(
                                ErrorFeedbackItemProvider::class,
                                ErrorFeedbackItemProvider::E1,
                                [
                                    $subcomponent_data_field_outputkey,
                                ]
                            ),
                            $field,
                            $objectTypeResolver,
                            [$field],
                        )
                    );
                }
            }
            return null;
        }
        return $subcomponentFieldNodeTypeResolver;
    }
}
