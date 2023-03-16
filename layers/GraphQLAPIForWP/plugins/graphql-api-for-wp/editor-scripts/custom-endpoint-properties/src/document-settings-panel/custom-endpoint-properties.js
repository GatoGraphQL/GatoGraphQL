/**
 * WordPress dependencies
 */
import { useSelect } from '@wordpress/data';
import { safeDecodeURIComponent } from '@wordpress/url';
import { __ } from '@wordpress/i18n';
import { ExternalLink } from '@wordpress/components';
import { store as editorStore } from '@wordpress/editor';

export default function CustomEndpointProperties() {
	const {
		postSlug,
		postLink,
		permalinkPrefix,
		permalinkSuffix,
	} = useSelect( ( select ) => {
		const post = select( editorStore ).getCurrentPost();
		const permalinkParts = select( editorStore ).getPermalinkParts();

		return {
			postSlug: safeDecodeURIComponent(
				select( editorStore ).getEditedPostSlug()
			),
			postLink: post.link,
			permalinkPrefix: permalinkParts?.prefix,
			permalinkSuffix: permalinkParts?.suffix,
		};
	}, [] );

	return (
		<>
			<div className="editor-post-url">
				<h3 className="editor-post-url__link-label">
					{ __( 'Endpoint URL' ) }
				</h3>
				<p>
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
				</p>
			</div>
			<div className="editor-post-url">
				<h3 className="editor-post-url__link-label">
					{ __( 'Endpoint Source' ) }
				</h3>
				<p>
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
								{ permalinkSuffix + '?view=source' }
							</span>
						</>
					</ExternalLink>
				</p>
			</div>
			<div className="editor-post-url">
				<h3 className="editor-post-url__link-label">
					{ __( 'GraphiQL client' ) }
				</h3>
				<p>
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
								{ permalinkSuffix + '?view=graphiql' }
							</span>
						</>
					</ExternalLink>
				</p>
			</div>
			<div className="editor-post-url">
				<h3 className="editor-post-url__link-label">
					{ __( 'Interactive Schema Client' ) }
				</h3>
				<p>
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
								{ permalinkSuffix + '?view=schema' }
							</span>
						</>
					</ExternalLink>
				</p>
			</div>
		</>
	);
}
