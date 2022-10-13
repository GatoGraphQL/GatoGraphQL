import { __ } from '@wordpress/i18n';
import { TabPanel } from '@wordpress/components';
import OperationMultiSelectControl from './operation-multi-select-control';
import TypeFieldMultiSelectControl from './type-field-multi-select-control';
import GlobalFieldMultiSelectControl from './global-field-multi-select-control';
import DirectiveMultiSelectControl from './directive-multi-select-control';

const SchemaElementsTabPanel = ( props ) => {
	const {
		className,
		operations,
		typeFields,
		globalFields,
		directives,
		enableOperations,
		enableTypeFields,
		enableGlobalFields,
		enableDirectives,
	} = props;
	const tabs = [
		...( enableOperations ? [ {
			name: 'tabOperations',
			title: __('Operations', 'graphql-api'),
			className: 'tab tab-operations',
		} ] : [] ),
		...( enableTypeFields ? [ {
			name: 'tabTypeFields',
			title: __('Fields', 'graphql-api'),
			className: 'tab tab-fields tab-standard-fields',
		} ] : [] ),
		...( enableGlobalFields ? [ {
			name: 'tabGlobalFields',
			title: __('Global Fields', 'graphql-api'),
			className: 'tab tab-fields tab-global-fields',
		} ] : [] ),
		...( enableDirectives ? [ {
			name: 'tabDirectives',
			title: __('Directives', 'graphql-api'),
			className: 'tab tab-directives',
		} ] : [] ),
	]
	return (
		<TabPanel
			className={ className + '__tab_panel' }
			activeClass="active-tab"
			tabs={ tabs }
		>
			{
				( tab ) =>
					tab.name == 'tabOperations' ?
						<OperationMultiSelectControl
							{ ...props }
							selectedItems={ operations }
						/> :
					tab.name == 'tabTypeFields' ?
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

export default SchemaElementsTabPanel;
