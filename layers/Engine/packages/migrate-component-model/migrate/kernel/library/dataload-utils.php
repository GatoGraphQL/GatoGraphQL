<?php
namespace PoP\ComponentModel;

use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Schema\FeedbackMessageStoreFacade;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

class DataloadUtils
{
    public static function getTypeResolverClassFromSubcomponentDataField(TypeResolverInterface $typeResolver, $subcomponent_data_field)
    {
        $subcomponent_typeResolver_class = $typeResolver->resolveFieldTypeResolverClass($subcomponent_data_field);
        // if (!$subcomponent_typeResolver_class && \PoP\ComponentModel\Server\Utils::failIfSubcomponentTypeDataLoaderUndefined()) {
        //     throw new \Exception(sprintf('There is no default typeResolver set for field  "%s" from typeResolver "%s" and typeResolver "%s" (%s)', $subcomponent_data_field, $typeResolver_class, $typeResolverClass, RequestUtils::getRequestedFullURL()));
        // }
        // If this field doesn't have a typeResolver, show a schema error
        // But if there are no FieldResolvers, then skip adding an error here, since that error will have been added already
        // Otherwise, there will appear 2 error messages:
        // 1. No FieldResolver
        // 2. No FieldDefaultTypeDataLoader
        if (!$subcomponent_typeResolver_class && $typeResolver->hasFieldResolversForField($subcomponent_data_field)) {
            $translationAPI = TranslationAPIFacade::getInstance();
            // If there is an alias, store the results under this. Otherwise, on the fieldName+fieldArgs
            $subcomponent_data_field_outputkey = FieldQueryInterpreterFacade::getInstance()->getFieldOutputKey($subcomponent_data_field);
            FeedbackMessageStoreFacade::getInstance()->addSchemaError(
                $typeResolver->getTypeOutputName(),
                $subcomponent_data_field_outputkey,
                sprintf(
                    $translationAPI->__('No “typeResolver” has been set for field \'%s\' to load relational data', 'pop-component-model'),
                    $subcomponent_data_field_outputkey
                )
            );
        }
        return $subcomponent_typeResolver_class;
    }

    public static function addFilterParams($url, $moduleValues = array())
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        $args = [];
        foreach ($moduleValues as $moduleValue) {
            $module = $moduleValue['module'];
            $value = $moduleValue['value'];
            $moduleprocessor = $moduleprocessor_manager->getProcessor($module);
            $args[$moduleprocessor->getName($module)] = $value;
        }
        return GeneralUtils::addQueryArgs($args, $url);
    }
}
