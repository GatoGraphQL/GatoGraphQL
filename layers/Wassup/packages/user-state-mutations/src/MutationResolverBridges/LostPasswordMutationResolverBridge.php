<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolverBridges;

use PoP\Engine\Route\RouteUtils;
use PoP\ComponentModel\QueryInputOutputHandlers\ResponseConstants;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoPSitesWassup\UserStateMutations\MutationResolvers\MutationInputProperties;
use PoPSitesWassup\UserStateMutations\MutationResolvers\LostPasswordMutationResolver;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;

class LostPasswordMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return LostPasswordMutationResolver::class;
    }

    public function getFormData(): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        return [
            MutationInputProperties::USERNAME_OR_EMAIL => $moduleprocessor_manager->getProcessor([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWD_USERNAME])->getValue([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWD_USERNAME]),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function execute(array &$data_properties): ?array
    {
        $executed = parent::execute($data_properties);
        if ($executed && is_array($executed) && $executed[ResponseConstants::SUCCESS]) {
            // Redirect to the "Reset password" page
            $executed[GD_DATALOAD_QUERYHANDLERRESPONSE_SOFTREDIRECT] = RouteUtils::getRouteURL(POP_USERLOGIN_ROUTE_LOSTPWDRESET);
        }
        return $executed;
    }
}
