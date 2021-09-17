<?php

declare(strict_types=1);

namespace PoPSchema\Stances\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\EditPosts\FunctionAPIFactory;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Route\RouteUtils;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class CustomPostFunctionalObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    public function __construct(
        \PoP\Translation\TranslationAPIInterface $translationAPI,
        \PoP\Hooks\HooksAPIInterface $hooksAPI,
        \PoP\ComponentModel\Instances\InstanceManagerInterface $instanceManager,
        \PoP\ComponentModel\Schema\FieldQueryInterpreterInterface $fieldQueryInterpreter,
        \PoP\LooseContracts\NameResolverInterface $nameResolver,
        \PoP\Engine\CMS\CMSServiceInterface $cmsService,
        \PoP\ComponentModel\HelperServices\SemverHelperServiceInterface $semverHelperService,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $fieldQueryInterpreter,
            $nameResolver,
            $cmsService,
            $semverHelperService,
        );
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }

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
        $types = [
            'addStanceURL' => $this->instanceManager->getInstance(URLScalarTypeResolver::class),
            'loggedInUserStances' => $this->instanceManager->getInstance(IntScalarTypeResolver::class),
            'hasLoggedInUserStances' => $this->instanceManager->getInstance(BooleanScalarTypeResolver::class),
            'editStanceURL' => $this->instanceManager->getInstance(URLScalarTypeResolver::class),
            'postStancesProURL' => $this->instanceManager->getInstance(URLScalarTypeResolver::class),
            'postStancesNeutralURL' => $this->instanceManager->getInstance(URLScalarTypeResolver::class),
            'postStancesAgainstURL' => $this->instanceManager->getInstance(URLScalarTypeResolver::class),
            'createStanceButtonLazy' => $this->instanceManager->getInstance(IDScalarTypeResolver::class),
            'stancesLazy' => $this->instanceManager->getInstance(IDScalarTypeResolver::class),
            'stanceName' => $this->instanceManager->getInstance(StringScalarTypeResolver::class),
            'catName' => $this->instanceManager->getInstance(StringScalarTypeResolver::class),
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'loggedInUserStances',
            'createStanceButtonLazy',
            'stancesLazy'
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'addStanceURL' => $this->translationAPI->__('', ''),
            'loggedInUserStances' => $this->translationAPI->__('', ''),
            'hasLoggedInUserStances' => $this->translationAPI->__('', ''),
            'editStanceURL' => $this->translationAPI->__('', ''),
            'postStancesProURL' => $this->translationAPI->__('', ''),
            'postStancesNeutralURL' => $this->translationAPI->__('', ''),
            'postStancesAgainstURL' => $this->translationAPI->__('', ''),
            'createStanceButtonLazy' => $this->translationAPI->__('', ''),
            'stancesLazy' => $this->translationAPI->__('', ''),
            'stanceName' => $this->translationAPI->__('', ''),
            'catName' => $this->translationAPI->__('', ''),
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
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $cmseditpostsapi = FunctionAPIFactory::getInstance();
        switch ($fieldName) {
            case 'addStanceURL':
                $routes = array(
                    'addStanceURL' => POP_USERSTANCE_ROUTE_ADDSTANCE,
                );
                $route = $routes[$fieldName];

                // $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
                // $input = [PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::class, PoP_UserStance_Module_Processor_PostTriggerLayoutFormComponentValues::MODULE_FORMCOMPONENT_CARD_STANCETARGET];
                // $input_name = $moduleprocessor_manager->getProcessor($input)->getName($input);
                $input_name = POP_INPUTNAME_STANCETARGET;
                return GeneralUtils::addQueryArgs([
                    $input_name => $objectTypeResolver->getID($post),
                ], RouteUtils::getRouteURL($route));

            case 'loggedInUserStances':
                $vars = ApplicationState::getVars();
                if (!$vars['global-userstate']['is-user-logged-in']) {
                    return array();
                }
                $query = array(
                    'authors' => [$vars['global-userstate']['current-user-id']],
                );
                \UserStance_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsStancesaboutpost($query, $objectTypeResolver->getID($post));

                return $customPostTypeAPI->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'hasLoggedInUserStances':
                $referencedby = $objectTypeResolver->resolveValue($object, 'loggedInUserStances', $variables, $expressions, $options);
                return !empty($referencedby);

            case 'editStanceURL':
                if ($referencedby = $objectTypeResolver->resolveValue($object, 'loggedInUserStances', $variables, $expressions, $options)) {
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
                return RequestUtils::addRoute($url, $routes[$fieldName]);

            // Lazy Loading fields
            case 'createStanceButtonLazy':
                return null;

            case 'stancesLazy':
                return array();

            case 'stanceName':
            case 'catName':
                $selected = $objectTypeResolver->resolveValue($object, 'stance', $variables, $expressions, $options);
                $params = array(
                    'selected' => $selected
                );
                $stance = new \GD_FormInput_Stance($params);
                return $stance->getSelectedValue();
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
