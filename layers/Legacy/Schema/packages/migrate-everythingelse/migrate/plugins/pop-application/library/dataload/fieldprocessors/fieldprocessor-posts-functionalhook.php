<?php
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Route\RouteUtils;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;

class PoP_Application_DataLoad_ObjectTypeFieldResolver_FunctionalPosts extends AbstractObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
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

    public function getFieldTypeResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        $types = [
			'multilayoutKeys' => \PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'latestcountsTriggerValues' => \PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'catsByName' => \PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'commentsLazy' => \PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver::class,
            'noheadercommentsLazy' => \PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver::class,
            'addCommentURL' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            'topicsByName' => \PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'appliestoByName' => \PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolverClass($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match($fieldName) {
            'multilayoutKeys',
            'latestcountsTriggerValues',
            'catsByName',
            'commentsLazy',
            'noheadercommentsLazy',
            'topicsByName',
            'appliestoByName'
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
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
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $post = $object;
        switch ($fieldName) {
            case 'multilayoutKeys':
                // Allow pop-categorypostlayouts to add more layouts
                return HooksAPIFacade::getInstance()->applyFilters(
                    'PoP_Application:TypeResolver_Posts:multilayout-keys',
                    array(
                        strtolower($objectTypeResolver->getTypeName()),
                    ),
                    $objectTypeResolver->getID($post),
                    $objectTypeResolver
                );

            case 'latestcountsTriggerValues':
                $value = array();
                $type = strtolower($objectTypeResolver->getTypeName());
                // If it has categories, use it. Otherwise, only use the post type
                if ($cats = $objectTypeResolver->resolveValue($post, 'categories', $variables, $expressions, $options)) {
                    foreach ($cats as $cat) {
                        $value[] = $type.'-'.$cat;
                    }
                } else {
                    $value[] = $type;
                }
                return $value;

         // Needed for using handlebars helper "compare" to compare a category id in a buttongroup, which is taken as a string, inside a list of cats, which must then also be strings
            case 'catsByName':
                $cats = $objectTypeResolver->resolveValue($post, 'categories', $variables, $expressions, $options);
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
                    $post_name => $objectTypeResolver->getID($post),
                ], RouteUtils::getRouteURL(POP_ADDCOMMENTS_ROUTE_ADDCOMMENT));

            case 'topicsByName':
                $selected = $objectTypeResolver->resolveValue($post, 'topics', $variables, $expressions, $options);
                $params = array(
                    'selected' => $selected
                );
                $categories = new GD_FormInput_Categories($params);
                return $categories->getSelectedValue();

            case 'appliestoByName':
                $selected = $objectTypeResolver->resolveValue($post, 'appliesto', $variables, $expressions, $options);
                $params = array(
                    'selected' => $selected
                );
                $appliesto = new GD_FormInput_AppliesTo($params);
                return $appliesto->getSelectedValue();
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new PoP_Application_DataLoad_ObjectTypeFieldResolver_FunctionalPosts())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
