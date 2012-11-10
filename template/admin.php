<?php include_once(ROOT.'admin/header.php') ?>
<?php showMsg($data['luceneMsg'], $data['luceneMsgType']); ?>
<?php
$lucene = new lucene();

$html = "<p>Pour utiliser ce plugin il faut copier cette ligne de commande (avec les balises php) dans le      fichier header ou footer du thème que vous utlisez: <b> echo \$lucene->getField();</b> <br />";
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
    
    $html .= "</td></tr>
             </table>
             <p><input type='submit' value='Enregistrer' /></p>
         ";
    
    
    echo $html;



include_once(ROOT.'admin/footer.php') ?>