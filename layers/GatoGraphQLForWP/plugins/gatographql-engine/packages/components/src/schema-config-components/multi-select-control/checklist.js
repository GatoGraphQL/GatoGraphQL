/**
 * External dependencies
 */
import { partial } from 'lodash';

/**
 * WordPress dependencies
 */
import { CheckboxControl, Tooltip, Icon } from '@wordpress/components';

function MultiSelectControlGroupChecklist( { items, value, onItemChange } ) {
	return (
		<ul className="multi-select-control__checklist">
			{ items.map( ( item ) => (
				<li
					key={ item.value }
					className="multi-select-control__checklist-item"
				>
					<CheckboxControl
						label={ item.title }
						checked={ value.includes( item.value ) }
						onChange={ partial( onItemChange, item.value ) }
						help={ item.help }
					/>
				</li>
			) ) }
		</ul>
	);
}

export default MultiSelectControlGroupChecklist;
