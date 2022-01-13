<?php
namespace PoP\UserAccount\WP;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\LooseContracts\AbstractLooseContractResolutionSet;
use PoP\LooseContracts\Facades\LooseContractManagerFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;

class CMSLooseContractImplementations extends AbstractLooseContractResolutionSet
{
	protected function resolveContracts(): void
    {
		// Filters.
		\PoP\Root\App::getHookManager()->addFilter('login_url', function($url, $redirect) {
			return \PoP\Root\App::getHookManager()->applyFilters('popcms:loginUrl', $url, $redirect);
		}, 10, 2);
		\PoP\Root\App::getHookManager()->addFilter('lostpassword_url', function($url, $redirect) {
			return \PoP\Root\App::getHookManager()->applyFilters('popcms:lostPasswordUrl', $url, $redirect);
		}, 10, 2);
		\PoP\Root\App::getHookManager()->addFilter('logout_url', function($url, $redirect) {
			return \PoP\Root\App::getHookManager()->applyFilters('popcms:logoutUrl', $url, $redirect);
		}, 10, 2);
		\PoP\Root\App::getHookManager()->addFilter('auth_cookie_expiration', function($time_in_seconds, $user_id, $remember) {
			return \PoP\Root\App::getHookManager()->applyFilters('popcms:authCookieExpiration', $time_in_seconds, $user_id, $remember);
		}, 10, 3);
		\PoP\Root\App::getHookManager()->addFilter('retrieve_password_title', function($title, $user_login, $user_data) {
			return \PoP\Root\App::getHookManager()->applyFilters('popcms:retrievePasswordTitle', $title, $user_login, $user_data);
		}, 10, 3);
		\PoP\Root\App::getHookManager()->addFilter('retrieve_password_message', function($message, $key, $user_login, $user_data) {
			return \PoP\Root\App::getHookManager()->applyFilters('popcms:retrievePasswordMessage', $message, $key, $user_login, $user_data);
		}, 10, 4);

		$this->looseContractManager->implementHooks([
			'popcms:loginUrl',
			'popcms:lostPasswordUrl',
			'popcms:logoutUrl',
			'popcms:authCookieExpiration',
			'popcms:retrievePasswordTitle',
			'popcms:retrievePasswordMessage',
		]);

		$this->nameResolver->implementNames([
			'popcms:capability:deletePages' => 'delete_pages',
		]);
	}
}

/**
 * Initialize
 */
new CMSLooseContractImplementations(
	LooseContractManagerFacade::getInstance(),
	NameResolverFacade::getInstance(),
	HooksAPIFacade::getInstance()
);

