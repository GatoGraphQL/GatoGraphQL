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

/**
 * @param {Object} props
 */
const ItemPrintoutBody = ( props ) => {
	const {
		items,
		emptyLabelString,
	} = props;
	return (
		<>
			{ !! items.length && (
				<ul class="checkbox-list">
					{ items.map( item =>
						<li
							key={ item }
						>
							<CheckboxControl
								label={ `${ item }` }
								checked={ true }
								disabled={ true }
							/>
						</li>
					) }
				</ul>
			) }
			{ !items.length && (
				emptyLabelString
			) }
		</>
	);
}

const TypeFieldPrintoutBody = ( props ) => {
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
const WithSpinnerTypeFieldPrintoutBody = compose( [
	withSpinner(),
	withErrorMessage(),
] )( TypeFieldPrintoutBody );

/**
 * Check if the typeFields are empty, then do not show the spinner
 * This is an improvement when loading a new Access Control post,
 * that it has no data, so the user is not waiting for nothing
 *
 * @param {Object} props
 */
const MaybeWithSpinnerTypeFieldPrintoutBody = ( props ) => {
	const { typeFields } = props;
	if ( !! typeFields.length ) {
		return (
			<WithSpinnerTypeFieldPrintoutBody { ...props } />
		)
	}
	return (
		<TypeFieldPrintoutBody { ...props } />
	);
}

// /**
//  * Add a spinner when loading the items and the corresponding attribute is not empty
//  */
// const WithSpinnerItemPrintoutBody = compose( [
// 	withSpinner(),
// 	withErrorMessage(),
// ] )( ItemPrintoutBody );

// /**
//  * Check if the items are empty, then do not show the spinner
//  * This is an improvement when loading a new Access Control post,
//  * that it has no data, so the user is not waiting for nothing
//  *
//  * @param {Object} props
//  */
// const MaybeWithSpinnerItemPrintoutBody = ( props ) => {
// 	const { items } = props;
// 	if ( !! items.length ) {
// 		return (
// 			<WithSpinnerItemPrintoutBody { ...props } />
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
const SchemaElementsPrintout = ( props ) => {
	const {
		emptyLabel,
		enableOperations,
		enableTypeFields,
		enableGlobalFields,
		enableDirectives,
		operations,
		globalFields,
		directives,
		operationHeader = __('Operations', 'graphql-api'),
		typeFieldHeader = __('Fields', 'graphql-api'),
		globalFieldHeader = __('Global Fields', 'graphql-api'),
		directiveHeader = __('Directives', 'graphql-api'),
	} = props;
	const emptyLabelString = emptyLabel != undefined ? emptyLabel : EMPTY_LABEL;
	return (
		<Card { ...props }>
			{ enableOperations && (
				<>
					<CardHeader isShady>{ operationHeader }</CardHeader>
					<CardBody>
						<ItemPrintoutBody
							{ ...props }
							items= { operations }
							emptyLabelString={ emptyLabelString }
						/>
					</CardBody>
				</>
			) }
			{ enableTypeFields && (
				<>
					<CardHeader isShady>{ typeFieldHeader }</CardHeader>
					<CardBody>
						<MaybeWithSpinnerTypeFieldPrintoutBody
							{ ...props }
							emptyLabelString={ emptyLabelString }
						/>
					</CardBody>
				</>
			) }
			{ enableGlobalFields && (
				<>
					<CardHeader isShady>{ globalFieldHeader }</CardHeader>
					<CardBody>
						<ItemPrintoutBody
							{ ...props }
							items= { globalFields }
							emptyLabelString={ emptyLabelString }
						/>
					</CardBody>
				</>
			) }
			{ enableDirectives && (
				<>
					<CardHeader isShady>{ directiveHeader }</CardHeader>
					<CardBody>
						{/* <MaybeWithSpinnerItemPrintoutBody */}
						<ItemPrintoutBody
							{ ...props }
							items={ directives }
							emptyLabelString={ emptyLabelString }
						/>
					</CardBody>
				</>
			) }
		</Card>
	);
}

export default compose( [
	withSelect( ( select, ownProps ) => {
		const { enableTypeFields } = ownProps;
		if ( ! enableTypeFields ) {
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
] )( SchemaElementsPrintout );
