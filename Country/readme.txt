##
##
##         Mod title:  Country
##
##       Mod version:  1.0.0
## Works on ModernBB:  3.5
##      Release date:  27.09.'14
##            Author:  Cyboulette (cyboulette58@hotmail.fr)
##
##       Description:  Adds country information
##
##    Repository URL:  
##
##    Affected files:  /lang/[language]/language.php
##					   /style/Core/templates/views/register-form.tpl.php
##					   /style/Core/templates/views/profile-settings.tpl.php
##                     /viewtopic.php
##                     /register.php
##					   /profile.php
##					   /include/functions.php
##					   
##
##        Affects DB:  Yes
##        			   ALTER TABLE `[prefix]_users` ADD `country`  VARCHAR(50) NOT NULL AFTER `signature`;
##
##
##             Notes:  English
##
##        DISCLAIMER:  Please note that "mods" are not officially supported by
##                     ModernBB. Installation of this modification is done at 
##                     your own risk. Backup your forum database and any and
##                     all applicable files before proceeding.
##
##

#
#---------[ 0. INFORMATION ]-----------------------------------------------------------
#

Upload the file countries.csv to the root of the forum.
Make sure you have update DataBase with : ALTER TABLE `[prefix]_users` ADD `country`  VARCHAR(50) NOT NULL AFTER `signature`;

#
#---------[ 1. OPEN ]-----------------------------------------------------------
#

/lang/[language]/language.php

#
#---------[ 2. ADD NEW ELEMENT OF ARRAY ]---------------------------------------
#

'Country'	=>	'Pays', //Or the traduction of your lang

#
#---------[ 3. SAVE ]-----------------------------------------------------------
#

/lang/[language]/language.php

#
#---------[ 4. OPEN ]-----------------------------------------------------------
#

/style/Core/templates/views/register-form.tpl.php

#
#---------[ 5. FIND ]-----------------------------------------------------------
#

	</fieldset>
	
#
#---------[ 6. ADD BEFORE ]---------------------------------------------------
#

	<div class="form-group">
		<label class="col-sm-3 control-label"><?php echo $lang['Country'] ?></label>
		<div class="col-sm-9">
			<select class="form-control" name="country">
				<?php
				$countries = lireFichierCSV(FORUM_ROOT."countries.csv");
				foreach ($countries as $temp)
				{
					echo "\t\t\t\t\t\t\t\t".'<option value="'.$temp.'">'.$temp.'</option>'."\n";
				}
				?>
			</select>
		</div>
	</div>

#
#---------[ 7. SAVE ]-----------------------------------------------------------
#

/style/Core/templates/views/register-form.tpl.php

#
#---------[ 8. OPEN ]-----------------------------------------------------------
#

/style/Core/templates/views/profile-settings.tpl.php

#
#---------[ 9. FIND ]----------------------------------------------------------
#

		<?php
			}
			$styles = forum_list_styles();

#
#---------[ 10. REPLACE WITH ]--------------------------------------------------
#

<?php
    }
?>
	<div class="form-group">
		<label class="col-sm-3 control-label"><?php echo $lang['Country'] ?></label>
		<div class="col-sm-9">
			<select class="form-control" name="form[country]">
				<?php
					$countries = lireFichierCSV(FORUM_ROOT."countries.csv");
					foreach ($countries as $temp)
					{
						if ($user['country'] == $temp)
							echo "\t\t\t\t\t\t\t\t".'<option value="'.$temp.'" selected="selected">'.$temp.'</option>'."\n";
						else
							echo "\t\t\t\t\t\t\t\t".'<option value="'.$temp.'">'.$temp.'</option>'."\n";
					}
				?>
			</select>
		</div>
	</div>
	<?php
		$styles = forum_list_styles();

#
#---------[ 11. SAVE ]----------------------------------------------------------
#

/style/Core/templates/views/profile-settings.tpl.php

#
#---------[ 12. OPEN ]-----------------------------------------------------------
#

/viewtopic.php

#
#---------[ 13. FIND ]-----------------------------------------------------------
#

// Retrieve the posts (and their respective poster/online status)
$result = $db->query('SELECT u.email, u.title, u.url, u.location, u.signature, u.email_setting, u.num_posts, u.registered, u.admin_note, p.id, p.poster AS username, p.poster_id, p.poster_ip, p.poster_email, p.message, p.hide_smilies, p.posted, p.edited, p.edited_by, p.marked, g.g_id, g.g_user_title, o.user_id AS is_online FROM '.$db->prefix.'posts AS p INNER JOIN '.$db->prefix.'users AS u ON u.id=p.poster_id INNER JOIN '.$db->prefix.'groups AS g ON g.g_id=u.group_id LEFT JOIN '.$db->prefix.'online AS o ON (o.user_id=u.id AND o.user_id!=1 AND o.idle=0) WHERE p.id IN ('.implode(',', $post_ids).') ORDER BY p.id', true) or error('Unable to fetch post info', __FILE__, __LINE__, $db->error());

#
#---------[ 14. REPLACE WITH ]-----------------------------------------------------------
#

// Retrieve the posts (and their respective poster/online status)
$result = $db->query('SELECT u.email, u.title, u.url, u.location, u.signature, u.country, u.email_setting, u.num_posts, u.registered, u.admin_note, p.id, p.poster AS username, p.poster_id, p.poster_ip, p.poster_email, p.message, p.hide_smilies, p.posted, p.edited, p.edited_by, p.marked, g.g_id, g.g_user_title, o.user_id AS is_online FROM '.$db->prefix.'posts AS p INNER JOIN '.$db->prefix.'users AS u ON u.id=p.poster_id INNER JOIN '.$db->prefix.'groups AS g ON g.g_id=u.group_id LEFT JOIN '.$db->prefix.'online AS o ON (o.user_id=u.id AND o.user_id!=1 AND o.idle=0) WHERE p.id IN ('.implode(',', $post_ids).') ORDER BY p.id', true) or error('Unable to fetch post info', __FILE__, __LINE__, $db->error());

#
#---------[ 15. FIND ]-----------------------------------------------------------
#

if ($luna_config['o_show_post_count'] == '1' || $luna_user['is_admmod'])
				$user_info[] = '<dd><span>'.$lang['Posts'].' '.forum_number_format($cur_post['num_posts']).'</span></dd>';

#
#---------[ 16. ADD AFTER ]-----------------------------------------------------------
#

$user_info[] = '<dd><span>'.$lang['Country'].' : '.luna_htmlspecialchars($cur_post['country']).'</span></dd>';

#
#---------[ 17. SAVE ]----------------------------------------------------------
#

/viewtopic.php

#
#---------[ 18. OPEN ]----------------------------------------------------------
#

/register.php

#
#---------[ 19. FIND ]----------------------------------------------------------
#

	else
		$language = $luna_config['o_default_lang'];

#
#---------[ 20. ADD AFTER ]----------------------------------------------------------
#

	if(isset($_POST['country'])) {
		$country = preg_replace('%[\.\\\/]%', '', $_POST['language']);
	} else {
		$country = "France"; //Or other pays in the list
	}

#
#---------[ 21. FIND ]----------------------------------------------------------
#

	// Add the user
	$db->query('INSERT INTO '.$db->prefix.'users (username, group_id, password, email, language, style, registered, registration_ip, last_visit) VALUES(\''.$db->escape($username).'\', '.$intial_group_id.', \''.$password_hash.'\', \''.$db->escape($email1).'\', \''.$db->escape($language).'\', \''.$luna_config['o_default_style'].'\', '.$now.', \''.$db->escape(get_remote_address()).'\', '.$now.')') or error('Unable to create user', __FILE__, __LINE__, $db->error());
	
#
#---------[ 22. REPLACE WITH ]----------------------------------------------------------
#

	// Add the user
	$db->query('INSERT INTO '.$db->prefix.'users (username, group_id, password, email, language, style, registered, registration_ip, last_visit, country) VALUES(\''.$db->escape($username).'\', '.$intial_group_id.', \''.$password_hash.'\', \''.$db->escape($email1).'\', \''.$db->escape($language).'\', \''.$luna_config['o_default_style'].'\', '.$now.', \''.$db->escape(get_remote_address()).'\', \''.$now.'\', \''.$country.'\')') or error('Unable to create user', __FILE__, __LINE__, $db->error());


#
#---------[ 23. SAVE ]----------------------------------------------------------
#

/register.php

#
#---------[ 24. OPEN ]----------------------------------------------------------
#

/profile.php

#
#---------[ 25. FIND ]----------------------------------------------------------
#

	// Make sure we got a valid language string
	if (isset($_POST['form']['language']))
	{
		$languages = forum_list_langs();
		$form['language'] = luna_trim($_POST['form']['language']);
		if (!in_array($form['language'], $languages))
			message($lang['Bad request'], false, '404 Not Found');
	}

#
#---------[ 26. ADD AFTER ]----------------------------------------------------------
#

	// Make sure we got a valid countrie string
	if (isset($_POST['form']['country']))
	{
		$countries = lireFichierCSV(FORUM_ROOT."countries.csv");
		$form['country'] = luna_trim($_POST['form']['country']);
		if (!in_array($form['country'], $countries))
			message($lang['Bad request'], false, '404 Not Found');
	}

#
#---------[ 27. FIND ]----------------------------------------------------------
#

$result = $db->query('SELECT u.username, u.email, u.title, u.realname, u.url, u.facebook, u.msn, u.twitter, u.google, u.location, u.signature, u.disp_topics, u.disp_posts, u.email_setting, u.notify_with_post, u.auto_notify, u.show_smilies, u.show_img, u.show_img_sig, u.show_avatars, u.show_sig, u.timezone, u.dst, u.language, u.style, u.backstage_color, u.num_posts, u.last_post, u.registered, u.registration_ip, u.admin_note, u.date_format, u.time_format, u.last_visit, g.g_id, g.g_user_title, g.g_moderator FROM '.$db->prefix.'users AS u LEFT JOIN '.$db->prefix.'groups AS g ON g.g_id=u.group_id WHERE u.id='.$id) or error('Unable to fetch user info', __FILE__, __LINE__, $db->error());


#
#---------[ 28. REPLACE WITH ]----------------------------------------------------------
#

$result = $db->query('SELECT u.username, u.email, u.title, u.realname, u.url, u.facebook, u.msn, u.twitter, u.google, u.location, u.signature, u.country, u.disp_topics, u.disp_posts, u.email_setting, u.notify_with_post, u.auto_notify, u.show_smilies, u.show_img, u.show_img_sig, u.show_avatars, u.show_sig, u.timezone, u.dst, u.language, u.style, u.backstage_color, u.num_posts, u.last_post, u.registered, u.registration_ip, u.admin_note, u.date_format, u.time_format, u.last_visit, g.g_id, g.g_user_title, g.g_moderator FROM '.$db->prefix.'users AS u LEFT JOIN '.$db->prefix.'groups AS g ON g.g_id=u.group_id WHERE u.id='.$id) or error('Unable to fetch user info', __FILE__, __LINE__, $db->error());


#
#---------[ 28. SAVE ]----------------------------------------------------------
#

/profile.php

#
#---------[ 29. OPEN ]----------------------------------------------------------
#

/include/functions.php

#
#---------[ 30. ADD AT THE END OF FILE ]----------------------------------------------------------
#

//Countries
if(!function_exists("lireFichierCSV")){
 function lireFichierCSV($nomFichier, $separateur=";"){
  // Initialisation du tableau qui sera retourné
  $tableauDonnees = array();

  // Ouverture du fichier csv
  $fichier = fopen($nomFichier, "r");

  // On parcourt chaque valeur du fichier CSV
  while($donneesLigne = fgetcsv($fichier, 0, $separateur)) {

   // Si on a des données
   if(count($donneesLigne) > 0) {
    // Comptage du nombre de colonnes dans cette ligne
    $nbColonnes = count($donneesLigne);

    // Pour chaque donnée dans la ligne
    for($i=0;$i<$nbColonnes;$i++){
     // Stockage de la donnée dans les données de la ligne
     $tableauDonnees[] = $donneesLigne[$i];
    }
   }
  }

  // Fermeture du fichier
  fclose($fichier);

  // Retour du tableau des données
  return $tableauDonnees;
 }
}

#
#---------[ 28. SAVE ]----------------------------------------------------------
#

/include/functions.php