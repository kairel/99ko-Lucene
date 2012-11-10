<?php include_once(ROOT.'admin/header.php') ?>
<?php showMsg($data['luceneMsg'], $data['luceneMsgType']); ?>
<?php
$lucene = new lucene();

//On active ou désactive le champs de recherche
    $html .= "<table>
             <form method='post' action='index.php?p=lucene&action=choice'>

             ".showAdminTokenField()."
             <tr><td>Activer la recherche</td><td>";
    
    if ($lucene->_searchValue){
        $html .= "<input type='checkbox' name = 'activeLucene' checked />";
    }
    else{
        $html .= "<input type='checkbox' name = 'activeLucene'/>";
    }

    $html .= "</td></tr><tr><td>Mettre la recherche dans la sidebar</td><td>";

    if ($runPlugin->getConfigVal('sidebarTitle') == ''){
      $html .= "<input type='checkbox' name = 'activeSidebar' />";    
    }
    else {
      $html .= "<input type='checkbox' name = 'activeSidebar' checked />";
    }

    $html .= "</td></tr></table>
             <p><input type='submit' value='Enregistrer' /></p>
         ";
    
    
    echo $html;



include_once(ROOT.'admin/footer.php') ?>