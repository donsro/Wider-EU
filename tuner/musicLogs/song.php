<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php
$html = "";
$url = "Song.xml";
$xml = simplexml_load_file($url);
for($i = 0; $i < 10; $i++){
$title = $xml->channel->item[$i]->title;
$html .= "$title";
}
echo $html;
?>
</body>
</html>