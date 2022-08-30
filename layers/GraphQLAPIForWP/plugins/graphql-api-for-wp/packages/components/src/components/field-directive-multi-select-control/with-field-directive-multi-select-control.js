/**
 * Internal dependencies
 */
import { createHigherOrderComponent } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';
import FieldDirectiveTabPanel from './field-directive-tab-panel';
import FieldDirectivePrintout from './field-directive-printout';
import FieldMultiSelectControl from './field-multi-select-control';
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
				directives
			},
			componentClassName,
			selectLabel,
			configurationLabel,
			disableFields,
			disableDirectives,
			hideLabels
		} = props;
		const className = 'graphql-api-multi-select-control-list';
		const leftSideLabel = selectLabel || __('Select fields and directives:', 'graphql-api');
		const rightSideLabel = configurationLabel || __('Configuration:', 'graphql-api');
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
											{ ! disableFields && ! disableDirectives &&
												<FieldDirectiveTabPanel
													{ ...props }
													typeFields={ typeFields }
													directives={ directives }
													className={ className }
												/>
											}
											{ ! disableFields && disableDirectives &&
												<FieldMultiSelectControl
													{ ...props }
													selectedItems={ typeFields }
												/>
											}
											{ disableFields && ! disableDirectives &&
												<DirectiveMultiSelectControl
													{ ...props }
													selectedItems={ directives }
												/>
											}
										</>
									) }
									{ !isSelected && (
										<FieldDirectivePrintout
											{ ...props }
											typeFields={ typeFields }
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
