/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { GRAPHQL_API_PRO_PLUGIN_WEBSITE_URL } from '../../constants/environment';

const GoProLink = ( props ) => {
	const {
		proPluginWebsiteURL = GRAPHQL_API_PRO_PLUGIN_WEBSITE_URL,
		title = __('Go PRO to unlock! 🔓', 'graphql-api'),
		className = "button button-primary",
		target = "_blank"
	} = props;
	return (
		<a
			target={ target }
			href={ proPluginWebsiteURL }
			class={ className }
		>
			{ title }
		</a>
	);
}

export default GoProLink;
