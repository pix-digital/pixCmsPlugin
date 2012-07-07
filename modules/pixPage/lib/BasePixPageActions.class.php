<?php

/**
 * page actions.
 *
 * @package    pixCmsPlugin
 * @subpackage pixPage
 * @author     Nicolas Ricci <nr@agencepix.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class BasePixPageActions extends sfActions
{

    public function executeIndex(sfWebRequest $request)
    {
        $this->redirect('@home_i18n?sf_culture=' . $this->getCulture($request));
    }

    protected function getCulture(sfWebRequest $request)
    {
        // add some logic here if needed
        return $request->getPreferredCulture(array_keys(sfConfig::get('app_pixPage_enabled_cultures')));
    }


    public function executeShow(sfWebRequest $request)
    {
        $culture = $this->getUser()->getCulture();

        if(!$request->getParameter('slug')){
            $menu_root = Doctrine_Core::getTable('Menu')->retrieveMenuRootForCulture('primary', $culture);
            $this->forward404Unless($menu_root instanceof Menu);

            $menu = $menu_root->getNode()->getFirstChild();
            $this->forward404Unless($menu instanceof Menu);
        }
        else{
            $menu = Doctrine_Core::getTable('Menu')->retrieveBySlug($request->getParameter('slug'), $culture);
            $this->forward404Unless($menu instanceof Menu);
        }

        $page = Doctrine_Core::getTable('Page')->retrieveOneByMenuIdAndCulture($menu->id, $culture);
        $this->forward404Unless($page instanceof Page);

        if($page->template){
            $this->setTemplate($page->template);
        }

        // metas
        $this->loadMetas(array(
					'title' => $page->meta_title ,
					'description' => $page->meta_description,
					'keywords' => $page->meta_keywords
				));

        // send variables to template
        $this->page = $page;
        $this->menu = $menu;

    }
    
    public function executeError404(sfWebRequest $request)
    {
    }

    public function executeSwitchLanguage(sfWebRequest $request)
    {

        $culture = $this->getUser()->getCulture();
        // culture has changed
        if (in_array($request->getParameter('l'), array_keys(sfConfig::get('app_pixPage_enabled_cultures')))) {
            $lang = $request->getParameter('l');
            $url = parse_url($request->getReferer());
            $path = explode('/', $url['path']);
            $slug = end($path);
            $menu = Doctrine_Core::getTable('Menu')->retrieveBySlug($slug, $culture);
            // in case referer does not exist
            if (!$menu instanceof Menu) {
                return $this->redirect('@home_i18n?sf_culture='.$lang);
            }

            // get the translated menu
            $translated_menu = Doctrine_Core::getTable('Menu')->retrieveOneByIdAndCulture($menu->id, $lang);
            if (!$translated_menu instanceof Menu) {
                return $this->redirect('@home_i18n?sf_culture='.$lang);
            }

            $this->redirect('@pix_page?sf_culture='.$lang.'&slug='. $translated_menu->Translation[$lang]->slug);
        }

        // in case the language does not exist
        return $this->redirect('@homepage');

    }

    /*public function executeHomepage(sfWebRequest $request){

        $site = Doctrine_Core::getTable('Site')->findOneBySlug($request->getParameter('site'));

        $this->setLayout($site->layout);

    }*/

    protected function loadMetas($params = array()){

        if(empty($params)){
			return;
		}

		$response = $this->getResponse();

		if(array_key_exists('title', $params)){
			$response->setTitle(sfConfig::get('app_pixPage_metas_default_title').$params['title']);
		}

		if(array_key_exists('description', $params)){
			$response->addMeta('description', sfConfig::get('app_pixPage_metas_default_description').$params['description']);
		}

		if(array_key_exists('keywords', $params)){
			$response->addMeta('keywords', sfConfig::get('app_pixPage_metas_default_keywords').strtolower($params['keywords']));
		}
	}
}
