import { MarkdownInfoModalButton } from '../markdown-modal';
import { InfoTooltip } from '../info-tooltip';
import { __, sprintf } from '@wordpress/i18n';

const CardHeaderContent = ( props ) => {
	const {
		header,
		tooltip,
		isSelected,
		getMarkdownContentCallback,
		documentationTitle = sprintf( __( 'Documentation for: "%s"', 'gatographql' ), header )
	} = props;
	return (
		<span>
			{ header }
			{ !! tooltip &&
				<InfoTooltip
					{ ...props }
					text={ tooltip }
				/>
			}
			{ !! getMarkdownContentCallback && isSelected && (
				<MarkdownInfoModalButton
					{ ...props }
					title={ documentationTitle }
					getMarkdownContentCallback={ getMarkdownContentCallback }
				/>
			) }
		</span>
	)
}

export default CardHeaderContent;

