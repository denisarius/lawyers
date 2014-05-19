<?php

$text = get_content();
if ($text === false)
	show_content_404();
else
{
	global $_o;
	set_page_title($text['title']);
	echo <<<stop
		{$text['content']}
stop;
}
?>