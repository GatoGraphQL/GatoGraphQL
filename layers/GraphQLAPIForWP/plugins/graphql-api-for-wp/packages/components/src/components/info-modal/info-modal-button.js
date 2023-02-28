/**
 * WordPress dependencies
 */
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Button } from '@wordpress/components';
import InfoModal from './info-modal';

/**
 * Internal dependencies
 */

const InfoModalButton = ( props ) => {
	const { 
		icon = "editor-help",
		variant = "tertiary",
		isSmall = true
	} = props;
	const [ isOpen, setOpen ] = useState( false );
	return (
		<>
			<Button
				{ ...props }
				variant={ variant } 
				icon={ icon }
				isSmall={ isSmall } 
				onClick={ () => setOpen( true ) }
			/>
			{ isOpen && (
				<InfoModal 
					{ ...props }
					onRequestClose={ () => setOpen( false ) }
				/>
			) }
		</>
	);
};
export default InfoModalButton;
