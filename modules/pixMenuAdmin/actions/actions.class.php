<?php

require_once dirname(__FILE__) . '/../lib/pixMenuAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/pixMenuAdminGeneratorHelper.class.php';

/**
 * menu admin actions.
 *
 * @package    pixCmsPlugin
 * @subpackage pixMenuAdmin
 * @author     Nicolas Ricci <nr@agencepix.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class pixMenuAdminActions extends autopixMenuAdminActions
{

    public function executeIndex(sfWebRequest $request)
    {
        // sorting
        if ($request->getParameter('sort') && $this->isValidSortColumn($request->getParameter('sort'))) {
            $this->setSort(array($request->getParameter('sort'), $request->getParameter('sort_type')));
        }

        // pager
        if ($request->getParameter('page')) {
            $this->setPage($request->getParameter('page'));
        }

        // category in tree
        $menu_table = Doctrine::getTable('Menu');

        $root_menu = $menu_table->getTree()->fetchRoot();

        if (!$root_menu) {
            $root_menu = new Menu;
            $root_menu->setLabel('Categories');
            $root_menu->setRootId(1);
            $menu_table->getTree()->createRoot($root_menu);
        }

        $this->root_menu = $root_menu;

        $this->pager = $this->getPager();
        $this->sort = $this->getSort();
    }

    protected function addSortQuery($query)
    {
        //don't allow sorting; always sort by tree and lft
        $query->addOrderBy('root_id, lft', 'asc');
    }

    public function executeCreate(sfWebRequest $request)
    {
        $this->form = $this->configuration->getForm();
        if(!$this->form->getObject()->getRootId()){
            $this->form->getObject()->setRootId(1); // to make sure root_id is defined
        }
        $this->menu = $this->form->getObject();

        $this->processForm($request, $this->form);

        $this->setTemplate('new');
    }


    public function executeUpdate(sfWebRequest $request)
    {
        $this->menu = $this->getRoute()->getObject();
        $this->form = $this->configuration->getForm($this->menu);
        if(!$this->form->getObject()->getRootId()){
            $this->form->getObject()->setRootId(1); // to make sure root_id is defined
        }

        $this->processForm($request, $this->form);

        $this->setTemplate('edit');
    }

}
