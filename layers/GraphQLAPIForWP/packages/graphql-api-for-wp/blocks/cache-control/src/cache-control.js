/**
 * WordPress dependencies
 */
import { __, sprintf } from '@wordpress/i18n';
import { TextControl, Card, CardHeader, CardBody } from '@wordpress/components';

/**
 * Internal dependencies
 */
import { MarkdownInfoModalButton, getEditableOnFocusComponentClass } from '@graphqlapi/components';
import { getMarkdownContentOrUseDefault } from './markdown-loader';

const CacheControl = ( props ) => {
	const { className, setAttributes, isSelected, attributes: { cacheControlMaxAge } } = props;
	const title = __('Cache-Control max-age', 'graphql-api')
	const componentClassName = getEditableOnFocusComponentClass(isSelected);
	return (
		<div className={ componentClassName }>
			<Card>
				<CardHeader isShady>
					<span>
						{ title }
						{ isSelected && (
							<MarkdownInfoModalButton
								title={ title }
								pageFilename="max-age"
								getMarkdownContentCallback={ getMarkdownContentOrUseDefault }
							/>
						) }
					</span>
				</CardHeader>
				<CardBody>
					{ isSelected && (
						<TextControl
							label={ __('Max-age (in seconds)', 'graphql-api') }
							type="text"
							value={ cacheControlMaxAge }
							className={ className+'__maxage' }
							onChange={ newValue =>
								setAttributes( {
									cacheControlMaxAge: Number(newValue),
								} )
							}
						/>
					) }
					{ !isSelected && (
						<span>
							{ cacheControlMaxAge == null && (
								<em>{ __('(not set)', 'graphql-api') }</em>
							) }
							{ cacheControlMaxAge != null && (
								<>
									{ cacheControlMaxAge == 0 && (
										sprintf(
											__('%s seconds (%s)', 'graphql-api'),
											cacheControlMaxAge,
											'no-store'
										)
									) }
									{ cacheControlMaxAge != 0 && (
										sprintf(
											__('%s seconds', 'graphql-api'),
											cacheControlMaxAge
										)
									) }
								</>
							) }
						</span>
					) }
				</CardBody>
			</Card>
		</div>
	);
}

export default CacheControl;
