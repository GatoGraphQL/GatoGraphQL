<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\ModuleProcessors\FormComponentModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\Translation\TranslationAPIInterface;

class DataloadHelperService implements DataloadHelperServiceInterface
{
    protected FeedbackMessageStoreInterface $feedbackMessageStore;
    protected FieldQueryInterpreterInterface $fieldQueryInterpreter;
    protected TranslationAPIInterface $translationAPI;
    protected ModuleProcessorManagerInterface $moduleProcessorManager;

    #[Required]
    public function autowireDataloadHelperService(FeedbackMessageStoreInterface $feedbackMessageStore, FieldQueryInterpreterInterface $fieldQueryInterpreter, TranslationAPIInterface $translationAPI, ModuleProcessorManagerInterface $moduleProcessorManager)
    {
        $this->feedbackMessageStore = $feedbackMessageStore;
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
        $this->translationAPI = $translationAPI;
        $this->moduleProcessorManager = $moduleProcessorManager;
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
                $subcomponent_data_field_outputkey = $this->fieldQueryInterpreter->getFieldOutputKey($subcomponent_data_field);
                $this->feedbackMessageStore->addSchemaError(
                    $objectTypeResolver->getTypeOutputName(),
                    $subcomponent_data_field_outputkey,
                    sprintf(
                        $this->translationAPI->__('Field \'%s\' is not a connection', 'pop-component-model'),
                        $subcomponent_data_field_outputkey
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
            /** @var FormComponentModuleProcessorInterface */
            $moduleprocessor = $this->moduleProcessorManager->getProcessor($module);
            $args[$moduleprocessor->getName($module)] = $value;
        }
        return GeneralUtils::addQueryArgs($args, $url);
    }
}
