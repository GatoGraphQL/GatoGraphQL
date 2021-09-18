<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\Engine\Route\RouteUtils;
use PoP\ComponentModel\QueryInputOutputHandlers\ResponseConstants;
use PoPSitesWassup\UserStateMutations\MutationResolvers\MutationInputProperties;
use PoPSitesWassup\UserStateMutations\MutationResolvers\LostPasswordMutationResolver;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;

class LostPasswordMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function __construct(
        protected \PoP\Hooks\HooksAPIInterface $hooksAPI,
        protected \PoP\Translation\TranslationAPIInterface $translationAPI,
        protected \PoP\ComponentModel\Instances\InstanceManagerInterface $instanceManager,
        protected \PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface $mutationResolutionManager,
        protected LostPasswordMutationResolver $LostPasswordMutationResolver,
    ) {
        parent::__construct(
            $hooksAPI,
            $translationAPI,
            $instanceManager,
            $mutationResolutionManager,
        );
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->LostPasswordMutationResolver;
    }

    public function getFormData(): array
    {
        return [
            MutationInputProperties::USERNAME_OR_EMAIL => $this->moduleProcessorManager->getProcessor([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWD_USERNAME])->getValue([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWD_USERNAME]),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function executeMutation(array &$data_properties): ?array
    {
        $executed = parent::executeMutation($data_properties);
        if ($executed && is_array($executed) && $executed[ResponseConstants::SUCCESS]) {
            // Redirect to the "Reset password" page
            $executed[GD_DATALOAD_QUERYHANDLERRESPONSE_SOFTREDIRECT] = RouteUtils::getRouteURL(POP_USERLOGIN_ROUTE_LOSTPWDRESET);
        }
        return $executed;
    }
}
