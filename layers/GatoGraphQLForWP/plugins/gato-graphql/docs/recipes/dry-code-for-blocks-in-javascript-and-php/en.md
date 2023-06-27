# DRY code for blocks in Javascript and PHP



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
