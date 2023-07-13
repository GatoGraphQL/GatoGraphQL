# Mapping JS components to (Gutenberg) blocks

This recipe _(inspired by [`Automattic/vip-block-data-api`](https://github.com/Automattic/vip-block-data-api/#preact-example))_ presents an example [Preact](https://preactjs.com/) app that queries for block data and maps it into customized JavaScript components.

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

Running the application produces the following HTML code from post data:

```html
<div class="post">
  <h1>Welcome to a single post full of blocks!</h1>
  <p>When I look back on my past and think how much time I wasted on nothing, how much time has been lost in futilities, errors, laziness, incapacity to live; how little I appreciated it, how many times I sinned against my heart and soul-then my heart bleeds. <strong>Life is a gift, life is happiness, every minute can be an eternity of happiness</strong>. (<a href="https://www.azquotes.com/author/4085-Fyodor_Dostoevsky" target="_blank" rel="noopener">Quote by Fyodor Dostoevsky</a>)<br></p>
  <h2>This blog post will be transformed...</h2>
  <p>If you make it a habit not to blame others, you will feel the growth of the ability to love in your soul, and you will see the growth of goodness in your life. (<a href="https://www.azquotes.com/author/14706-Leo_Tolstoy" target="_blank" rel="noopener">Quote by Leo Tolstoy</a>)<br></p>
  <img src="https://i.ytimg.com/vi/z4KKd2nGlEM/maxresdefault.jpg">
  <h2><mark style="background-color:#D1D1E4" class="has-inline-color">I love these veggies!!!</mark></h2>
  <h2>When going to eat out, I normally go for one of these:</h2>
  <h2>This heading (H3) is boring (Regex test: $1 #1), but these guys are not</h2>
  <div class="gallery">
    <div class="images">
      <img src="https://i.insider.com/5f44388342f43f001ddfec52">
      <img src="https://i.pinimg.com/originals/85/a7/f5/85a7f5cee4c93cb3995d1b51e3a0289f.jpg">
    </div>
  </div>
</div>
```
