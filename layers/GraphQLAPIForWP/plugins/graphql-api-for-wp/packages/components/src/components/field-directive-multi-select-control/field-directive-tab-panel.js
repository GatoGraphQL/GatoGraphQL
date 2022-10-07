import { __ } from '@wordpress/i18n';
import { TabPanel } from '@wordpress/components';
import TypeFieldMultiSelectControl from './type-field-multi-select-control';
import GlobalFieldMultiSelectControl from './global-field-multi-select-control';
import DirectiveMultiSelectControl from './directive-multi-select-control';

const FieldDirectiveTabPanel = ( props ) => {
	const {
		className,
		typeFields,
		globalFields,
		directives,
		disableTypeFields,
		disableGlobalFields,
		disableDirectives,
	} = props;
	const tabs = [
		...( disableTypeFields ? [] : [ {
			name: 'tabTypeFields',
			title: __('Fields', 'graphql-api'),
			className: 'tab tab-fields tab-standard-fields',
		} ] ),
		...( disableGlobalFields ? [] : [ {
			name: 'tabGlobalFields',
			title: __('Global Fields', 'graphql-api'),
			className: 'tab tab-fields tab-global-fields',
		} ] ),
		...( disableDirectives ? [] : [ {
			name: 'tabDirectives',
			title: __('Directives', 'graphql-api'),
			className: 'tab tab-directives',
		} ] ),
	]
	return (
		<TabPanel
			className={ className + '__tab_panel' }
			activeClass="active-tab"
			tabs={ tabs }
		>
			{
				( tab ) => tab.name == 'tabTypeFields' ?
					<TypeFieldMultiSelectControl
						{ ...props }
						selectedItems={ typeFields }
					/> :
				tab.name == 'tabGlobalFields' ?
					<GlobalFieldMultiSelectControl
						{ ...props }
						selectedItems={ globalFields }
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
