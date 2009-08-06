<?php

/******************************************************************************
Twitter Trending Topics Crossref with Digg

The idea of this little app is to take the current trending topics on Twitter
and run a search for each on Digg. In theory the result provides the user
deeper info about whatever Twitter is buzzing about at the moment.

The output is simple HTML.  Each topic is printed, and then a listing of the
Digg article topics is printed with a link to each story.  Simple.


Written By: 	Jeremy D. McCarthy
Date:			2009/08/06
******************************************************************************/

//digg code
function searchDigg($query){
	$baseurl = 'http://services.digg.com/search/stories';
	$count = 5;
	$appkey = 'http://jmccarthy.dyndns.org';

	ini_set('user_agent', 'php');
	return simplexml_load_file("$baseurl?query=\"$query\"&count=$count&appkey=$appkey");
}

//twitter code
function getTwitterTopics(){
	return json_decode(file_get_contents('http://search.twitter.com/trends/current.json?exclude=hashtags'), true);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>
<head>
	<title>Digg Results from Twitter Trends</title>
</head>

<body>
<h1>Twitter Trends/Digg Stories</h1>
<p>The following is a listing of the current Twitter trending topics. The links below each topic are the most popular Digg stories related to that topic. Hopefully this provides you with some interesting background information on what people are buzzing about on Twitter at any given moment.</p>

<?php
$twitter = getTwitterTopics();

foreach($twitter[trends] as $value){
	foreach($value as $topic){
		echo "<h2>$topic[name]</h2>\n<ul>\n";
		$digg = searchDigg($topic[name]);
		foreach($digg->story as $story){
			echo "\t<li><a href=\"$story[link]\">$story->title</a></li>\n";
		}
		echo "</ul>\n";
	}
}
?>

</body>
</html>
