<?php
use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;

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

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface
    {
        return match($fieldName) {
			'multilayoutKeys' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'latestcountsTriggerValues' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'catsByName' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'commentsLazy' => \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver::class,
            'noheadercommentsLazy' => \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver::class,
            'addCommentURL' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            'topicsByName' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'appliestoByName' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
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
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match($fieldName) {
			'multilayoutKeys' => $translationAPI->__('', ''),
            'latestcountsTriggerValues' => $translationAPI->__('', ''),
            'catsByName' => $translationAPI->__('', ''),
            'commentsLazy' => $translationAPI->__('', ''),
            'noheadercommentsLazy' => $translationAPI->__('', ''),
            'addCommentURL' => $translationAPI->__('', ''),
            'topicsByName' => $translationAPI->__('', ''),
            'appliestoByName' => $translationAPI->__('', ''),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field,
        \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $post = $object;
        switch ($field->getName()) {
            case 'multilayoutKeys':
                // Allow pop-categorypostlayouts to add more layouts
                return \PoP\Root\App::applyFilters(
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
                if ($cats = $objectTypeResolver->resolveValue($post, 'categories', $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options)) {
                    foreach ($cats as $cat) {
                        $value[] = $type.'-'.$cat;
                    }
                } else {
                    $value[] = $type;
                }
                return $value;

         // Needed for using handlebars helper "compare" to compare a category id in a buttongroup, which is taken as a string, inside a list of cats, which must then also be strings
            case 'catsByName':
                $cats = $objectTypeResolver->resolveValue($post, 'categories', $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
                $value = array();
                foreach ($cats as $cat) {
                    $value[] = strval($cat);
                }
                return $value;

            case 'commentsLazy':
            case 'noheadercommentsLazy':
                return array();

            case 'addCommentURL':
                $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
                $post_name = $componentprocessor_manager->getComponentProcessor([PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_COMMENTPOST])->getName([PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_Application_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_COMMENTPOST]);
                return GeneralUtils::addQueryArgs([
                    $post_name => $objectTypeResolver->getID($post),
                ], RouteUtils::getRouteURL(POP_ADDCOMMENTS_ROUTE_ADDCOMMENT));

            case 'topicsByName':
                $selected = $objectTypeResolver->resolveValue($post, 'topics', $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
                $categories = new GD_FormInput_Categories('', $selected);
                return $categories->getSelectedValue();

            case 'appliestoByName':
                $selected = $objectTypeResolver->resolveValue($post, 'appliesto', $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
                $appliesto = new GD_FormInput_AppliesTo('', $selected);
                return $appliesto->getSelectedValue();
        }

        return parent::resolveValue($objectTypeResolver, $object, $field, $objectTypeFieldResolutionFeedbackStore);
    }
}

// Static Initialization: Attach
(new PoP_Application_DataLoad_ObjectTypeFieldResolver_FunctionalPosts())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
