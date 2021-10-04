<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryInputOutputHandlers\ResponseConstants;
use PoP\Engine\Route\RouteUtils;
use PoPSitesWassup\UserStateMutations\MutationResolvers\LostPasswordMutationResolver;
use PoPSitesWassup\UserStateMutations\MutationResolvers\MutationInputProperties;
use Symfony\Contracts\Service\Attribute\Required;

class LostPasswordMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    protected LostPasswordMutationResolver $lostPasswordMutationResolver;

    #[Required]
    final public function autowireLostPasswordMutationResolverBridge(
        LostPasswordMutationResolver $lostPasswordMutationResolver,
    ): void {
        $this->lostPasswordMutationResolver = $lostPasswordMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->lostPasswordMutationResolver;
    }

    public function getFormData(): array
    {
        return [
            MutationInputProperties::USERNAME_OR_EMAIL => $this->moduleProcessorManager->getProcessor([\PoP_Module_Processor_LoginTextFormInputs::class, \PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWD_USERNAME])->getValue([\PoP_Module_Processor_LoginTextFormInputs::class, \PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWD_USERNAME]),
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
