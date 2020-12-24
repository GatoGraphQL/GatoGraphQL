import { __ } from '@wordpress/i18n';
import { TabPanel } from '@wordpress/components';
import FieldMultiSelectControl from './field-multi-select-control';
import DirectiveMultiSelectControl from './directive-multi-select-control';

const FieldDirectiveTabPanel = ( props ) => {
	const { className, typeFields, directives } = props;
	return (
		<TabPanel
			className={ className + '__tab_panel' }
			activeClass="active-tab"
			tabs={ [
				{
					name: 'tabFields',
					title: __('Fields', 'graphql-api'),
					className: 'tab tab-fields',
				},
				{
					name: 'tabDirectives',
					title: __('Directives', 'graphql-api'),
					className: 'tab tab-directives',
				},
			] }
		>
			{
				( tab ) => tab.name == 'tabFields' ?
					<FieldMultiSelectControl
						{ ...props }
						selectedItems={ typeFields }
					/> :
					<DirectiveMultiSelectControl
						{ ...props }
						selectedItems={ directives }
					/>
			}
		</TabPanel>
	);
}

export default FieldDirectiveTabPanel;
