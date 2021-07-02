<?php
namespace PoP\UserAccount\WP;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoP\LooseContracts\Facades\LooseContractManagerFacade;
use PoP\LooseContracts\AbstractLooseContractResolutionSet;

class CMSLooseContractImplementations extends AbstractLooseContractResolutionSet
{
	protected function resolveContracts(): void
    {
		// Filters.
		$this->hooksAPI->addFilter('login_url', function($url, $redirect) {
			return $this->hooksAPI->applyFilters('popcms:loginUrl', $url, $redirect);
		}, 10, 2);
		$this->hooksAPI->addFilter('lostpassword_url', function($url, $redirect) {
			return $this->hooksAPI->applyFilters('popcms:lostPasswordUrl', $url, $redirect);
		}, 10, 2);
		$this->hooksAPI->addFilter('logout_url', function($url, $redirect) {
			return $this->hooksAPI->applyFilters('popcms:logoutUrl', $url, $redirect);
		}, 10, 2);
		$this->hooksAPI->addFilter('auth_cookie_expiration', function($time_in_seconds, $user_id, $remember) {
			return $this->hooksAPI->applyFilters('popcms:authCookieExpiration', $time_in_seconds, $user_id, $remember);
		}, 10, 3);
		$this->hooksAPI->addFilter('retrieve_password_title', function($title, $user_login, $user_data) {
			return $this->hooksAPI->applyFilters('popcms:retrievePasswordTitle', $title, $user_login, $user_data);
		}, 10, 3);
		$this->hooksAPI->addFilter('retrieve_password_message', function($message, $key, $user_login, $user_data) {
			return $this->hooksAPI->applyFilters('popcms:retrievePasswordMessage', $message, $key, $user_login, $user_data);
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

