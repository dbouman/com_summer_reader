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
  * @static
  * @package Summer_reader
  */
  class Summer_readerViewSummer_reader extends JView
  {
  function display($tpl = null)
  {
  global $mainframe, $option;
 $db =& JFactory::getDBO();
  $uri =& JFactory::getURI();
 Summer_readerHelper::checkCategories() ;
 $filter_state = $mainframe->getUserStateFromRequest( $option.'filter_state', 'filter_state', '', 'word' );
  $filter_catid = $mainframe->getUserStateFromRequest( $option.'filter_catid', 'filter_catid', 0, 'int' );
  $filter_order = $mainframe->getUserStateFromRequest( $option.'filter_order', 'filter_order', 'a.ordering', 'cmd' );
  $filter_order_Dir = $mainframe->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', '', 'word' );
  $search = $mainframe->getUserStateFromRequest( $option.'search', 'search', '', 'string' );
  $search = JString::strtolower( $search );
 // Get data from the model
  $items = & $this->get( 'Data');
  $total = & $this->get( 'Total');
  $pagination = & $this->get( 'Pagination' );
 // build list of categories
  $javascript = 'onchange="document.adminForm.submit();"';
  $lists['catid'] = JHTML::_('list.category', 'filter_catid', $option, intval( $filter_catid ), $javascript );
 // state filter
  $lists['state'] = JHTML::_('grid.state', $filter_state );
 // table ordering
  $lists['order_Dir'] = $filter_order_Dir;
  $lists['order'] = $filter_order;
 // search filter
  $lists['search']= $search;
 $this->assignRef('user', JFactory::getUser());
  $this->assignRef('lists', $lists);
  $this->assignRef('items', $items);
  $this->assignRef('pagination', $pagination);
  
  parent::display($tpl);
  }
  }