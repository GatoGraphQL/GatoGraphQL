/**
 * WordPress dependencies
 */
import { useSelect } from '@wordpress/data';
import { safeDecodeURIComponent, cleanForSlug } from '@wordpress/url';
import { __ } from '@wordpress/i18n';
import { ExternalLink } from '@wordpress/components';
import { store as editorStore } from '@wordpress/editor';

/**
 * Internal dependencies
 */

export default function CustomEndpointProperties( { onClose } ) {
	const {
		isEditable,
		postSlug,
		postLink,
		permalinkPrefix,
		permalinkSuffix,
	} = useSelect( ( select ) => {
		const post = select( editorStore ).getCurrentPost();
		const permalinkParts = select( editorStore ).getPermalinkParts();
		const hasPublishAction = post?._links?.[ 'wp:action-publish' ] ?? false;

		return {
			isEditable:
				select( editorStore ).isPermalinkEditable() && hasPublishAction,
			postSlug: safeDecodeURIComponent(
				select( editorStore ).getEditedPostSlug()
			),
			postLink: post.link,
			permalinkPrefix: permalinkParts?.prefix,
			permalinkSuffix: permalinkParts?.suffix,
		};
	}, [] );

	return (
		<div className="editor-post-url">
			{ isEditable && (
				<h3 className="editor-post-url__link-label">
					{ __( 'GraphQL Custom Endpoint URL' ) }
				</h3>
			) }
			<p>
				<ExternalLink
					className="editor-post-url__link"
					href={ postLink }
					target="_blank"
				>
					{ isEditable ? (
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
					) : (
						postLink
					) }
				</ExternalLink>
			</p>
		</div>
	);
}
