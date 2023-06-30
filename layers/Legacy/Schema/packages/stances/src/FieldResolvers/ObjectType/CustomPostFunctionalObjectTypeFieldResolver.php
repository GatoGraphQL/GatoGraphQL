<?php

declare(strict_types=1);

namespace PoPSchema\Stances\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use GD_FormInput_Stance;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\EditPosts\FunctionAPIFactory;
use PoP\Engine\Route\RouteUtils;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\Root\App;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;
use UserStance_Module_Processor_CustomSectionBlocksUtils;

class CustomPostFunctionalObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?URLScalarTypeResolver $urlScalarTypeResolver = null;
    
    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        if ($this->booleanScalarTypeResolver === null) {
            /** @var BooleanScalarTypeResolver */
            $booleanScalarTypeResolver = $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
            $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        }
        return $this->booleanScalarTypeResolver;
    }
    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        if ($this->idScalarTypeResolver === null) {
            /** @var IDScalarTypeResolver */
            $idScalarTypeResolver = $this->instanceManager->getInstance(IDScalarTypeResolver::class);
            $this->idScalarTypeResolver = $idScalarTypeResolver;
        }
        return $this->idScalarTypeResolver;
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        if ($this->intScalarTypeResolver === null) {
            /** @var IntScalarTypeResolver */
            $intScalarTypeResolver = $this->instanceManager->getInstance(IntScalarTypeResolver::class);
            $this->intScalarTypeResolver = $intScalarTypeResolver;
        }
        return $this->intScalarTypeResolver;
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final public function setURLScalarTypeResolver(URLScalarTypeResolver $urlScalarTypeResolver): void
    {
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
    }
    final protected function getURLScalarTypeResolver(): URLScalarTypeResolver
    {
        if ($this->urlScalarTypeResolver === null) {
            /** @var URLScalarTypeResolver */
            $urlScalarTypeResolver = $this->instanceManager->getInstance(URLScalarTypeResolver::class);
            $this->urlScalarTypeResolver = $urlScalarTypeResolver;
        }
        return $this->urlScalarTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'addStanceURL',
            'loggedInUserStances',
            'hasLoggedInUserStances',
            'editStanceURL',
            'postStancesProURL',
            'postStancesNeutralURL',
            'postStancesAgainstURL',
            'createStanceButtonLazy',
            'stancesLazy',
            'stanceName',
            'catName',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match($fieldName) {
            'addStanceURL' => $this->getUrlScalarTypeResolver(),
            'loggedInUserStances' => $this->getIntScalarTypeResolver(),
            'hasLoggedInUserStances' => $this->getBooleanScalarTypeResolver(),
            'editStanceURL' => $this->getUrlScalarTypeResolver(),
            'postStancesProURL' => $this->getUrlScalarTypeResolver(),
            'postStancesNeutralURL' => $this->getUrlScalarTypeResolver(),
            'postStancesAgainstURL' => $this->getUrlScalarTypeResolver(),
            'createStanceButtonLazy' => $this->getIdScalarTypeResolver(),
            'stancesLazy' => $this->getIdScalarTypeResolver(),
            'stanceName' => $this->getStringScalarTypeResolver(),
            'catName' => $this->getStringScalarTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'loggedInUserStances',
            'createStanceButtonLazy',
            'stancesLazy'
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match($fieldName) {
            'addStanceURL' => $this->getTranslationAPI()->__('', ''),
            'loggedInUserStances' => $this->getTranslationAPI()->__('', ''),
            'hasLoggedInUserStances' => $this->getTranslationAPI()->__('', ''),
            'editStanceURL' => $this->getTranslationAPI()->__('', ''),
            'postStancesProURL' => $this->getTranslationAPI()->__('', ''),
            'postStancesNeutralURL' => $this->getTranslationAPI()->__('', ''),
            'postStancesAgainstURL' => $this->getTranslationAPI()->__('', ''),
            'createStanceButtonLazy' => $this->getTranslationAPI()->__('', ''),
            'stancesLazy' => $this->getTranslationAPI()->__('', ''),
            'stanceName' => $this->getTranslationAPI()->__('', ''),
            'catName' => $this->getTranslationAPI()->__('', ''),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $post = $object;
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $cmseditpostsapi = FunctionAPIFactory::getInstance();
        switch ($fieldDataAccessor->getFieldName()) {
            case 'addStanceURL':
                $routes = array(
                    'addStanceURL' => POP_USERSTANCE_ROUTE_ADDSTANCE,
                );
                $route = $routes[$fieldDataAccessor->getFieldName()];

                // $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();
                // $input = [PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::COMPONENT_FORMCOMPONENT_CARD_STANCETARGET];
                // $input_name = $componentprocessor_manager->getComponentProcessor($input)->getName($input);
                $input_name = POP_INPUTNAME_STANCETARGET;
                return GeneralUtils::addQueryArgs([
                    $input_name => $objectTypeResolver->getID($post),
                ], RouteUtils::getRouteURL($route));

            case 'loggedInUserStances':
                if (!App::getState('is-user-logged-in')) {
                    return array();
                }
                $query = array(
                    'authors' => [App::getState('current-user-id')],
                );
                UserStance_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsStancesaboutpost($query, $objectTypeResolver->getID($post));

                return $customPostTypeAPI->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'hasLoggedInUserStances':
                $referencedby = $objectTypeResolver->resolveValue(
                    $object,
                    new LeafField(
                        'loggedInUserStances',
                        null,
                        [],
                        [],
                        $fieldDataAccessor->getField()->getLocation()
                    ),
                    $objectTypeFieldResolutionFeedbackStore,
                );
                return !empty($referencedby);

            case 'editStanceURL':
                if ($referencedby = $objectTypeResolver->resolveValue(
                    $object,
                    new LeafField(
                        'loggedInUserStances',
                        null,
                        [],
                        [],
                        $fieldDataAccessor->getField()->getLocation()
                    ),
                    $objectTypeFieldResolutionFeedbackStore,
                    )) {
                    return urldecode($cmseditpostsapi->getEditPostLink($referencedby[0]));
                }
                return null;

            case 'postStancesProURL':
            case 'postStancesNeutralURL':
            case 'postStancesAgainstURL':
                $routes = array(
                    'postStancesProURL' => POP_USERSTANCE_ROUTE_STANCES_PRO,
                    'postStancesNeutralURL' => POP_USERSTANCE_ROUTE_STANCES_NEUTRAL,
                    'postStancesAgainstURL' => POP_USERSTANCE_ROUTE_STANCES_AGAINST,
                );
                $url = $customPostTypeAPI->getPermalink($post);
                return RequestUtils::addRoute($url, $routes[$fieldDataAccessor->getFieldName()]);

            // Lazy Loading fields
            case 'createStanceButtonLazy':
                return null;

            case 'stancesLazy':
                return array();

            case 'stanceName':
            case 'catName':
                $selected = $objectTypeResolver->resolveValue(
                    $object,
                    new LeafField(
                        'stance',
                        null,
                        [],
                        [],
                        $fieldDataAccessor->getField()->getLocation()
                    ),
                    $objectTypeFieldResolutionFeedbackStore,
                );
                $stance = new GD_FormInput_Stance('', $selected);
                return $stance->getSelectedValue();
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
