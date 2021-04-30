<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolvers;

use PoP\Engine\Route\RouteUtils;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoPSitesWassup\UserStateMutations\MutationResolverUtils\MutationResolverUtils;

class LostPasswordMutationResolver extends AbstractMutationResolver
{
    public function retrievePasswordMessage($key, $user_login, $user_id)
    {
        $code = MutationResolverUtils::getLostPasswordCode($key, $user_login);
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();

        // $moduleprocessor_manager = ModuleProcessorManagerFacade::getInstance();
        // $input_name = $moduleprocessor_manager->getProcessor([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWDRESET_CODE])->getName([PoP_Module_Processor_LoginTextFormInputs::class, PoP_Module_Processor_LoginTextFormInputs::MODULE_FORMINPUT_LOSTPWDRESET_CODE]);
        $input_name = POP_INPUTNAME_CODE;
        $link = GeneralUtils::addQueryArgs([
            $input_name => $code,
        ], RouteUtils::getRouteURL(POP_USERLOGIN_ROUTE_LOSTPWDRESET));

        $message = sprintf(
            '<p>%s</p><br/>',
            sprintf(
                $this->translationAPI->__('Someone requested that the password be reset for your account on <a href="%s">%s</a>. If this was a mistake, or if it was not you who requested the password reset, just ignore this email and nothing will happen.', 'pop-application'),
                GeneralUtils::maybeAddTrailingSlash($cmsengineapi->getHomeURL()),
                $cmsapplicationapi->getSiteName()
            )
        );
        $message .= sprintf(
            '<p>%s</p>',
            $this->translationAPI->__('To reset your password, please click on the following link:</p>', 'pop-application')
        );
        $message .= sprintf(
            '<p>%s</p><br/>',
            sprintf(
                '<a href="%1$s">%1$s</a>',
                $link
            )
        );
        $message .= sprintf(
            '<p>%s</p>',
            $this->translationAPI->__('Alternatively, please paste the following code in the "Code" input:', 'pop-application')
        );
        $message .= sprintf(
            // '<p><pre style="%s">%s</pre></p><br/>',
            // 'background-color: #f1f1f2; width: 100%; padding: 5px;',
            '<p><em>%s</em></p>',
            $code
        );

        return $message;
    }

    public function validateErrors(array $form_data): ?array
    {
        $errors = [];
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $user_login = $form_data[MutationInputProperties::USER_LOGIN];

        // Code copied from file wp-login.php (We can't invoke it directly, since wp-login.php has not been loaded, and we can't do it since it executes a lot of unwanted code producing and output)
        if (empty($user_login)) {
            $errors[] = $this->translationAPI->__('Enter a username or e-mail address.');
        } elseif (strpos($user_login, '@')) {
            $user = $cmsusersapi->getUserByEmail(trim($user_login));
            if (empty($user)) {
                $errors[] = $this->translationAPI->__('There is no user registered with that email address.');
            }
        } else {
            $login = trim($user_login);
            $user = $cmsusersapi->getUserByLogin($login);
        }

        if (!$user) {
            $errors[] = $this->translationAPI->__('Invalid username or e-mail.');
        }

        return $errors;
    }

    public function execute(array $form_data): mixed
    {
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $cmsuseraccountapi = \PoP\UserAccount\FunctionAPIFactory::getInstance();
        $cmsusersresolver = \PoPSchema\Users\ObjectPropertyResolverFactory::getInstance();
        $user_login = $form_data[MutationInputProperties::USER_LOGIN];

        if (strpos($user_login, '@')) {
            $user = $cmsusersapi->getUserByEmail(trim($user_login));
        } else {
            $login = trim($user_login);
            $user = $cmsusersapi->getUserByLogin($login);
        }

        // Generate something random for a password reset key.
        $key = $cmsuseraccountapi->getPasswordResetKey($user);

        if (GeneralUtils::isError($key)) {
            return $key;
        }

        /*
        * The blogname option is escaped with esc_html on the way into the database
        * in sanitize_option we want to reverse this for the plain text arena of emails.
        */
        // $site_name = wp_specialchars_decode($cmsapplicationapi->getSiteName(), ENT_QUOTES);
        // $title = sprintf($this->translationAPI->__('[%s] Password Reset'), $site_name);
        $user_id = $cmsusersresolver->getUserId($user);
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        $title = sprintf($this->translationAPI->__('[%s] Password Reset'), $cmsapplicationapi->getSiteName());
        $title = $this->hooksAPI->applyFilters('popcms:retrievePasswordTitle', $title, $user_login, $user);
        $message = $this->retrievePasswordMessage($key, $user_login, $user_id);
        $message = $this->hooksAPI->applyFilters('popcms:retrievePasswordMessage', $message, $key, $user_login, $user);

        $user_email = $cmsusersresolver->getUserEmail($user);
        return PoP_EmailSender_Utils::sendEmail($user_email, htmlspecialchars_decode($title)/*wp_specialchars_decode($title)*/, $message);
    }
}
