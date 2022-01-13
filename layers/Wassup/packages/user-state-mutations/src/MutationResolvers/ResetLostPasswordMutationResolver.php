<?php

declare(strict_types=1);

namespace PoPSitesWassup\UserStateMutations\MutationResolvers;

use PoP\Root\App;
use PoP\ComponentModel\Error\Error;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\MutationResolvers\ErrorTypes;
use PoP\UserAccount\FunctionAPIFactory;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPSitesWassup\UserStateMutations\MutationResolverUtils\MutationResolverUtils;

class ResetLostPasswordMutationResolver extends AbstractMutationResolver
{
    private ?UserTypeAPIInterface $userTypeAPI = null;

    final public function setUserTypeAPI(UserTypeAPIInterface $userTypeAPI): void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        return $this->userTypeAPI ??= $this->instanceManager->getInstance(UserTypeAPIInterface::class);
    }

    public function getErrorType(): int
    {
        return ErrorTypes::CODES;
    }

    public function validateErrors(array $form_data): array
    {
        $errorcodes = array();
        $code = $form_data[MutationInputProperties::CODE];
        $pwd = $form_data[MutationInputProperties::PASSWORD];
        $repeatpwd = $form_data[MutationInputProperties::REPEAT_PASSWORD];

        if (!$code) {
            $errorcodes[] = 'error-nocode';
        }
        if (!$pwd) {
            $errorcodes[] = 'error-nopwd';
        } elseif (strlen($pwd) < 8) {
            $errorcodes[] = 'error-short';
        }
        if (!$repeatpwd) {
            $errorcodes[] = 'error-norepeatpwd';
        }
        if ($pwd != $repeatpwd) {
            $errorcodes[] = 'error-pwdnomatch';
        }
        return $errorcodes;
    }
    public function executeMutation(array $form_data): mixed
    {
        $code = $form_data[MutationInputProperties::CODE];
        $pwd = $form_data[MutationInputProperties::PASSWORD];

        $cmsuseraccountapi = FunctionAPIFactory::getInstance();
        $decoded = MutationResolverUtils::decodeLostPasswordCode($code);
        $rp_key = $decoded['key'];
        $rp_login = $decoded['login'];

        if (!$rp_key || !$rp_login) {
            return new Error(
                'error-wrongcode'
            );
        } else {
            $user = $cmsuseraccountapi->checkPasswordResetKey($rp_key, $rp_login);
            if (!$user) {
                return new Error(
                    'error-invalidkey'
                );
            }
            if (GeneralUtils::isError($user)) {
                return $user;
            }
        }

        // Do the actual password reset
        $cmsuseraccountapi->resetPassword($user, $pwd);

        $userID = $this->getUserTypeAPI()->getUserId($user);
        App::getHookManager()->doAction('gd_lostpasswordreset', $userID);
        return $userID;
    }
}
