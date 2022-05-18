<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoP\ComponentModel\App;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Feedback\SchemaFeedback;
use PoP\ComponentModel\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\ComponentProcessors\FilterInputComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
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
     * for this result without checking in advance what's the typeResolver
     */
    public function getTypeResolverFromSubcomponentDataField(RelationalTypeResolverInterface $relationalTypeResolver, string $subcomponent_data_field): ?RelationalTypeResolverInterface
    {
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
        $variables = [];
        $objectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $subcomponentFieldTypeResolver = $objectTypeResolver->getFieldTypeResolver($subcomponent_data_field, $variables, $objectTypeFieldResolutionFeedbackStore);
        App::getFeedbackStore()->schemaFeedbackStore->incorporateFromObjectTypeFieldResolutionFeedbackStore($objectTypeFieldResolutionFeedbackStore, $objectTypeResolver, $subcomponent_data_field);
        if (
            $subcomponentFieldTypeResolver === null
            || !($subcomponentFieldTypeResolver instanceof RelationalTypeResolverInterface)
        ) {
            // But if there are no ObjectTypeFieldResolvers, then skip adding an error here, since that error will have been added already
            // Otherwise, there will appear 2 error messages:
            // 1. No ObjectTypeFieldResolver
            // 2. No FieldDefaultTypeDataLoader
            if ($objectTypeResolver->hasObjectTypeFieldResolversForField($subcomponent_data_field)) {
                // If there is an alias, store the results under this. Otherwise, on the fieldName+fieldArgs
                $subcomponent_data_field_outputkey = $this->getFieldQueryInterpreter()->getFieldOutputKey($subcomponent_data_field);
                App::getFeedbackStore()->schemaFeedbackStore->addError(
                    new SchemaFeedback(
                        new FeedbackItemResolution(
                            ErrorFeedbackItemProvider::class,
                            ErrorFeedbackItemProvider::E1,
                            [
                                $subcomponent_data_field_outputkey,
                            ]
                        ),
                        LocationHelper::getNonSpecificLocation(),
                        $objectTypeResolver,
                        $subcomponent_data_field,
                    )
                );
            }
            return null;
        }
        return $subcomponentFieldTypeResolver;
    }
}
