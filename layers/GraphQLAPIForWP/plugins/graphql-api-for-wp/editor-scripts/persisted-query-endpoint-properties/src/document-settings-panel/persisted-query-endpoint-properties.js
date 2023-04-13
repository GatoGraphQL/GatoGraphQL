/**
 * WordPress dependencies
 */
import { useSelect } from '@wordpress/data';
import { safeDecodeURIComponent } from '@wordpress/url';
import { __ } from '@wordpress/i18n';
import { ExternalLink } from '@wordpress/components';
import { store as editorStore } from '@wordpress/editor';
import { store as blockEditorStore } from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import './style.scss';

export default function PersistedQueryEndpointProperties() {
	const {
		postSlug,
		postLink,
		postLinkHasParams,
		postStatus,
		isPostPublished,
		permalinkPrefix,
		permalinkSuffix,
		isPersistedQueryEndpointEnabled,
	} = useSelect( ( select ) => {
		const post = select( editorStore ).getCurrentPost();
		const permalinkParts = select( editorStore ).getPermalinkParts();
		const blocks = select( blockEditorStore ).getBlocks();
		const persistedQueryEndpointOptionsBlock = blocks.filter(
			block => block.name === 'graphql-api/persisted-query-endpoint-options'
		).shift();

		return {
			postSlug: safeDecodeURIComponent(
				select( editorStore ).getEditedPostSlug()
			),
			postLink: post.link,
			postLinkHasParams: post.link.indexOf('?') >= 0,
			postStatus: post.status,
			isPostPublished: post.status === 'publish',
			permalinkPrefix: permalinkParts?.prefix,
			permalinkSuffix: permalinkParts?.suffix,
			/**
			 * Same attribute name as defined in
			 * GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractEndpointOptionsBlock::ATTRIBUTE_NAME_IS_ENABLED
			 */
			isPersistedQueryEndpointEnabled: persistedQueryEndpointOptionsBlock.attributes.isEnabled,
		};
	}, [] );

	const postLinkFirstParamSymbol = postLinkHasParams ? '&' : '?';
	const statusCircle = isPostPublished ? '🟢' : '🟡';
	return (
		<>
			<div className="editor-post-url">
				{ isPersistedQueryEndpointEnabled && (
					<p className="notice-message">
						<Notice status={ isPostPublished ? "success" : "warning"} isDismissible={ false }>
							<strong>{ __('Status ', 'graphql-api') }<code>{ postStatus }</code>:</strong><br/>
							<span className="notice-inner-message">
								{ isPostPublished && (
									__('Available to everyone.', 'graphql-api')
								) }
								{ ! isPostPublished && (
									__('Available to the Schema editors only.', 'graphql-api')
								) }
							</span>
						</Notice>
					</p>
				) }
				<h3 className="editor-post-url__link-label">
					{ isPersistedQueryEndpointEnabled ? statusCircle : '🔴'} { __( 'Persisted Query Endpoint URL' ) }
				</h3>
				<p>
					{ isPersistedQueryEndpointEnabled && (
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
					{ ! isPersistedQueryEndpointEnabled && (
						<span className="disabled-text">{ __('Disabled', 'graphql-api') }</span>
					) }
				</p>
			</div>
			<hr/>
			<div className="editor-post-url">
				<h3 className="editor-post-url__link-label">
					{ __( 'View Persisted Query Source' ) }
				</h3>
				<p>
					<ExternalLink
						className="editor-post-url__link"
						href={ postLink + postLinkFirstParamSymbol + 'view=source' }
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
		</>
	);
}
