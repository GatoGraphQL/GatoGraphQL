/**
 * WordPress dependencies
 */
import { Tooltip, Icon } from '@wordpress/components';

const InfoTooltip = ( props ) => {
	const {
		text,
		iconSize = 24,
		onlyIfIsSelected = true,
		isSelected
	} = props;
	if (onlyIfIsSelected && !isSelected) {
		return '';
	}
	return (
		<Tooltip text={ text }>
			<span>
				<Icon icon="editor-help" size={ iconSize } />
			</span>
		</Tooltip>
	);
}

export default InfoTooltip;
