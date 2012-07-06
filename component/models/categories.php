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


jimport('joomla.application.component.model');

/**
  * Summer_reader Component Categories Model
  *
  * @package Summer_reader
  */
  class Summer_readerModelCategories extends JModel
  {
  /**
  * Categories data array
  *
  * @var array
  */
  var $_data = null;
 /**
  * Categories total
  *
  * @var integer
  */
  var $_total = null;
 /**
  * Constructor
  *
  * @since 1.5
  */
 function __construct()
  {
  parent::__construct();
 }
 /**
  * Method to get item item data for the category
  *
  * @access public
  * @return array
  */
  function getData()
  {
  // Lets load the content if it doesn't already exist
  if (empty($this->_data))
  {
  $query = $this->_buildQuery();
  $this->_data = $this->_getList($query);
  }
 return $this->_data;
  }
 /**
  * Method to get the total number of item items for the category
  *
  * @access public
  * @return integer
  */
  function getTotal()
  {
  // Lets load the content if it doesn't already exist
  if (empty($this->_total))
  {
  $query = $this->_buildQuery();
  $this->_total = $this->_getListCount($query);
  }
 return $this->_total;
  }
 function _buildQuery()
  {
  $user =& JFactory::getUser();
  $aid = $user->get('aid', 0);
 //Query to retrieve all categories that belong under the summer_reader section and that are published.
  $query = 'SELECT cc.*, COUNT(a.id) AS numlinks,'
  .' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(\':\', cc.id, cc.alias) ELSE cc.id END as slug'
  .' FROM #__categories AS cc'
  .' LEFT JOIN #__summer_reader AS a ON a.catid = cc.id'
  .' WHERE a.published = 1'
  .' AND section = \'com_summer_reader\''
  .' AND cc.published = 1'
  .' AND cc.access <= '.(int) $aid
  .' GROUP BY cc.id'
  .' ORDER BY cc.ordering';
 return $query;
  }
  }
  ?>