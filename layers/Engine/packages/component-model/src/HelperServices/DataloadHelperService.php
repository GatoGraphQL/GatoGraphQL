<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\SchemaFeedback;
use PoP\ComponentModel\FeedbackMessageProviders\FeedbackMessageProvider;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\ModuleProcessors\FilterInputModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
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
    private ?ModuleProcessorManagerInterface $moduleProcessorManager = null;
    private ?FeedbackMessageProvider $feedbackMessageProvider = null;

    final public function setFieldQueryInterpreter(FieldQueryInterpreterInterface $fieldQueryInterpreter): void
    {
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
    }
    final protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface
    {
        return $this->fieldQueryInterpreter ??= $this->instanceManager->getInstance(FieldQueryInterpreterInterface::class);
    }
    final public function setModuleProcessorManager(ModuleProcessorManagerInterface $moduleProcessorManager): void
    {
        $this->moduleProcessorManager = $moduleProcessorManager;
    }
    final protected function getModuleProcessorManager(): ModuleProcessorManagerInterface
    {
        return $this->moduleProcessorManager ??= $this->instanceManager->getInstance(ModuleProcessorManagerInterface::class);
    }
    final public function setFeedbackMessageProvider(FeedbackMessageProvider $feedbackMessageProvider): void
    {
        $this->feedbackMessageProvider = $feedbackMessageProvider;
    }
    final protected function getFeedbackMessageProvider(): FeedbackMessageProvider
    {
        return $this->feedbackMessageProvider ??= $this->instanceManager->getInstance(FeedbackMessageProvider::class);
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
        $subcomponentFieldTypeResolver = $objectTypeResolver->getFieldTypeResolver($subcomponent_data_field);
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
                        $this->getFeedbackMessageProvider()->getMessage(FeedbackMessageProvider::E1, $subcomponent_data_field_outputkey),
                        $this->getFeedbackMessageProvider()->getNamespacedCode(FeedbackMessageProvider::E1),
                        LocationHelper::getNonSpecificLocation(),
                        $objectTypeResolver,
                        [$subcomponent_data_field_outputkey],
                    )
                );
            }
            return null;
        }
        return $subcomponentFieldTypeResolver;
    }

    /**
     * @param array<array<string, mixed>> $moduleValues
     */
    public function addFilterParams(string $url, array $moduleValues = []): string
    {
        $args = [];
        foreach ($moduleValues as $moduleValue) {
            $module = $moduleValue['module'];
            $value = $moduleValue['value'];
            /** @var FilterInputModuleProcessorInterface */
            $moduleProcessor = $this->getModuleProcessorManager()->getProcessor($module);
            $args[$moduleProcessor->getName($module)] = $value;
        }
        return GeneralUtils::addQueryArgs($args, $url);
    }
}
