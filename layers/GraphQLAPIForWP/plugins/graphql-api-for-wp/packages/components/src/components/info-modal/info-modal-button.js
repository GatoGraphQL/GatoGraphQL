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
	} = props;
	const [ isOpen, setOpen ] = useState( false );
	return (
		<>
			<Button
				variant="tertiary"
				isSmall 
				onClick={ () => setOpen( true ) }
				icon={ icon } 
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
