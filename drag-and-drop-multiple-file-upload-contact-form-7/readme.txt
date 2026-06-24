=== Drag and Drop Multiple File Upload for Contact Form 7 ===
Contributors: glenwpcoder, yordansoares
Donate link : http://codedropz.com/donation
Tags: drag and drop, contact form 7, ajax uploader, multiple file, upload
Requires at least: 3.0.1
Tested up to: 7.0
Stable tag: 1.3.9.8
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This simple plugin create Drag & Drop or choose Multiple File upload in your Confact Form 7 Forms.

== Description ==

**Drag and Drop Multiple File Upload** is a simple, straightforward WordPress plugin extension for Contact Form 7, which allows the user to upload multiple files using the **drag-and-drop** feature or the common browse-file of your webform.

Drag and Drop Multiple File Upload for Contact Form 7 is an independent plugin, not affiliated with or endorsed by the developers of Contact Form 7.

Here's a little [DEMO](http://codedropz.com/contact).

### Features

* File Type Validation
* File Size Validation
* Ajax Uploader
* Limit number of files Upload.
* Limit files size for each field
* Can specify custom file types or extension
* Manage Text and Error message in admin settings
* Drag & Drop or Browse File - Multiple Upload
* Support Multiple Drag and Drop in One Form.
* Able to delete uploaded file before being sent
* Send files as **email attachment** or as a **links**. *(see note below)*
* Support multiple languages
* Mobile Responsive
* Cool Progress Bar
* Compatible with any browser

**PLUGIN GUIDE - FREE VERSION**

[youtube https://www.youtube.com/watch?v=DvuvmzIImYo]

**Note:** On Free version, all uploaded files moves to a temporary folder *("/wp-content/uploads/wp_dndcf7_uploads")* then attaches the file to the mail and sends it. After that **"Drag & Drop File Upload"** removes the file from the temporary folder **1 hour** after the submission. *( same process with the default **"file"** upload of Contact Form 7 - [See here](https://contactform7.com/file-uploading-and-attachment/#How-your-uploaded-files-are-managed) )*

To **adjust** or **disable** the auto-deletion feature, we suggest upgrading to the **PRO version** for more options *(see below)*.

### ⭐ Premium Features ⭐

Check out the available features in the [**PRO version**](https://www.codedropz.com/drag-drop-multiple-file-upload-for-contact-form-7/#shop).

1. **Upload Large File** - Supports uploading large files.
2. **Image Preview** - Displays thumbnails for images.
3. **Auto Delete Files** - Automatically deletes files after a set time *(hours, weeks, days, months, etc)*
4. **Zip Files** - Compress uploaded files into a ZIP archive
5. **Save Files to Media Library** - Store files in the WordPress media library.
6. **Change Upload Directory** - Customize the default WordPress upload directory.
7. **Upload Folder** - 📂 Choose a custom folder to store files:
   ✅ Contact Form 7 Fields: Use any field name
   ✅ Generated Date & Time: Timestamp-based folders
   ✅ Random Folder: Auto-generated letters & numbers
   ✅ By User: Requires login to store files in the user's email or first name.
   ✅ Custom Folder: Manually input a folder name
   ✅ Dynamic Folder: *User (name, id), Post (id, slug), CF7 field*
8. **Send as Attachments, Zip, or Links** - Flexible file delivery options.
9. **Chunked Uploads** - Upload large files in smaller chunks to avoid timeouts.
10. **Max Total Size** - Set the maximum combined size for all uploaded files.
11. **Parallel Upload** - Limit simultaneous uploads to optimize server performance.
12. **Custom Filename** - Define custom filename patterns: *( {filename}, {cf7-field-name}, {ip_address}, {random}, {post_id}, {post_slug}, etc. )*
13. **Color Options** - Customize colors for **file size**, **progress bar**, **filename**, and more.
14. **Prevent Duplicate** - Disable button to prevent duplicate submissions.
15. **Custom Theme** - Switch between **"Dark"** or **"Light"** themes.
16. **Form Entries** - Store form entries in WordPress admin.
17. **Seamless Remote Storage Integration**
	🔥 Supports: **OneDrive**, **Google Drive**, **Amazon S3**, **Dropbox**, **FTP**.
18. **Image Size Validation** - Ensure images meet required width and height.
19. **Image Resize** - Supports image resizing (e.g., 800x800). *(**Standard** Version Only)*
20. **Optimize Image** - Optimize images after resizing. *(**Standard** Version Only)*
21. **Security** - Ensure security with regular updates, vulnerability scans, and threat protection.
22. **Optimized Code & Performance** – Improve speed and efficiency.

**Pro version** is also compatible with:

* Contact Form 7 Add-on – Arshid
* Database for Contact Form 7- Ninja
* Advanced Contact form 7 DB – Vsourz Digital

You can get [PRO Version here](https://www.codedropz.com/drag-drop-multiple-file-upload-for-contact-form-7/#shop)!

**PRO VERSION - PLUGIN OVERVIEW**

[youtube https://youtu.be/PoQA4KmIETA?si=udM-70n6l4lsQAfp]

### Other Plugins You May Like

* [Order Files for WooCommerce](https://www.codedropz.com/woo-order-files/)
An extension that attach files to existing **WooCommerce orders**, allowing both customers and admins to upload and manage files easily.

* [Easy File Upload & Approval](https://wordpress.org/plugins/easy-file-upload-approval/)
**Easy File Upload & Approval** - A simple file management plugin that lets users effortlessly upload and submit files for review through a clean and simple drag-and-drop interface.

* [Drag & Drop Multiple File Upload - WooCommerce](https://wordpress.org/plugins/drag-and-drop-multiple-file-upload-for-woocommerce/)
An extension for **WooCommerce** - Transform your simple file upload into beautiful **"Drag & Drop Multiple File Upload"**.

* [Drag & Drop Multiple File Upload - WPForms](https://www.codedropz.com/drag-drop-file-uploader-wpforms/)
An extension for **WPForms** - Transform your simple file upload into beautiful **"Drag & Drop Multiple File Upload"**.

== Frequently Asked Questions ==

= How can I send feedback or get help with a bug? =

For any bug reports go to <a href="https://wordpress.org/support/plugin/drag-and-drop-multiple-file-upload-contact-form-7">Support</a> page.

= How can I limit file size? =

To limit file size in `multiple file upload` field generator under Contact Form 7, there's a field `File size limit (bytes)`.

You can also manually add limit parameter in existing [mfile] tag.

Example: *[mfile upload-file-433 limit:20971520]* - This limit the user to upload upto 20MB only.

Please take note it should be `Bytes` you may use any converter just Google (MB to Bytes converter) default of this plugin is 5MB(5242880 Bytes).

= How can I limit the number of files in my Upload? =

You can limit the number of files in your file upload by adding this parameter `max-file:3` to your shortcode :

Example: *[mfile upload-file-344 max-file:3]* - this option will limit the user to upload only 3 files.

= How can I Add or Limit file types =

You can add or change file types in cf7 Form-tag Generator Options by adding `jpeg|png|jpg|gif` in `Acceptable file types field`.

Example : *[mfile upload-file-433 filetypes:jpeg|png|jpg|gif]*

= How can I change text in Drag and Drop Uploading area? =

You can change text `Drag & Drop Files Here or Browse Files` text in Wordpress Admin menu under `Contact` > `Drag & Drop Upload`.

= How to Display Links in an Email =

Some email servers have limitations on file attachment sizes (e.g., Google allows a maximum of 20-25 MB). Attaching large files to emails can be problematic. Consider using this option to display links in the email instead of attaching the files.

Go to WP Admin `Contact -> Drag & Drop Upload` settings then check "Send Attachment as links?" option.

To manage mail template, go to Contact Forms edit specific form and Select `Mail` tab. In `Message Body` add generated code from [mfile]. ( Example Below )

Message Body : [your-message]

File Links 1 : [upload-file-754]
File Links2 : [upload-file-755]

Note : No need to add in `File Attachments` field.

See [Video Demonstration](https://www.youtube.com/watch?v=DvuvmzIImYo&t=232s)

= How to Attach Files to an Email =

1. In order to attach files to email you will need to check and make sure **"send as file(s) as links"** option is unchecked.
2. Go to Wordpress admin menu "Contact -> Edit {specific_form}" click or hover the cf7 form you want to edit.
3. In **"Edit Contact Form"** page click "Mail" tab and in the bottom you will see **"File attachments"** field, on this field add your upload field name (ie: **[upload-file-xxx]**), you will find the upload name in **"Form"** tab generated from `[mfile]` shortcode.
4. If attaching multiple files from a different file upload just add all the upload fields name. (see example below)
File attachments: `[upload-file-111] [upload-file-222]`

See [Video Demonstration](https://www.youtube.com/watch?v=DvuvmzIImYo&t=113s)

== Installation ==

To install this plugin see below:

1. Upload the plugin files to the `/wp-content/plugins/drag-and-drop-multiple-file-upload-contact-form-7.zip` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to "Contact > Drag & Drop Upload" for the settings.
4. See [Tutorial](https://www.youtube.com/watch?v=DvuvmzIImYo)

== Screenshots ==

1. Generate Upload Field - Admin
2. Form Field Settings - Admin
3. Uploader Settings - Admin
4. Email Attachment- Gmail
5. Email Attachment As links - Gmail
6. Multiple Drag and Drop Fields - Front
7. Remote Storage - Pro Features

== Changelog ==

= 1.3.9.8 =
- Bug Fix - Js error disabled button after upload when "enable jquery" option is used.
- Security Fix - Cross Site Scripting (XSS) (via patchstack)
- Security Fix - Fixed Unauthenticated Arbitrary File Upload (Thanks to "Nguyen Dinh Hung (Mitchell)")
- JS Fix - Fixed file counter when file upload has an error or when deleting file.
- Vulnerability - Fixed Authenticated (Administrator+) Stored Cross-Site Scripting via admin fields Settings. (via "Wordfence")
- Improved - Added sanitization for admin settings/options and escaped localized script data for better security and data handling.

= 1.3.9.7 =
- Improved : Change htacess generated file to support apache 2.2 & 2.4
- Security Fix : Unauthenticated Arbitrary File Upload via Non-ASCII Filename Blacklist (Thanks to Leonid Semenenko via WordFence)
- Security Fix : Unauthenticated Limited Arbitrary File Read via mfile Field (Thanks to Osvaldo Noe Gonzalez Del Rio via WordFence)
- Security Vulnerability : Fix Unauthenticated Arbitrary File Deletion (Thanks to Bao Nguyen)

= 1.3.9.6 =
- New : Replaced cookies with localStorage for unique upload folder generation.
- Security :  Unauthenticated Arbitrary File Upload (Reported by Thomas Sanzey via WordFence) - user able to upload "php5 - php8" for non ascii filename by bypassing extensions present in the blacklists.

= 1.3.9.5 =
- Hot Fix: Minor spelling mistakes.

= 1.3.9.4 =
- Fixes: Change shutdown hook to cron events in order to fix this issue. [Support Link](https://wordpress.org/support/topic/commands-out-of-sync-mysql-error-during-shutdown-hook-v1-3-9-3-2/)
- Fixes: Move the js cookie generation from wp_footer hook to wp_add_inline_script. [Support Link](https://wordpress.org/support/topic/enqueueing-of-javascript-is-not-complaint-csp-conflict/)
- Improvement: Minor fixes and improvement.

= 1.3.9.3 =
- Security: Fixed vulnerability issues reported by WordFence (reported by shark3y) - unauthorized modification of data due to a missing ownership check in the dnd_codedropz_upload_delete() function.
- Security: Fixed an unauthenticated limited arbitrary file upload issue allowing .phar and .svg files when using blacklist mode with file types set to *. (by WordFence via andrea bocchetti)

= 1.3.9.2 =
- Fixed - File Upload required fields conflicts with Conditional Fields for CF7.
- Fixed - Typo error in Cf7 field editor.
- Fixed - Multiple errors showing in upload field.
- Check - WordPress 6.8.3 compatibility.

= 1.3.9.1 =
- Fixed : Security issues related to cookie (Thanks to WordFence)
- Bug : Fixed or Replace crypto.randomUUID() error on non https. [Support Link](https://wordpress.org/support/topic/crypto-randomuuid-error/)

= 1.3.9.0 =
- Security: Fixed security issues reported by Wordfence "Remote Code Execution via PHAR File Upload if changing the filename something like poc.&#112;har".
- Bug Fix: Modified script for compatibility on "conditional field for Contact Form 7".

= 1.3.8.9 =
- Check: Verified compatibility with WordPress 6.8.
- Security: Enhanced security measures.
- Bug Fix: Fixed an issue with file deletion from PHAR archive when associated Flamingo entries are deleted.

= 1.3.8.8 =
- Fixes - Fixed Vulnerability issues reported by Phat RiO - BlueRock (via Wordfence)
  * Unauthenticated Arbitrary File Deletion
  * Unauthenticated PHP Object Injection via PHAR to Arbitrary File Deletion
- Added - Solution to prevent file deletion when flamingo message is deleted from the admin.
- Fixed - Cookie issues prevent from caching. [Support Link](https://wordpress.org/support/topic/wpcf7_guest_user_id-cookie/)

= 1.3.8.7 =
- Fixes - Header already sent issue[support](https://wordpress.org/support/topic/debug-php-warning-2/)
- Fixes - Unable to delete file when "send file(s) as link" enabled. [support](https://wordpress.org/support/topic/deleting-uploaded-files-is-not-working/)

= 1.3.8.6 =
- Fixes - Security Updates (fixed Vulnerability issue reported by Wordfence - CVE ID:CVE-2024-12267)
- Bug Fix - Fixed bug [Support Link](https://wordpress.org/support/topic/argument-1-value-must-be-of-type-countablearray-string-given/)
- Improvement - Added a random directory for each user/guest uploads to prevent file deletion across folders (related to item # 1)

= 1.3.8.5 =
- Hot fix ( Showing critical error on Php 7.3 and Up )
- Improvement - Improved I18N (Thanks to @alexclassroom)[Support Link](https://wordpress.org/support/topic/improve-i18n-issues-based-on-1-3-8-4/)

= 1.3.8.4 =
- Added Compatibility on Contact Form 7 6.0.
- Wordpress 6.7 Compatibility check.

= 1.3.8.3 =
- Bug - Fixed "send file(s) as links" option not creating year/month folder structure.

= 1.3.8.2 =
- Fixes - Show query error using Query Monitor plugin [Here](https://wordpress.org/support/topic/php-error-pops-up-via-query-monitor/)
- Added - JS/PHP hooks after successful upload [Here](https://wordpress.org/support/topic/javascript-jquery-event-to-trigger-successful-uploads/)
- Tweak - Move error message above files upload [Here](https://wordpress.org/support/topic/is-it-possible-to-move-the-error-message-location/)
- Fixes - Minor fixes and improvements

= 1.3.8.1 =
- Quick Fix - Unable to uncheck "Send file(s) as links" option.

= 1.3.8.0 =
- Quick fix to prevent auto-deletion if the "Don't delete files" setting was overridden by recent updates.

= 1.3.7.9 =
- Bug - Added back the "Don't delete files" option.
- Optimized -  Optimized plugin settings by saving them as an array instead of retrieving individual settings from the wp_options table.
- Compatibility check on latest version of Contact Form 7 5.9.5.

== Upgrade Notice ==

= 1.2.3 =
This version fixed minor issues/bugs and add multiple drag and drop fields in a form.

= 1.2.1 =
This version fixed minor issues and bugs.

= 1.2.2 =
Added some useful features.

= 1.2.4 =
Added new features and fixes.