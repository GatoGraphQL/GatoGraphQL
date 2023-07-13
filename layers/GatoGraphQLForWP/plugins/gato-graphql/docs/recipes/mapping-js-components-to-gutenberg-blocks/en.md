# Mapping JS components to (Gutenberg) blocks

_(This recipe has been inspired by [`Automattic/vip-block-data-api`](https://github.com/Automattic/vip-block-data-api/#preact-example).)_

An example [Preact](https://preactjs.com/) app that queries for block data and maps it into customized components.

The following code retrieves post and block metadata from the GraphQL single endpoint, and maps each block into a custom component.

```html
<!DOCTYPE html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gato GraphQL - Mapping JS components to (Gutenberg) blocks: Preact example</title>
</head>

<body></body>

<script type="module">
  import { h, render } from 'https://esm.sh/preact';

  const endpoint = "https://mysite.com/graphql/";  
  
  renderPost(endpoint, 40);
  
  async function renderPost(endpoint, postId) {
    const data = {
      query: `
        query GetPost($postId: ID!) {
          post(by: { id: $postId }) {
            title
            blockDataItems
          }
        }
      `,
      variables: {
        postId: `${ postId }`
      },
    };

    const response = await fetch(
      endpoint,
      {
        method: 'post',
        body: JSON.stringify(data),
        headers: {
          Accept: 'application/json',
          'Content-Type': 'application/json',
          'Content-Length': data.length,
        },
      }
    );

    // Execute the query, and await the response
    const json = await response.json();

    // If the query produced errors, exit
    if (json.errors) {
      console.log(JSON.stringify(json.errors));
      return;
    }
    // console.log(json.data);

    const postTitle = json.data.post?.title;
    const blocks = json.data.post?.blockDataItems;

    const App = Post(postTitle, blocks);
    render(App, document.body);
  }

  function mapBlockToComponent(block) {
    if (block.name === 'core/heading') {
      return Heading(block);
    } else if (block.name === 'core/paragraph') {
      return Paragraph(block);
    } else if (block.name === 'core/media-text') {
      return MediaText(block);
    } else if (block.name === 'core/gallery') {
      return Gallery(block);
    } else if (block.name === 'core/image') {
      return Image(block);
    } else {
      return null;
    }
  }

  /* Components */

  function Post(title, blocks) {
    return h('div', { className: 'post' },
      h('h1', null, title),
      blocks.map(mapBlockToComponent),
    );
  }

  function Heading(props) {
    // Use dangerouslySetInnerHTML for rich text formatting
    return h('h2', { dangerouslySetInnerHTML: { __html: props.attributes.content } });
  }

  function Paragraph(props) {
    // Use dangerouslySetInnerHTML for rich text formatting
    return h('p', { dangerouslySetInnerHTML: { __html: props.attributes.content } });
  }

  function MediaText(props) {
    return h('div', { className: 'media-text' },
      h('div', { className: 'media' },
        h('img', { src: props.attributes.mediaUrl })
      ),
      h('div', { className: 'text' },
        props.innerBlocks ? props.innerBlocks.map(mapBlockToComponent) : null,
      ),
    )
  }

  function Gallery(props) {
    return h('div', { className: 'gallery' },
      h('div', { className: 'images' },
        props.innerBlocks ? props.innerBlocks.map(mapBlockToComponent) : null,
      ),
    )
  }

  function Image(props) {
    return h('img', { src: props.attributes.url });
  }
</script>
</html>
```
