/**
 * WordPress dependencies
 */
import { useState, useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { InfoModalButton } from '../info-modal';

const MarkdownInfoModalButton = ( props ) => {
	const { 
		pageFilename,
		getMarkdownContentCallback,
	} = props;
	const [ page, setPage ] = useState([]);
	useEffect(() => {
		getMarkdownContentCallback( pageFilename ).then( value => {
			setPage( value )
		});
	}, []);
	return (
		<InfoModalButton
			{ ...props }
			content={ page }
		/>
	);
};
export default MarkdownInfoModalButton;
