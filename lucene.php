<?php
if(!defined('ROOT')) die();

$lucene = new lucene();
###### Installation
function luceneInstall() {}
###### code du plugin

function luceneDisplayfield(){
    global $lucene;
    $output = $lucene->getField();
    return $output;
}
/*
** prechauffage
*/
define('LUCENE_DATAPATH', ROOT.'data/plugin/lucene/');
define('LUCENE_PLUGINPATH', ROOT.'plugin/lucene/');

class lucene{
    /**
     * Recherche activée ou non
     * @var bool
     */
    protected $_searchValue = '';
    /**
     * Constructeur de la classe
     */
    public function __construct() {
       $this->_searchValue = self::isActive();
        	    
    }
    /**
     * Affecte une propriete a une variable
     * @param string $name Nom de la propriete
     * @param string $value valeur a affecter a la propriete
     */
    function __set($name, $value) {
        if (is_string($name)) {
            $this->$name = $value;
        }
    }
    /*
     * Retourne la valeur de la propriété
     */
    function __get($name){
        return $this->$name;
    }
    /**
     * Enregistre le tableau de lien dans un fichier json
     */
    function save(){
        return @file_put_contents(ROOT.'data/plugin/lucene/config.json', json_encode($this->_searchValue), 0666);
    }
    /*
     * Retourne boolean true si active
     */
    static function isActive(){
        if(is_dir(LUCENE_DATAPATH)){
        	if (file_exists(ROOT.'data/plugin/lucene/config.json')){
                    $data = json_decode(@file_get_contents(ROOT.'data/plugin/lucene/config.json'));
                        return $data;
                }
	       return false;                
        } 
        return false;
    }

    /*
     * Retourne le html du champs de recherche
     * @param $baliseO Balise ouvrante à intégrer a la div de recherche
     * @param $baliseF Balise fermante à intégrere a la div de recherche
     */
    static function getField ($baliseO = "", $balisesF = ""){
        if (self::isActive()){
            return $baliseO.'<div id = "lucene">
                    <form id="searchform" method="GET" action="?p=lucene">
                    <input type = "hidden" name ="p" value = "lucene">
		    <input type="text" value="" name="searchValue" id="searchfield" placeholder="Recherche"><input type="hidden" id="searchsubmit" value="Search">
		    </form>
                    </div>'.$baliseF;
        }
        else {
            return '';
        }
    }
    /**
     * Récupére les liens à afficher dans le sitemap.xml
     * @param string $dirname nom du repertoire
     */
    protected function _scanDir($dirname , $search){
        $data = $dataObject = array();
        if (is_dir(ROOT.'data/plugin/'.$dirname)){ 
            $dir = opendir(ROOT.'data/plugin/'.$dirname);
            $output = '';
            while($file = readdir($dir)) {
                if($file != '.' && $file != '..' && !is_dir(ROOT.'data/plugin/'.$dirname.'/'.$file) && $file != 'categories.json' && $file !='config.txt' && $file != 'index.json'){
                    $object = json_decode(@file_get_contents(ROOT.'data/plugin/'.$dirname.'/'.$file));
                    if (!is_array($object)){
                        $dataObject[] = $object;
                    }
                    else {
                        $dataObject = $object;
                        unset($object);
                    }
                    foreach ($dataObject as $key => $object){
                      if (($dirname == 'blog' && $object->published == 'on') || ($dirname == 'page' && $object->isHidden == '0') || ($dirname == 'news')){
                        //Si correspond au pattern
                        if (preg_match('/'.$search.'/',@strtoupper($object->title)) || preg_match('/'.$search.'/' ,strtoupper($object->content)) || preg_match('/'.$search.'/' ,@strtoupper($object->name)) ){
                            if (isset($object->title) && !empty($object->title)){
                                $data[$object->id]['title'] = $object->title;
                            }
                            else {   
                                $data[$object->id]['title'] = $object->name;
                            }
                            if ($dirname == 'news'){
                              $data[$object->id]['href']  = "?p=news&action=read&name=".$object->name."&id=".$object->id;
                            }
                            else {
                              $data[$object->id]['content'] = $object->content;
                              $data[$object->id]['href'] = '?p='.$dirname.'&id='.$object->id;
                            }
                        }
                      }
                    }
                }
            }
            closedir($dir);
        }
        return $data;
    }
    public function search ($search){
        return array_merge($this->_scanDir('page' , $search) , $this->_scanDir('blog' , $search),$this->_scanDir('news' , $search));
    } 
}
?>
