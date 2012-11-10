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
      if (isset($_POST['activeSidebar']) && $_POST['activeSidebar'] == 'on'){
        $runPlugin->setConfigVal('sidebarTitle', 'Recherche'); 
        $runPlugin->setConfigVal('sidebarCallFunction', 'luceneDisplayField' );
      }
      else {
        $runPlugin->setConfigVal('sidebarCallFunction', '');
        $runPlugin->setConfigVal('sidebarTitle', '');
      }
      $pluginsManager->savePluginConfig($runPlugin);
      $lucene->save();
	break;
}
$lucene = new lucene();

?>
