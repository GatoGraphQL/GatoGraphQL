/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { TextareaControl } from '@wordpress/components';
import './style.scss';

const EditableArrayTextareaControl = ( props ) => {
	const {
		values,
		className,
		setAttributes,
		isSelected,
		attributeName
	} = props;
	const componentClassName = 'graphql-api-textarea-control';
	return (
		<>
			{ isSelected &&
				<TextareaControl
					{ ...props }
					value={ values.join('\n') }
					onChange={ value =>
						setAttributes( {
							[ attributeName ]: value.split('\n')
						} )
					}
				/>
			}
			{ !isSelected && !!values.length && (
				<div className={ `${ className }__value-group ${ componentClassName }__value-group` }>
					{ values.map( val =>
						<div className={ `${ className }__value-item ${ componentClassName }__value-item ` }>
							{ !!val && val }
							{ !val && (
								<>&nbsp;</>
							) }
						</div>
					) }
				</div>
			) }
			{ !isSelected && !values.length && (
				<div className={ `${ className }__not-set ${ componentClassName }__not-set` }>
					<em>{ __('(not set)', 'graphql-api') }</em>
				</div>
			) }
		</>
	)
}

export default EditableArrayTextareaControl;
