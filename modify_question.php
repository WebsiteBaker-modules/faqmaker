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
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$question_id = $_GET['question_id'];
}

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

// Load Language file
if(LANGUAGE_LOADED) {
    require_once(WB_PATH.'/modules/faqmaker/languages/EN.php');
    if(file_exists(WB_PATH.'/modules/faqmaker/languages/'.LANGUAGE.'.php')) {
        require_once(WB_PATH.'/modules/faqmaker/languages/'.LANGUAGE.'.php');
    }
}

// Get info on question
$query_question = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_faqmaker_questions WHERE question_id='$question_id' AND section_id='".$section_id."' ");
$fetch_question = $query_question->fetchRow();
?>

<style type="text/css">
.setting_name {
	vertical-align: top;
}
</style>

<form name="modify" action="<?php echo WB_URL; ?>/modules/faqmaker/save_question.php" method="post" style="margin: 0;">

<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
<input type="hidden" name="question_id" value="<?php echo $question_id; ?>">
<input type="hidden" name="old_topic" value="<?php echo $fetch_question['topic_id']; ?>"> 
<input type="hidden" name="position" value="<?php echo $fetch_question['position']; ?>">

<table class="row_a" cellpadding="2" cellspacing="0" border="0" align="center" width="100%">
<tr>
	<td class="setting_name">
		<label for="topic" accesskey="c"><b><?php echo $FQTEXT['TOPIC']; ?>:</b></label>
	</td>
</tr>
<tr>
	<td class="setting_name">
		<select name="topic" id="topic" style="width: 98%;">
		<?php
		// Get topics
		$query_topics = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_faqmaker_topics WHERE section_id='".$section_id."' ORDER BY position ASC");
		if($query_topics->numRows() > 0){
			while( $fetch_topic = $query_topics->fetchRow() ) {
				if($fetch_topic['topic_id'] == $fetch_question['topic_id']){ $selected = "selected"; } else { $selected = ""; }
				echo '<option value="'.$fetch_topic['topic_id'].'" '.$selected.'>'.$admin->strip_slashes($fetch_topic['topic_name']).'</option>';
			}
		}
		?>
		</select>
	</td>
</tr>
<tr>
	<td class="setting_name">
		<label for="question" accesskey="q"><b><?php echo $FQTEXT['QUESTION']; ?>:</b></label>
	</td>
</tr>
<tr>
	<td class="setting_name">
		<input type="text" name="question" id="question" value="<?php echo $admin->strip_slashes($fetch_question['question']); ?>" style="width: 98%;" maxlength="255" />
	</td>
</tr>
<tr>
	<td class="setting_name">
		<label for="answer" accesskey="a"><b><?php echo $FQTEXT['ANSWER']; ?>:</b></label>
	</td>
</tr>
</table>

<?php
$content = (htmlspecialchars($fetch_question['answer']));

	if (!defined('WYSIWYG_EDITOR') OR WYSIWYG_EDITOR=="none" OR !file_exists(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php')) {
		function show_wysiwyg_editor($name,$id,$content,$width,$height) {
			echo '<textarea name="'.$name.'" id="'.$id.'" style="width: '.$width.'; height: '.$height.';">'.$content.'</textarea>';
		}
	} else {
		$id_list=array("answer");
			require(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php');
	}
		

show_wysiwyg_editor('answer','answer',$content,'725px','350px');

?>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td align="left">
		<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;"></form>
	</td>
	<td align="right">
		<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
	</td>
</tr>
</table>
<?php

// Print admin footer
$admin->print_footer();

?>
