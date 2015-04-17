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

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

// Load Language file
if(LANGUAGE_LOADED) {
    require_once(WB_PATH.'/modules/faqmaker/languages/EN.php');
    if(file_exists(WB_PATH.'/modules/faqmaker/languages/'.LANGUAGE.'.php')) {
        require_once(WB_PATH.'/modules/faqmaker/languages/'.LANGUAGE.'.php');
    }
}

//Delete empty records
$database->query("DELETE FROM ".TABLE_PREFIX."mod_faqmaker_topics WHERE section_id = '$section_id' and topic_name=''");
$database->query("DELETE FROM ".TABLE_PREFIX."mod_faqmaker_questions WHERE section_id = '$section_id' and topic_id=''"); 

$query_settings = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_faqmaker_settings WHERE section_id='$section_id'");
if( $query_settings->numRows() == 0 ) {
	$database->query("INSERT INTO ".TABLE_PREFIX."mod_faqmaker_settings VALUES($section_id,'','','','')");
}

?>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td align="left" width="25%">
		<input type="button" value="<?php echo $FQTEXT['ADD_QUESTION_ANSWER']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/faqmaker/add_question.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>';" style="width: 100%;" />
	</td>
	<td align="center" width="25%">
		<input type="button" value="<?php echo $FQTEXT['NEW_TOPIC']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/faqmaker/add_topic.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>';" style="width: 100%;" />
	</td>
<td align="center" width="25%">
                <input type="button" value="<?php echo $FQTEXT['VIEW_SUBMITTED']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/faqmaker/view_submitted.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>';" style="width: 100%;" />
        </td>
	<td align="center" width="25%">
		<input type="button" value="<?php echo $FQTEXT['SETTINGS']; ?>" onclick="javascript: window.location = '<?php echo WB_URL; ?>/modules/faqmaker/modify_settings.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>';" style="width: 100%;" />
	</td>
</tr>
</table>

<br /><br />

<?php

// Loop through existing links
$query_topics = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_faqmaker_topics` WHERE section_id='$section_id' ORDER BY position ASC");
if($query_topics->numRows() > 0) {
	$num_topics = $query_topics->numRows();
	while($topic = $query_topics->fetchRow()) {
		?>
		<h2><?php echo $admin->strip_slashes($topic['topic_name']); ?>
			<a href="<?php echo WB_URL; ?>/modules/faqmaker/modify_topic.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&topic_id=<?php echo $topic['topic_id']; ?>">
				<img src="<?php echo ADMIN_URL; ?>/images/modify_16.png" border="0" alt="<?php echo $TEXT['MODIFY']; ?> - " />
			</a>
			<?php if ($topic['position'] != 1) { ?>
			<a href="<?php echo WB_URL; ?>/modules/faqmaker/move_up.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&topic_id=<?php echo $topic['topic_id']; ?>" title="<?php echo $TEXT['MOVE_UP']; ?>">
				<img src="<?php echo ADMIN_URL; ?>/images/up_16.png" border="0" alt="^" />
			</a>
			<?php } 
			if ($topic['position'] != $num_topics) { ?>
			<a href="<?php echo WB_URL; ?>/modules/faqmaker/move_down.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&topic_id=<?php echo $topic['topic_id']; ?>" title="<?php echo $TEXT['MOVE_DOWN']; ?>">
				<img src="<?php echo ADMIN_URL; ?>/images/down_16.png" border="0" alt="v" />
			</a>
			<?php } ?>
			<a href="#" onclick="javascript: confirm_link('<?php echo $FQTEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL; ?>/modules/faqmaker/delete_topic.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&topic_id=<?php echo $topic['topic_id']; ?>');" title="<?php echo $TEXT['DELETE']; ?>">
				<img src="<?php echo ADMIN_URL; ?>/images/delete_16.png" border="0" alt="X" />
			</a>
		</h2>
		<table cellpadding="2" cellspacing="0" border="0" width="100%">
		<?php
		$query_quests = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_faqmaker_questions` WHERE section_id='$section_id' AND topic_id='".$topic['topic_id']."' ORDER BY position ASC");
		if($query_quests->numRows() > 0) {
			$num_quests = $query_quests->numRows();
			$row = 'a';
			while($quest = $query_quests->fetchRow()) {
			?>
			<tr class="row_<?php echo $row; ?>" height="20">
				<td width="20">
					<a href="<?php echo WB_URL; ?>/modules/faqmaker/modify_question.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&question_id=<?php echo $quest['question_id']; ?>">
						<img src="<?php echo ADMIN_URL; ?>/images/modify_16.png" border="0" alt="<?php echo $TEXT['MODIFY']; ?> - " />
					</a>
				</td>
				<td width="180">
					<a href="<?php echo WB_URL; ?>/modules/faqmaker/modify_question.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&question_id=<?php echo $quest['question_id']; ?>">
						<?php echo $admin->strip_slashes(substr(strip_tags($quest['question']),0,40)); ?>..
					</a>
				</td>
				<td width="280">
					<?php echo $admin->strip_slashes(substr(strip_tags($quest['answer']),0,50)); ?>..
				</td>
				<td width="18">
				<?php if ($quest['position'] != 1) { ?>
					<a href="<?php echo WB_URL; ?>/modules/faqmaker/move_up.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&question_id=<?php echo $quest['question_id']; ?>" title="<?php echo $TEXT['MOVE_UP']; ?>">
						<img src="<?php echo ADMIN_URL; ?>/images/up_16.png" border="0" alt="^" />
					</a>
				<?php } ?>
				</td>
				<td width="18">
				<?php if ($quest['position'] != $num_quests) { ?>
					<a href="<?php echo WB_URL; ?>/modules/faqmaker/move_down.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&question_id=<?php echo $quest['question_id']; ?>" title="<?php echo $TEXT['MOVE_DOWN']; ?>">
						<img src="<?php echo ADMIN_URL; ?>/images/down_16.png" border="0" alt="v" />
					</a>
				<?php } ?>
				</td>
				<td width="18">
					<a href="#" onclick="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL; ?>/modules/faqmaker/delete_question.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&topic_id=<?php echo $quest['topic_id']; ?>&question_id=<?php echo $quest['question_id']; ?>');" title="<?php echo $TEXT['DELETE']; ?>">
						<img src="<?php echo ADMIN_URL; ?>/images/delete_16.png" border="0" alt="X" />
					</a>
				</td>
			</tr>
			<!-- question end -->
			<?php
			// Alternate row color
			if($row == 'a') { $row = 'b'; } else { $row = 'a'; }
		}
	} else {
		echo '<i>'.$TEXT['NONE_FOUND'].'</i>';
	}
	?>

	</table>
	<br /><br />
	
	<!-- new topic -->

	<?php
	}
} else {
	echo '<i>'.$TEXT['NONE_FOUND'].'</i>';
}

?>
