<?php

declare(strict_types=1);

namespace PoPSchema\Stances\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractFunctionalFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\Route\RouteUtils;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class CustomPostFunctionalFieldResolver extends AbstractFunctionalFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(
            IsCustomPostFieldInterfaceResolver::class,
        );
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

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'addStanceURL' => SchemaDefinition::TYPE_URL,
            'loggedInUserStances' => SchemaDefinition::TYPE_INT,
            'hasLoggedInUserStances' => SchemaDefinition::TYPE_BOOL,
            'editStanceURL' => SchemaDefinition::TYPE_URL,
            'postStancesProURL' => SchemaDefinition::TYPE_URL,
            'postStancesNeutralURL' => SchemaDefinition::TYPE_URL,
            'postStancesAgainstURL' => SchemaDefinition::TYPE_URL,
            'createStanceButtonLazy' => SchemaDefinition::TYPE_ID,
            'stancesLazy' => SchemaDefinition::TYPE_ID,
            'stanceName' => SchemaDefinition::TYPE_STRING,
            'catName' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match($fieldName) {
            'loggedInUserStances',
            'createStanceButtonLazy',
            'stancesLazy'
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
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
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $post = $resultItem;
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $cmseditpostsapi = \PoP\EditPosts\FunctionAPIFactory::getInstance();
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
                    $input_name => $typeResolver->getID($post),
                ], RouteUtils::getRouteURL($route));

            case 'loggedInUserStances':
                $vars = ApplicationState::getVars();
                if (!$vars['global-userstate']['is-user-logged-in']) {
                    return array();
                }
                $query = array(
                    'authors' => [$vars['global-userstate']['current-user-id']],
                );
                \UserStance_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsStancesaboutpost($query, $typeResolver->getID($post));

                return $customPostTypeAPI->getCustomPosts($query, ['return-type' => ReturnTypes::IDS]);

            case 'hasLoggedInUserStances':
                $referencedby = $typeResolver->resolveValue($resultItem, 'loggedInUserStances', $variables, $expressions, $options);
                return !empty($referencedby);

            case 'editStanceURL':
                if ($referencedby = $typeResolver->resolveValue($resultItem, 'loggedInUserStances', $variables, $expressions, $options)) {
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
                $selected = $typeResolver->resolveValue($resultItem, 'stance', $variables, $expressions, $options);
                $params = array(
                    'selected' => $selected
                );
                $stance = new \GD_FormInput_Stance($params);
                return $stance->getSelectedValue();
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
