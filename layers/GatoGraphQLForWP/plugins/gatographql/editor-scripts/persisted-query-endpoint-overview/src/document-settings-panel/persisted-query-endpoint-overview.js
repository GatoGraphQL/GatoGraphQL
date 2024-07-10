/**
 * WordPress dependencies
 */
import { useSelect } from '@wordpress/data';
import { safeDecodeURIComponent } from '@wordpress/url';
import { __ } from '@wordpress/i18n';
import {
	ExternalLink,
	Notice,
} from '@wordpress/components';
import { store as editorStore } from '@wordpress/editor';
import { store as blockEditorStore } from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import './style.scss';

export default function PersistedQueryEndpointOverview() {
	const {
		postSlug,
		postLink,
		postLinkHasParams,
		postStatus,
		isPostPublished,
		isPostDraftOrPending,
		isPostPrivate,
		isPostPasswordProtected,
		permalinkPrefix,
		permalinkSuffix,
		isPersistedQueryEndpointEnabled,
	} = useSelect( ( select ) => {
		const post = select( editorStore ).getCurrentPost();
		const permalinkParts = select( editorStore ).getPermalinkParts();
		const blocks = select( blockEditorStore ).getBlocks();
		const persistedQueryEndpointOptionsBlock = blocks.filter(
			block => block.name === 'gatographql/persisted-query-endpoint-options'
		).shift();

		return {
			postSlug: safeDecodeURIComponent(
				select( editorStore ).getEditedPostSlug()
			),
			postLink: post.link,
			postLinkHasParams: post.link.indexOf('?') >= 0,
			postStatus: post.status,
			isPostPublished: post.status === 'publish',
			isPostDraftOrPending: post.status === 'draft' || post.status === 'pending',
			isPostPrivate: post.status === 'private',
			isPostPasswordProtected: !! post.password,
			permalinkPrefix: permalinkParts?.prefix,
			permalinkSuffix: permalinkParts?.suffix,
			/**
			 * Same attribute name as defined in
			 * GatoGraphQL\GatoGraphQL\Services\Blocks\AbstractEndpointOptionsBlock::ATTRIBUTE_NAME_IS_ENABLED
			 */
			isPersistedQueryEndpointEnabled: persistedQueryEndpointOptionsBlock.attributes.isEnabled,
		};
	}, [] );

	const postLinkFirstParamSymbol = postLinkHasParams ? '&' : '?';
	const statusCircle = isPostPublished && !isPostPasswordProtected ? '🟢' : (isPostDraftOrPending || isPostPrivate || isPostPasswordProtected ? '🟡' : '🔴');
	const isPostAvailable = isPostPublished || isPostDraftOrPending || isPostPrivate;
	return (
		<>
			{ isPersistedQueryEndpointEnabled && (
				<p className="notice-message">
					<Notice status={ isPostPublished && ! isPostPasswordProtected ? "success" : (isPostDraftOrPending || isPostPrivate || isPostPasswordProtected ? "warning" : "error") } isDismissible={ false }>
						<strong>
							{ __('Status ', 'gatographql') }
							<code>{ postStatus }</code>
							{ isPostPasswordProtected && (
								__(' (protected by password)', 'gatographql')
							) }
							{ __(': ', 'gatographql') }
						</strong>
						<br/>
						<span className="notice-inner-message">
							{ isPostPublished && ! isPostPasswordProtected && (
								__('Persisted query is public, available to everyone.', 'gatographql')
							) }
							{ isPostPublished && isPostPasswordProtected && (
								__('Persisted query is public, available to anyone with the required password.', 'gatographql')
							) }
							{ isPostDraftOrPending && ! isPostPasswordProtected && (
								__('Persisted query is not yet public, only available to the Schema editors.', 'gatographql')
							) }
							{ isPostDraftOrPending && isPostPasswordProtected && (
								__('Persisted query is not yet public, only available to the Schema editors with the required password.', 'gatographql')
							) }
							{ isPostPrivate && (
								__('Persisted query is private, only available to the Schema editors.', 'gatographql')
							) }
							{ ! isPostAvailable && (
								__('Persisted query is not yet available.', 'gatographql')
							) }
						</span>
					</Notice>
				</p>
			) }
			{ isPostAvailable && (
				<>
					<div className="editor-post-url">
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
								<span className="disabled-text">{ __('Disabled', 'gatographql') }</span>
							) }
						</p>
					</div>
					<hr/>
					<div className="editor-post-url">
						<h3 className="editor-post-url__link-label">
							{ isPostAvailable ? '🟡' : '🔴' } { __( 'View Persisted Query Source' ) }
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
									<span className="editor-endpoint-custom-post-url__link-view">
										{ '?view=' }
									</span>
									<span className="editor-endpoint-custom-post-url__link-view-item">
										{ 'source' }
									</span>
								</>
							</ExternalLink>
						</p>
					</div>
				</>
			) }
		</>
	);
}
