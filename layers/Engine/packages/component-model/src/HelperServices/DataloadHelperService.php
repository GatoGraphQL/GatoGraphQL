<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Translation\TranslationAPIInterface;

class DataloadHelperService implements DataloadHelperServiceInterface
{
    public function __construct(
        protected FeedbackMessageStoreInterface $feedbackMessageStore,
        protected FieldQueryInterpreterInterface $fieldQueryInterpreter,
        protected TranslationAPIInterface $translationAPI,
        protected ModuleProcessorManagerInterface $moduleProcessorManager,
    ) {
    }

    public function getTypeResolverClassFromSubcomponentDataField(TypeResolverInterface $typeResolver, string $subcomponent_data_field): ?string
    {
        $subcomponent_typeResolver_class = $typeResolver->resolveFieldTypeResolverClass($subcomponent_data_field);
        // if (!$subcomponent_typeResolver_class && \PoP\ComponentModel\Environment::failIfSubcomponentTypeDataLoaderUndefined()) {
        //     throw new \Exception(sprintf('There is no default typeResolver set for field  "%s" from typeResolver "%s" and typeResolver "%s" (%s)', $subcomponent_data_field, $typeResolver_class, $typeResolverClass, RequestUtils::getRequestedFullURL()));
        // }
        // If this field doesn't have a typeResolver, show a schema error
        // But if there are no FieldResolvers, then skip adding an error here, since that error will have been added already
        // Otherwise, there will appear 2 error messages:
        // 1. No FieldResolver
        // 2. No FieldDefaultTypeDataLoader
        if (!$subcomponent_typeResolver_class && $typeResolver->hasFieldResolversForField($subcomponent_data_field)) {
            // If there is an alias, store the results under this. Otherwise, on the fieldName+fieldArgs
            $subcomponent_data_field_outputkey = $this->fieldQueryInterpreter->getFieldOutputKey($subcomponent_data_field);
            $this->feedbackMessageStore->addSchemaError(
                $typeResolver->getTypeOutputName(),
                $subcomponent_data_field_outputkey,
                sprintf(
                    $this->translationAPI->__('Field \'%s\' is not a connection', 'pop-component-model'),
                    $subcomponent_data_field_outputkey
                )
            );
        }
        return $subcomponent_typeResolver_class;
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
            $moduleprocessor = $this->moduleProcessorManager->getProcessor($module);
            $args[$moduleprocessor->getName($module)] = $value;
        }
        return GeneralUtils::addQueryArgs($args, $url);
    }
}
