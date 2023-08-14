/**
 * WordPress dependencies
 */
import { Modal } from '@wordpress/components';

const InfoModal = ( props ) => {
	const { content } = props;
	return (
		<Modal 
			{ ...props }
		>
			<div
				dangerouslySetInnerHTML={ { __html: content } }
			/>
		</Modal>
	);
};
export default InfoModal;
