/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { MarkdownInfoModalButton } from '../../components/markdown-modal';
 
const GraphAPIPROBlock = ( props ) => {
	const {
		title,
		description,
		getMarkdownContentCallback,
	} = props;
	return (
		<>
			<em>{ description }</em>
			<MarkdownInfoModalButton
				{ ...props }
				title = { __(`Documentation for: "${ title }"`, 'graphql-api') }
				getMarkdownContentCallback = { getMarkdownContentCallback }
				text = { __('View details', 'graphql-api') }
				// icon = { null }
				variant = ""
				isSmall = { false }
			/>
		</>
	);
}

export default GraphAPIPROBlock;