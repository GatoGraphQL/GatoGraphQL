<?php
use PoP\Engine\Route\RouteUtils;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoP\ComponentModel\FieldResolvers\AbstractFunctionalFieldResolver;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;

class PoP_Application_DataLoad_FieldResolver_FunctionalPosts extends AbstractFunctionalFieldResolver
{
    public static function getClassesToAttachTo(): array
    {
        return array(
            IsCustomPostFieldInterfaceResolver::class,
        );
    }

    public static function getFieldNamesToResolve(): array
    {
        return [
            'multilayoutKeys',
            'latestcountsTriggerValues',
            'catsByName',
            'commentsLazy',
            'noheadercommentsLazy',
            'addCommentURL',
            'topicsByName',
            'appliestoByName',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
			'multilayoutKeys' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
            'latestcountsTriggerValues' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
            'catsByName' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
            'commentsLazy' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'noheadercommentsLazy' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'addCommentURL' => SchemaDefinition::TYPE_URL,
            'topicsByName' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
            'appliestoByName' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'multilayoutKeys' => $translationAPI->__('', ''),
            'latestcountsTriggerValues' => $translationAPI->__('', ''),
            'catsByName' => $translationAPI->__('', ''),
            'commentsLazy' => $translationAPI->__('', ''),
            'noheadercommentsLazy' => $translationAPI->__('', ''),
            'addCommentURL' => $translationAPI->__('', ''),
            'topicsByName' => $translationAPI->__('', ''),
            'appliestoByName' => $translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     * @return mixed
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ) {
        $post = $resultItem;
        switch ($fieldName) {
            case 'multilayoutKeys':
                // Allow pop-categorypostlayouts to add more layouts
                return HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_Application:TypeResolver_Posts:multilayout-keys',
                    array(
                        strtolower($typeResolver->getTypeName()),
                    ),
                    $typeResolver->getID($post),
                    $typeResolver
                );

            case 'latestcountsTriggerValues':
                $value = array();
                $type = strtolower($typeResolver->getTypeName());
                // If it has categories, use it. Otherwise, only use the post type
                if ($cats = $typeResolver->resolveValue($post, 'categories', $variables, $expressions, $options)) {
                    foreach ($cats as $cat) {
                        $value[] = $type.'-'.$cat;
                    }
                } else {
                    $value[] = $type;
                }
                return $value;

         // Needed for using handlebars helper "compare" to compare a category id in a buttongroup, which is taken as a string, inside a list of cats, which must then also be strings
            case 'catsByName':
                $cats = $typeResolver->resolveValue($post, 'categories', $variables, $expressions, $options);
                $value = array();
                foreach ($cats as $cat) {
                    $value[] = strval($cat);
                }
                return $value;

            case 'commentsLazy':
            case 'noheadercommentsLazy':
                return array();

            case 'addCommentURL':
                $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
                $post_name = $moduleprocessor_manager->getProcessor([PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_COMMENTPOST])->getName([PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_COMMENTPOST]);
                return GeneralUtils::addQueryArgs([
                    $post_name => $typeResolver->getID($post),
                ], RouteUtils::getRouteURL(POP_ADDCOMMENTS_ROUTE_ADDCOMMENT));

            case 'topicsByName':
                $selected = $typeResolver->resolveValue($post, 'topics', $variables, $expressions, $options);
                $params = array(
                    'selected' => $selected
                );
                $categories = new GD_FormInput_Categories($params);
                return $categories->getSelectedValue();

            case 'appliestoByName':
                $selected = $typeResolver->resolveValue($post, 'appliesto', $variables, $expressions, $options);
                $params = array(
                    'selected' => $selected
                );
                $appliesto = new GD_FormInput_AppliesTo($params);
                return $appliesto->getSelectedValue();
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
PoP_Application_DataLoad_FieldResolver_FunctionalPosts::attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
