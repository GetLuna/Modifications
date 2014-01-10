##
##
##         Mod title:  Spoiler
##
##       Mod version:  1.1.0
## Works on ModernBB:  2.2.2
##      Release date:  01.10.'14
##            Author:  ModernBB Group, Visman (visman@inbox.ru)
##
##       Description:  Adds bb-BBCode [spoiler]
##
##    Repository URL:  
##
##    Affected files:  /lang/[language]/language.php
##                     /include/search_idx.php
##                     /include/parser.php
##
##        Affects DB:  No
##
##             Notes:  English
##
##        DISCLAIMER:  Please note that "mods" are not officially supported by
##                     MoernBB. Installation of this modification is done at 
##                     your own risk. Backup your forum database and any and
##                     all applicable files before proceeding.
##
##


#
#---------[ 1. OPEN ]-----------------------------------------------------------
#

/lang/[language]/language.php

#
#---------[ 2. ADD NEW ELEMENT OF ARRAY ]---------------------------------------
#

'Hidden text' => 'Hidden text',

#
#---------[ 3. SAVE ]-----------------------------------------------------------
#

/lang/[language]/language.php

#
#---------[ 4. OPEN ]-----------------------------------------------------------
#

/include/search_idx.php

#
#---------[ 5. FIND ]-----------------------------------------------------------
#

	// Remove BBCode
	$text = preg_replace('%\[/?(b|u|s|ins|del|em|i|h|colou?r|quote|code|img|url|email|list|topic|post|forum|user|left|center|right|hr|justify)(?:\=[^\]]*)?\]%', ' ', $text);

#
#---------[ 6. REPLACE WITH ]---------------------------------------------------
#

	// Remove BBCode
	$text = preg_replace('%\[/?(spoiler|b|u|s|ins|del|em|i|h|colou?r|quote|code|img|url|email|list|topic|post|forum|user|left|center|right|hr|justify)(?:\=[^\]]*)?\]%', ' ', $text);

#
#---------[ 7. SAVE ]-----------------------------------------------------------
#

/include/search_idx.php

#
#---------[ 8. OPEN ]-----------------------------------------------------------
#

/include/parser.php

#
#---------[ 9. FIND) ]----------------------------------------------------------
#

		if (preg_match('%\[/?(?:quote|code|list|h)\b[^\]]*\]%i', $text))
			$errors[] = $lang['Signature quote/code/list/h'];

#
#---------[ 10. REPLACE WITH ]--------------------------------------------------
#

		if (preg_match('%\[/?(?:spoiler|quote|code|list|h)\b[^\]]*\]%i', $text))
			$errors[] = $lang['Signature quote/code/list/h'];

#
#---------[ 11. FIND ]----------------------------------------------------------
#

	// Remove empty tags
	while (!is_null($new_text = preg_replace('%\[(b|u|s|ins|del|em|i|h|colou?r|quote|img|url|email|list|topic|post|forum|user|acronym|q|sup|sub|left|right|center|justify|video)(?:\=[^\]]*)?\]\s*\[/\1\]%', '', $text)))

#
#---------[ 12. REPLACE WITH ]--------------------------------------------------
#

	// Remove empty tags
	while (!is_null($new_text = preg_replace('%\[(spoiler|b|u|s|ins|del|em|i|h|colou?r|quote|img|url|email|list|topic|post|forum|user|acronym|q|sup|sub|left|right|center|justify|video)(?:\=[^\]]*)?\]\s*\[/\1\]%', '', $text)))

#
#---------[ 13. FIND ]----------------------------------------------------------
#

	// List of all the tags
	$tags = array('size', 'font', 'hr', 'quote', 'code', 'b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'url', 'email', 'img', 'list', '*', 'h', 'topic', 'post', 'forum', 'user', 'acronym', 'q', 'sup', 'sub', 'left', 'right', 'center', 'justify', 'video');

#
#---------[ 14. REPLACE WITH ]--------------------------------------------------
#

	// List of all the tags
	$tags = array('spoiler', 'size', 'font', 'hr', 'quote', 'code', 'b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'url', 'email', 'img', 'list', '*', 'h', 'topic', 'post', 'forum', 'user', 'acronym', 'q', 'sup', 'sub', 'left', 'right', 'center', 'justify', 'video');

#
#---------[ 15. FIND ]----------------------------------------------------------
#

	// Tags we can nest and the depth they can be nested to
	$tags_nested = array('quote' => $pun_config['o_quote_depth'], 'list' => 5, '*' => 5);

#
#---------[ 16. REPLACE WITH ]--------------------------------------------------
#

	// Tags we can nest and the depth they can be nested to
	$tags_nested = array('quote' => $pun_config['o_quote_depth'], 'list' => 5, '*' => 5, 'spoiler' => 5);

#
#---------[ 17. FIND ]----------------------------------------------------------
#

	// Block tags, block tags can only go within another block tag, they cannot be in a normal tag
	$tags_block = array('quote', 'code', 'list', 'h', '*', 'left', 'right', 'center', 'justify');

#
#---------[ 18. REPLACE WITH ]--------------------------------------------------
#

	// Block tags, block tags can only go within another block tag, they cannot be in a normal tag
	$tags_block = array('quote', 'code', 'list', 'h', '*', 'left', 'right', 'center', 'justify', 'spoiler');

#
#---------[ 19. FIND ]----------------------------------------------------------
#

	if (!$is_signature)
	{

#
#---------[ 20. BEFORE, ADD ]---------------------------------------------------
#

	if (strpos($text, '[spoiler') !== false)
	{
		$text = str_replace('[spoiler]', "</p><div class=\"quotebox\" style=\"padding: 0px;\"><div onclick=\"var e,d,c=this.parentNode,a=c.getElementsByTagName('div')[1],b=this.getElementsByTagName('span')[0];if(a.style.display!=''){while(c.parentNode&&(!d||!e||d==e)){e=d;d=(window.getComputedStyle?getComputedStyle(c, null):c.currentStyle)['backgroundColor'];if(d=='transparent'||d=='rgba(0, 0, 0, 0)')d=e;c=c.parentNode;}a.style.display='';a.style.backgroundColor=d;b.innerHTML='&#9650;';}else{a.style.display='none';b.innerHTML='&#9660;';}\" style=\"font-weight: bold; cursor: pointer; font-size: 0.9em;\"><span style=\"padding: 0 5px;\">&#9660;</span>".$lang_common['Hidden text']."</div><div style=\"padding: 6px; margin: 0; display: none;\"><p>", $text);
		$text = preg_replace('#\[spoiler=(.*?)\]#s', '</p><div class="quotebox" style="padding: 0px;"><div onclick="var e,d,c=this.parentNode,a=c.getElementsByTagName(\'div\')[1],b=this.getElementsByTagName(\'span\')[0];if(a.style.display!=\'\'){while(c.parentNode&&(!d||!e||d==e)){e=d;d=(window.getComputedStyle?getComputedStyle(c, null):c.currentStyle)[\'backgroundColor\'];if(d==\'transparent\'||d==\'rgba(0, 0, 0, 0)\')d=e;c=c.parentNode;}a.style.display=\'\';a.style.backgroundColor=d;b.innerHTML=\'&#9650;\';}else{a.style.display=\'none\';b.innerHTML=\'&#9660;\';}" style="font-weight: bold; cursor: pointer; font-size: 0.9em;"><span style="padding: 0 5px;">&#9660;</span>$1</div><div style="padding: 6px; margin: 0; display: none;"><p>', $text);
		$text = str_replace('[/spoiler]', '</p></div></div><p>', $text);
	}

#
#---------[ 21. SAVE ]----------------------------------------------------------
#

/include/parser.php

