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

// Get id
if(!isset($_GET['topic_id']) OR !is_numeric($_GET['topic_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$topic_id = $_GET['topic_id'];
}

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');

// Delete row
$database->query("DELETE FROM ".TABLE_PREFIX."mod_faqmaker_topics WHERE topic_id = '$topic_id' LIMIT 1");

// Include the ordering class or clean-up ordering
require(WB_PATH.'/framework/class.order.php');
$order = new order(TABLE_PREFIX.'mod_faqmaker_topics', 'position', 'topic_id', 'section_id');
$order->clean($_GET['section_id']);

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
} else {
	$database->query("DELETE FROM ".TABLE_PREFIX."mod_faqmaker_questions WHERE topic_id='$topic_id'");
	if($database->is_error()) {
		$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	} else {
		$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
	}
}

// Print admin footer
$admin->print_footer();

?>
