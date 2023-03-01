/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

const GoProLink = ( props ) => {
	const {
		proPluginWebsiteURL,
		title = __('Go PRO to unlock! 🔓', 'graphql-api')
	} = props;
	return (
		<a target="_blank" href={ proPluginWebsiteURL } class="button">{ title }</a>
	);
}

export default GoProLink;
