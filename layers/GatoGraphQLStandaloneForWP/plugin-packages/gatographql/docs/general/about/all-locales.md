<div class="gato-about__header">
<div class="gato-about__identity">
<div class="gato-about__logo">{plugin-icon}</div>
<div class="gato-about__heading">
<h1>{title}</h1>
<p class="gato-about__tagline">{support-intro}</p>
</div>
</div>
<div class="show-for-customers">
<span class="gato-badge gato-badge--success gato-about__license-status">{license-active}</span>
</div>
</div>

<hr class="wp-header-end">

<div class="hide-for-customers">
<div class="gato-card gato-about__notice">
<span class="gato-about__notice-icon" aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2"></rect><path d="M8 11V7a4 4 0 0 1 8 0v4"></path></svg></span>
<p>{support-customers-note}</p>
</div>
</div>

<div class="show-for-customers">
<div class="gato-about__support">
<form class="gato-card gato-about__support-form" action="{contact-form-url}" method="POST" name="support" target="_blank">
<input type="hidden" name="form-name" value="support">
<div class="gato-about__support-fields">
<div class="gato-about__field-row">
<p class="gato-about__field">
<label for="field-name">{your-name-label}</label>
<input type="text" name="name" id="field-name" class="gato-input" required="" aria-required="true" placeholder="John Doe" autocomplete="name" autocorrect="off" autocapitalize="none">
</p>
<p class="gato-about__field">
<label for="field-email">{your-email-label}</label>
<input type="email" name="email" id="field-email" class="gato-input" required="" aria-required="true" placeholder="your@email.com" autocomplete="email">
</p>
</div>
<p class="gato-about__field">
<label for="field-subject">{subject-label}</label>
<input type="text" name="subject" id="field-subject" class="gato-input" required="" aria-required="true" placeholder="Subject..." autocorrect="off" autocapitalize="none" spellcheck="true" data-remove-prefix="true">
</p>
<p class="gato-about__field">
<label for="field-message">{message-label}</label>
<textarea id="field-message" name="message" class="gato-textarea" rows="10" placeholder="Your message..." required="" aria-required="true" spellcheck="true" autocapitalize="sentences"></textarea>
</p>
<details class="gato-about__license-data">
<summary>{license-data-label}</summary>
<textarea id="field-extensions-license-data" name="extensions-license-data" rows="8" aria-label="{license-data-label}" required="" aria-required="true" readonly>{extensions-license-data}</textarea>
</details>
</div>
<div class="gato-about__support-footer">
<button type="submit" class="button button-primary">{send-message-button}</button>
</div>
</form>
<div class="gato-card gato-about__aside">
<h3>{license-data-title}</h3>
<p>{license-data-description}</p>
</div>
</div>
</div>
