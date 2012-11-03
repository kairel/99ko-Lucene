<?php
if(!defined('ROOT')) die();

###### fonctions necessaires

/*
** fonction chargee de retourner la configuration par defaut du plugin (obligatoire)
** return : array
*/
function luceneConfig(){
	return array(
		'priority' => 5,
		'activate' => 1,
	);
}
/*
** fonction chargee de retourner les infos du plugin (obligatoire)
** return : array
*/
function luceneInfos(){
	return array(
		'name' => 'Lucene',
		'description' => 'Permet la recherche sur les pages et les billets de blog du site',
		'author' => 'Michael Alves Lobo',
		'authorEmail' => 'alveslobo.michael@gmail.com',
		'authorWebsite' => 'http://kairel.fr',
		'version' => '1.0',
	);
}
/*
** fonction chargee de retourner les hooks utilises par le plugin (obligatoire)
** return : array
*/
function luceneHooks(){
	return array();	
}
###### code du plugin

/*
** prechauffage
*/
define('LUCENE_DATAPATH', ROOT.'data/plugin/lucene/');
define('LUCENE_PLUGINPATH', ROOT.'plugin/lucene/');
$lucene = new lucene();

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
    static function getField ($baliseO , $balisesF){
        if (self::isActive()){
            return $baliseO.'<h2>Rechercher</h2>
                    <div id = "lucene">
                    <form id="searchform" method="GET" action="?p=lucene">
                    <input type = "hidden" name ="p" value = "lucene">
		    <input type="text" value="" name="searchValue" id="searchfield"><input type="hidden" id="searchsubmit" value="Search">
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
        $dir = opendir(ROOT.'data/plugin/'.$dirname); 
        $data = array();
        $output = '';
        while($file = readdir($dir)) {
            if($file != '.' && $file != '..' && !is_dir(ROOT.'data/plugin/'.$dirname.'/'.$file) && $file != 'categories.json' && $file !='config.txt' && $file != 'index.json'){
                $object = json_decode(@file_get_contents(ROOT.'data/plugin/'.$dirname.'/'.$file));
                //Ne recherche que dans les contenus publiés ou visibles
                if (($dirname == 'blog' && $object->published == 'on') || ($dirname == 'page' && $object->isHidden == '0')){
                    //Si correspond au pattern
                    if (preg_match('/'.$search.'/',@strtoupper($object->title)) || preg_match('/'.$search.'/' ,strtoupper($object->content)) || preg_match('/'.$search.'/' ,@strtoupper($object->name)) ){
                        if (isset($object->title) && !empty($object->title)){
                            $data[$object->id]['title'] = $object->title;
                        }
                        else {   
                            $data[$object->id]['title'] = $object->name;
                        }
                        $data[$object->id]['content'] = $object->content;
                        $data[$object->id]['href'] = '?p='.$dirname.'&id='.$object->id;
                    }
                }
            }
        }
        closedir($dir);
        return $data;
    }
    public function search ($search){
        return array_merge($this->_scanDir('page' , $search) , $this->_scanDir('blog' , $search));
    } 
}

?>
