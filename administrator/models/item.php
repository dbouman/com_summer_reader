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
	class Summer_readerModelItem extends JModel
	{
	/**
	* Item id
	*
	* @var int
	*/
	var $_id = null;
	/**
	* Item data
	*
	* @var array
	*/
	var $_data = null;
	/**
	* Constructor
	*
	
	*/
	function __construct()
	{
	parent::__construct();
	$array = JRequest::getVar('cid', array(0), '', 'array');
	$edit = JRequest::getVar('edit',true);
	if($edit)
	$this->setId((int)$array[0]);
	}
	/**
	* Method to set the item identifier
	*
	* @access public
	* @param int Item identifier
	*/
	function setId($id)
	{
	// Set item id and wipe data
	$this->_id = $id;
	$this->_data = null;
	}
	/**
	* Method to get a item
	*
	
	*/
	function &getData()
	{
	// Load the item data
	if ($this->_loadData())
	{
	// Initialize some variables
	$user = &JFactory::getUser();
	// Check to see if the category is published
	if (!$this->_data->cat_pub) {
	JError::raiseError( 404, JText::_("Resource Not Found") );
	return;
	}
	// Check whether category access level allows access
	if ($this->_data->cat_access > $user->get('aid', 0)) {
	JError::raiseError( 403, JText::_('ALERTNOTAUTH') );
	return;
	}
	}
	else $this->_initData();
	return $this->_data;
	}
	/**
	* Tests if item is checked out
	*
	* @access public
	* @param int A user id
	* @return boolean True if checked out
	
	*/
	function isCheckedOut( $uid=0 )
	{
	if ($this->_loadData())
	{
	if ($uid) {
	return ($this->_data->checked_out && $this->_data->checked_out != $uid);
	} else {
	return $this->_data->checked_out;
	}
	}
	}
	/**
	* Method to checkin/unlock the item
	*
	* @access public
	* @return boolean True on success
	
	*/
	function checkin()
	{
	if ($this->_id)
	{
	$item = & $this->getTable();
	if(! $item->checkin($this->_id)) {
	$this->setError($this->_db->getErrorMsg());
	return false;
	}
	}
	return false;
	}
	/**
	* Method to checkout/lock the item
	*
	* @access public
	* @param int $uid User ID of the user checking the article out
	* @return boolean True on success
	
	*/
	function checkout($uid = null)
	{
	if ($this->_id)
	{
	// Make sure we have a user id to checkout the article with
	if (is_null($uid)) {
	$user =& JFactory::getUser();
	$uid = $user->get('id');
	}
	// Lets get to it and checkout the thing...
	$item = & $this->getTable();
	if(!$item->checkout($uid, $this->_id)) {
	$this->setError($this->_db->getErrorMsg());
	return false;
	}
	return true;
	}
	return false;
	}
	/**
	* Method to store the item
	*
	* @access public
	* @return boolean True on success
	
	*/
	function store($data)
	{
	$row =& $this->getTable();
	$user=& JFactory::getUser();
	// Bind the form fields to the item table
	if (!$row->bind($data)) {
	$this->setError($this->_db->getErrorMsg());
	return false;
	}
	// if new item, order last in appropriate group
	if (!$row->id) {
	$where = 'catid = ' . (int) $row->catid ;
	$row->ordering = $row->getNextOrder( $where );
	}
		$isNew = true;
		// Are we saving from an item edit?
		if ($row->id) {
			$isNew = false;
			$datenow =& JFactory::getDate();
			$row->modified 	= $datenow->toMySQL();
			$row->modified_by 	= $user->get('id');
		}

		$row->created_by 	= $row->created_by ? $row->created_by : $user->get('id');
		$row->created_by_alias 	= $row->created_by_alias ? $row->created_by_alias : $user->get('name');
		if ($row->created && strlen(trim( $row->created )) <= 10) {
			$row->created 	.= ' 00:00:00';
		}

		$config =& JFactory::getConfig();
		$tzoffset = $config->getValue('config.offset');
		$date =& JFactory::getDate($row->created, $tzoffset);
		$row->created = $date->toMySQL();
		
	// Make sure the item table is valid
	if (!$row->check()) {
	$this->setError($this->_db->getErrorMsg());
	return false;
	}
	// Store the item table to the database
	if (!$row->store()) {
	$this->setError($this->_db->getErrorMsg());
	return false;
	}
	return true;
	}
	/**
	* Method to remove a item
	*
	* @access public
	* @return boolean True on success
	
	*/
	function delete($cid = array())
	{
	$result = false;
	if (count( $cid ))
	{
	JArrayHelper::toInteger($cid);
	$cids = implode( ',', $cid );
	$query = 'DELETE FROM #__summer_reader'
	. ' WHERE id IN ( '.$cids.' )';
	$this->_db->setQuery( $query );
	if(!$this->_db->query()) {
	$this->setError($this->_db->getErrorMsg());
	return false;
	}
	}
	return true;
	}
	/**
	* Method to (un)publish a item
	*
	* @access public
	* @return boolean True on success
	
	*/
	function publish($cid = array(), $publish = 1)
	{
	$user =& JFactory::getUser();
	if (count( $cid ))
	{
	JArrayHelper::toInteger($cid);
	$cids = implode( ',', $cid );
	$query = 'UPDATE #__summer_reader'
	. ' SET published = '.(int) $publish
	. ' WHERE id IN ( '.$cids.' )'
	. ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ) )'
	;
	$this->_db->setQuery( $query );
	if (!$this->_db->query()) {
	$this->setError($this->_db->getErrorMsg());
	return false;
	}
	}
	return true;
	}
	/**
	* Method to move a item
	*
	* @access public
	* @return boolean True on success
	
	*/
	function move($direction)
	{
	$row =& $this->getTable();
	if (!$row->load($this->_id)) {
	$this->setError($this->_db->getErrorMsg());
	return false;
	}
	if (!$row->move( $direction, ' catid = '.(int) $row->catid.' AND published >= 0 ' )) {
	$this->setError($this->_db->getErrorMsg());
	return false;
	}
	return true;
	}
	/**
	* Method to move a item
	*
	* @access public
	* @return boolean True on success
	
	*/
	function saveorder($cid = array(), $order)
	{
	$row =& $this->getTable();
	$groupings = array();
	// update ordering values
	for( $i=0; $i < count($cid); $i++ )
	{
	$row->load( (int) $cid[$i] );
	// track categories
	$groupings[] = $row->catid;
	if ($row->ordering != $order[$i])
	{
	$row->ordering = $order[$i];
	if (!$row->store()) {
	$this->setError($this->_db->getErrorMsg());
	return false;
	}
	}
	}
	// execute updateOrder for each parent group
	$groupings = array_unique( $groupings );
	foreach ($groupings as $group){
	$row->reorder('catid = '.(int) $group);
	}
	return true;
	}
	/**
	* Method to load content item data
	*
	* @access private
	* @return boolean True on success
	
	*/
	function _loadData()
	{
	// Lets load the item if it doesn't already exist
	if (empty($this->_data))
	{
	$query = 'SELECT w.*, cc.title AS category,'.
	' cc.published AS cat_pub, cc.access AS cat_access'.
	' FROM #__summer_reader AS w' .
	' LEFT JOIN #__categories AS cc ON cc.id = w.catid' .
	' WHERE w.id = '.(int) $this->_id;
	$this->_db->setQuery($query);
	$this->_data = $this->_db->loadObject();
	return (boolean) $this->_data;
	}
	return true;
	}
	/**
	* Method to initialise the item data
	*
	* @access private
	* @return boolean True on success
	
	*/
	function _initData()
	{
	// Lets load the item if it doesn't already exist
	if (empty($this->_data))
	{
	$item = new stdClass();
	$item->id = 0;
	$item->catid = 0;
	$item->sid = 0;
	$item->title = null;
	$item->alias = null;
	$item->testfield1 = null;$item->testfield2 = null;
	$item->created = null;
	$item->created_by = 0;
	$item->created_by_alias = null;
	$item->modified_by = 0;
	$item->checked_out = 0;
	$item->checked_out_time = 0;
	$item->published = 0;
	$item->ordering = 0;
	$item->params = null;
	$item->hits = 0;
	$this->_data = $item;
	return (boolean) $this->_data;
	}
	return true;
	}
	}
	