/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { RadioControl } from '@wordpress/components';
import { compose, withState } from '@wordpress/compose';

/**
 * Internal dependencies
 */
import { getModuleDocMarkdownContentOrUseDefault } from './module-doc-markdown-loader';
import {
	InfoTooltip,
	withCard,
	withEditableOnFocus,
} from '@graphqlapi/components';

const SchemaConfigCustomPostsCard = ( props ) => {
	const {
		isSelected,
		setAttributes,
		attributes: {
			includedCustomPostTypes,
		},
	} = props;
	const options = [
		{
			label: '@complete',
			value: '@complete',
		},
	];
	const optionValues = options.map( option => option.value );
	return (
		<>
			<em>{ __('Included custom post types', 'graphql-api') }</em>
			<InfoTooltip
				{ ...props }
				text={ __('Select the custom post types that can be queried. A custom post type will be represented by its own type in the schema (such as Post or Page) or, otherwise, via GenericCustomPost.', 'graphql-api') }
			/>
			{ !isSelected && (
				<>
					<br />
					{ ( includedCustomPostTypes == '@complete' || !optionValues.includes(includedCustomPostTypes) ) &&
						<span>🟡 { __('@Complete', 'graphql-api') }</span>
					}
					{ includedCustomPostTypes == '@complete' &&
						<span>❌ { __('@Complete', 'graphql-api') }</span>
					}
				</>
			) }
			{ isSelected &&
				<RadioControl
					{ ...props }
					options={ options }
					selected={ includedCustomPostTypes }
					onChange={ newValue => (
						setAttributes( {
							includedCustomPostTypes: newValue
						} )
					)}
				/>
			}
		</>
	);
}

export default compose( [
	withState( {
		header: __('Custom Posts', 'graphql-api'),
		className: 'graphql-api-customposts',
		getMarkdownContentCallback: getModuleDocMarkdownContentOrUseDefault
	} ),
	withEditableOnFocus(),
	withCard(),
] )( SchemaConfigCustomPostsCard );