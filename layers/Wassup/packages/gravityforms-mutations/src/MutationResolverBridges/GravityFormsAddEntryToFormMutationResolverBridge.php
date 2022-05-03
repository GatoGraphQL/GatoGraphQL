<?php

declare(strict_types=1);

namespace PoPSitesWassup\GravityFormsMutations\MutationResolverBridges;

use GD_GF_Module_Processor_TextFormInputs;
use PoP_FormUtils;
use PoP_Forms_Module_Processor_TextFormInputs;
use PoP_Forms_ConfigurationUtils;
use PoP_Module_Processor_CaptchaFormInputs;
use GD_Captcha;
use PoP\ComponentModel\App;
use PoP\ComponentModel\ModuleProcessors\FormInputModuleProcessorInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryInputOutputHandlers\ResponseConstants;
use PoP\Root\Constants\HookNames;
use PoP\Root\Exception\GenericClientException;
use PoP\Root\Services\AutomaticallyInstantiatedServiceInterface;
use PoP\Root\Services\AutomaticallyInstantiatedServiceTrait;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;
use PoPSitesWassup\GravityFormsMutations\MutationResolvers\GravityFormsAddEntryToFormMutationResolver;

class GravityFormsAddEntryToFormMutationResolverBridge extends AbstractFormComponentMutationResolverBridge implements AutomaticallyInstantiatedServiceInterface
{
    use AutomaticallyInstantiatedServiceTrait;

    public final const HOOK_FORM_FIELDNAMES = __CLASS__ . ':form-fieldnames';

    private ?UserTypeAPIInterface $userTypeAPI = null;
    private ?GravityFormsAddEntryToFormMutationResolver $gravityFormsAddEntryToFormMutationResolver = null;

    final public function setUserTypeAPI(UserTypeAPIInterface $userTypeAPI): void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        return $this->userTypeAPI ??= $this->instanceManager->getInstance(UserTypeAPIInterface::class);
    }
    final public function setGravityFormsAddEntryToFormMutationResolver(GravityFormsAddEntryToFormMutationResolver $gravityFormsAddEntryToFormMutationResolver): void
    {
        $this->gravityFormsAddEntryToFormMutationResolver = $gravityFormsAddEntryToFormMutationResolver;
    }
    final protected function getGravityFormsAddEntryToFormMutationResolver(): GravityFormsAddEntryToFormMutationResolver
    {
        return $this->gravityFormsAddEntryToFormMutationResolver ??= $this->instanceManager->getInstance(GravityFormsAddEntryToFormMutationResolver::class);
    }

    final public function initialize(): void
    {
        // Execute before $hooksAPI->addAction('wp',  array('RGForms', 'maybe_process_form'), 9);
        if ('POST' === App::server('REQUEST_METHOD')) {
            App::addAction(
                HookNames::AFTER_BOOT_APPLICATION,
                $this->setup(...),
                5
            );

            // The 2 functions below must be executed in this order, otherwise 'renameFields' may remove the value filled by 'maybeFillFields'
            App::addAction(
                HookNames::AFTER_BOOT_APPLICATION,
                $this->renameFields(...),
                6
            );
            App::addAction(
                HookNames::AFTER_BOOT_APPLICATION,
                $this->maybeFillFields(...),
                7
            );
            App::addAction(
                HookNames::AFTER_BOOT_APPLICATION,
                $this->maybeValidateCaptcha(...),
                8
            );
        }
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getGravityFormsAddEntryToFormMutationResolver();
    }

    /**
     * @param array<string, mixed> $data_properties
     * @return array<string, mixed>
     */
    public function executeMutation(array &$data_properties): ?array
    {
        $executed = parent::executeMutation($data_properties);

        $execution_response = App::getMutationResolutionStore()->getResult($this);

        // These are the Strings to use to return the errors: This is how they must be used to return errors / success
        // (Eg: in Gravity Forms confirmations)
        // $errorcode = "{{gd:ec:%s}}";
        // $errorstring = "{{gd:es:%s}}";
        // $softredirect = "{{gd:sr:%s}}";
        // $hardredirect = "{{gd:hr:%s}}";
        // $success = "{{gd:success}}";

        // Error codes
        preg_match_all("/\{\{gd\:ec\:(.*?)\}\}/", $execution_response, $errorcodes);

        // Error strings
        preg_match_all("/\{\{gd\:es\:(.*?)\}\}/", $execution_response, $errorstrings);

        // Soft Redirect
        preg_match_all("/\{\{gd\:sr\:(.*?)\}\}/", $execution_response, $softredirect);

        // Hard Redirect
        preg_match_all("/\{\{gd\:hr\:(.*?)\}\}/", $execution_response, $hardredirect);

        // Success
        preg_match_all("/\{\{gd\:success\}\}/", $execution_response, $success);

        // $executed = array();
        if (!empty($success[0])) {
            $executed[ResponseConstants::SUCCESS] = true;
        } elseif (!empty($errorstrings[1]) || !empty($errorcodes[1])) {
            $executed[ResponseConstants::ERRORSTRINGS] = $errorstrings[1];
            $executed[ResponseConstants::ERRORCODES] = $errorcodes[1];
        }

        // Redirects are unique values, so just get the first occurrence
        if ($softredirect[1]) {
            $executed[\GD_DATALOAD_QUERYHANDLERRESPONSE_SOFTREDIRECT] = $softredirect[1][0];
        } elseif ($hardredirect[1]) {
            $executed[\GD_DATALOAD_QUERYHANDLERRESPONSE_HARDREDIRECT] = $hardredirect[1][0];
        }

        return $executed;
    }

    public function getFormData(): array
    {
        /** @var FormInputModuleProcessorInterface */
        $formid_processor = $this->getModuleProcessorManager()->getProcessor([GD_GF_Module_Processor_TextFormInputs::class, GD_GF_Module_Processor_TextFormInputs::MODULE_GF_FORMINPUT_FORMID]);
        $form_data = array(
            'form_id' => $formid_processor->getValue([GD_GF_Module_Processor_TextFormInputs::class, GD_GF_Module_Processor_TextFormInputs::MODULE_GF_FORMINPUT_FORMID]),
        );

        return $form_data;
    }

    public function setup(): void
    {
        // Since GF 1.9.44, they setup field $_POST[ 'is_submit_' . $form['id'] ] )
        // (in file plugins/gravityforms/form_display.php function validate)
        // So here re-create that field
        if ($form_id = App::request('gform_submit')) {
            App::getRequest()->request->set('is_submit_' . $form_id, true);
        }
    }

    protected function getFormFieldnames($form_id)
    {
        return App::applyFilters(
            self::HOOK_FORM_FIELDNAMES,
            array(),
            $form_id
        );
    }

    public function maybeFillFields(): void
    {
        // Pre-populate values when the user is logged in
        // These are needed since implementing PoP where the user is always logged in, so we can't print the name/email
        // on the front-end anymore, instead fields PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NAME and PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_EMAIL are
        // not visible when the user is logged in
        if (PoP_FormUtils::useLoggedinuserData() && App::getState('is-user-logged-in')) {
            if ($form_id = App::request('gform_submit')) {
                // Hook the fieldnames from the configuration
                if ($fieldnames = $this->getFormFieldnames($form_id)) {
                    $user_id = App::getState('current-user-id');

                    // Fill the user name
                    /** @var FormInputModuleProcessorInterface */
                    $moduleProcessor = $this->getModuleProcessorManager()->getProcessor([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NAME]);
                    $name = $moduleProcessor->getName([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_NAME]);
                    if (isset($fieldnames[$name])) {
                        App::getRequest()->request->set($fieldnames[$name], $this->getUserTypeAPI()->getUserDisplayName($user_id));
                    }

                    // Fill the user email
                    /** @var FormInputModuleProcessorInterface */
                    $moduleProcessor = $this->getModuleProcessorManager()->getProcessor([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_EMAIL]);
                    $email = $moduleProcessor->getName([PoP_Forms_Module_Processor_TextFormInputs::class, PoP_Forms_Module_Processor_TextFormInputs::MODULE_FORMINPUT_EMAIL]);
                    if (isset($fieldnames[$email])) {
                        App::getRequest()->request->set($fieldnames[$email], $this->getUserTypeAPI()->getUserEmail($user_id));
                    }
                }
            }
        }
    }

    public function renameFields(): void
    {
        // We need to populate the $_POST using the input names needed by Gravity Forms
        if ($form_id = App::request('gform_submit')) {
            // Hook the fieldnames from the configuration
            if ($fieldnames = $this->getFormFieldnames($form_id)) {
                foreach ($fieldnames as $module_name => $gf_form_fieldname) {
                    // For each regular PoP module value, set it also under the expected form input name by Gravity Forms
                    App::getRequest()->request->set($gf_form_fieldname, App::request($module_name));
                }
            }
        }
    }

    public function maybeValidateCaptcha(): void
    {
        // This is a workaround to validate the form which takes place in advance based on if the captcha is present or not
        // this is done now because GF sends the email at the beginning, this can't be postponed
        // Check only if the user is not logged in. When logged in, we never use the captcha
        if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
            if (!(PoP_FormUtils::useLoggedinuserData() && App::getState('is-user-logged-in'))) {
                if ($form_id = App::request('gform_submit')) {
                    // Check if there's a captcha sent along
                    /** @var FormInputModuleProcessorInterface */
                    $moduleProcessor = $this->getModuleProcessorManager()->getProcessor([PoP_Module_Processor_CaptchaFormInputs::class, PoP_Module_Processor_CaptchaFormInputs::MODULE_FORMINPUT_CAPTCHA]);
                    $captcha_name = $moduleProcessor->getName([PoP_Module_Processor_CaptchaFormInputs::class, PoP_Module_Processor_CaptchaFormInputs::MODULE_FORMINPUT_CAPTCHA]);
                    if ($captcha = App::request($captcha_name)) {
                        // Validate the captcha. If it fails, remove the attr "gform_submit" from $_POST
                        try {
                            GD_Captcha::assertIsValid($captcha);
                        } catch (GenericClientException $e) {
                            // By unsetting this value in the $_POST, the email won't be processed by function RGForms::maybe_process_form
                            App::getRequest()->request->remove('gform_submit');
                        }
                    }
                }
            }
        }
    }
}
