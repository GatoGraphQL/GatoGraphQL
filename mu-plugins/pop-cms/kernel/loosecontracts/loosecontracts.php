<?php
namespace PoP\CMS;

class CMSLooseContracts extends CMSLooseContractsBase
{
	public function getRequiredHooks() {
		return [
			// Actions
			'popcms:init',
			'popcms:beforeInit',

			'popcms:footer',
			'popcms:publishToDraft',
			// 'popcms:retrievePasswordKey',
			'popcms:savePost',
			'popcms:deleteUser',
			'popcms:insertComment',
			'popcms:pendingToPublish',
			'popcms:transitionPostStatus',
			'popcms:enqueueScripts',
			'popcms:printFooterScripts',
			'popcms:head',
			'popcms:printStyles',
			'popcms:printScripts',
			'popcms:userRegister',
			'popcms:spamComment',
			'popcms:deleteComment',
			// Filters
			'popcms:styleSrc',
			'popcms:scriptSrc',
			'popcms:title',
			'popcms:content',
			'popcms:excerptMore',
			'popcms:loginUrl',
			'popcms:lostPasswordUrl',
			'popcms:logoutUrl',
			'popcms:authCookieExpiration',
			'popcms:scriptTag',
			'popcms:styleTag',
			'popcms:retrievePasswordTitle',
			'popcms:retrievePasswordMessage',
		];
	}

	public function getRequiredNames() {
		return [
			// Options
			'popcms:capability:editPost',
			'popcms:capability:editPosts',
			'popcms:capability:deletePages',
			'popcms:option:limit',
			'popcms:option:dateFormat',
			'popcms:option:charset',
			'popcms:option:gmtOffset',
			'popcms:option:timezone',
			// DB Columns
			'popcms:dbcolumn:orderby:comments:date',
			'popcms:dbcolumn:orderby:posts:date',
			'popcms:dbcolumn:orderby:posts:modified',
			'popcms:dbcolumn:orderby:posts:id',
			'popcms:dbcolumn:orderby:tags:count',
			'popcms:dbcolumn:orderby:tags:id',
			'popcms:dbcolumn:orderby:users:name',
			'popcms:dbcolumn:orderby:users:id',
			'popcms:dbcolumn:orderby:users:registrationdate',
		];
	}
}

/**
 * Initialize
 */
new CMSLooseContracts();

