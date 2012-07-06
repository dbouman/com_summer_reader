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
  * HTML View class for the summer_reader component
  * @package Summer_reader
  */
  class Summer_readerViewCategory extends JView
  {
function display( $tpl = null )
{
global $mainframe;
 // Initialize some variables
  $document = &JFactory::getDocument();
  $uri = &JFactory::getURI();
  $pathway = &$mainframe->getPathway();
 // Get the parameters of the active menu item
  $menus = &JSite::getMenu();
  $menu = $menus->getActive();
 // Get some data from the model
  $items = &$this->get('data' );
  $total = &$this->get('total');
  $pagination = &$this->get('pagination');
  $category = &$this->get('category' );
  $state = &$this->get('state');
//get the categories model, so we can use it also here
 $model =& JModel::getInstance('categories', 'summer_readermodel');
  $categories =& $model->getData();
 // Get the page/component configuration
  $params = &$mainframe->getParams();
 $category->total = $total;
 // Add alternate feed link
  if($params->get('show_feed_link', 1) == 1)
  {
  $link = '&view=category&id='.$category->slug.'&format=feed&limitstart=';
  $attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
  $document->addHeadLink(JRoute::_($link.'&type=rss'), 'alternate', 'rel', $attribs);
  $attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
  $document->addHeadLink(JRoute::_($link.'&type=atom'), 'alternate', 'rel', $attribs);
  }
 $menus = &JSite::getMenu();
  $menu = $menus->getActive();
 // because the application sets a default page title, we need to get it
  // right from the menu item itself
  if (is_object( $menu )) {
  $menu_params = new JParameter( $menu->params );
  if (!$menu_params->get( 'page_title')) {
  $params->set('page_title', $category->title);
  }
  } else {
  $params->set('page_title', $category->title);
  }
  $document->setTitle( $params->get( 'page_title' ) );
 //set breadcrumbs
  if(is_object($menu) && $menu->query['view'] != 'category') {
  $pathway->addItem($category->title, '');
  }
 // Prepare category description
  $category->description = JHTML::_('content.prepare', $category->description);
 // table ordering
  $lists['order_Dir'] = $state->get('filter_order_dir');
  $lists['order'] = $state->get('filter_order');
 // Set some defaults if not set for params
  $params->def('comp_description', JText::_('SUMMER_READER_DESC'));
  // Define image tag attributes
  if (isset( $category->image ) && $category->image != '')
  {
  $attribs['align'] = $category->image_position;
  $attribs['hspace'] = 6;
 // Use the static HTML library to build the image tag
  $category->image = JHTML::_('image', 'images/stories/'.$category->image, JText::_('SUMMER_READER'), $attribs);
  }
 // icon in table display
  if ( $params->get( 'link_icons' ) <> -1 ) {
  $image = JHTML::_('image.site', $params->get('link_icons', 'weblink.png'), '/images/M_images/', $params->get( 'weblink_icons' ), '/images/M_images/', 'Link' );
  }
 $k = 0;
  $count = count($items);
  for($i = 0; $i < $count; $i++)
  {
  $item =& $items[$i];
 $link = JRoute::_( 'index.php?view=item&catid='.$category->slug.'&id='. $item->slug);
 $menuclass = 'category'.$params->get( 'pageclass_sfx' );
 $itemParams = new JParameter($item->params);
  $item->link = '<a href="'. $link .'" class="'. $menuclass .'">'. $this->escape($item->title) .'</a>';  
 $item->image = $image;
 $item->odd = $k;
  $item->count = $i;
  $k = 1 - $k;
  }
 $count = count($categories);
  for($i = 0; $i < $count; $i++)
  {
  $cat =& $categories[$i];
  $cat->link = JRoute::_('index.php?option=com_summer_reader&view=category&id='. $cat->slug);
  }
 $this->assignRef('lists', $lists);
  $this->assignRef('params', $params);
  $this->assignRef('category', $category);
  $this->assignRef('categories', $categories);
  $this->assignRef('items', $items);
  $this->assignRef('pagination', $pagination);
 
 parent::display($tpl);
  }

}