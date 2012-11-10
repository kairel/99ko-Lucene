<?php
if(!defined('ROOT')) die();
$data['luceneMode'] = 'displayList';
$data['luceneMsg'] = '';
$data['luceneMsgType'] = '';

switch(ACTION){
	case 'choice':
            $lucene = new lucene();
            if (isset($_POST['activeLucene']) && $_POST['activeLucene'] == 'on'){
                $lucene->_searchValue = true;   
            }
            else {
                $lucene->_searchValue = false;
            }
            $lucene->save();
		break;
	
}
$lucene = new lucene();

?>
