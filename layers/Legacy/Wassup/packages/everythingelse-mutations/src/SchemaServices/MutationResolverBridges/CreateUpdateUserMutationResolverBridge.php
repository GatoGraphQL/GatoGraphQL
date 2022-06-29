<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP_Application_Utils;
use PoP_Forms_ConfigurationUtils;
use PoP\Root\Exception\GenericSystemException;
use PoP\Root\App;
use Exception;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\EditUsers\HelperAPIFactory;
use PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers\CreateUpdateUserMutationResolver;

class CreateUpdateUserMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    private ?CreateUpdateUserMutationResolver $createUpdateUserMutationResolver = null;
    
    final public function setCreateUpdateUserMutationResolver(CreateUpdateUserMutationResolver $createUpdateUserMutationResolver): void
    {
        $this->createUpdateUserMutationResolver = $createUpdateUserMutationResolver;
    }
    final protected function getCreateUpdateUserMutationResolver(): CreateUpdateUserMutationResolver
    {
        return $this->createUpdateUserMutationResolver ??= $this->instanceManager->getInstance(CreateUpdateUserMutationResolver::class);
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getCreateUpdateUserMutationResolver();
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        // For the update, gotta return the success string
        // If user is logged in => It's Update
        // Otherwise => It's Create
        if (App::getState('is-user-logged-in')) {
            // Allow PoP Service Workers to add the attr to avoid the link being served from the browser cache
            return sprintf(
                $this->getTranslationAPI()->__('View your <a href="%s" target="%s" %s>updated profile</a>.', 'pop-application'),
                getAuthorProfileUrl(App::getState('current-user-id')),
                PoP_Application_Utils::getPreviewTarget(),
                App::applyFilters('GD_DataLoad_ActionExecuter_CreateUpdate_UserBase:success_msg:linkattrs', '')
            );
        }
    }

    public function addArgumentsForMutation(WithArgumentsInterface $withArgumentsAST): void
    {
        $cmseditusershelpers = HelperAPIFactory::getInstance();
        $cmsapplicationhelpers = \PoP\Application\HelperAPIFactory::getInstance();
        $user_id = App::getState('is-user-logged-in') ? App::getState('current-user-id') : '';
        $inputs = $this->getFormInputs();
        
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('user_id', $user_id, \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('username', $cmseditusershelpers->sanitizeUsername($this->getComponentProcessorManager()->getComponentProcessor($inputs['username'])->getValue($inputs['username'])), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('password', $this->getComponentProcessorManager()->getComponentProcessor($inputs['password'])->getValue($inputs['password']), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('repeat_password', $this->getComponentProcessorManager()->getComponentProcessor($inputs['repeat_password'])->getValue($inputs['repeat_password']), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('first_name', trim($cmsapplicationhelpers->escapeAttributes($this->getComponentProcessorManager()->getComponentProcessor($inputs['first_name'])->getValue($inputs['first_name']))), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('user_email', trim($this->getComponentProcessorManager()->getComponentProcessor($inputs['user_email'])->getValue($inputs['user_email'])), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('description', trim($this->getComponentProcessorManager()->getComponentProcessor($inputs['description'])->getValue($inputs['description'])), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('user_url', trim($this->getComponentProcessorManager()->getComponentProcessor($inputs['user_url'])->getValue($inputs['user_url'])), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));

        if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
            $withArgumentsAST->addArgument(new \PoP\GraphQLParser\Spec\Parser\Ast\Argument('captcha', $this->getComponentProcessorManager()->getComponentProcessor($inputs['captcha'])->getValue($inputs['captcha']), \PoP\GraphQLParser\StaticHelpers\LocationHelper::getNonSpecificLocation()));
        }

        // Allow to add extra inputs
        $form_data = App::applyFilters('gd_createupdate_user:form_data', $form_data);

        if ($user_id) {
            $this->getUpdateuserFormData($withArgumentsAST);
        } else {
            $this->getCreateuserFormData($withArgumentsAST);
        }
    }

    protected function getCreateuserFormData(WithArgumentsInterface $withArgumentsAST)
    {
        // Allow to add extra inputs
        App::doAction('gd_createupdate_user:form_data:create', $withArgumentsAST);
    }

    protected function getUpdateuserFormData(WithArgumentsInterface $withArgumentsAST)
    {
        // Allow to add extra inputs
        App::doAction('gd_createupdate_user:form_data:update', $withArgumentsAST);
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

        $inputs = App::applyFilters(
            'GD_CreateUpdate_User:form-inputs',
            $form_inputs
        );

        // If any input is null, throw an exception
        $null_inputs = array_filter($inputs, 'is_null');
        if ($null_inputs) {
            throw new GenericSystemException(
                sprintf(
                    'No form inputs defined for: %s',
                    '"' . implode('", "', array_keys($null_inputs)) . '"'
                )
            );
        }

        return $inputs;
    }
}
