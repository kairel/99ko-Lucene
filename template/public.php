<?php
if(!defined('ROOT')) die();

$runPlugin->setMainTitle('');
$runPlugin->initBreadcrumb();
?>

<?php include_once(ROOT.'theme/'.$coreConf['theme'].'/header.php') ?>
<?php
echo '<div id = "blogContent">';
$lucene = new lucene();
$html = '';

$nb_result = 0;
foreach($lucene->search(strtoupper(htmlentities(utf8_decode(urldecode($_GET['searchValue']))))) as $item){
    $html .= '<div class = "blogEntry">';
    $html .= '<h2><a href = "'.$item['href'].'" >'.$item['title'].'</a></h2>';
    $html .= '<div class = "blogEntryContent">'.strip_tags(substr($item['content'],0,200)).' ...</div>';
    $html .= '</div>';
    $nb_result++;
}
if ($nb_result > 0){
	$result_html = "<p id = 'search_text'>".$nb_result." r&#233;sultat(s) &#225; votre recherche</p>";
}
else {
	$result_html = "<p id = 'search_text'>Aucun r&#233;sultat.</p>";
}
echo $result_html.' '.$html;
echo '</div>';
?>
<?php include_once(ROOT.'theme/'.$coreConf['theme'].'/footer.php') ?>