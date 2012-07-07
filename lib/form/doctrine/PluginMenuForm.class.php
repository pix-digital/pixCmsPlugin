<?php

/**
 * PluginMenu form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginMenuForm extends BaseMenuForm
{
    protected $parentId = null;
    protected $move = null;
    protected $whereToMove = null;

    public function setup()
    {
        parent::setup();

        unset($this['created_at'],
        $this['updated_at'],
        $this['root_id'],
        $this['lft'],
        $this['rgt'],
        $this['level']
        );


        $this->widgetSchema['parent_id'] = new sfWidgetFormDoctrineChoice(array(
            'model' => 'Menu',
            'add_empty' => '~ (object is at root level)',
            'order_by' => array('root_id, lft', ''),
            'method' => 'getIndentedName'
        ));
        $this->validatorSchema['parent_id'] = new sfValidatorDoctrineChoice(array(
            'required' => false,
            'model' => 'Menu'
        ));
        $this->setDefault('parent_id', $this->object->getParentId());
        $this->widgetSchema->setLabel('parent_id', 'Child of');


        $this->widgetSchema['move'] = new sfWidgetFormDoctrineChoice(array(
            'model' => 'Menu',
            'add_empty' => true,
            'order_by' => array('root_id, lft', ''),
            'method' => 'getIndentedName'
        ));
        $this->validatorSchema['move'] = new sfValidatorDoctrineChoice(array(
            'required' => false,
            'model' => 'Menu'
        ));
        $this->widgetSchema->setLabel('move', 'Position menu item');
        $choices = array(
            '' => '',
            'Prev' => 'Before',
            'Next' => 'After'
        );
        $this->widgetSchema['where_to_move'] = new sfWidgetFormChoice(array('choices' => $choices));
        $this->validatorSchema['where_to_move'] = new sfValidatorChoice(array(
            'required' => false,
            'choices' => array_keys($choices)
        ));
        $this->widgetSchema->setLabel('where_to_move', 'Position before or after?');


        $this->embedI18n(array_keys(sfConfig::get('app_pixPage_enabled_cultures')));
    }

    public function updateParentIdColumn($parentId)
    {
        $this->parentId = $parentId;
    }

    public function updateMoveColumn($move)
    {
        $this->move = $move;
    }

    public function updateWhereToMoveColumn($whereToMove)
    {
        $this->whereToMove = $whereToMove;
    }

    protected function doSave($con = null)
    {
        parent::doSave($con);

        $node = $this->object->getNode();

        if ($this->parentId != $this->object->getParentId() || !$node->isValidNode()) {
            if (empty($this->parentId)) {
                //save as a root
                if ($node->isValidNode()) {
                    $node->makeRoot($this->object['id']);
                    $this->object->save($con);
                }
                else
                {
                    $this->object->getTable()->getTree()->createRoot($this->object); //calls $this->object->save internally
                }
            }
            else
            {
                //form validation ensures an existing ID for $this->parentId
                $parent = $this->object->getTable()->find($this->parentId);
                $method = ($node->isValidNode() ? 'move' : 'insert') . 'AsLastChildOf';
                $node->$method($parent); //calls $this->object->save internally
            }
        }

        if ($this->move) {
            $type = $this->whereToMove ? $this->whereToMove : 'Next';
            $func = 'moveAs' . $type . 'SiblingOf';
            $node->$func($this->object->getTable()->find($this->move));
        }
    }
}
