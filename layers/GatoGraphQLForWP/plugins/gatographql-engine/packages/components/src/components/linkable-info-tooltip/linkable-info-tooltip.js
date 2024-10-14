/**
 * WordPress dependencies
 */
import { Tooltip, Icon, ExternalLink } from '@wordpress/components';

/**
 * Internal dependencies
 */
import './style.scss';


const LinkableInfoTooltip = ( props ) => {
	const {
		text,
		className,
		href,
		iconSize = 24,
		onlyIfIsSelected = true,
		isSelected
	} = props;
	if (onlyIfIsSelected && !isSelected) {
		return '';
	}
	const linkClassName = ( className ? ( className+'__link' ) : '' ) + 'linkable-info-tooltip__link';
	return (
		<span className={ className }>
			<Tooltip text={ text }>
				<span>
					<Icon icon="editor-help" size={ iconSize } />
					<sup>
						<ExternalLink
							className={ linkClassName }
							href={ href }
						>
						</ExternalLink>
					</sup>
				</span>
			</Tooltip>
		</span>
	);
}

export default LinkableInfoTooltip;
