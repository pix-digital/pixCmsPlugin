<?php
/**
 * page components.
 *
 * @package    pixCmsPlugin
 * @subpackage pixPage
 * @author     Nicolas Ricci <nr@agencepix.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class BasePixPageComponents extends sfComponents
{
    public function executeMainMenu(sfWebRequest $request)
    {

        $culture = $this->getUser()->getCulture();

        $menu_root = Doctrine::getTable('Menu')->retrieveMenuRootForCulture($this->menu_name, $culture);

        $this->menu_level1 = Doctrine::getTable('Menu')->retrieveLevel1ForRootIdAndCulture($menu_root->root_id, $culture);
    }

    /*
    * folder : dossier à parser
    */
    public function executeDiaporama(sfWebRequest $request)
    {

        $diapoConfig = sfConfig::get('app_pixPage_diaporamas');

        if(!$this->folder){
            $this->folder = $this->getDiaporamaFolder($request);
        }

        $diaporamaFolder = $this->folder;

        $media_list = array();

        if ($handle = opendir(sfConfig::get('sf_web_dir') . $diaporamaFolder)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && $file != ".svn") {
                  $media_list[] = $diaporamaFolder . '/' . $file;
                }
            }
            closedir($handle);
        }

        asort($media_list);
        
        if (count($media_list) == 0)
          die ($diaporamaFolder);

        $this->visuels = $media_list;
    }

    protected function getDiaporamaFolder(sfWebRequest $request){

        $diapoConfig = sfConfig::get('app_pixPage_diaporamas');
        $culture = $this->getUser()->getCulture();

        if(!$request->getParameter('slug')){
            $menu_root = Doctrine_Core::getTable('Menu')->retrieveMenuRootForCulture('primary', $culture);
            if(!$menu_root instanceof Menu){
                return $diapoConfig['default_folder'];
            }
            $menu = $menu_root->getNode()->getFirstChild();
        }
        else{
            $menu = Doctrine_Core::getTable('Menu')->retrieveBySlug($request->getParameter('slug'), $culture);
        }

        if(!$menu instanceof Menu){
            return $diapoConfig['default_folder'];
        }

        $page = Doctrine_Core::getTable('Page')->retrieveOneByMenuIdAndCulture($menu->id, $culture);
        if(!$page instanceof Page){
            return $diapoConfig['default_folder'];
        }

        if(empty($page->diaporama_folder) or !file_exists(sfConfig::get('sf_web_dir').$diapoConfig['base_path'].'/'.$page->diaporama_folder)){
            return $diapoConfig['default_folder'];
        }

        return $page->diaporama_folder;
    }
}
