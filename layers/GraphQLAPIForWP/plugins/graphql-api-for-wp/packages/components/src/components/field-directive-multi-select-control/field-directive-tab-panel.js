import { __ } from '@wordpress/i18n';
import { TabPanel } from '@wordpress/components';
import FieldMultiSelectControl from './field-multi-select-control';
import DirectiveMultiSelectControl from './directive-multi-select-control';

const FieldDirectiveTabPanel = ( props ) => {
	const { className, typeFields, globalFields, directives } = props;
	return (
		<TabPanel
			className={ className + '__tab_panel' }
			activeClass="active-tab"
			tabs={ [
				{
					name: 'tabTypeFields',
					title: __('Fields', 'graphql-api'),
					className: 'tab tab-fields tab-standard-fields',
				},
				{
					name: 'tabGlobalFields',
					title: __('Global Fields', 'graphql-api'),
					className: 'tab tab-fields tab-global-fields',
				},
				{
					name: 'tabDirectives',
					title: __('Directives', 'graphql-api'),
					className: 'tab tab-directives',
				},
			] }
		>
			{ ( tab ) => tab.name == 'tabTypeFields' && (
				<FieldMultiSelectControl
					{ ...props }
					selectedItems={ typeFields }
				/>
			) }
			{ ( tab ) => tab.name == 'tabGlobalFields' && (
				<FieldMultiSelectControl
					{ ...props }
					selectedItems={ globalFields }
				/>
			) }
			{ ( tab ) => tab.name == 'tabDirectives' && (
				<DirectiveMultiSelectControl
				{ ...props }
				selectedItems={ directives }
			/>
			) }
		</TabPanel>
	);
}

export default FieldDirectiveTabPanel;
