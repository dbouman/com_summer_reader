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


jimport('joomla.application.component.view');
 /**
  * @package Summer_reader
  */
  class Summer_readerViewItem extends JView
  {
  		function display($tpl = null)
 	    {
  			global $mainframe;
 
			$pathway = &$mainframe->getPathway();
			$document = & JFactory::getDocument();
  			$model = &$this->getModel();
  			$params = &$mainframe->getParams();
  
			// Get the parameters of the active menu item
			$menus = &JSite::getMenu();
			$menu = $menus->getActive();
			$item =& $this->get('data');
 			// Set the document page title
  			// because the application sets a default page title, we need to get it
  			// right from the menu item itself
  			if (is_object( $menu ) && isset($menu->query['view']) && $menu->query['view'] == 'item' && isset($menu->query['id']) && $menu->query['id'] == $item->id) {
  				$menu_params = new JParameter( $menu->params );
 		    	 if (!$menu_params->get( 'page_title')) {
 				 $params->set('page_title', $item->title);
  					}
  				} 
  				else 
  				{
  				$params->set('page_title', $item->title);
  				}
			$document->setTitle( $params->get( 'page_title' ) );
			
 				//set breadcrumbs
 				if (isset( $menu ) && isset($menu->query['view']) && $menu->query['view'] != 'item'){
 				 $pathway->addItem($item->title, '');
 				 }
 				 
			 $this->assignRef('params', $params);
 			 $this->assignRef('item', $item);
  
  			 parent::display($tpl);
 		 }
  }