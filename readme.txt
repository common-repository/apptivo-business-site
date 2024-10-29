=== Apptivo Business Site CRM ===
Contributors: vigneshwaran144, balajiselvam, apptivo, rmohanasundaram, prabhuganapathy, manjuladevimariyappan
Tags: apptivo, contact forms, crm, newsletters, testimonials,Job Portal
Requires at least: 6.0
WordPress Version: 6.0 or higher
Tested up to: 6.6.2
PHP Version: 7.3 or higher
Stable tag: 5.3

Create contact forms, newsletter signups, and customer testimonials, integrated with Apptivo.

== Description ==

The Apptivo Business Site CRM makes it simple to create effective business websites with features that integrate with Apptivo.  Create customized contact forms that work with Apptivo CRM tools, create newsletter signup forms, and manage customer testimonials.

All information is synced directly with your Apptivo small business management account, making it simple to keep your website in sync with your business.

= Plugin's Official Site =

Apptivo Wordpress Plugins ([https://www.apptivo.com/integrations/wordpress-crm-plugin/](https://www.apptivo.com/integrations/wordpress-crm-plugin/))


== Installation ==

1. Upload the extracted archive to `wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Purchase a API key from Apptivo. http://www.apptivo.com/where-to-find-your-apptivo-api-key-apptivo-access-key/
4. Go to the "General Settings" and enter your API Key and Access Key
5. Enjoy!

== Frequently Asked Questions ==

= Is this plugin free? =
No, you'll need to purchase a API key from Apptivo to use this plugin.

= Where do I get a API key? =

You'll need to register for an account at www.apptivo.com. http://www.apptivo.com/where-to-find-your-apptivo-api-key-apptivo-access-key/

= Where is the data stored? =

The plugin settings will be stored in your local Wordpress database, but content will be stored in Apptivo.

= Where to get help =

You can find the complete developer's guide here: https://runapptivo.apptivo.com/apptivo-business-site-developer-guide-7937.html

== Screenshots ==

1. screenshot-1.jpg
2. screenshot-2.jpg

== Changelog ==

= 5.3 =
* Upgraded to the Google V3 reCAPTCHA.
* Fixed the Fatal error issues.

= 5.2 =
* Implemented to prevent Spam Leads from Apptivo Form submission during the lead collection.
* Fixed the Fatal error issues.

= 5.1 =
* Implemented address collection in customers app based association with the Contact Form.
* Fixed the PHP Deprecated issues.
* Fixed the Fatal error issues.

= 5.0 =
* Implemented the tag functionalities in the contact form.
* Fixed the fatal error issues.
* Fixed the PHP Warnings.
* Fixed the PHP Deprecated issues in get_current_screen(), contextual_help, the_editor.

= 4.0.3 =
* Fixed the CURL Timeout issue.
* Handled the CSS Fixes for Newsletter Subscription in Contact Forms

= 4.0.2 =
* Fixed the state code bug in Contact Forms.

= 4.0.1 =
* General Bug Fixes in Contact Forms.

= 4.0.0 =
* Implemented Input and Escape Sanitization with latest functionalities

= 3.0.15 =
* Bug Fixes.

= 3.0.14 =
* Bug Fixes.
* Updated with wordpress recent functionalities
* Supports above PHP 8.
* Supports above Wordpress 6.

= 3.0.13 =
* Implemented the Vulnerability Fixes and Script Injection

= 3.0.12 =
* Increased the count of contact form 

= 3.0.11 =
* Fix issues in publishing Newsletter Form

= 3.0.10 =
* Dynamic construction of addresstype in contact form

= 3.0.9 =
* Duplicate Lead Checking issue

= 3.0.8 =
* newsletter Email subject name changed dynamic with domain name

= 3.0.7 =
* Bug Fixes.

= 3.0.6 =
* Bug Fixes.

= 3.0.5 =
* Bug Fixes.

= 3.0.4 =
* Bug Fixes.

= 3.0.3 =
* Bug Fixes.

= 3.0.2 =
* Bug Fixes.

= 3.0.1 =
* Bug Fixes.
* Supports PHP7.4

= 3.0.0 =
* Bug Fixes.
* Supports PHP7.3.


= 2.0.9 =
* Bug Fixes.

= 2.0.8 =
* Bug Fixes.

= 2.0.7 =
* Replaced curl with HTTP API.

= 2.0.6 =
* Migrated Testimonials to V6 API's.

= 2.0.5 =
* Associated the candidate To Position.

= 2.0.4 =
* Migrated Apptivo V3 to V6 API's.
* Supports PHP5 and PHP7.

= 2.0.3 =
* Added Email notification in Apptivo newsletter.


= 2.0.2 =
* Bug Fixes.

= 2.0.1 =
* Added Custom field support for Apptivo Cases,Contact Forms.
* Supports PHP5 and PHP7.
* Upgraded Google Captcha V2.

= 2.0.0 =
* Supports PHP7.

= 1.3.0 =
* Enabled TLS 1.1 and TLS1.2 Support.

= 1.2.9 =
* Fixed security exploits issues
* Removed redundant jQuery files

= 1.2.8 =
* Removed uploadify plugin which is used in the Jobs Applicant form

= 1.2.7 =
* JS conflict issue with Themify (Elegant) Theme

= 1.2.6 =
* Fixes on contact form, cases form, Newsletter templates and updated with responsive
* Jobs notes API updated to save notes

= 1.2.5 =
* Removed SSL Chiper list.
* Updated new brand icon

= 1.2.4 =
* Disabled SSLv3 Support.
* API connection error with PHP Soap Client

= 1.2.3.1 =
* Added new contact form template with Placeholder.

= 1.2.3 =
* Added Lead assignee (employee or team), lead source, rank, status, type for contact forms.
* Added customer and contact association for cases by creating new or existing customer/contact.
* Added Case assignee (employee or team), priority, status, type for Cases. 
* Added double column layout and multiple custom fields for cases
* Added customer association to lead for Contact forms by creating new or existing customer. 
* Bug Fixes on Jobs and Newsletter plugin.

= 1.2.2 =
* added simple captcha
* fixed validation and backend exception

= 1.2.1.2 =
* reCAPTCHA js issue fixed

= 1.2.1.1 =
* Jobs country list issue fixed
* cases type and priority updated based on enable/disable status

= 1.2.1 =
* REST API updated for contact forms and Cases forms
* Google ReCaptcha added
* Added Responsive support forms
* Added web Testimonial forms

= 1.1.2.1 =
* Soap Client values passed as stream context for some php versions throw Fatal exception on accessing webservice. 

= 1.1.2 =
* Fixes for forms validation
* Soft handling of some PHP warnings

= 1.1.1 =
* Fix - Faulty html in custom field options.
* Added hooks for contact form, newsletter, cases and job applicant form.

= 1.1 =
* Updated Plugin to use Apptivo new API Key and Access Key in Firm Business settings.
* Old Plugin users using Site key / Access key from Website App, has to update the keys in Plugin with Business settings API Key / Access Key.
* For more information please check http://www.apptivo.com/where-to-find-your-apptivo-api-key-apptivo-access-key/
* Improved performance of jobs plugin.

= 1.0.1 =
* Jobs Upload functionality Bug fixes

= 1.0 =
* Hot Fix to Jobs Upload features. Older version of plugin has to be updated to this release to continue uploading resumes on Jobs.
* Cases feature added to Plugin. It enables customers to log a case from your website and you can manage it using Apptivo Cases App.
* Other bug fixes.

= 0.7.2 =
* UI bug fixes for IE

= 0.7.1 =
* Security Bug fixes related to Uploadify plugin used for Jobs.

= 0.7 =
* Form Submission IP Restriction
* Custom template API's
* Added Powered by Apptivo options
* Enabled Image Upload functionality
* Bug Fixes

= 0.6.1 =
* Fixes

= 0.6 =
* Disk cache implementation

= 0.5 =
* Plugin released!
