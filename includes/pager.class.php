<?php

// ==================================================================
//  Author:    Justin Vincent (justin@visunet.ie)
//	Web:       http://php.justinvincent.com
//	Name:      EZ Results
// 	Desc:      Class to make it fast and easy to display results on your website.
//  Licence:   LGPL (No Restrictions)
//	Version:   1.1


// ==================================================================
//  Modifications by Steve Warwick Ph.D. (smarty@clickbuild.com)

// 	Name: Smart EZ Results
//  Purpose: modify this class for use with Smarty (smarty.php.net)
// 	Desc: Reduced the class to provide only the results set as an array. 
//		  The only config variables required are ones to modify the data 
//	      inside a link, such as naming of the link and a CSS class//
//  Licence:   LGPL (No Restrictions)
//	Version:   1.1

// added current_page to the results set as it is useful in Smarty


/* output:

print_r:
Array 
(
	[num_records] => 56
	[num_pages] => 6
	[first_page] => << 
	[prev] => < 
	[nav] => 1 2 3 4 5 
	[next] => >
	[last_page] => >>
	[current_page] => 1
)

Set the names of the links (not the numbers) using 
set_name_x()

set_name_first('name')
set_name_last('name')
set_name_prev('name')
set_name_next('name')

To set the other variables such as num_results_per_page etc. It is still 
non OO $obj->num_results_per_page='x' -- see the vars.

I have decided to be different on the stylesheets. Rather than poking around
in the class to format the output, the style sheet names are pre-defined.  
The format is: 'ezr_' + name of the returned variable. 
For example ezr_first_page for the first_page variable and so on. If the
result will be both linked and not linked, then the unlinked stylesheet name
has _na appended eg, ezr_first_page_na

# num_records & num_pages are never links but to maintain a uniform interface
they have a style available as well.

here is an ultra simple stylesheet example
.ezr_num_records { font-weight: bold }
.ezr_num_pages { font-weight: bold }
.ezr_first_page { font-weight: bold }
.ezr_first_page_na {  }
.ezr_back { font-weight: bold }
.ezr_back_na {  }
.ezr_nav { font-weight: bold }
.ezr_nav_na {  }
.ezr_next { font-weight: bold }
.ezr_next_na {  }
.ezr_last_page { font-weight: bold }
.ezr_last_page_na {  }


WHen a result set is at the beginning or at the end I have it set so that nothing
is displayed for those items (currently commented out. To have the first, last,
next, prev always displayed, uncomment the else sections.
*/

class smart_ez_results
{
	
	/********************************************************
	*	BASIC SETTINGS
	*/

	var $num_results_per_page = 10;
	var $num_browse_links     = 5;
	var $hide_results         = false;
	var $set_num_results      = 0;
	var $cur_num_results      = 0;
	var $display_lang			= true;

	/********************************************************
	*	$ez_results->ez_results
	*
	*	Constructor. Allows users to use ez_sql object other than the std $bcdb->
	*/
	
	function smart_ez_results( $ez_sql_object = 'db')
	{
		$this->ez_sql_object = $ez_sql_object ;
		
		// Stop annoying warnign message that comes up in new versions of PHP
		ini_set('allow_call_time_pass_reference', true);
		$this->set_name_first();
		$this->set_name_last();
		$this->set_name_prev();
		$this->set_name_next();
	}
	
	/********************************************************
	*	$ez_results->set_name_x
	*
	*	USed to set the link names to defaults or custom
	*/
	function set_name_first($x='') {
		if($x=='') {
			$this->first_page='&laquo;';
		} else { 
			$this->first_page=$x;
		}
	}
	function set_name_last($x='') {
		if($x=='') {
			$this->last_page='&raquo;';
		} else { 
			$this->last_page=$x;
		}
	}
	function set_name_prev($x='') {
		if($x=='') {
			$this->prev='&lsaquo;';
		} else { 
			$this->prev=$x;
		}
	}
	function set_name_next($x='') {
		if($x=='') {
			$this->next='&rsaquo;';
		} else { 
			$this->next=$x;
		}
	}
	/*function set_url_params ($params) {			
		$params = str_replace('&amp;', '&', $params);
		$params = str_replace('&', '&amp;', $params);
		$this->url_params = $params;
	}*/
	/********************************************************
	*	$ez_results->query_mysql
	*
	*	Perform results query (mysql & ezSQL) can use normal queries
	*
	*	$query = 'SELECT user, name, password FROM users'
	*/

	function query_mysql($query, $output = ARRAY_A)
	{
		global $bcdb, $lang;
		
		// make sure that start row is set to zero if first call
		$this->init_start_row();
		
		/*
			Added by brau
		*/			
		/*
		if ( $this->display_lang && $lang != get_default_lang())
			$this->set_qs_val('lang', $lang);
		if ( !empty($_REQUEST['t']) )
			$this->set_qs_val('t', $_REQUEST['t']);			
		*/
		/* End */
		
		// get total number of results
		if ( ! isset( $this->num_results ) ) # Hacked
			$this->get_num_results($query);

		// Do query
		$this->results = $bcdb->get_results($query 
		. " LIMIT {$_REQUEST['PageIndex']},$this->num_results_per_page",$output);

		$this->cur_num_results = count($this->results);

	}

	/********************************************************
	*	$ez_results->query_oracle
	*
	*	Perform an an oracle query.
	*	Needs to be split up due to nature of oracle.
	*
	*	This only works for single table queries
	*
	*	$table       = 'TABLE_NAME'
	*	$field_list  = 'ID, USER, PASSWORD' (must not be *)
	*	$where       = 'ID > 50 AND ID < 60'
	*	$order_by    = 'USER DESC'
	*/

	function query_oracle($table,$field_list,$where=false,$order_by=false)
	{
		global $bcdb;
		
		// Make sure that start row is set to zero if first call
		$this->init_start_row();

		// Count total number of results
		$this->get_num_results("SELECT count(*) FROM $table ".($where?"WHERE $where ":null));

		// Do query
		$this->results = $bcdb->get_results("SELECT $field_list FROM (SELECT ROWNUM ROW_A, $field_list FROM $table ".($where?"WHERE $where ":null)." ".($order_by?"ORDER BY $order_by":null).") B WHERE B.ROW_A >= ".$_REQUEST['PageIndex']." AND B.ROW_A <= ".($_REQUEST['PageIndex']+$this->num_results_per_page),ARRAY_N);
	
		$this->cur_num_results = count($this->results);
	}

	/********************************************************
	*	$ez_results->get
	*
	*	Main function to get and format the results.
	*	This function returns results rather than prints them to screen.
	*
	* - Steve Warwick: renamed/edited to match and behave  like theEzSQL function 
	*	makes interchange easier
	*/

	function get_results($q, $output = ARRAY_A)
	{
		$this->query_mysql($q, $output);			
		$this->build_navigation();						
		return $this->results;			
	}

	/********************************************************
	*	$ez_results->set_qs_val
	*
	*	Appends values to the GET query string to be carried over
	*	during browsing - useful to change order by etc
	*/

	var $qs;
	
	function set_qs_val($name,$val)
	{
		$amp = '';
		if (!empty($amp))
			$amp = '&amp';
		if ( strpos($this->qs, $name . '=' . urlencode($val)) === false )
			$this->qs .= "$amp$name=".urlencode($val);
	}

	/********************************************************
	*	$ez_results->debug
	*
	*	Maps out this object and all values it contains
	*/

	function debug()
	{
		print "<pre>";
		print_r($this);
		print "</pre>";
	}

	/********************************************************
	*	$ez_results->build_navigation (internal)
	*
	*	Main function that builds the result output.
	*	(Note: print out is returned not printed)
	
	change the functionality - each result item is pushed into a variable
	*/
	
	function build_navigation()
	{
		$temp = '';
		// This is for if we are just using the navigation part
		if ( ! isset($this->num_results) )
		{
			$this->num_results = $this->set_num_results;
		}
		
		// Calculate number of pages (of results)
		$this->num_pages = 
		($this->num_results - ($this->num_results % $this->num_results_per_page)) 
		/ $this->num_results_per_page;
		if ( $this->num_results % $this->num_results_per_page ) 
		{
			$this->num_pages++;
		}

		
		// Calculate which page we are browsing
		$this->cur_page = 
		($_REQUEST['PageIndex'] - ( $_REQUEST['PageIndex'] % $this->num_results_per_page )) 
		/ $this->num_results_per_page;

		// Calculate which set of $this->num_browse_links we are browsing
		$this->cur_page_set = 
		($this->cur_page - ( $this->cur_page % $this->num_browse_links )) 
		/ $this->num_browse_links;
		
		// addition; steve warwick --
		// if the num pages are less than the num links make browse links
		// eq the num pages - ensures consistent navigation output
		if($this->num_pages <= $this->num_browse_links) {
			$this->num_browse_links = $this->num_pages == 0 ? 1 : $this->num_pages;
		}
		
		# ------------

		// Output total num records if required
		$this->RESULT_SET['num_records'] = '<span class="ezr_num_records">'
		. number_format($this->num_results) 
		. '</span>';


		// Output num pages if required
		$this->RESULT_SET['num_pages'] = '<span class="ezr_num_pages">'
		. number_format($this->num_pages) 
		. '</span>';
		

		/* modification: Steve Warwick 
		If the number of pages is one there is no need for prev/next
		links etc. Only return the Total and the number of pages
		*/
		if($this->num_pages > 1) {
			// Output back to start page
			if ( $this->cur_page != 0)
			{				
				$this->RESULT_SET['first_page'] = '<a href="' 
				. preg_replace("/\?.*/",'',$_SERVER['REQUEST_URI']) 
				. $this->get_query_params(0) . '"class="ezr_first_page">'
				.$this->first_page.'</a> ';
			}
			/*
			else
			{
				if ( $this->num_pages >= $this->num_browse_links)
				{
					$this->RESULT_SET['first_page'] = 
					'<span class="ezr_first_page_na">'
					. $this->first_page
					. '</span>';
					
				}
			}
			*/

			// Output back if not on first page
			if ( $this->cur_page != 0 )
			{
				$this->RESULT_SET['prev'] = '<a href="' 
				. preg_replace("/\?.*/",'',$_SERVER['REQUEST_URI']) 
				. $this->get_query_params($_REQUEST['PageIndex']-$this->num_results_per_page) 
				. '" class="ezr_back">'.$this->prev.'</a> ';
			}
			/*
			else
			{
				if ( $this->num_pages >= $this->num_browse_links )
				{
					$this->RESULT_SET['prev']='<span class="ezr_back_na">'
					. $this->prev.'</span> ';
				}
			}
			*/


			// Output nav links
			if ( $this->num_results > $this->num_results_per_page )
			{
				
				for ( $i=($this->cur_page_set*$this->num_browse_links); 
					$i < ($this->cur_page_set*$this->num_browse_links)
					+ $this->num_browse_links; 
					$i++ )
				{
					if ( ($i*$this->num_results_per_page) < $this->num_results )
					{
						// if current page
						if ($i==$this->cur_page)
						{
							$temp .= '<span class="ezr_nav_na">'.($i+1).'</span> ';
						}
						// if a nav link
						else
						{
							$temp .= '<a href="' 
							. preg_replace("/\?.*/",'',$_SERVER['REQUEST_URI']) 
							. $this->get_query_params($i*$this->num_results_per_page) 
							. '" class="ezr_nav">'.($i+1).'</a> ';
						}
					}		
				}
				$this->RESULT_SET['nav']=$temp;
			}


			// Output Next (if not on last page and ther are more pages than cur page
			if ( ($this->num_pages >= $this->num_browse_links) 
			&& (($_REQUEST['PageIndex'] + $this->num_results_per_page) 
			< $this->num_results))
			{
				$this->RESULT_SET['next'] = '<a href="' 
				. preg_replace("/\?.*/",'',$_SERVER['REQUEST_URI']) 
				. $this->get_query_params($_REQUEST['PageIndex']+$this->num_results_per_page) 
				. '" class="ezr_next">'.$this->next.'</a> ';
			}
			/*
			else
			{
				if ( $this->num_pages >= $this->num_browse_links )
				{
					$this->RESULT_SET['next'] = '<span class="ezr_next_na">'
					. $this->next.'</span> ';
				}
			}
			*/
			
			
			// Output last page
			if ( ($this->num_pages >= $this->num_browse_links) 
			&& (($_REQUEST['PageIndex'] + $this->num_results_per_page) 
			< $this->num_results))
			{
				$this->RESULT_SET['last_page'] = '<a href="' 
				. preg_replace("/\?.*/",'',$_SERVER['REQUEST_URI']) 
				. $this->get_query_params(($this->num_pages * $this->num_results_per_page) 
					- $this->num_results_per_page)					  
				. '" class="ezr_last_page">'.$this->last_page.'</a> ';
			}
			/*
			else
			{
				if ($this->num_pages >= $this->num_browse_links)
				{
					$this->RESULT_SET['last_page'] = '<span class="ezr_last_page_na">'
					. $this->last_page.'</span> ';
				}
			}
			*/
		
		
		}
		// can be userd for the page x of y style pagination
		$this->RESULT_SET['current_page'] = $this->cur_page+1;
		
		// the results are available thru $obj->ezr_results
	}
	
	function get_query_params( $page ) {			
		if (!empty($page)) {
			$page = 'PageIndex=' . intval($page);
		}
		else {
			$page = null;
		}
		if ( !empty($page) && !empty($this->qs) ) {
			$this->qs = '&amp;' . trim($this->qs, '&amp;');
		}
		elseif (empty($page)) {
			$this->qs = trim($this->qs, '&amp;');
		}
		if ( trim($page.$this->qs) == '' ) {
			return null;
		}
		return "?$page$this->qs";
	}
	
	function get_navigation() {
		return $this->RESULT_SET ? $this->RESULT_SET : null;
	}

	/********************************************************
	*	$ez_results->get_num_results (internal)
	*
	*	Count total results for this query
	*/
	
	// modified: Steve Warwick: added the s option to the preg so thatg the 
	// dot will get newlines as well.

	function get_num_results($query)
	{
		global $bcdb;

		$this->num_results =  
			$bcdb->get_var(
			preg_replace("/SELECT.*?FROM(.*)(ORDER BY.*)?$/Uis","SELECT count(*) FROM $1",$query)
			);

	}

	/********************************************************
	*	$ez_results->init_start_row (internal)
	*
	*	Internal function to make sure that start row is set to zero
	*/
	
	function init_start_row()
	{

         if (isset($_POST['PageIndex'])) 
              $_REQUEST['PageIndex'] = $_POST['PageIndex']; 
              
        if (isset($_GET['PageIndex'])) 
              $_REQUEST['PageIndex'] = $_GET['PageIndex']; 
              
        if (isset($_COOKIE['PageIndex'])) 
              $_REQUEST['PageIndex'] = $_COOKIE['PageIndex'];

		// browse results start row from GET, POST, COOKIE, etc
		if ( ! isset($_REQUEST['PageIndex']) )
		{
			$_REQUEST['PageIndex'] = 0;
		}
		
		$_REQUEST['PageIndex'] = abs(intval($_REQUEST['PageIndex']));		
	}
}

$bcrs = new smart_ez_results('bcdb');

if ( defined('NUM_ITEMS') )
	$bcrs->num_results_per_page = NUM_ITEMS;
else 
	$bcrs->num_results_per_page = 10;
?>
