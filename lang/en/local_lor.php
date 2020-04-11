<?php

// General.
$string['pluginname'] = "WCLN Learning Object Repository";
$string['lor:insert'] = "Add new items to the WCLN Learning Object Repository";
$string['lor:edit']   = "Edit items in the WCLN Learning Object Repository";

// Insert form.
$string['heading']                = "Add to WCLN Learning Object Repository";
$string['subheading']             = "Add an item to the WCLN Learning Object Repository. Start by selecting the type of item you would like to add.";
$string['title']                  = "Title";
$string['inquiry']                = "Title / Inquiry Question";
$string['description']            = "Inquiry Question";
$string['categories']             = "Choose one or more categories";
$string['topics']                 = "Topics (Comma separated: eg. circulatory system, heart, blood)";
$string['word']                   = "Upload a Word document of the project";
$string['pdf']                    = "Attach a PDF version of the project";
$string['icon']                   = "Upload an icon (200x150px)";
$string['grade']                  = "Grade(s)";
$string['contributors']           = "Contributor(s) (Comma separated)";
$string['files']                  = 'Files';
$string['about']                  = 'About';
$string['submit']                 = "Submit";
$string['link']                   = 'Link to game';
$string['image']                  = 'Preview image (200x150px)';
$string['iframe_size']            = 'IFrame Size';
$string['width']                  = "Width";
$string['height']                 = "Height";
$string['iframe_size_paragraph']  = 'Specify a width and a height for the IFrame in which the game will be displayed.';
$string['preview_image_optional'] = 'Uploading a preview image is optional. It is best to leave this empty unless you have a specific 200x150px image to use. A generic preview image will be used if you do not upload an image.';

// lessons insert form.
$string['book_id'] = "Book ID";

// Videos insert form.
$string['video_id']      = "Video ID";
$string['video_id_help'] = "The video ID is found by browsing to the YouTube video you would like to add, then copying the text from the URL after '?v='.";

// Type form.
$string['type'] = "Item Type";
$string['next'] = 'Next';

// Errors.
$string['error_filenames']       = "All files must have the same name!";
$string['error_filename_exists'] = "This filename exists in the database already! Please change the filename of all files.";
$string['error_file_exists']     = "This filename already exists on the server! Please change the filename of all files.";
$string['error_categories']      = "You must choose AT LEAST one category.";
$string['error_title_not_found'] = "[ERROR: TITLE NOT FOUND!]";
$string['error_missing_form']    = 'Error: That insert form is not yet available.';
$string['error_unexpected']      = 'An unexpected error occured. Please contact a WCLN admin. The following exception occurred: ';
$string['error_title_length']    = 'Title is too long!';
$string['error_no_video']        = 'Unable to insert: YouTube video with that video ID does not exist.';

// Navigation bar.
$string['lor']              = "LOR";
$string['nav_insert_form']  = "Insert Form";
$string['nav_edit_form']    = "Edit Form";
$string['nav_default_type'] = "Item";
$string['nav_insert']       = "Insert ";

// Search form.
$string['search']            = "Search";
$string['sort_by']           = "Sort by";
$string['recently_added']    = "Recently added";
$string['alphabetical']      = "Alphabetical";
$string['topics']            = "Topics";
$string['search_categories'] = "Categories";
$string['search_grades']     = "Grades";
$string['all']               = "All";
$string['reset']             = 'Reset';
$string['keywords']          = 'Keywords';
$string['keywords_help']     = 'Searches titles, categories, contributors and topics.';

// Edit form.
$string['save']          = "Save";
$string['delete']        = "Delete";
$string['heading_edit']  = 'Editing \'{$a->title}\'';
$string['error_no_item'] = 'Error: No item is set.';
$string['type_header']   = '{$a->name} Specific Settings';
$string['edit_server']   = '{$a->name} specific settings are not yet incorporated on this form. To edit {$a->name} files, access the server and manually replace the files corresponding with the ID {$a->id}.';
$string['image_help']    = 'Leave blank to use the current preview image. If you upload a new image, it will overwrite the existing image. Remember to choose an image that scales well to 200x150px.';

// Not allowed template.
$string['not_allowed_header']    = 'You do not have permission to access this page.';
$string['not_allowed_paragraph'] = 'Please contact a site administrator if you believe this is incorrect.';

// Scheduled Task.
$string['updatevideos'] = 'Update LOR videos from YouTube';
$string['deleteitems']  = 'Permanently delete marked items from the LOR database.';

$string['type:media']            = 'Game/media';
$string['subplugintype_lortype'] = 'LOR Item type';

$string['add_heading'] = 'Add a new media item';
$string['add_title']   = 'Add an item';
$string['add_success'] = 'The item was added successfully!';
$string['add_error']   = 'The item could not be created.';

$string['edit_heading']  = 'Editing: {$a}';
$string['edit_title']    = 'Edit an item';
$string['edit_success']  = 'The item was saved successfully!';
$string['edit_error']    = 'The item failed to update.';
$string['error_sesskey'] = 'Invalid session key!';

$string['delete_heading'] = 'Deleting: {$a}';
$string['delete_title']   = 'Delete an item';
$string['delete_success'] = 'Item deleted successfully!';
$string['delete_error']   = 'An error occurred while trying to delete the item.';
$string['delete_confirm'] = 'Are you sure you want to permanently delete the item \'{$a}\'?';
