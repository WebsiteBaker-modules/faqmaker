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

if(defined('WB_URL')) {
	
	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_faqmaker_topics`");
	$mod_faqmaker_topics = 'CREATE TABLE `'.TABLE_PREFIX.'mod_faqmaker_topics` ( '
  		. '`section_id` INT(10) NOT NULL DEFAULT \'0\','
		. '`page_id` INT NOT NULL DEFAULT \'0\' ,'
		. '`topic_id` INT(10) NOT NULL AUTO_INCREMENT,'
		. '`topic_name` VARCHAR(255) NOT NULL DEFAULT \'\','
		. '`position` INT(10) NOT NULL DEFAULT \'0\','
		. 'PRIMARY KEY  (topic_id)'
		. ')';
	$database->query($mod_faqmaker_topics);

	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_faqmaker_questions`");
	$mod_faqmaker_questions = 'CREATE TABLE `'.TABLE_PREFIX.'mod_faqmaker_questions` ( '
		. '`section_id` INT(10) NOT NULL DEFAULT \'0\','
		. '`page_id` INT NOT NULL DEFAULT \'0\' ,'
		. '`question_id` INT(10) NOT NULL AUTO_INCREMENT,'
		. '`topic_id` INT(10) NOT NULL DEFAULT \'0\','
		. '`question` VARCHAR(255) NOT NULL DEFAULT \'\','
		. '`answer` TEXT NOT NULL,'
		. '`position` INT(10) NOT NULL DEFAULT \'0\','
		. '`modified_when` INT(11) NOT NULL DEFAULT \'0\','
		. 'PRIMARY KEY (question_id)'
		. ')';
	$database->query($mod_faqmaker_questions);
	
	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_faqmaker_settings`");
	$mod_faqmaker_settings = 'CREATE TABLE `'.TABLE_PREFIX.'mod_faqmaker_settings` ( '
		. '`section_id` INT(10) NOT NULL DEFAULT \'0\','
		. '`page_id` INT NOT NULL DEFAULT \'0\' ,'
		. '`header` TEXT NOT NULL,'
		. '`footer` TEXT NOT NULL,'
		. '`message` TEXT NOT NULL,'
		. '`email_to` TEXT NOT NULL,'
		. '`use_captcha` tinyint(1) NOT NULL DEFAULT \'0\','
		. '`show_details` tinyint(1) NOT NULL DEFAULT \'0\','
		. '`show_ask_link` tinyint(1) NOT NULL DEFAULT \'0\','
		. '`question_destination` tinyint(1) NOT NULL DEFAULT \'0\','
		. 'PRIMARY KEY (section_id)'
		. ')';
	$database->query($mod_faqmaker_settings);

 $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_faqmaker_submitted_questions`");
 $mod_faqmaker_submitted_questions = 'CREATE TABLE `'.TABLE_PREFIX.'mod_faqmaker_submitted_questions` ( '
  . '`section_id` INT(10) NOT NULL DEFAULT \'0\','
  . '`page_id` INT NOT NULL DEFAULT \'0\','
  . '`submission_id` INT(10) NOT NULL AUTO_INCREMENT,'
		. '`name` VARCHAR(255) NOT NULL DEFAULT \'\','
		. '`email` VARCHAR(255) NOT NULL DEFAULT \'\','
		. '`submitted_question` VARCHAR(255) NOT NULL DEFAULT \'\','
		. '`submitted_when` INT(11) NOT NULL DEFAULT \'0\','
		. 'PRIMARY KEY (submission_id)'
		. ')';
	$database->query($mod_faqmaker_submitted_questions);

	// Insert info into the search table
	// Module query info
	$field_info = array();
	$field_info['page_id'] = 'page_id';
	$field_info['title'] = 'page_title';
	$field_info['link'] = 'link';
	$field_info['description'] = 'description';
	$field_info['modified_when'] = 'modified_when';
	$field_info['modified_by'] = 'modified_by';
	$field_info = serialize($field_info);
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('module', 'faqmaker', '$field_info')");
	// Query start
	// Query start
	$query_start_code = "SELECT [TP]pages.page_id, [TP]pages.page_title,	[TP]pages.link, [TP]pages.description, [TP]pages.modified_when, [TP]pages.modified_by	FROM [TP]mod_faqmaker_questions, [TP]pages WHERE ";
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_start', '$query_start_code', 'faqmaker')");
	// Query body
	$query_body_code = "
	[TP]pages.page_id = [TP]mod_faqmaker_questions.page_id AND [TP]mod_faqmaker_questions.question LIKE \'%[STRING]%\'
	OR [TP]pages.page_id = [TP]mod_faqmaker_questions.page_id AND [TP]mod_faqmaker_questions.answer LIKE \'%[STRING]%\' ";
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_body', '$query_body_code', 'faqmaker')");
	// Query end
	$query_end_code = "";	
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_end', '$query_end_code', 'faqmaker')");
	
	// Insert blank row (there needs to be at least on row for the search to work)
	$database->query("INSERT INTO ".TABLE_PREFIX."mod_faqmaker_questions (page_id,section_id) VALUES ('0','0')");
	
}

?>
