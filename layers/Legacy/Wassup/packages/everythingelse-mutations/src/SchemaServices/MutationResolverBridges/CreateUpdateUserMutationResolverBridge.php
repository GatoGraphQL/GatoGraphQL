<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolverBridges;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use Exception;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\EditUsers\HelperAPIFactory;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\Root\App;
use PoP\Root\Exception\GenericSystemException;
use PoP_Application_Utils;
use PoP_Forms_ConfigurationUtils;
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

    public function addArgumentsForMutation(FieldInterface $mutationField): void
    {
        $cmseditusershelpers = HelperAPIFactory::getInstance();
        $cmsapplicationhelpers = \PoP\Application\HelperAPIFactory::getInstance();
        $user_id = App::getState('is-user-logged-in') ? App::getState('current-user-id') : '';
        $inputs = $this->getFormInputs();
        
        $mutationField->addArgument(new Argument('user_id', new Literal($user_id, LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('username', new Literal($cmseditusershelpers->sanitizeUsername($this->getComponentProcessorManager()->getComponentProcessor($inputs['username'])->getValue($inputs['username'])), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('password', new Literal($this->getComponentProcessorManager()->getComponentProcessor($inputs['password'])->getValue($inputs['password']), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('repeat_password', new Literal($this->getComponentProcessorManager()->getComponentProcessor($inputs['repeat_password'])->getValue($inputs['repeat_password']), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('first_name', new Literal(trim($cmsapplicationhelpers->escapeAttributes($this->getComponentProcessorManager()->getComponentProcessor($inputs['first_name'])->getValue($inputs['first_name']))), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('user_email', new Literal(trim($this->getComponentProcessorManager()->getComponentProcessor($inputs['user_email'])->getValue($inputs['user_email'])), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('description', new Literal(trim($this->getComponentProcessorManager()->getComponentProcessor($inputs['description'])->getValue($inputs['description'])), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        $mutationField->addArgument(new Argument('user_url', new Literal(trim($this->getComponentProcessorManager()->getComponentProcessor($inputs['user_url'])->getValue($inputs['user_url'])), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));

        if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
            $mutationField->addArgument(new Argument('captcha', new Literal($this->getComponentProcessorManager()->getComponentProcessor($inputs['captcha'])->getValue($inputs['captcha']), LocationHelper::getNonSpecificLocation()), LocationHelper::getNonSpecificLocation()));
        }

        // Allow to add extra inputs
        App::doAction('gd_createupdate_user:form_data', $mutationField);

        if ($user_id) {
            $this->getUpdateuserFormData($mutationField);
        } else {
            $this->getCreateuserFormData($mutationField);
        }
    }

    protected function getCreateuserFormData(FieldInterface $mutationField)
    {
        // Allow to add extra inputs
        App::doAction('gd_createupdate_user:form_data:create', $mutationField);
    }

    protected function getUpdateuserFormData(FieldInterface $mutationField)
    {
        // Allow to add extra inputs
        App::doAction('gd_createupdate_user:form_data:update', $mutationField);
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
