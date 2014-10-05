<?php

/**
 * Copyright (C) 2013-2014 ModernBB Group
 * Based on code by FluxBB copyright (C) 2008-2012 FluxBB
 * Based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 * Licensed under GPLv3 (http://modernbb.be/license.php)
 */
 
// The forum from which we'll pull the news bits
$forum_id = 1;

// Number of news posts to include in the index
$num_posts_index = 1;

// Path to template from FluxBB root (must begin with a slash)
$news_template_path = FORUM_ROOT.'plugins/AP_News_Generator/news.tpl';

// Directory in which plugin will save generated markup from Fluxbb root (must begin & end with slash)
$output_dir_latest = FORUM_ROOT;

//error_reporting(E_ALL | E_STRICT);

// Make sure no one attempts to run this script "directly"
if (!defined('FORUM'))
	exit;
	
// Load the admin_news_generator.php language file
if (file_exists(FORUM_ROOT.'lang/'.$admin_language.'/admin_news_generator.php'))
	require FORUM_ROOT.'lang/'.$admin_language.'/admin_news_generator.php';
else
	require FORUM_ROOT.'lang/English/admin_news_generator.php';

// Load parser, needed for generating output
require FORUM_ROOT.'include/parser.php';

// Tell admin_loader.php that this is indeed a plugin and that it is loaded
define('FORUM_PLUGIN_LOADED', 1);

//
// Crop post message if set to be smaller
//	
function crop_message($text, $length)
{
	// if we crop text then parse it so we don't cut tags and leave them without closed tags
	// NOTE: couldn't find fluxbb function that does this for me so I made my own
	$cur_length = strlen($text);
	if ($length != 0 && $length < $cur_length)
	{
		$regex_tags = "(\bquote\b|\bcode\b|\bb\b|\bi\b|\bu\b|\bs\b|\bins\b|\bdel\b|\bem\b|\bcolor\b|\bcolour\b|\burl\b|\bemail\b|\bimg\b|\blist\b|\bh\b)";
		$regex_tags_special = "\*";
		$regex_tags_count = array("\bquote\b" => 0, "\bcode\b" => 0, "\bb\b" => 0, "\bi\b" => 0, "\bu\b" => 0, 
		"\bs\b" => 0, "\bins\b" => 0, "\bdel\b" => 0, "\bem\b" => 0, "\bcolor\b" => 0, 
		"\bcolour\b" => 0, "\burl\b" => 0, "\bemail\b" => 0, "\bimg\b" => 0, "\*" => 0, 
		"\blist\b" => 0, "\bh\b" => 0);
		
		// first find all the tags and count them so we know where they are before cropping
		if(preg_match_all('/\[\/?'.$regex_tags.'=*[^\]]*\]/', $text, $matches, PREG_OFFSET_CAPTURE))
		{
			// NOTE: can't figure out how to avoid extra regex search without making the array messy 8(
			if(preg_match_all('/\[\/?'.$regex_tags_special.'\]/', $text, $matches2, PREG_OFFSET_CAPTURE)); {
				$count = count($matches[0])+1;
				foreach ($matches2 as $key => $row) {
					foreach ($row as $key2 => $row2) {
						foreach ($row2 as $key3 => $match) {
							$matches[0][$count][] = $match;
						}
						$count++;
					}
				}
			}
			
			// count tag lengths and their position range
			$count = 0;
			$total_tag_length = 0;
			$tag_ranges = array(array());
			foreach ($matches as $key => $row) {
				foreach ($row as $key2 => $row2) {
					foreach ($row2 as $key3 => $match) {
						if(is_int($match)) {
							$match_length = strlen($matches[0][$key2][0]);
							$total_tag_length += $match_length;
							$tag_ranges[$count][0] = $match;
							$tag_ranges[$count][1] = $match + $match_length;
						}
					}
					$count++;
				}
			}
			
			// crop precisely by avoiding tag cuts
			$max_length = $length + $total_tag_length; // don't count tags as character limit
			foreach ($tag_ranges as $key => $row) {
				if (($max_length > $tag_ranges[$key][0]) && ($max_length < $tag_ranges[$key][1])) {
					$max_length = $tag_ranges[$key][1]; // crop after the tag
				}
			}
			$text = substr($text, 0, $max_length)." ...";
			
			// count tags by doing a regex search yet again so we can figure out what tags to close
			foreach ($regex_tags_count as $reg => &$rcount) {
				if ($reg != "\*") {
					if (preg_match_all('%\[\/?'.$reg.'=*[^\]]*\]%', $text, $matches)) {
						$rcount=count($matches[0]);
					}
				}
				else {
					if (preg_match_all('%\[\/?'.$reg.'\]%', $text, $matches)) {
						$rcount=count($matches[0]);
					}
				}
			}
			unset($rcount);
			
			// finally close tags we may have accidently opened up
			foreach ($regex_tags_count as $reg => $rcount) {
				if ($rcount%2) { // close odd numbered tag counts
					if ($reg != "\*") $text .= "[/".substr($reg,2,(strlen($reg)-4))."]";
					else $text .= "[/*]";
				}
			}
			$text = strip_empty_bbcode($text, $errors); // incase we closed one that had nothing in it >.>
		}
	}
	return $text;
}

if (isset($_POST['gen_news']))
{	
	// take value out of POST and check if values are good
	$topic_id = (int)$_POST['get_tid'];
	$forum_id = (int)$_POST['get_fid'];
	$num_posts_index = (int)$_POST['get_pnum'];
	$post_maxlength = (int)$_POST['get_pmaxlength'];
	// make sure path values are in the proper format
	if(preg_match("%^[-./_a-zA-Z0-9]+$%",$_POST['get_ntpath'])) $news_template_path = $_POST['get_ntpath'];
	else message($lang_admin_news_generator['bad template_path value']);
	if(preg_match("%^[-./_a-zA-Z0-9]+$%",$_POST['get_odir_latest'])) $output_dir_latest = $_POST['get_odir_latest'];
	else message($lang_admin_news_generator['bad output_dir_latest value']);
	
	// used so I can define different templates to create different html files
	$template_name = substr($news_template_path, strrpos($news_template_path, '/') + 1, (strlen($news_template_path) - (strrpos($news_template_path, '/') + 1)) - 4);
	
	// Generate front page news if set to do so
	if ($topic_id != 0)
		$result = $db->query('SELECT id, subject, num_views, num_replies FROM '.$db->prefix.'topics WHERE forum_id='.$forum_id.' AND id='.$topic_id.' ORDER BY sticky DESC, posted DESC LIMIT 0, '.$num_posts_index) or error($lang_admin_news_generator['error topic list'], __FILE__, __LINE__, $db->error());
	else
		$result = $db->query('SELECT id, subject, num_views, num_replies FROM '.$db->prefix.'topics WHERE forum_id='.$forum_id.' ORDER BY sticky DESC, posted DESC LIMIT 0, '.$num_posts_index) or error($lang_admin_news_generator['error topic list'], __FILE__, __LINE__, $db->error());
	if (!$db->num_rows($result))
		message(sprintf($lang_admin_news_generator['no topics'], $forum_id));

	// open template file
	$news_tpl = file_get_contents($news_template_path) or error(sprintf($lang_admin_news_generator['error opening template'], $news_template_path), __FILE__, __LINE__);
	$fh = @fopen($output_dir_latest.$template_name.'.html', 'wb');
	if (!$fh) 
		error(sprintf($lang_admin_news_generator['error write'], $output_dir_latest), __FILE__, __LINE__);
	
	// start parsing
	while ($cur_topic = $db->fetch_assoc($result))
	{
		if ($topic_id == 0 || $num_posts_index != 1) 
			$topic_id = $cur_topic['id'];
		$result2 = $db->query('SELECT posted, poster, message, hide_smilies FROM '.$db->prefix.'posts WHERE topic_id='.$topic_id.' ORDER BY posted ASC LIMIT 1') or error($lang_admin_news_generator['error topic list'], __FILE__, __LINE__, $db->error());
		$cur_post = $db->fetch_assoc($result2);
		$message = crop_message($cur_post['message'], $post_maxlength);
		if($message != $cur_post['message']) $message .= "\n\r\n\r[url=".luna_htmlspecialchars($luna_config['o_base_url']).'/viewtopic.php?id='.$topic_id.']'.$lang_admin_news_generator['read more'].'[/url]';
		//$first_post = $message; // used for debugging purposes
		$search = array('<news_subject>', '<news_posted>', '<news_poster>', '<news_message>', '<news_comments>', '<news_replies>', '<news_views>');
		$replace = array('<a href="'.luna_htmlspecialchars($luna_config['o_base_url']).'/viewtopic.php?id='.$topic_id.'">'.luna_htmlspecialchars($cur_topic['subject']).'</a>', format_time($cur_post['posted']), luna_htmlspecialchars($cur_post['poster']), parse_message($message, $cur_post['hide_smilies']), '<a href="'.luna_htmlspecialchars($luna_config['o_base_url']).'/viewtopic.php?id='.$topic_id.'">'.$lang_admin_news_generator['comments'].'</a>', forum_number_format($cur_topic['num_replies']), forum_number_format($cur_topic['num_views']));
		array_push($search, '<lang_posted_by>', '<lang_on>', '<lang_views>');
		array_push($replace, $lang_admin_news_generator['posted by'], $lang_admin_news_generator['on'], $lang_admin_news_generator['views']);
		fwrite($fh, str_replace($search, $replace, $news_tpl));
	}
	fclose($fh);

	generate_admin_menu($plugin);
?>
	<div class="block">
		<h2><span><?php echo $lang_admin_news_generator['results title'] ?></span></h2>
		<div class="box">
			<div class="inbox">
				<p><?php echo $lang_admin_news_generator['results message'] ?></p>
				<p><?php echo '<a href="'.luna_htmlspecialchars($luna_config['o_base_url']).substr($output_dir_latest, 1).$template_name.'.html">'.sprintf($lang_admin_news_generator['view'], $template_name.'.html').'</a>' ?></p>
				<!--<textarea name="req_message" rows="20" cols="95" tabindex="2"><?php //echo $first_post ?></textarea>-->
			</div>
		</div>
	</div>
<?php

}
else
{
	generate_admin_menu($plugin);

?>
<h2><?php echo $lang_admin_news_generator['plugin title'] ?></h2>
<form class="form-horizontal" id="news" method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>&amp;foo=bar">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Title<span class="pull-right"><input class="btn btn-primary" type="submit" name="gen_news" value="<?php echo $lang_admin_news_generator['submit text'] ?>" /></span></h3>
        </div>
        <div class="panel-body">
            <fieldset>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $lang_admin_news_generator['input forum id'] ?></label>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" name="get_fid" size="3" value="<?php echo $forum_id ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $lang_admin_news_generator['input post length explanation'] ?></label>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" name="get_pmaxlength" size="10" />
                    </div>
                </div>
                <hr />
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $lang_admin_news_generator['input number posts'] ?></label>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" name="get_pnum" size="3" value="<?php echo $num_posts_index ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $lang_admin_news_generator['input news template'] ?></label>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" name="get_ntpath" size="50" value="<?php echo $news_template_path ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $lang_admin_news_generator['input directory latest'] ?></label>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" name="get_odir_latest" size="50" value="<?php echo $output_dir_latest ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo $lang_admin_news_generator['input topic id'] ?><span class="help-block"><?php echo $lang_admin_news_generator['input topic id explanation'] ?></span></label>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" name="get_tid" size="10" />
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</form>
<?php

}

require FORUM_ROOT.'backstage/footer.php';
