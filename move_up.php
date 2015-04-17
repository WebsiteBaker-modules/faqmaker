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
if(!isset($_GET['question_id']) OR !is_numeric($_GET['question_id'])) {
	if(!isset($_GET['topic_id']) OR !is_numeric($_GET['topic_id'])) {
		header("Location: index.php");
	} else {
		$id = $_GET['topic_id'];
		$id_field = 'topic_id';
		$common_field = 'section_id';
		$table = TABLE_PREFIX.'mod_faqmaker_topics';
	}
} else {
	$id = $_GET['question_id'];
	$id_field = 'question_id';
	$common_field = 'topic_id';
	$table = TABLE_PREFIX.'mod_faqmaker_questions';
}

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

// Include the ordering class
require(WB_PATH.'/framework/class.order.php');

//require('class.order.php');

// Create new order object an reorder
$order = new order($table, 'position', $id_field, $common_field);
if($order->move_up($id)) {
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
} else {
	$admin->print_error($TEXT['ERROR'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

?>
