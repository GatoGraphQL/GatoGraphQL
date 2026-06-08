# {title}

<div class="hide-for-customers" markdown=1>

_{support-customers-note}_

</div>

<div class="show-for-customers" markdown=1>

{support-intro}

<form action="{contact-form-url}" method="POST" name="support" target="_blank">
  <input type="hidden" name="form-name" value="support" />

  <label for="field-name">{your-name-label}</label>
  <br/>
  <input
    type="text"
    name="name"
    id="field-name"
    class="regular-text"
    required=""
    aria-required="true"
    placeholder="John Doe"
    autocomplete="name"
    autocorrect="off"
    autocapitalize="none"
  />

  <br/><br/>

  <label for="field-email">{your-email-label}</label>
  <br/>
  <input
    type="email"
    name="email"
    id="field-email"
    class="regular-text"
    required=""
    aria-required="true"
    placeholder="your@email.com"
    autocomplete="email"
  />

  <br/><br/>

  <label for="field-subject">{subject-label}</label>
  <br/>
  <input
    type="text"
    name="subject"
    id="field-subject"
    class="regular-text"
    required=""
    aria-required="true"
    placeholder="Subject..."
    autocorrect="off"
    autocapitalize="none"
    spellcheck="true"
    data-remove-prefix="true"
  />

  <br/><br/>

  <label for="field-message">{message-label}</label>
  <br/>
  <textarea
    id="field-message"
    name="message"
    rows="10"
    cols="100"
    placeholder="Your message..."
    required=""
    aria-required="true"
    spellcheck="true"
    autocapitalize="sentences"
  ></textarea>
  
  <br/><br/>

  <button type="submit" class="button">
    {send-message-button}
  </button>

  <br/><br/><hr/><br/>

  <label for="field-domain"><em>{license-data-label}</em>:</label>
  <br/>
  <textarea
    id="field-extensions-license-data"
    name="extensions-license-data"
    rows="5"
    cols="100"
    required=""
    aria-required="true"
    readonly
  >{extensions-license-data}</textarea>
</form>

</div>
