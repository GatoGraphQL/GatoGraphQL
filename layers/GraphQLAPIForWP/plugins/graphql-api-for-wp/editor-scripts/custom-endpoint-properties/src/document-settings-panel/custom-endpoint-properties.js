/**
 * WordPress dependencies
 */
import { useSelect } from '@wordpress/data';
import { safeDecodeURIComponent } from '@wordpress/url';
import { __ } from '@wordpress/i18n';
import { ExternalLink } from '@wordpress/components';
import { store as editorStore } from '@wordpress/editor';

/**
 * Internal dependencies
 */
import './style.scss';

export default function CustomEndpointProperties() {
	const {
		postSlug,
		postLink,
		isPostPublished,
		permalinkPrefix,
		permalinkSuffix,
		isCustomEndpointEnabled,
		isGraphiQLClientEnabled,
		isVoyagerClientEnabled,
	} = useSelect( ( select ) => {
		const post = select( editorStore ).getCurrentPost();
		const permalinkParts = select( editorStore ).getPermalinkParts();
		const blocks = select( editorStore ).getBlocks();
		const customEndpointOptionsBlock = blocks.filter(
			block => block.name === 'graphql-api/custom-endpoint-options'
		).shift();
		const graphiQLClientBlock = blocks.filter(
			block => block.name === 'graphql-api/endpoint-graphiql'
		).shift();
		const voyagerClientBlock = blocks.filter(
			block => block.name === 'graphql-api/endpoint-voyager'
		).shift();

		return {
			postSlug: safeDecodeURIComponent(
				select( editorStore ).getEditedPostSlug()
			),
			postLink: post.link,
			isPostPublished: post.status === 'publish',
			permalinkPrefix: permalinkParts?.prefix,
			permalinkSuffix: permalinkParts?.suffix,
			/**
			 * Same attribute name as defined in
			 * GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractEndpointOptionsBlock::ATTRIBUTE_NAME_IS_ENABLED
			 */
			isCustomEndpointEnabled: customEndpointOptionsBlock.attributes.isEnabled,
			/**
			 * Same attribute name as defined in
			 * GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractEndpointOptionsBlock::ATTRIBUTE_NAME_IS_ENABLED
			 */
			isGraphiQLClientEnabled: graphiQLClientBlock.attributes.isEnabled,
			/**
			 * Same attribute name as defined in
			 * GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractEndpointOptionsBlock::ATTRIBUTE_NAME_IS_ENABLED
			 */
			isVoyagerClientEnabled: voyagerClientBlock.attributes.isEnabled,
		};
	}, [] );

	return (
		<>
			{ ! isPostPublished && (
				<p className="unpublished-endpoint">
					<em>{ __('This section will present information when the Custom Endpoint is published', 'graphql-api') }</em>
				</p>
			) }
			{ isPostPublished && (
				<>
					<div className="editor-post-url">
						<h3 className="editor-post-url__link-label">
							{ isCustomEndpointEnabled ? '🟢' : '🔴'} { __( 'Custom Endpoint URL' ) }
						</h3>
						<p>
							{ isCustomEndpointEnabled && (
								<ExternalLink
									className="editor-post-url__link"
									href={ postLink }
									target="_blank"
								>
									<>
										<span className="editor-post-url__link-prefix">
											{ permalinkPrefix }
										</span>
										<span className="editor-post-url__link-slug">
											{ postSlug }
										</span>
										<span className="editor-post-url__link-suffix">
											{ permalinkSuffix }
										</span>
									</>
								</ExternalLink>
							) }
							{ ! isCustomEndpointEnabled && (
								<span className="disabled-text">{ __('Disabled', 'graphql-api') }</span>
							) }
						</p>
					</div>
					<hr/>
					<div className="editor-post-url">
						<h3 className="editor-post-url__link-label">
							🔵 { __( 'View Endpoint Source' ) }
						</h3>
						<p>
							<ExternalLink
								className="editor-post-url__link"
								href={ postLink + '?view=source' }
								target="_blank"
							>
								<>
									<span className="editor-post-url__link-prefix">
										{ permalinkPrefix }
									</span>
									<span className="editor-post-url__link-slug">
										{ postSlug }
									</span>
									<span className="editor-post-url__link-suffix">
										{ permalinkSuffix }
									</span>
									<span className="editor-endoint-custom-post-url__link-view">
										{ '?view=' }
									</span>
									<span className="editor-endoint-custom-post-url__link-view-item">
										{ 'source' }
									</span>
								</>
							</ExternalLink>
						</p>
					</div>
					<hr/>
					<div className="editor-post-url">
						<h3 className="editor-post-url__link-label">
							{ isGraphiQLClientEnabled ? '🟢' : '🔴'} { __( 'GraphiQL client' ) }
						</h3>
						<p>
							{ isGraphiQLClientEnabled && (
								<ExternalLink
									className="editor-post-url__link"
									href={ postLink + '?view=graphiql' }
									target="_blank"
								>
									<>
										<span className="editor-post-url__link-prefix">
											{ permalinkPrefix }
										</span>
										<span className="editor-post-url__link-slug">
											{ postSlug }
										</span>
										<span className="editor-post-url__link-suffix">
											{ permalinkSuffix }
										</span>
										<span className="editor-endoint-custom-post-url__link-view">
											{ '?view=' }
										</span>
										<span className="editor-endoint-custom-post-url__link-view-item">
											{ 'graphiql' }
										</span>
									</>
								</ExternalLink>
							) }
							{ ! isGraphiQLClientEnabled && (
								<span className="disabled-text">{ __('Disabled', 'graphql-api') }</span>
							) }
						</p>
					</div>
					<hr/>
					<div className="editor-post-url">
						<h3 className="editor-post-url__link-label">
							{ isVoyagerClientEnabled ? '🟢' : '🔴'} { __( 'Interactive Schema Client' ) }
						</h3>
						<p>
							{ isVoyagerClientEnabled && (
								<ExternalLink
									className="editor-post-url__link"
									href={ postLink + '?view=schema' }
									target="_blank"
								>
									<>
										<span className="editor-post-url__link-prefix">
											{ permalinkPrefix }
										</span>
										<span className="editor-post-url__link-slug">
											{ postSlug }
										</span>
										<span className="editor-post-url__link-suffix">
											{ permalinkSuffix }
										</span>
										<span className="editor-endoint-custom-post-url__link-view">
											{ '?view=' }
										</span>
										<span className="editor-endoint-custom-post-url__link-view-item">
											{ 'schema' }
										</span>
									</>
								</ExternalLink>
							) }
							{ ! isVoyagerClientEnabled && (
								<span className="disabled-text">{ __('Disabled', 'graphql-api') }</span>
							) }
						</p>
					</div>
				</>
			) }
		</>
	);
}
