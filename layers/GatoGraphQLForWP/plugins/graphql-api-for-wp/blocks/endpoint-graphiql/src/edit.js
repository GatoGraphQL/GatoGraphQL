/**
 * Application imports
 */
import EndpointGraphiQL from './endpoint-graphiql';

const EditBlock = ( props ) => {
	const { className } = props;
	return (
		<div class={ className }>
			<EndpointGraphiQL
				{ ...props }
			/>
		</div>
	)
}

export default EditBlock;
