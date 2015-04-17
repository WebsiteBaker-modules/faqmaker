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
if(!isset($_POST['question_id']) OR !is_numeric($_POST['question_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$question_id = $_POST['question_id'];
}

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');

// Validate all fields
if($admin->get_post('question') == '' AND $admin->get_post('answer') == '') {
	$admin->print_error($MESSAGE['GENERIC']['FILL_IN_ALL'], WB_URL.'/modules/faqmaker/modify_question.php?page_id='.$page_id.'&section_id='.$section_id.'&question_id='.$question_id);
} else {
	$question = htmlspecialchars($admin->add_slashes($admin->get_post('question')));
	$answer = $admin->add_slashes($admin->get_post('answer'));
	$topic_id = $admin->get_post('topic');
	$old_topic = $admin->get_post('old_topic');
	$position = $admin->get_post('position');
}

// Check if topic has changed
if($old_topic != $topic_id and $topic_id != '') {
        // Include the ordering class
        require(WB_PATH.'/framework/class.order.php');
        // Get new order
	$order = new order(TABLE_PREFIX.'mod_faqmaker_questions', 'position', 'question_id', 'topic_id');
	$position = $order->get_new($topic_id);
}

// Update row
$database->query("UPDATE ".TABLE_PREFIX."mod_faqmaker_questions SET question='$question', answer='$answer', topic_id='$topic_id', position='$position', modified_when='".mktime()."' WHERE question_id='$question_id'");

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/faqmaker/modify_question.php?page_id='.$page_id.'&section_id='.$section_id.'&question_id='.$question_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

?>
