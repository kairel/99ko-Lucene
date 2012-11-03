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

<?php include_once(ROOT.'admin/header.php') ?>
<?php showMsg($data['luceneMsg'], $data['luceneMsgType']); ?>
<?php
$lucene = new lucene();
//On active ou désactive le champs de recherche
    $html = "<table>
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



include_once(ROOT.'admin/footer.php') 

?>
