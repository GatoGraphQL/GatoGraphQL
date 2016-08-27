=== Events Manager Pro ===
Contributors: netweblogic
Tags: events, event, event registration, event calendar, events calendar, event management, paypal, registration, ticket, tickets, ticketing, tickets, theme, widget, locations, maps, booking, attendance, attendee, buddypress, calendar, gigs, payment, payments, sports,
Requires at least: 3.3
Tested up to: 3.6
Stable tag: 2.3.7

== Description ==

Thank you for downloading Events Manager Pro!

Please check these pages for further information:

http://wp-events-plugin.com/documentation/ - lots of docs to help get you started
http://wp-events-plugin.com/tutorials/ - for advanced users, see how far you can take EM and Pro!

If you have any issues/questions with the plugin, or would like to request a feature, please visit:
http://wp-events-plugin.com/support/

== Installation ==

Please visit http://wp-events-plugin.com/documentation/installation/

== Changelog ==
= 2.3.7.1 (dev) =
* changed html titles of event admin booking options from bolded text to h4 (coupons & custom forms)
* fixed booking forms not saving user fields if logged in (reverting previous modification in 2.3.7 which stopped saving this info to booking meta as well)
* added emp_hidden_reg_fields filter to allow custom showing of name and other hidden user fields
* fixed "pending payment" bug for authorize.net
* added optional css class flags (same as EM Booking form) to MB checkout/cart pages
* fixed custom booking/attendee forms not being passed onto recurrences
* fixed slashes being added to custom email html
* added wp_kses sanitization to custom emails
* changed saving gateway settings will stay on gateway settings page

= 2.3.7 =
* fixed site/event-wide coupons not showing up in coupon manager in admin area
* changed event/site wide choice to be one or the other, to avoid confusion
* fixed user link problems on secondary ajax-loaded pages of transactions table
* changed $EM_Booking->booking_meta['registration'] will not save user info if already logged in
* fixed CSV export issues if exporting by attendee and booking has no attendee data (if not previously activated/used)
* fixed bug where double-spaced option values fail required validation (saved form strips extra spaces, entities will still work and validate)
* added gateway column to booking tables and exports
* fixed date not being formatted in transactions table
* fixed unwanted MB page refresh on checkout if redirect option enabled

= 2.3.6 =
* fixed rejections being given a transaction record as if paid for offline bookings
* fixed booking form not accepting blank values when editing (e.g. textboxes)
* fixed newly added checkboxes showing as checked on previous bookings
* fixed some _doing_it_wrong triggered functions in BuddyPress whilst in debug mode
* prevented non-existent values for texboxes and textareas defaulting to n/a when editing a booking
* updated German
* fixed coupon-event associations not being deleted with event
* fixed coupons being associated with event if event/site-wide (not necessary)
* fixed text fields when editing booking showing n/a when originally empty
* changed MB bookingsummary template file name to bookingsummary-multiple.php 
* added excel hack to attendees csv form,
* changed EM_Coupons functions to static to prevent php warning
* fixed translations missing domains
* fixed price breakdowns in MB mode
* removed g preg modifier in authorize.net gateway for event name sanitizing
* fixed conflicts with wpmudev membership
* added em_gateawy_authorize_aim_sale_var filter before sending to AIM,
* changed paypal ipn endpoint error message if user visits url
* added showing coupons available to events in MB mode when editing event
* changed available coupons in bookings are now hidden/collapsed
* improved EM_Coupons::get() argument logic for site/event-wide searches 
* changed MB mode coupon searches return all coupons since all are now site-wide 
* changed saving coupons in MB mode automatically makes it event/site-side
* changed #_BOOKINGTICKETS template file name to bookingtickets-multiple.php,
* fixed confirmation notice being added as an error when saving mb booking
* added 'total' text to MB mode cart widget
* fixed coupon not applying discount to total price in MB mode cart/checkout
* fixed MB mode admin cart showing  last ticket price as sub-total
* corrected double underscore in MB cancelled email template option names
* fixed mb mode single event emails not going to event owners if enabled in settings page
* changed MB email options won't show until MB mode is enabled
* fixed modifying a single booking not updating over multiple booking total price
* added is_normal_field and is_user_field functions to EM_Form,
* added MB mode fields to booking tables and export columns
* fixed validation issues for select/country/radio fields and non-permitted values
* changed - moved files containing main classes in add-on folder into sub-folders
* changed - moved admin functions for email and mb mode to designated admin classes
* changed - separated admin functions from EM_Gateways to EM_Gateways_Admin class
* added custom emails functionality to events and gateways
* fixed mb cart 'checkout' button pointing to homepage if not using permalinks
* fixed MB bookings not sending email if total price equals 0
* fixed non site/event-wide coupons not being applied to recurrences
* fixed various missing translation domains for gettext functions
* updated POT file

= 2.3.5 =
* revamp of coupons system, uses v5.4 discount system
* coupons now support Multiple Bookings Mode
* coupons can now be added pre/post tax
* delayed transactions table init() so BP doesn't trigger a _doing_it_wrong function
* moved MB stuff out of emp-admin.php and into multiple-bookings-admin.php
* added option for redirection to checkout page for multiple bookings whenever making a single booking
* updated MB mode and PayPal gateway to use new v5.4 pricing functions
* fixed some php warnings
* updated russian language file
* improved retrieval of booking name in MB mode, e.g. single event booking = event name, multiple booking = 'Multiple Events'

= 2.3.4 =
* fixed bug when trying to switch booking forms in form editor
* fixed localization typo
* added sanitation to various input textboxes in admin area

= 2.3.3 =
* fixed coupon final price miscalculations when tax is automatically included in ticket price
* fixed customer user gateway fields not being passed on correctly when in no-user mode
* fixed password user field problems in MB mode
* improved pro update notifier and key checking consistency
* added option for MB bookings submit button
* fixed form regex rules still being required if value is blank and form field not required
* fixed user profile page failed validation still resulting in update notice along with errors

= 2.3.2 =
* added Finnish
* updated German
* fixed various PHP warnings
* fixed user bookings link pointing to admin admin area from front-end bookings admin
* removed some redundant code from paypal gateway
* added 'empty cart' button to 'view cart' page
* fixed custom user fields not being saved properly in MB mode
* fixed manual bookings for MB mode
* Multiple Bookings now beta, no flag required in wp-config.php file

= 2.3.1 =
* added dbem_capability_forms_editor wp_option for showing form editor menu to other user roles
* added em_attendees_form_admin_page_actions action
* added some extra esc_ and wp_kses functions for sanitization
* added ids to setting sections to work with new EM UX JS
* improved MS Global mode so unnecessary tables aren't created for new blogs
* added wp_title filter to multiple bookings widget
* fixed manual bookings asking for a gateway since v2.3

= 2.3 =
* fixed newly created user during booking not being deleted on bad card info via authorize.net
* updated Swedish translation
* fixed php warning
* fixed permission problems in MS preventing form editor and other admin screens from showing to admins without plugin rights
* updated Swedish
* fixed newly created users not being deleted in MultiSite if bad A.net card info is supplied
* added Multiple Bookings feature
* fixed MultiSite PHP warning on blog creation when visiting blog first time round
* added hooks to edit no-user booking personal information and custom user fields (requires EM 5.3.5.3 or higher)
* better template/class renaming, adjusted AJAX loading methods to account for caching plugins
* moved email reminders out of beta
* fixed checkboxes, radios and multiselect custom fields in booking form not being editable by admin
* fixed checkboxes, radios and multiselect attendee fields not being correctly editable by admin
* fixed some attendee form display and CSS issues
* fixed tips not appearing for core user fields

= 2.2.9 =
* important security update for some XSS vunlerabilities - see http://em.cm/xss
* fixed blank date and time custom fields breaking datepickers for editing user/booking information
* fixed coupon placeholders remaining if booking doesn't have a coupon associated with it 

= 2.2.8 =
* fixed IPN validation failing when ticket names contain special characters
* improved IPN validation requesting, using GET method via wp_remote_get 

= 2.2.7 - Users using 2.2.6 with PayPal should upgrade immediately =
* fixed mysql error when getting transaction data in specific circumstances
* fixed pro scripts not loading along with EM scripts in admin area (e.g. bookings dashboards)
* (critical) fixed IPN verifications failing with live PayPal accounts 

= 2.2.6 =
* fixed events with one non-required ticket not showing 1 attendee form (when shown in ticket table format)
* fixed some non-translated strings, updated pot file 
* added Russian translation
* fixed registered user info not showing up on booking details/exports if no-user mode and manual booking is made
* fixed dates and other fields not being formatted when displayed using placeholders e.g. in emails
* updated French translation
* adding 'define('EMP_SHARED_CUSTOM_FIELDS',true);' to your wp-config.php file allows user field ids to not be prefixed with dbem_ (for sharing user meta with other plugins)
* added compatability with new script loading system
* fixed PayPal IPN verification mechanism, including fallback for curl on servers with outdated SSL certificates
* limited admin JS loading
* added fix for badly saved/displaying user meta date/time fields, added installation script to fix previously bad date/time user meta values 
* added fix for normal field country output formatting
* added coupon calculation to CSV ticket total calculation
* added removal of header in CSV if EM_CSV_DISABLE_HEADERS is defined
* added coupon code placeholders #_BOOKINGCOUPON, #_BOOKINGCOUPONCODE, #_BOOKINGCOUPONDISCOUNT, #_BOOKINGCOUPONNAME, #_BOOKINGCOUPONDESCRIPTION
* added coupon code column to booking tables and csv export
* fixed IE8/safari form editor display issues
* fixed manual bookings allowing double bookings depending on EM settings
* fixed updates not accessing update information e.g. changelogs from our servers
* changed em_booking_add apply_filter to add_action (since it's an action)

= 2.2.5 =
* fixed some non-translated strings
* updated the POT file
* added Chinese translation, thanks to Leo Losoviz
* fixed pending/reserved spaces not being approvable, requires EM 5.3 to work

= 2.2.4.1 =
* fixed badly named folder for upgrade
* fixed require_once() with hardcoded path causing install/upgrade issues

= 2.2.4 =
* fixed attendee forms ommitting first attendee in each ticket
* fixed attendee #NUM# not being converted if not in an html element
* fixed tips not being added to dynamic attendee fields
* fixed radio and checkboxes not being read properly for attendees
* updated Swedish
* updated bookings currency tip link
* added $field info to emp_forms_output_field filter
* changed user creation/deletion in first-registration failed authorize.net bookings to use internal account creation timer
* fixed event reminders not reading booking placeholders
* fixed/improved first-time user deletion on bad authorize.net card data
* changed paypal cron hook em_cron_hook to em_paypal_cron
* fixed pending individual ticket counts with PayPal bookings in progress/reserved
* added condition to not validate #_BOOKINGBUTTON bookings
* added complete activation/deactivation of attendee ticket functions
* changed is_main_blog functions to is_main_site
* updated pot file, Swedish
* added check for paid bookings with no gateway choice (anti-spam/hack)
* added Japanese
* removed site language option (repeated option, typo)
* added all countries for paypal destination site language
* prevented/fixed various php warnings
* removed parse_query hook for permalink gateway handling (i.e. catching paypal IPNs), gateways now use a direct wp-admin/admin-ajax.php... url
* fixed validation of manual bookings and editing of bookings forcing address fields, changed priority of EM_Booking_Form booking interception
* moved ticket/booking pending space calculations to base gateway class, now accounts for all gateways
* fixed person data not being saved to EM_Person instance on first booking
* fixed attendee form loading depending on default tickets (based on introduction of EM_Ticket::is_required() in EM 5.2.9)
* fixed EM_Person information not being saved to instance on first booking, causing authorize.net to not retrieve first/last name correctly
* removed custom html from form when editing a booking
* improved default attendee form to include attendee #NUM#
* attendee fields is now beta and ready to go

= 2.2.3 =
* added attendee forms - alpha - add define('EM_ATTENDEES',true); to your wp-config.php file
* fixed some display / validation errors in booking forms when modifying booking
* fixed #_BOOKINGTXNID returning placeholder if there's an empty value, now returns an empty value
* fixed minimum spaces calculations for attendees, as per fix in 5.2.5.2
* fixed non-editable user fields breaking validation
* updated German translation
* fixed link still showing on the single booking view to the assigned default user for no-user bookings
* hid some js localized vars if bookings disabled

= 2.2.2 =
* fixed no-user mode and user fields bug still happening in CSV exports
* fixed MS global mode showing network transactions on all blogs
* cleaned up options html for form editor
* added tip text to every field and fixed display of tip text
* fixed some badly named options
* fixed custom user form field options not superceding defaults and vice versa
* fixed paypal bookings resume payment button not working as expected
* added cancel link to paypal bookings in progress
* fixed AIM fatal error when using address 2 in forms etc.
* fixed no-user mode not updating custom user fields for already logged in users
* fixed name user field not validating properly
* streamlined emp-forms.php validation switch, name and email validated like other reg fields

= 2.2.1 =
* fixed MS network blog tables not being deleted by WP along with rest of blog
* fixed no-user mode bug showing assigned user information on the booking information page
* fixed reminder emails including long spanning events that already started 

= 2.2 =
* db table installation will take current blog prefix rather than determine if it's in global tables mode
* fixed transactions not deleting if event is already deleted
* fixed coupon dates not working
* added em_coupon_get_discount_text filter
* added paypal default language option
* added extra values to the epm_forms_output_field_input filter
* fixed multisite error when fetching transaction info
* fixed some form action calls (from add_action to do_action)
* added country to form field
* fixed extra blank field in form editor
* added user address field association, allowing for tighter integration with gateways
* added email reminders
* added option to show logged in users their registration fields in booking forms
* fixed PayPal gateway not taking pending payments into account and treating as in-progress (deleted automatically)
* fixed custom booking form not showing on forms outside of main event page
* fixed manual bookings not showing new user fields
* fixed default form install bug if pro installed first
* fixed some action typos on EMP_Forms editor html
* added em_coupon_is_valid filter
* fixed em_coupon_get_person filter typo
* added user password custom field
* added date and time picker custom fields
* added 'required' asterisks next to labels
* fixed required text fields not accepting a 0
* fixed paypal settings not saving if paypal email not supplied
* added custom tooltips to field labels

= 2.1.5 =
* fixed manual bookings not allowing admins booked to that event with double bookings disabled
* added missing error message on manual booking form admin-side validation
* fixed offline status not being editable if de-activated yet making a manual booking
* added classes to coupon code and authorize booking form elements
* fixed manual bookings bug for another user without a payment
* set status to pending rather than cancelled for re-review if partial refunds are made
* transactions now get deleted with bookings
* added manual delete transaction
* fix for multiple booking forms on one page
* further improvement to loading of a.net SDK to avoid plugin conflicts

= 2.1.4 =
* fixed authorize.net conflicts if SDK already loaded by another plugin
* added failed email message to offline bookings that go through
* improved fallback for javascript booking form failures (particularly paypal)
* added input class to text fields in booking form for coupons and gateways
* fixed manual booking link issues
* fixed authorize.net "invalid line 1" errors due to long ticket names
* fixed email regex settings not working (requires a resave of form settings)
* manual bookings accept partial payments
* fixed invalid coupons still allowing bookings to go through

= 2.1.3 =
* added gateway transaction id to booking collumns
* fixed form editor validation problems

= 2.1.2 =
* allowed form labels to accept HTML
* fixed paypal resume payment button
* fixed paypal payment status text
* modified coupon calculation to add tax after discount, if tax is added seperately
* made paypal bookings editable even if pending
* fixed various form editor bugs
* fixed email problems with paypal confirmations
* manual bookings now accept coupons and anonymous registrations, as well as custom payment amounts
* added more html css classes to booking form
* made update notices more user-friendly if pro-api-key isn't valid

= 2.1.1 =
* fixed coupon pagination problem
* fixed captcha failures due to duplicated checks
* fixed user fields and Array being shown for multi-option fields
* removed dev version checking (moving to free) and add one-off dev version check
* reverted to using .delegate() instead of jQuery 1.7+ .on() listener for compatibility

= 2.1 =
* offline payment confirmation window can be properly cancelled (bugfix)
* membership key options now showing properly in MS mode
* added custom user fields
* added custom booking forms per event
* detached booking form editor into a re-usable class for user fields and future custom forms

= 2.0.4 =
* fixed pro member key issue in MultiSite
* coupons saving properly in MS Global Tables mode.
* added coupon count and history

= 2.0.2 =
* added html filtering for ticket names sent to paypal
* fixed offline manual partial payemnt formats bug
* added some translatable strings
* membership key entry will force recheck of plugin updates
* fixed captcha includes breaking form submissions
* added classes to custom booking form html
* added cancel url to PayPal gateway
* fixed Authorize.net gateway creating wp accounts when CC info is bad

= 2.0 =
* fixed checkboxes defaulting to selected
* rewritten gateway API, add custom gatways much faster and efficiently
* added Authorize.net AIM Gateway
* added coupons feature Coupons
* restructured files
* various minor bug fixes
* updated Russian translation
* prevented from loading EMP if EM isn't activated

= 1.51 =
* fixed offline custom message not working
* fixed paypal ticket descriptions and special characters (using UTF-8)
* fixed view transactions blank page from gateways page

= 1.5 =
* paypal now pre-registers user before redirecting if applicable (more stable, more possibilities)
* added #_BOOKINGTXNID to booking placeholders for paypal transaction ID
* fixed placeholders for custom form fields
* html now accepted in booking form feedback in gateways
* small usability improvements to manual booking form
* transactions tabled now unified to reduce clutter
* paypal return url modified to use a static file (wp-admin/admin-ajax.php) and the previous url as a fallback

= 1.45 =
* fixed booking form placeholders
* #_CUSTOMBOOKING now works for #_CUSTOMBOOKINGREG fields
* html not escaped with slashes in custom booking gateway feedback messages

= 1.44 =
* fixed booking form regexes making inputs required
* paypal won't allow registered emails in guest mode
* paypal bookings only considered as pending if timeout is set (paypal pending payments view coming shortly)

= 1.43 =
* important bug fix for paypal bookings

= 1.42 =
* Custom registration booking placeholder fixed

= 1.41 =
* Updated to support version 5 (required)

= 1.39 =
* fixed yahoo field name for saving into booking regsitration
* fixed page navigation for pending payments
* fixed checklist booking saving bug
* paypal IPN soft fail introduced, to reduce alternante payment software 404s

= 1.38 =
* fixed minor php warning
* added em_gateway_paypal_get_paypal_vars filter
* fixed default custom form issue with validating emails in guest bookings
* fixed duplicate indexes in transaction table
* manual bookings by less than admins not impeded by permission errors

= 1.37 =
* allows negative manual payments
* paypal return url instructions corrected

= 1.36 =
* fixed bug which prevented transaction tables showing unregistered/deleted users.
* warning added if EM plugin version is too low
* update notices appear on the network admin area as well
* added cron tasks for paypal booking timeouts
* added return url option for paypal
* custom booking form information properly escaped and filtered
* paypal manual approvals won't take effect with normal approvals disabled
* offline and paypal pending spaces taken into account
* paypal and offline payments take tax into account (requires EM 4.213)
* fixed logo not being shown on paypal payment page
* payments in no-user mode accepted (requires EM 4.213)

= 1.35 =
* added alternative notification check for servers with old SSL Certificates
* added dev mode updates option in the events setttings page
* removed the main gateway JS
* manual bookings can now be done by all users with the right permissions
* paypal payments will not include free tickets during checkout paying, avoiding errors on paypal
* pot files updated
* German and Swedish translations updated
* fixed various warnings
* multiple alert boxes when confirming offline payments fixed