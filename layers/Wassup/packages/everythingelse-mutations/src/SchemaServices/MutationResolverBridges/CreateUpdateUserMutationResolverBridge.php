<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Facades\ModuleProcessors\ModuleProcessorManagerFacade;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\CreateUpdateUserMutationResolver;

class CreateUpdateUserMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return CreateUpdateUserMutationResolver::class;
    }

    /**
     * @param mixed $result_id Maybe an int, maybe a string
     */
    public function getSuccessString($result_id): ?string
    {
        // For the update, gotta return the success string
        // If user is logged in => It's Update
        // Otherwise => It's Create
        $vars = ApplicationState::getVars();
        if ($vars['global-userstate']['is-user-logged-in']) {
            // Allow PoP Service Workers to add the attr to avoid the link being served from the browser cache
            return sprintf(
                TranslationAPIFacade::getInstance()->__('View your <a href="%s" target="%s" %s>updated profile</a>.', 'pop-application'),
                getAuthorProfileUrl($vars['global-userstate']['current-user-id']),
                PoP_Application_Utils::getPreviewTarget(),
                HooksAPIFacade::getInstance()->applyFilters('GD_DataLoad_ActionExecuter_CreateUpdate_UserBase:success_msg:linkattrs', '')
            );
        }
    }

    public function getFormData(): array
    {
        $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();

        $cmseditusershelpers = \PoP\EditUsers\HelperAPIFactory::getInstance();
        $cmsapplicationhelpers = \PoP\Application\HelperAPIFactory::getInstance();
        $vars = ApplicationState::getVars();
        $user_id = $vars['global-userstate']['is-user-logged-in'] ? $vars['global-userstate']['current-user-id'] : '';
        $inputs = $this->getFormInputs();
        $form_data = array(
            'user_id' => $user_id,
            'username' => $cmseditusershelpers->sanitizeUsername($moduleprocessor_manager->getProcessor($inputs['username'])->getValue($inputs['username'])),
            'password' => $moduleprocessor_manager->getProcessor($inputs['password'])->getValue($inputs['password']),
            'repeat_password' => $moduleprocessor_manager->getProcessor($inputs['repeat_password'])->getValue($inputs['repeat_password']),
            'first_name' => trim($cmsapplicationhelpers->escapeAttributes($moduleprocessor_manager->getProcessor($inputs['first_name'])->getValue($inputs['first_name']))),
            'user_email' => trim($moduleprocessor_manager->getProcessor($inputs['user_email'])->getValue($inputs['user_email'])),
            'description' => trim($moduleprocessor_manager->getProcessor($inputs['description'])->getValue($inputs['description'])),
            'user_url' => trim($moduleprocessor_manager->getProcessor($inputs['user_url'])->getValue($inputs['user_url'])),
        );

        if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
            $form_data['captcha'] = $moduleprocessor_manager->getProcessor($inputs['captcha'])->getValue($inputs['captcha']);
        }

        // Allow to add extra inputs
        $form_data = HooksAPIFacade::getInstance()->applyFilters('gd_createupdate_user:form_data', $form_data);

        if ($user_id) {
            $form_data = $this->getUpdateuserFormData($form_data);
        } else {
            $form_data = $this->getCreateuserFormData($form_data);
        }

        return $form_data;
    }

    protected function getCreateuserFormData(array $form_data)
    {
        // Allow to add extra inputs
        $form_data = HooksAPIFacade::getInstance()->applyFilters('gd_createupdate_user:form_data:create', $form_data);

        return $form_data;
    }

    protected function getUpdateuserFormData(array $form_data)
    {
        // Allow to add extra inputs
        $form_data = HooksAPIFacade::getInstance()->applyFilters('gd_createupdate_user:form_data:update', $form_data);

        return $form_data;
    }

    private function getFormInputs()
    {
        $form_inputs = array(
            'username' => null,
            'password' => null,
            'repeat_password' => null,
            'first_name' => null,
            'user_email' => null,
            'description' => null,
            'user_url' => null,
        );

        if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
            $form_inputs['captcha'] = null;
        }

        $inputs = HooksAPIFacade::getInstance()->applyFilters(
            'GD_CreateUpdate_User:form-inputs',
            $form_inputs
        );

        // If any input is null, throw an exception
        $null_inputs = array_filter($inputs, 'is_null');
        if ($null_inputs) {
            throw new Exception(
                sprintf(
                    'No form inputs defined for: %s',
                    '"' . implode('", "', array_keys($null_inputs)) . '"'
                )
            );
        }

        return $inputs;
    }
}
