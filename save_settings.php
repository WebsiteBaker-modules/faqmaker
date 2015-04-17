<?php

/*
FAQ Maker
Copyright (C) 2008, Nick Willey
nick.willey@gmail.com

This module is free software.
You can redistribute it and/or modify it
under the terms of the GNU General Public License
- version 2 or later, as published by the Free Software Foundation:
http://www.gnu.org/licenses/gpl.html.

This module is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

require('../../config.php');

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');

// This code removes any php tags and adds slashes
$header = $admin->add_slashes(str_replace('?php', '', $_POST['header']));
$footer = $admin->add_slashes(str_replace('?php', '', $_POST['footer']));
$message = $admin->add_slashes(str_replace('?php', '', $_POST['message']));
$email_to = $admin->add_slashes(str_replace('?php', '', $_POST['email_to']));
$use_captcha = $admin->add_slashes($_POST['use_captcha']);
$show_details = $admin->add_slashes($_POST['show_details']);
$show_ask_link = $admin->add_slashes($_POST['show_ask_link']);
$question_destination = $admin->add_slashes($_POST['question_destination']);

// Update settings
$database->query("UPDATE ".TABLE_PREFIX."mod_faqmaker_settings SET "
	." header = '$header', "
	." footer = '$footer', "
	." message = '$message', "
	." email_to = '$email_to', "
	." use_captcha = '$use_captcha', "
	." show_details = '$show_details', "
	." show_ask_link = '$show_ask_link', "
	." question_destination = '$question_destination' "
	." WHERE section_id = '$section_id'");

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

?>
