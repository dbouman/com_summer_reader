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


?>
<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
<div class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<?php echo $this->escape($this->params->get('page_title')); ?>
</div>
<?php endif; ?>
<?php if ( ($this->params->def('image', -1) != -1) || $this->params->def('show_comp_description', 1) ) : ?>
<table width="100%" cellpadding="4" cellspacing="0" border="0" align="center" class="contentpane<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<tr>
<td valign="top" class="contentdescription<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<?php
  if ( isset($this->image) ) : echo $this->image; endif;
  echo $this->params->get('comp_description');
  ?>
</td>
</tr>
</table>
<?php endif; ?>
<ul>
<?php foreach ( $this->categories as $category ) : ?>
<li>
<a href="<?php echo $category->link; ?>" class="category<?php echo $this->params->get( 'pageclass_sfx' ); ?>">
<?php echo $this->escape($category->title);?></a>

<span class="small">
  (<?php echo $category->numlinks;?>)
</span>
</li>
<?php endforeach; ?>
</ul>