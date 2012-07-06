<?php 

/**
 * Joomla! 1.5 component summer_reader
 * Code generated by : Danny's Joomla! 1.5 MVC Component Code Generator
 * http://www.joomlafreak.be
 * date generated:  
 * @version 0.8
 * @author Danny Buytaert 
 * @package com_summer_reader
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 **/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


  jimport( 'joomla.application.component.view');
/**
  * HTML View class for the summer_reader component
  *
  * @static
  * @package Summer_reader
  */
  class Summer_readerViewItem extends JView
  {
  function display($tpl = null)
  {
  global $mainframe;
 if($this->getLayout() == 'form') {
  $this->_displayForm( $tpl );
  return;
  }
 //get the item
  $item =& $this->get('data');
 parent::display($tpl);
  }
 function _displayForm($tpl)
  {
  global $mainframe, $option;
 $db =& JFactory::getDBO();
  $uri =& JFactory::getURI();
  $user =& JFactory::getUser();
  $model =& $this->getModel( 'item' );
 $lists = array();
 Summer_readerHelper::checkCategories() ;
 //get the item
  $item =& $this->get('data');
  $isNew = ($item->id < 1);
 // fail if checked out not by 'me'
  if ($model->isCheckedOut( $user->get('id') )) {
  $msg = JText::sprintf( 'DESCBEINGEDITTED', JText::_( 'The list' ), $item->title );
  $mainframe->redirect( 'index.php?option='. $option, $msg );
  }
 // Edit or Create?
  if (!$isNew)
  {
  $model->checkout( $user->get('id') );
  }
  else
  {
  // initialise new record
 $item->published = 1;
  $item->order = 0;
  $item->catid = JRequest::getVar( 'catid', 0, 'post', 'int' );
  }
 // build the html select list for ordering
  $query = 'SELECT ordering AS value, title AS text'
  . ' FROM #__summer_reader'
  . ' WHERE catid = ' . (int) $item->catid
  . ' ORDER BY ordering';
 $lists['ordering'] = JHTML::_('list.specificordering', $item, $item->id, $query, 1 );
  $lists['catid'] = JHTML::_('list.category', 'catid', $option, intval( $item->catid ) );
  $lists['published'] = JHTML::_('select.booleanlist', 'published', 'class="inputbox"', $item->published );

 //clean item data
  jimport('joomla.filter.output');
  JFilterOutput::objectHTMLSafe( $item, ENT_QUOTES, 'text' );
 $file = JPATH_COMPONENT.DS.'models'.DS.'item.xml';
  $params = new JParameter( $item->params, $file );
 $this->assignRef('lists', $lists);
  $this->assignRef('item', $item);
  $this->assignRef('params', $params);
 parent::display($tpl);
  }
  }
