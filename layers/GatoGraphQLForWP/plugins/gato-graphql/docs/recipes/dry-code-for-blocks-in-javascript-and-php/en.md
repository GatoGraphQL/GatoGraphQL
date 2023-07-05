# DRY code for blocks in Javascript and PHP

[Dynamic blocks](https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/creating-dynamic-blocks/) are blocks that build their structure and content on the fly when the block is rendered on the front end.

Then, rendering a dynamic block in the front-end (to display it in the WordPress editor) and in the server-side (to generate the HTML for the blog post) must fetch its data twice, in two different ways:

- Connecting to the API on the client-side (JavaScript)
- Calling WordPress functions on the server-side (PHP)

With Gato GraphQL and extensions, it is possible to make this DRY, having a single source of truth to fetch data for both the client and server-sides.

The previous recipe explained how to connect to the GraphQL server from the client, using JavaScript.

	Document:
		Move calling accessControlLists and all the others to a .gql file
			So can use in docs!!!
		Use:
			https://v4.webpack.js.org/loaders/raw-loader/
			https://stackoverflow.com/questions/47122504/import-raw-files-from-typescript-with-webpack-using-the-import-statement
		Check:
			submodules/PoP/layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/blocks/schema-configuration/graphql-documents/schema-configurations.gql




	Add recipe "Using GraphQL to feed data to blocks (frontend and backend)"
		wp-admin endpoint can be used by blocks from the editor
			then blocks can be rendered by executing a query against it
			print the URL of this endpoint
				Here and also in documentation in graphql-api.com
		and PHP blocks can be rendered by calling GraphQLServerFactory::getInstance()->...
		Demonstrate code re-using the same query.gql file, read by both:
			- the .js (block)
			- the .php (rendering)
