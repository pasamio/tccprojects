<?php
/**
 * Personal Content Manager - HTML Renderer
 * 
 * This file contains the HTML rendering classes 
 * Code borrowed from Sam Moffatt's Selected Newsflash extension
 * 
 * PHP4/5
 *  
 * Created on Nov 22, 2007
 * 
 * @package Personal-Content
 * @subpackage Component
 * @author Sam Moffatt <sam.moffatt@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */
 
 
// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

/**
* @package Personal-Content
* @subpackage Newsfeeds
*/
class HTML_personalcontent {
	
	function showSelection( &$rows, &$lists, $pageNav, $option ) {
		global $my;

		mosCommonHTML::loadOverlib();
		?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			Personal Content Manager
			</th>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="20">
			#
			</th>
			<th width="20">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
			</th>
			<th class="title">
				User
			</th>
			<th class="title">
				Article Title
			</th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row = &$rows[$i];

			$link 	= 'index2.php?option=com_newsfeeds&task=editA&hidemainmenu=1&uid='. $row->uid;

/*			$img 	= $row->published ? 'tick.png' : 'publish_x.png';
			$task 	= $row->published ? 'unpublish' : 'publish';
			$alt 	= $row->published ? 'Published' : 'Unpublished';
*/			
			$checked = '<input id="cb'.$i.'" name="cid[]" value="'.$row->uid.'" onclick="isChecked(this.checked);" type="checkbox">';
			?>
			<tr class="<?php echo 'row'. $k; ?>">
				<td align="center">
				<?php echo $pageNav->rowNumber( $i ); ?>
				</td>
				<td>
				<?php echo $checked; ?>
				</td>
				<td><a href="index2.php?option=com_personalcontent&task=edit&uid=<?php echo $row->uid ?>">
					<?php echo $row->username ? $row->username : 'Anonymous' ?>
					</a>
				</td>
				<td>
					<?php echo $row->title; ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		<?php
	}
	
	function newPersonalContent( $option, $row, $users ) {
		?>
	        <script language="JavaScript">
			// This code was a quick mock up to test AJAX integration in a Joomla framework (mambots)
			var interval = 100;
			//this.timerID = setInterval(function () { preSearch(true); }, interval);
			var lastsearchstring = '';
			
			function preSearch() {
				//Put the form data into a variable
				var theQuery = document.getElementById('query').value  ;
				if(theQuery != lastsearchstring) {
					lastsearchstring = theQuery;
				} else {
					return;
				}
				if(theQuery.length < 4) {
					return;
				}
				//If the form data is *not* blank, query the DB and return the results
				if(theQuery !== "") {
					xajax_ajaxDoArticleSearch(theQuery,'cid');
				} else {
			//                xajax_ajaxDoArticleSearch("%");
				}
			}
			
		</script>
	
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			Select new content item
			</th>
		</tr>
		</table>
		<table class="adminform">
		<tr>
			<th colspan="2">
			Details
			</th>
		</tr>
		<tr>
			<td>
			Content ID:
			</td>
			<td>
			<input class="inputbox" type="text" size="40" id="cid" name="cid" value="<?php echo $row->cid ?>"> 
			</td>
		</tr>
		<tr>
			<td>
			User ID:
			</td>
			<td>
			<?php echo $users ?>
			<!-- <input class="inputbox" type="text" size="40" id="uid" name="uid" value="<?php echo $row->uid ?>"> -->
			</td>
		</tr>		
		<tr>				
		</table>
				
		<table class="adminform">
		<tr>
			<th colspan="2">
			Search for Article
			</th>
		</tr>
		<tr>
			<td valign="top">Search:<br>
			  <input type="text" name="search" autocomplete="off" id="query" onKeyUp="preSearch()" />
			</td>
			<td rowspan="2" valign="top"><div id="preview" style="border: 1px solid black"></div></td>
		</tr>
		<tr>
			<td valign="top">Results:<br>
			<div id="results"></div>
			</td>
		</tr>
		</table>
		<input type="hidden" name="id" value="0">
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		<input type="hidden" name="task" value="">
		</form>
		<?php
	}
}
