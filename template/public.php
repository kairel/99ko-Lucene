<?php
if(!defined('ROOT')) die();


?>

<?php include_once(ROOT.'theme/'.$coreConf['theme'].'/header.php') ?>
<?php
echo '<div id = "blogContent">';
$lucene = new lucene();
$html = '';


foreach($lucene->search(strtoupper(htmlentities(utf8_decode(urldecode($_GET['searchValue']))))) as $item){
    $html .= '<div class = "blogEntry">';
    $html .= '<h2><a href = "'.$item['href'].'" >'.$item['title'].'</a></h2>';
    $html .= '<div class = "blogEntryContent">'.strip_tags(substr($item['content'],0,200)).' ...</div>';
    $html .= '</div>';
}

echo $html;
echo '</div>';
?>
<?php include_once(ROOT.'theme/'.$coreConf['theme'].'/footer.php') ?>