<?php
namespace PoP\Engine;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class Validation
{
    public function getProviderValidationClass()
    {
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_Engine_Validation:provider-validation-class',
            null
        );
    }
    public function validate()
    {
        $success = true;
        $provider_validation_class = $this->getProviderValidationClass();
        if (is_null($provider_validation_class)) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'providerinstall_warning'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'providerinstall_warning'));
            $success = false;
        } elseif (!(new $provider_validation_class())->validate()) {
            HooksAPIFacade::getInstance()->addAction('admin_notices', array($this, 'providerinitialize_warning'));
            HooksAPIFacade::getInstance()->addAction('network_admin_notices', array($this, 'providerinitialize_warning'));
            $success = false;
        }

        return $success;
    }
    public function providerinstall_warning()
    {
        $this->providerinstall_warning_notice(
            TranslationAPIFacade::getInstance()->__('PoP Engine', 'pop-engine')
        );
    }
    protected function providerinstall_warning_notice($plugin)
    {
        $this->adminNotice(
            sprintf(
                TranslationAPIFacade::getInstance()->__('Error: %s', 'pop-engine-webplatform'),
                sprintf(
                    TranslationAPIFacade::getInstance()->__('There is no provider (underlying implementation) for <strong>%s</strong>.', 'pop-engine-webplatform'),
                    $plugin
                )
            )
        );
    }
    protected function adminNotice($message)
    {
        ?>
        <div class="error">
            <p>
        <?php echo $message ?><br/>
                <em>
        <?php _e('Only admins see this message.', 'pop-engine-webplatform'); ?>
                </em>
            </p>
        </div>
        <?php
    }
}
