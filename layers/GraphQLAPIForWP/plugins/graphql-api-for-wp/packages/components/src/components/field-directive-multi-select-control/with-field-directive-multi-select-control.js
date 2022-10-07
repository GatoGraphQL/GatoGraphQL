/**
 * Internal dependencies
 */
import { createHigherOrderComponent } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';
import FieldDirectiveTabPanel from './field-directive-tab-panel';
import FieldDirectivePrintout from './field-directive-printout';
import TypeFieldMultiSelectControl from './type-field-multi-select-control';
import GlobalFieldMultiSelectControl from './global-field-multi-select-control';
import DirectiveMultiSelectControl from './directive-multi-select-control';

/**
 * Display an error message if loading data failed
 */
const withFieldDirectiveMultiSelectControl = () => createHigherOrderComponent(
	( WrappedComponent ) => ( props ) => {
		const {
			isSelected,
			attributes: {
				typeFields,
				globalFields,
				directives
			},
			componentClassName,
			selectLabel,
			configurationLabel,
			disableTypeFields,
			disableGlobalFields,
			disableDirectives,
			hideLabels
		} = props;
		const className = 'graphql-api-multi-select-control-list';
		const leftSideLabel = selectLabel || __('Select fields and directives:', 'graphql-api');
		const rightSideLabel = configurationLabel || __('Configuration:', 'graphql-api');
		const onlyEnableTypeFields = ! disableTypeFields && disableGlobalFields && disableDirectives;
		const onlyEnableGlobalFields = ! disableGlobalFields && disableTypeFields && disableDirectives;
		const onlyEnableDirectives = ! disableDirectives && disableTypeFields && disableGlobalFields;
		return (
			<div className={ className }>
				<div className={ className+'__items' }>
					<div className={ className+'__item' }>
						<div className={ className+'__item_data' }>
							<div className={ className+'__item_data_for' }>
								{ ! hideLabels &&
									<div className={ className+'__item_data__title' }>
										<strong>{ leftSideLabel }</strong>
									</div>
								}
								<div className={ componentClassName }>
									{ isSelected && (
										<>
											{ onlyEnableTypeFields &&
												<TypeFieldMultiSelectControl
													{ ...props }
													selectedItems={ typeFields }
												/>
											}
											{ onlyEnableGlobalFields &&
												<GlobalFieldMultiSelectControl
													{ ...props }
													selectedItems={ globalFields }
												/>
											}
											{ onlyEnableDirectives &&
												<DirectiveMultiSelectControl
													{ ...props }
													selectedItems={ directives }
												/>
											}
											{ 	! onlyEnableTypeFields && ! onlyEnableGlobalFields && ! onlyEnableDirectives &&
												<FieldDirectiveTabPanel
													{ ...props }
													typeFields={ typeFields }
													globalFields={ globalFields }
													directives={ directives }
													className={ className }
												/>
											}
										</>
									) }
									{ !isSelected && (
										<FieldDirectivePrintout
											{ ...props }
											typeFields={ typeFields }
											globalFields={ globalFields }
											directives={ directives }
											className={ className }
										/>
									) }
								</div>
							</div>
							<div className={ className+'__item_data_who' }>
								{ ! hideLabels &&
									<div className={ className+'__item_data__title' }>
										<strong>{ rightSideLabel }</strong>
									</div>
								}
								<div className={ className+'__item_data__who' }>
									<WrappedComponent
										className={ className }
										{ ...props }
									/>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		);
	},
	'withFieldDirectiveMultiSelectControl'
);

export default withFieldDirectiveMultiSelectControl;
