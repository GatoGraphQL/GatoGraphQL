/**
 * External dependencies
 */
import { without, map, intersection } from 'lodash';

/**
 * WordPress dependencies
 */
import { compose, withInstanceId } from '@wordpress/compose';
import { CheckboxControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import MultiSelectControlGroupChecklist from './checklist';

function MultiSelectControlGroup( {
	instanceId,
	group,
	groupKind = '',
	groupDescription = '',
	items,
	selectedItems,
	setAttributes,
	attributeName,
} ) {
	const checkedItems = intersection(
		map( items, 'value' ),
		selectedItems
	);
	const toggleVisible = ( itemValue, nextIsChecked ) => {
		setAttributes( {
			[ attributeName ]: nextIsChecked ? [...selectedItems, itemValue] : without(selectedItems, itemValue)
		} );
	};
	const toggleAllVisible = ( nextIsChecked ) => {
		const itemValues = map( items, 'value' );
		setAttributes( {
			[ attributeName ]: nextIsChecked ? [...selectedItems, ...itemValues] : without(selectedItems, ...itemValues)
		} );
	};

	const titleId =
		'multi-select-control__category-title-' + instanceId;

	const isAllChecked = checkedItems.length === items.length;

	let ariaChecked;
	if ( isAllChecked ) {
		ariaChecked = 'true';
	} else if ( checkedItems.length > 0 ) {
		ariaChecked = 'mixed';
	} else {
		ariaChecked = 'false';
	}

	const className = `multi-select-control__category ${ groupKind }`
	return (
		<div
			role="group"
			aria-labelledby={ titleId }
			className={ className }
		>
			<CheckboxControl
				checked={ isAllChecked }
				onChange={ toggleAllVisible }
				className="multi-select-control__category-title"
				aria-checked={ ariaChecked }
				label={ <span id={ titleId }>{ group }</span> }
				help={ groupDescription }
			/>
			<MultiSelectControlGroupChecklist
				items={ items }
				value={ checkedItems }
				onItemChange={ toggleVisible }
			/>
		</div>
	);
}

export default compose( [
	withInstanceId,
] )( MultiSelectControlGroup );
