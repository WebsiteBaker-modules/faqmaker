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
if(!isset($_GET['submission_id']) OR !is_numeric($_GET['submission_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$submission_id = $_GET['submission_id'];
}

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');

// Delete row
$database->query("DELETE FROM ".TABLE_PREFIX."mod_faqmaker_submitted_questions WHERE submission_id = '$submission_id' LIMIT 1");

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/faqmaker/view_submitted.php?page_id='.$page_id.'&section_id='.$section_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/faqmaker/view_submitted.php?page_id='.$page_id.'&section_id='.$section_id);
}

// Print admin footer
$admin->print_footer();

?>
