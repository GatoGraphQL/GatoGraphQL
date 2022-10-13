/**
 * Internal dependencies
 */
import { createHigherOrderComponent } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';
import FieldDirectiveTabPanel from './field-directive-tab-panel';
import FieldDirectivePrintout from './field-directive-printout';

/**
 * Display an error message if loading data failed
 */
const withFieldDirectiveMultiSelectControl = () => createHigherOrderComponent(
	( WrappedComponent ) => ( props ) => {
		const {
			isSelected,
			attributes: {
				operations,
				typeFields,
				globalFields,
				directives
			},
			componentClassName,
			selectLabel,
			configurationLabel,
			enableOperations,
			enableTypeFields,
			enableGlobalFields,
			enableDirectives,
			hideLabels
		} = props;
		if (! enableOperations && ! enableTypeFields && ! enableGlobalFields && ! enableDirectives) {
			throw 'At least 1 option must be enabled: [operations, type fields, global fields, directives]';
		}
		const className = 'graphql-api-multi-select-control-list';
		const leftSideLabel = selectLabel || __('Select schema elements:', 'graphql-api');
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
										<FieldDirectiveTabPanel
											{ ...props }
											operations={ operations }
											typeFields={ typeFields }
											globalFields={ globalFields }
											directives={ directives }
											className={ className }
										/>
									) }
									{ !isSelected && (
										<FieldDirectivePrintout
											{ ...props }
											operations={ operations }
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
