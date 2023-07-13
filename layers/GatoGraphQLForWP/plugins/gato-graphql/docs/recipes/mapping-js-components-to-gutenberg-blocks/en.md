# Mapping JS components to (Gutenberg) blocks

This recipe _(inspired by [`Automattic/vip-block-data-api`](https://github.com/Automattic/vip-block-data-api/#preact-example))_ presents an example [Preact](https://preactjs.com/) app that queries for block data and maps it into customized JavaScript components.

The GraphQL query contained in the code below retrieves the post's block data as a JSON object (via field `CustomPost.blockDataItems`), and then the JavaScript code maps each block data item into a custom component:

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

  // Input here your domain, and enable the single endpoint
  const endpoint = "https://mysite.com/graphql/";

  // Input here the ID of a post with blocks
  const postId = 40;
  
  renderPost(endpoint, postId);
  
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

    // Uncomment here to visualize the GraphQL response
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

<div class="doc-config-highlight" markdown=1>

⚙️ **PHP code alert:**

Because Gato GraphQL currently does not deal with CORS, for testing this Preact app from a different domain than your website's (or even as a static `.html` file in your computer), you may need to add the following PHP code to your theme's `functions.php` file:

```php
add_filter(
  \PoP\ComponentModel\Engine\EngineHookNames::HEADERS,
  function (array $headers): array {
    return array_merge(
      $headers,
      [
        'Access-Control-Allow-Origin' => 'null',
        'Access-Control-Allow-Headers' => 'content-type,content-length,accept',
      ]
    );
  }
);
```

</div>
