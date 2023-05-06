/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { GATO_GRAPHQL_PRO_PLUGIN_WEBSITE_URL } from '../../pro-constants/environment';

const GoProLink = ( props ) => {
	const {
		proPluginWebsiteURL = GATO_GRAPHQL_PRO_PLUGIN_WEBSITE_URL,
		title = __('Go PRO to unlock! 🔓', 'gato-graphql'),
		className = "button button-secondary",
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
