/**
 * Internal dependencies
 */
import { compose } from '@wordpress/compose';
import { withSelect } from '@wordpress/data';
import { Card, CardHeader, CardBody, CheckboxControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { TYPE_FIELD_SEPARATOR_FOR_DB, TYPE_FIELD_SEPARATOR_FOR_PRINT } from './block-constants';
import withSpinner from '../loading/with-spinner';
import withErrorMessage from '../loading/with-error-message';
import { GROUP_FIELDS_UNDER_TYPE_FOR_PRINT, EMPTY_LABEL } from '../../default-configuration';
import '../base-styles/checkbox-list.scss';

const FieldPrintoutBody = ( props ) => {
	const {
		typeFields,
		typeFieldNames,
		groupFieldsUnderTypeForPrint,
		emptyLabelString,
	} = props;
	const groupFieldsUnderType = groupFieldsUnderTypeForPrint != undefined ? groupFieldsUnderTypeForPrint : GROUP_FIELDS_UNDER_TYPE_FOR_PRINT;

	/**
	 * Create a dictionary, with typeName as key, and an array with all its fields as the value
	 */
	let combinedTypeFieldNames = {};
	typeFields.forEach(function(typeField) {
		const typeFieldEntry = typeFieldNames[ typeField ];
		// If it doesn't find the entry, it's because the schema has changed, and the DB is still
		// referenting a removed item. For instance, having saved entry `QueryableObject.slug` and
		// then renaming interface `QueryableObject` to `Queryable`, the entry must be considered stale
		if (typeFieldEntry == undefined) {
			const undefinedEntry = __( '(Undefined entries)', 'graphql-api')
			combinedTypeFieldNames[ undefinedEntry ] = combinedTypeFieldNames[ undefinedEntry ] || [];
			combinedTypeFieldNames[ undefinedEntry ].push( typeField );
		}
		else {
			combinedTypeFieldNames[ typeFieldEntry.typeName ] = combinedTypeFieldNames[ typeFieldEntry.typeName ] || [];
			combinedTypeFieldNames[ typeFieldEntry.typeName ].push( typeFieldEntry.field );
		}
	} );
	return (
		<>
			{ !! typeFields.length && (
					( !groupFieldsUnderType && (
						<ul class="checkbox-list">
							{ typeFields.map( typeField =>
								<li>
									<CheckboxControl
										label={ `${ typeFieldNames[ typeField ].typeName }${ TYPE_FIELD_SEPARATOR_FOR_PRINT }${ typeFieldNames[ typeField ].field }` }
										checked={ true }
										disabled={ true }
									/>
								</li>
							) }
						</ul>
					)
				) || ( groupFieldsUnderType && Object.keys(combinedTypeFieldNames).map( typeName =>
					<>
						<strong>{ typeName }</strong>
						<ul class="checkbox-list">
							{ combinedTypeFieldNames[ typeName ].map( field =>
								<li>
									<CheckboxControl
										label={ `${ field }` }
										checked={ true }
										disabled={ true }
									/>
								</li>
							) }
						</ul>
					</>
				) )
			) }
			{ !typeFields.length && (
				emptyLabelString
			) }
		</>
	);
}

/**
 * Add a spinner when loading the typeFieldNames and typeFields is not empty
 */
const WithSpinnerFieldPrintoutBody = compose( [
	withSpinner(),
	withErrorMessage(),
] )( FieldPrintoutBody );

/**
 * Check if the typeFields are empty, then do not show the spinner
 * This is an improvement when loading a new Access Control post, that it has no data, so the user is not waiting for nothing
 *
 * @param {Object} props
 */
const MaybeWithSpinnerFieldPrintoutBody = ( props ) => {
	const { typeFields } = props;
	if ( !! typeFields.length ) {
		return (
			<WithSpinnerFieldPrintoutBody { ...props } />
		)
	}
	return (
		<FieldPrintoutBody { ...props } />
	);
}

/**
 * Print the selected fields and directives.
 * Watch out: object `typeFields` contains the namespaced type as value, such as `PoP_ComponentModel_Root.users`, which is not proper
 * Then, convert this value to what the user expects: `Root/users`. Because of this formatting, we need to execute a call against the server,
 * to fetch the information of how the type name and type namespaced name
 *
 * @param {Object} props
 */
const DirectivePrintoutBody = ( props ) => {
	const {
		directives,
		emptyLabelString,
	} = props;
	return (
		<>
			{ !! directives.length && (
				<ul class="checkbox-list">
					{ directives.map( directive =>
						<li
							key={ directive }
						>
							<CheckboxControl
								label={ `${ directive }` /* `@${ directive }` */ }
								checked={ true }
								disabled={ true }
							/>
						</li>
					) }
				</ul>
			) }
			{ !directives.length && (
				emptyLabelString
			) }
		</>
	);
}

// /**
//  * Add a spinner when loading the typeFieldNames and typeFields is not empty
//  */
// const WithSpinnerDirectivePrintoutBody = compose( [
// 	withSpinner(),
// 	withErrorMessage(),
// ] )( DirectivePrintoutBody );

// /**
//  * Check if the typeFields are empty, then do not show the spinner
//  * This is an improvement when loading a new Access Control post, that it has no data, so the user is not waiting for nothing
//  *
//  * @param {Object} props
//  */
// const MaybeWithSpinnerDirectivePrintoutBody = ( props ) => {
// 	const { directives } = props;
// 	if ( !! directives.length ) {
// 		return (
// 			<WithSpinnerDirectivePrintoutBody { ...props } />
// 		)
// 	}
// 	return (
// 		<DirectivePrintoutBody { ...props } />
// 	);
// }

/**
 * Print the selected fields and directives.
 * Watch out: object `typeFields` contains the namespaced type as value, such as `PoP_ComponentModel_Root.users`, which is not proper
 * Then, convert this value to what the user expects: `Root/users`. Because of this formatting, we need to execute a call against the server,
 * to fetch the information of how the type name and type namespaced name
 *
 * @param {Object} props
 */
const FieldDirectivePrintout = ( props ) => {
	const {
		emptyLabel,
		disableFields,
		disableDirectives,
		removeHeaderIfItemDisabled,
		fieldHeader = __('Fields', 'graphql-api'),
		directiveHeader = __('Directives', 'graphql-api'),
	} = props;
	const emptyLabelString = emptyLabel != undefined ? emptyLabel : EMPTY_LABEL;
	return (
		<Card { ...props }>
			{ ! disableFields && (
				<>
					{ ( ! removeHeaderIfItemDisabled || ! disableDirectives ) && (
						<CardHeader isShady>{ fieldHeader }</CardHeader>
					) }
					<CardBody>
						<MaybeWithSpinnerFieldPrintoutBody
							{ ...props }
							emptyLabelString={ emptyLabelString }
						/>
					</CardBody>
				</>
			) }
			{ ! disableDirectives && (
				<>
					{ ( ! removeHeaderIfItemDisabled || ! disableFields ) && (
						<CardHeader isShady>{ directiveHeader }</CardHeader>
					) }
					<CardBody>
						{/* <MaybeWithSpinnerDirectivePrintoutBody */}
						<DirectivePrintoutBody
							{ ...props }
							emptyLabelString={ emptyLabelString }
						/>
					</CardBody>
				</>
			) }
		</Card>
	);
}

// /**
//  * Check if the typeFields are empty, then do not show the spinner
//  * This is an improvement when loading a new Access Control post, that it has no data, so the user is not waiting for nothing
//  *
//  * @param {Object} props
//  */
// const MaybeWithSpinnerFieldDirectivePrintout = ( props ) => {
// 	const { typeFields } = props;
// 	if ( !! typeFields.length ) {
// 		return (
// 			<WithSpinnerFieldDirectivePrintout { ...props } />
// 		)
// 	}
// 	return (
// 		<FieldDirectivePrintout { ...props } />
// 	);
// }

// /**
//  * Add a spinner when loading the typeFieldNames and typeFields is not empty
//  */
// const WithSpinnerFieldDirectivePrintout = compose( [
// 	withSpinner(),
// 	withErrorMessage(),
// ] )( FieldDirectivePrintout );

export default compose( [
	withSelect( ( select, ownProps ) => {
		const { disableFields } = ownProps;
		if ( disableFields ) {
			return {};
		}

		const {
			getTypeFields,
			hasRetrievedTypeFields,
			getRetrievingTypeFieldsErrorMessage,
		} = select ( 'graphql-api/components' );
		/**
		 * Convert typeFields object, from this structure:
		 * [{type:"Type", fields:["field1", "field2",...]},...]
		 * To this one:
		 * {namespacedTypeName.field:"typeName/field",...}
		 */
		const reducer = (accumulator, currentValue) => Object.assign(accumulator, currentValue);
		const typeFieldNames = getTypeFields().flatMap(function(typeItem) {
			return typeItem.fields.flatMap(function(field) {
				return {
					[`${ typeItem.typeNamespacedName }${ TYPE_FIELD_SEPARATOR_FOR_DB }${ field }`]: {
						typeName: typeItem.typeName,
						field: field,
					},
				}
			})
		}).reduce(reducer, {});
		return {
			typeFieldNames,
			hasRetrievedItems: hasRetrievedTypeFields(),
			errorMessage: getRetrievingTypeFieldsErrorMessage(),
		};
	} ),
] )( FieldDirectivePrintout/*MaybeWithSpinnerFieldDirectivePrintout*/ );
