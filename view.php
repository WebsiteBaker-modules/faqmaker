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

// Get settings
$query_settings = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_faqmaker_settings` WHERE section_id='$section_id' LIMIT 1");
$fetch_settings = $query_settings->fetchRow();

$header= $admin->strip_slashes($fetch_settings['header']);
$footer= $admin->strip_slashes($fetch_settings['footer']);
$message= $admin->strip_slashes($fetch_settings['message']);
$faq_email_to = $admin->strip_slashes($fetch_settings['email_to']);
$use_captcha = $admin->strip_slashes($fetch_settings['use_captcha']);
$show_details = $admin->strip_slashes($fetch_settings['show_details']);
$show_ask_link = $admin->strip_slashes($fetch_settings['show_ask_link']);
$question_destination = $admin->strip_slashes($fetch_settings['question_destination']);

// check if frontend.css file needs to be included into the <body></body> of view.php
if((!function_exists('register_frontend_modfiles') || !defined('MOD_FRONTEND_CSS_REGISTERED')) &&  file_exists(WB_PATH .'/modules/faqmaker/frontend.css')) {
   echo '<style type="text/css">';
   include(WB_PATH .'/modules/faqmaker/frontend.css');
   echo "\n</style>\n";
}

?>

<table width="100%" border="0">
<?php
// Print header
if ( $header <> "" ) {
	echo "<tr><td colspan='2' class='faq_header'>". $header ."</td></tr>";
}

//Display faq menu
echo "<tr><td colspan='2' class='faq_menu'><a href=?>". $FQTEXT['BROWSE'] ." ". $FQTEXT['TOPICS'] ."</a>";
if ($show_ask_link == 1) {
echo "<a href='?ask=ask'>". $FQTEXT['ASK_QUESTION'] ."</a></td></tr>";
}


if (!isset($_GET['t_id']) AND !isset($_GET['qa_id']) AND !isset($_GET['ask']) AND !isset($_GET['success']) AND !isset($_GET['fail'])) {

	// Print main message
	if ( $message <> "" ) {
	        echo "<tr><td colspan='2' class='faq_mainMessage'>". $message ."</td></tr>";
	}

	//Display all topics
	echo "<tr><td colspan='2' class='faq_browseTopics'>". $FQTEXT['BROWSE'] ." ". $FQTEXT['TOPICS'] ."</td></tr>";
 
	$query_topics = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_faqmaker_topics` WHERE section_id='$section_id' ORDER BY position ASC");
	if ($query_topics->numRows() > 0) {
		echo "<UL>";
		echo "<tr><td colspan='2' class='faq_topicList'><li><a href='?t_id=ALL'>". $FQTEXT['VIEW_ALL'] ."</a></li></td></tr>";
		while ($topic = $query_topics->fetchRow()) {
			echo "<tr><td colspan='2' class='faq_topicList'><li><a href='?t_id=".$topic['topic_id']."'>".$topic['topic_name']."</a></li></td></tr>";
		}
		echo "</UL>";
	}
}


//Display All FAQs 
if (isset($_GET['t_id']) AND $_GET['t_id'] == "ALL") {
	echo "<tr><td colspan='2' class='faq_topicHeader'>". $FQTEXT['ALL'] ." ". $FQTEXT['FAQS'] ."</td></tr>\n";
	
	//Get Topics
	$query_topics = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_faqmaker_topics` WHERE section_id='$section_id' ORDER BY position ASC");
	if ($query_topics->numRows() > 0) {
		//List Topics
		while ($topic = $query_topics->fetchRow()) {
			$topic_id = $topic['topic_id'];
			$topic_name = $topic['topic_name'];
			
			echo "<tr><td colspan='2' class='faq_topicList'><a href='?t_id=".$topic_id."'>".$topic_name."</a></td></tr>\n";
			
			$query_quests = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_faqmaker_questions` WHERE topic_id='$topic_id' ORDER BY position ASC");
			if($query_quests->numRows() > 0) {
				echo "<UL>\n";
				while($quest = $query_quests->fetchRow()) {
					$question = $quest['question'];
					$q_id = $quest['question_id'];
					
					echo "<tr><td colspan='2' class='faq_question'><li><a href='?qa_id=". $topic_id.".".$q_id."'>".$question."</a></li></td></tr>\n";
				}
				echo "</UL>\n";
			}			
			
			
		}
	}
}

//Display Single Topic
if (isset($_GET['t_id']) AND is_numeric($_GET['t_id'])) {
	$topic_id = $_GET['t_id'];
	$query_topics = $database->query("SELECT topic_name FROM `".TABLE_PREFIX."mod_faqmaker_topics` WHERE topic_id='$topic_id'");

	// Loop through questions
	if ($query_topics->numRows() > 0) {
        	while ($topic = $query_topics->fetchRow()) {
			echo "<tr><td colspan='2' class='faq_topicHeader'>Topic: ". $topic['topic_name'] ."</td></tr>";
	                $query_quests = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_faqmaker_questions` WHERE topic_id='$topic_id' ORDER BY position ASC");
	                if($query_quests->numRows() > 0) {
	                        echo "<UL>";
	                        while($quest = $query_quests->fetchRow()) {
	                                $question=$quest['question'];
	                                echo "<tr><td colspan='2' class='faq_question'><li><a href='?qa_id=". $topic_id.".".$quest['question_id']."'>".$question."</a></li></td></tr>";
	                        }
	                        echo "</UL>";
	                }
	        }
	}
}

//Display single question and answer
if (isset($_GET['qa_id'])) {

	$qa = explode(".", $_GET['qa_id']);
	$t_id = $qa[0];
	$q_id = $qa[1];
	
	$query_quests = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_faqmaker_questions` WHERE question_id='$q_id'");
	$quests = $query_quests->fetchRow();
	$answer = $quests['answer'];
	$modified_when = $quests['modified_when'];
	$wb->preprocess($answer);
	
	$query_topics = $database->query("SELECT topic_name FROM `".TABLE_PREFIX."mod_faqmaker_topics` WHERE topic_id='$t_id'");
	$topic_name = $query_topics->fetchRow();	
	
	echo "<tr><td colspan='2' class='faq_topicHeader'>Topic: <a href='?t_id=". $t_id ."'>". $topic_name['topic_name'] ."</a></td></tr>";	
	echo "<tr><td class='faq_questionQ'>".$FQTEXT['QUESTION']."</td>";
	
	if ($show_details == 1) {
		echo "<td class='faq_modified'>".$FQTEXT['LAST_UPDATED']." ".$FQTEXT['ON']." ".gmdate(DATE_FORMAT, $modified_when+TIMEZONE)." ".$FQTEXT['AT']." ".gmdate(TIME_FORMAT, $modified_when+TIMEZONE)."</td>";
	}

	echo "</tr>";
	echo "<tr><td colspan='2' class='faq_question'>". $quests['question'] ."</td></tr>";
	echo "<tr><td colspan='2' class='faq_answerA'>".$FQTEXT['ANSWER']."</td></tr>";
	echo "<tr><td colspan='2' class='faq_answer'>". $answer ."</td></tr>";
}


//Display ask question form
if (isset($_GET['ask'])) {

	//Include captcha for WB 2.7.x
	if(file_exists(WB_PATH.'/include/captcha/captcha.php')) {
		require_once(WB_PATH.'/include/captcha/captcha.php');
	}

	echo "<tr><td class='faq_topicHeader'>Ask a Question</td></tr>";

	echo "<form name='ask_question' action='". $_SERVER['PHP_SELF'] ."?ask=ask' method='post'>";

	echo "<tr><td>". $FQTEXT['NAME'] .":</td><tr>";
	echo "<tr><td><input type='text' name='name' size='60' ";  
	if(isset($_SESSION['name'])) { 
		echo " value='".$_SESSION['name']."'"; unset($_SESSION['name']); 
	}
	echo "></td></tr>";

	echo "<tr><td>". $FQTEXT['EMAIL_ADDRESS'] .":";
	if(isset($_SESSION['email_from_error'])) {
		echo "&nbsp;&nbsp;<font color='#FF0000'>".$_SESSION['email_from_error']."</font>";
		unset($_SESSION['email_from_error']);
	}
	echo "</td></tr>";
	echo "<tr><td><input type='text' name='email_from' size='60' ";
	if(isset($_SESSION['email_from'])) { 
		echo " value='".$_SESSION['email_from']."'"; unset($_SESSION['email_from']); 
	} 
	echo "></td></tr>";

	echo "<tr><td>". $FQTEXT['QUESTION'] .":";
	if(isset($_SESSION['question_error'])) {
		echo "&nbsp;&nbsp;<font color='#FF0000'>".$_SESSION['question_error']."</font>";
		unset($_SESSION['question_error']);
	}
	echo "</td></tr>";
	echo "<tr><td><textarea name='question' style='width: 90%; height: 150px;' >";
	if(isset($_SESSION['question'])) { 
		echo $_SESSION['question']; unset($_SESSION['question']);  
	} 
	echo "</textarea></td></tr>";

	if(isset($_SESSION['captcha_error'])) {
                echo "<tr><td><font color='#FF0000'>".$_SESSION['captcha_error']."</font></td></tr><br />";
	}

                // Captcha
                if($use_captcha == 1) {
			//WB 2.7.x captcha
			if(file_exists(WB_PATH.'/include/captcha/captcha.php')) {
				echo "<table cellpadding='2' cellspacing='0' border='0'>";
                                echo "<tr>";
				echo "<td>". $TEXT['VERIFICATION'] .":</td>";
                                echo "<td>". call_captcha() ."</td>";
				echo "</tr></table>";
                                if(isset($_SESSION['captcha_error'])) {
                                        unset($_SESSION['captcha_error']);
                                        ?><script>document.ask_question.captcha.focus();</script><?php
                                }
			}
                }
	
	echo "<tr><td><input name='ask_question' type='submit' value='". $FQTEXT['ASK'] ."' style='width: 100px; margin-top: 5px;'>";
	echo "<input type='button' value='". $TEXT['CANCEL'] ."' onclick=\"javascript: window.location = '". $_SERVER['PHP_SELF'] ."?'\"></td></tr>";

	echo "</form>";


	if(isset($_POST['ask_question'])) {
		require_once(WB_PATH.'/framework/class.wb.php');
		$wb = new wb;

  $name = $wb->add_slashes(strip_tags($_POST['name']));

		if(!isset($_POST['email_from']) OR $_POST['email_from'] == '') {
   $email_from = 'Anonymous';
  } else {
   if($wb->validate_email($_POST['email_from']) == false) {
				$_SESSION['email_from_error'] = $FQTEXT['EMAIL_FROM_ERROR'];
			}
  }

                
		if(!isset($_POST['question']) OR $_POST['question'] == '') {
   $_SESSION['question_error'] = $FQTEXT['QUESTION_ERROR'];
		} else {
   $question = $wb->add_slashes(strip_tags($_POST['question']));
		}
		
		if(isset($_SESSION['email_from_error']) OR isset($_SESSION['question_error'])) { 
			$_SESSION['name'] = $name;
			$_SESSION['email_from'] = $email_from;
			$_SESSION['question'] = $question;
			exit(header('Location: '.$_SERVER['PHP_SELF'].'?ask=ask'));
		}

  //captcha check
		if($use_captcha == 1) {
			//WB 2.7.x captcha
			if(file_exists(WB_PATH.'/include/captcha/captcha.php')) {
				if(isset($_POST['captcha']) AND $_POST['captcha'] != '') {
					// Check for a mismatch
				        if(!isset($_POST['captcha']) OR !isset($_SESSION['captcha']) OR $_POST['captcha'] != $_SESSION['captcha']) {
				                $_SESSION['captcha_error'] = $MESSAGE['MOD_FORM']['INCORRECT_CAPTCHA'];
				                $_SESSION['name'] = $name;
				                $_SESSION['email_from'] = $email_from;
				                $_SESSION['question'] = $question;
				                exit(header('Location: '.$_SERVER['PHP_SELF'].'?ask=ask'));
				        }
				} else {
					$_SESSION['captcha_error'] = $MESSAGE['MOD_FORM']['INCORRECT_CAPTCHA'];
					$_SESSION['name'] = $name;
					$_SESSION['email_from'] = $email_from;
					$_SESSION['question'] = $question;
					exit(header('Location: '.$_SERVER['PHP_SELF'].'?ask=ask'));
				}
			}
		}
		if(isset($_SESSION['captcha'])) { unset($_SESSION['captcha']); }


		if($question_destination == 0) {
			// Send the email
			if($faq_email_to != '') {
				if($email_from != '') {
					if($wb->mail($email_from,$faq_email_to,$FQTEXT['FAQS'] .' - '.$FQTEXT['ASK_QUESTION'],$question,$name)) 
					{
						$success = true;
					}
				} else {
					if($wb->mail('',$faq_email_to,'FAQ - Ask a Question',$question,$name)) 
					{
						$success = true;
					}
				}
			}
			} else {
				//submit question to database
				$submitted_when = mktime();
				$query = $database->query("INSERT INTO ".TABLE_PREFIX."mod_faqmaker_submitted_questions (section_id,page_id,name,email,submitted_question,submitted_when) VALUES ('$section_id','$page_id','$name','$email_from','$question','$submitted_when')");
				if($database->is_error()) {
    $admin->print_error($database->get_error());
					$success = false;
				} else {
					$success = true;
				}	
			}
		if(isset($success) AND $success == true) {
			header('Location: '.$_SERVER['PHP_SELF'].'?success=success');
		} else {
			header('Location: '.$_SERVER['PHP_SELF'].'?fail=fail');
		}
	}
}
if(isset($_GET['success'])) {
	echo "<tr><td class='faq_mainMessage'>". $FQTEXT['THANK_YOU'] ."</td></tr>\n";
	echo "<script language='javascript' type='text/javascript'>\n";
	echo "setTimeout(\"location.href='". $_SERVER['PHP_SELF'] ."?'\", 4000);";
	echo "</script>";
}

if(isset($_GET['fail'])) {
	echo "<tr><td class='faq_mainMessage'>". $FQTEXT['SUBMISSION_ERROR'] ."</td></tr>\n";
        echo "<script language='javascript' type='text/javascript'>\n";
        echo "setTimeout(\"location.href='". $_SERVER['PHP_SELF'] ."?'\", 4000);";
        echo "</script>";
}

//Print footer
if ($footer <> "") {
	echo "<tr><td class='faq_footer'>$footer</td></tr>\n";
}

//End faq_container
echo "</table>\n";


?>
