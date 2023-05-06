/**
 * WordPress dependencies
 */
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { ExternalLink, Button, Guide, GuidePage } from '@wordpress/components';

const PersistedQueryEndpointGuide = ( props ) => {
	return (
		<Guide { ...props } >
            <GuidePage>
                <h1>{ __('Creating Persisted Queries', 'gato-graphql') }</h1>
                <p>{ __('This tutorial video demonstrates how to create a new GraphQL persisted query:', 'gato-graphql') }</p>
                <video width="640" height="400" controls>
                <source src="https://d1c2lqfn9an7pb.cloudfront.net/presentations/gato-graphql/videos/gato-graphql-creating-persisted-query.mov" type="video/mp4" />
                { __('Your browser does not support the video tag.', 'gato-graphql') }
                </video> 
                <p>
                    <ExternalLink
                        href="https://vimeo.com/413503547"
                    >
                        { __('Watch in Vimeo', 'gato-graphql') }
                    </ExternalLink>
                </p>
            </GuidePage>
            <GuidePage>
                <h1>{ __('GraphiQL client', 'gato-graphql') }</h1>
                <p>GraphiQL is an in-browser tool for writing, validating, and testing GraphQL queries.</p>
                <p>Type queries into this side of the screen, and you will see intelligent typeaheads aware of the current GraphQL type schema and live syntax and validation errors highlighted within the text.</p>
                <p>GraphQL queries typically start with a {'{'} character. Lines that starts with a # are ignored.</p>
                <p>An example GraphQL query might look like:</p>
                <pre>
                {
`{
  field(arg: "value") {
    subField
  }
}`
                }
                </pre>
            </GuidePage>
            <GuidePage>
                <h1>{ __('Persisted Query Options', 'gato-graphql') }</h1>
                <h2>{ __('Enabled', 'gato-graphql') }</h2>
                <p>If disabled, loading the persisted query's permalink will not execute the query, and the response will be the HTML page for the post</p>
                <h2>{ __('Schema configuration', 'gato-graphql') }</h2>
                <p>What schema configuration to apply to the persisted query. A schema configuration contains the set of Access Control Lists, Cache Control Lists and Field Deprecation Lists to be applied on the schema.</p>
                <p>Select <code>"Default"</code> to use the option configured through the general settings, <code>"Inherit from parent"</code> to create an API hierarchy, <code>"None"</code> if no configuration applies, or the corresponding user-generated Schema configuration from the list.</p>
                <h2>{ __('Accept variables as URL params?', 'gato-graphql') }</h2>
                <p>Override the values passed to variables in the query using URL parameters</p>
            </GuidePage>
        </Guide>
	)
}
const PersistedQueryEndpointGuideButton = ( props ) => {
	const [ isOpen, setOpen ] = useState( false );

	const openGuide = () => setOpen( true );
	const closeGuide = () => setOpen( false );
	return (
		<>
			<Button isTertiary onClick={ openGuide }>
				{ __('Open Guide: “Creating Persisted Queries”', 'gato-graphql') }
			</Button>
			{ isOpen && (
				<PersistedQueryEndpointGuide 
					{ ...props }
					onFinish={ closeGuide }
				/>
			) }
		</>
	);
};
export default PersistedQueryEndpointGuideButton;
