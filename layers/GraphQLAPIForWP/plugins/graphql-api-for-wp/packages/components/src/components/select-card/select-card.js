/**
 * WordPress dependencies
 */
import { compose } from '@wordpress/compose';
import { __ } from '@wordpress/i18n';
import { Card, CardHeader, CardBody } from '@wordpress/components';
import { getEditableOnFocusComponentClass } from '../base-styles'
import { InfoTooltip } from '../info-tooltip';
import { MarkdownInfoModalButton } from '../markdown-modal';

/**
 * External dependencies
 */
import Select from 'react-select';

/**
 * Internal dependencies
 */
import { withErrorMessage, withSpinner } from '../loading';
import './style.scss';

const GetLabelForNotFoundValue = ( val ) => val;

const SelectCardBody = ( props ) => {
	const { options, defaultValue, className, setAttributes, isSelected, attributes, attributeName } = props;
	/**
	 * Optional props
	 */
	const isMulti = props.isMulti != undefined ? props.isMulti : true;
	/**
	 * By default, if not defined, use the opposite value to isMulti
	 */
	const closeMenuOnSelect = props.closeMenuOnSelect != undefined ? props.closeMenuOnSelect : !isMulti;
	/**
	 * The attribute to update is passed through `attributeName`
	 * For either isMulti or not, make value always be an array
	 */
	const value = isMulti ? attributes[ attributeName ] : ( attributes[ attributeName ] != null ? [ attributes[ attributeName ] ] : [] )
	/**
	 * If the defaultValue is not found in the options, either display the value,
	 * or display a value containing the error message
	 */
	const getLabelForNotFoundValueCallback = props.getLabelForNotFoundValueCallback || GetLabelForNotFoundValue;
	/**
	 * Create a dictionary, with value as key, and label as the value
	 * The options may be grouped, in that case extract the options from within them
	 * To find out, check if the first element itself has entry "options",then it's grouped
	 */
	const maybeUngroupedOptions = (options || []).length ?
		(options[0].options != undefined ?
			options.map( option => option.options).flat()
			: options
		)
		: [];
	let valueLabelDictionary = {};
	value.forEach( function( val ) {
		var entry = (maybeUngroupedOptions.filter( option => option.value == val )).shift();
		valueLabelDictionary[ val ] = entry ?
			entry.label
			: getLabelForNotFoundValueCallback( val );
	} );
	const componentClassName = 'graphql-api-select-card';
	const multiOrSingleClass = isMulti ? 'multi' : 'single';
	return (
		<>
			{ isSelected &&
				<Select
					defaultValue={ defaultValue }
					options={ options }
					isMulti={ isMulti }
					closeMenuOnSelect={ closeMenuOnSelect }
					onChange={ selected =>
						setAttributes( {
							[ attributeName ]: isMulti ?
								(selected || []).map(option => option.value) :
								selected.value
						} )
					}
				/>
			}
			{ !isSelected && !!value.length && (
				<div className={ `${ className }__label-group ${ componentClassName }__label-group ${ multiOrSingleClass }` }>
					{ value.map( val =>
						<div className={ `${ className }__label-item ${ componentClassName }__label-item ${ multiOrSingleClass } ` }>
							{ valueLabelDictionary[ val ] }
						</div>
					) }
				</div>
			) }
			{ !isSelected && !value.length && (
				<em>{ __('(not set)', 'graphql-api') }</em>
			) }
		</>
	)
}

const WithDataLoadingSelectCardBody = compose( [
	withSpinner(),
	withErrorMessage(),
] )( SelectCardBody );

/**
 * If data has not been fetched yet, show a spinner instead of the body
 *
 * @param {Object} props
 */
const MaybeWithDataLoadingSelectCardBody = ( props ) => {
	const { maybeShowSpinnerOrError } = props;
	if ( maybeShowSpinnerOrError ) {
		return (
			<WithDataLoadingSelectCardBody { ...props } />
		)
	}
	return (
		<SelectCardBody { ...props } />
	);
}

const SelectCard = ( props ) => {
	const {
		label,
		isSelected,
		tooltip,
		getMarkdownContentCallback,
	} = props;
	const componentClassName = 'graphql-api-select-card';
	const componentClass = `${ componentClassName } ${ getEditableOnFocusComponentClass(isSelected) }`;
	const documentationTitle = __(`Documentation for: "${ label }"`, 'graphql-api');
	return (
		<div className={ componentClass }>
			<Card { ...props }>
				<CardHeader isShady>
					<div>
						{ label }
						{ !! tooltip &&
							<InfoTooltip
								{ ...props }
								text={ tooltip }
							/>
						}
						{ !! getMarkdownContentCallback && (
							<MarkdownInfoModalButton
								title={ documentationTitle }
								getMarkdownContentCallback={ getMarkdownContentCallback }
							/>
						) }
					</div>
				</CardHeader>
				<CardBody>
					<MaybeWithDataLoadingSelectCardBody
						{ ...props }
					/>
				</CardBody>
			</Card>
		</div>
	);
}

export default SelectCard;
