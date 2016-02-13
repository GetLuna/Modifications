## About
- **Name:** Spoiler
- **Version:** 2.2
- **Luna version:** 1.3, 1.4
- **Developer:** Luna Group, Visman
- **Developer website:** http://getluna.org
- **Description:** Adds the spoiler tag
- **Last release date:** 13.02.'16

## Instructions

### 1. Open

`/include/search_idx.php`

### 2. Find

```php
	// Remove BBCode
	$text = preg_replace('%\[/?(b|u|s|ins|del|em|i|h|colou?r|quote|code|img|url|email|list|thread|comment|forum|user|left|center|right|hr|justify)(?:\=[^\]]*)?\]%', ' ', $text);
```

### 3. Replace with

```php
	// Remove BBCode
	$text = preg_replace('%\[/?(b|u|s|ins|del|em|i|h|colou?r|quote|code|img|url|email|list|thread|comment|forum|user|left|center|right|hr|justify|spoiler)(?:\=[^\]]*)?\]%', ' ', $text);
```

### 4. Save

`/include/search_idx.php`

### 5. Open

`/include/parser.php`

### 6. Find

```php
		if (preg_match('%\[/?(?:quote|code|video|list|h)\b[^\]]*\]%i', $text))
			$errors[] = __('The quote, code, list, video, and heading BBCodes are not allowed in signatures.', 'luna');
```

### 7. Replace with

```php
		if (preg_match('%\[/?(?:quote|code|video|list|h|spoiler)\b[^\]]*\]%i', $text))
			$errors[] = __('The quote, code, list, video, heading, and spoiler BBCodes are not allowed in signatures.', 'luna');
```

### 8. Find

```php
	// Remove empty tags
	while (!is_null($new_text = preg_replace('%\[(b|u|s|ins|i|h|color|size|center|quote|c|img|url|email|list|sup|sub|video)(?:\=[^\]]*)?\]\s*\[/\1\]%', '', $text))) {
```

### 9. Replace with

```php
	// Remove empty tags
	while (!is_null($new_text = preg_replace('%\[(b|u|s|ins|i|h|color|size|center|quote|c|img|url|email|list|sup|sub|video|spoiler)(?:\=[^\]]*)?\]\s*\[/\1\]%', '', $text))) {
```

### 10. Find

```php
	// List of all the tags
	$tags = array('quote', 'code', 'c', 'b', 'i', 'u', 's', 'ins', 'size', 'center', 'color', 'url', 'email', 'img', 'list', '*', 'h', 'sup', 'sub', 'video');
```

### 11. Replace with

```php
	// List of all the tags
	$tags = array('quote', 'code', 'c', 'b', 'i', 'u', 's', 'ins', 'size', 'center', 'color', 'url', 'email', 'img', 'list', '*', 'h', 'sup', 'sub', 'video', 'spoiler');
```

### 12. Find

```php
	// Tags we can nest and the depth they can be nested to
	$tags_nested = array('quote' => $luna_config['o_quote_depth'], 'list' => 5, '*' => 5);
```

### 13. Replace with

```php
	// Tags we can nest and the depth they can be nested to
	$tags_nested = array('quote' => $luna_config['o_quote_depth'], 'list' => 5, '*' => 5, 'spoiler' => 5);
```

### 14. Find

```php
	// Block tags, block tags can only go within another block tag, they cannot be in a normal tag
	$tags_block = array('quote', 'code', 'list', 'h', '*');
```

### 15. Replace with

```php
	// Block tags, block tags can only go within another block tag, they cannot be in a normal tag
	$tags_block = array('quote', 'code', 'list', 'h', '*', 'spoiler');
```

### 16. Find

```php
	if (!$is_signature)
	{
```

### 17. Before, add

```php
	if (strpos($text, '[spoiler') !== false)
	{
		$text = str_replace('[spoiler]', "</p><div class=\"quotebox\" style=\"padding: 0px;\"><div onclick=\"var e,d,c=this.parentNode,a=c.getElementsByTagName('div')[1],b=this.getElementsByTagName('span')[0];if(a.style.display!=''){while(c.parentNode&&(!d||!e||d==e)){e=d;d=(window.getComputedStyle?getComputedStyle(c, null):c.currentStyle)['backgroundColor'];if(d=='transparent'||d=='rgba(0, 0, 0, 0)')d=e;c=c.parentNode;}a.style.display='';a.style.backgroundColor=d;b.innerHTML='&#9650;';}else{a.style.display='none';b.innerHTML='&#9660;';}\" style=\"font-weight: bold; cursor: pointer; font-size: 0.9em;\"><span style=\"padding: 0 5px;\">&#9660;</span>".$lang_common['Hidden text']."</div><div style=\"padding: 6px; margin: 0; display: none;\"><p>", $text);
		$text = preg_replace('#\[spoiler=(.*?)\]#s', '</p><div class="quotebox" style="padding: 0px;"><div onclick="var e,d,c=this.parentNode,a=c.getElementsByTagName(\'div\')[1],b=this.getElementsByTagName(\'span\')[0];if(a.style.display!=\'\'){while(c.parentNode&&(!d||!e||d==e)){e=d;d=(window.getComputedStyle?getComputedStyle(c, null):c.currentStyle)[\'backgroundColor\'];if(d==\'transparent\'||d==\'rgba(0, 0, 0, 0)\')d=e;c=c.parentNode;}a.style.display=\'\';a.style.backgroundColor=d;b.innerHTML=\'&#9650;\';}else{a.style.display=\'none\';b.innerHTML=\'&#9660;\';}" style="font-weight: bold; cursor: pointer; font-size: 0.9em;"><span style="padding: 0 5px;">&#9660;</span>$1</div><div style="padding: 6px; margin: 0; display: none;"><p>', $text);
		$text = str_replace('[/spoiler]', '</p></div></div><p>', $text);
	}
```

### 18. Save

`/include/parser.php`

## Translation instructions

For translations to be in effect, you'll need to regenerate the language files from Luna with Poedit.
