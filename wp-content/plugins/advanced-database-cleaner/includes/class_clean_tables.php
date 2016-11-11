<?php

class ADBC_Tables_List extends WP_List_Table {

	/** Holds the message to be displayed if any */
	private $aDBc_message = "";

	/** Holds the class for the message : updated or error. Default is updated */
	private $aDBc_class_message = "updated";

	/** Holds tables that will be displayed */
	private $aDBc_tables_to_display = array();

	/** Holds counts + info of tables categories */
	private $aDBc_tables_categories_info	= array();

    function __construct(){
        parent::__construct(array(
            'singular'  => __('Table', 'advanced-db-cleaner'),		//singular name of the listed records
            'plural'    => __('Tables', 'advanced-db-cleaner'),		//plural name of the listed records
            'ajax'      => false									//does this table support ajax?
		));
		if(isset($_POST['aDBc_new_search_button']) && $_GET['aDBc_cat'] == "all"){
			$this->aDBc_message  = __('This feature is available in Pro version only.');
			$this->aDBc_message .= " <a href='?page=advanced_db_cleaner&aDBc_tab=premium'>" . __('Please upgrade to pro version') . "</a>";
			$this->aDBc_class_message  = "aDBc-upgrade-msg";
		}
		$this->aDBc_prepare_and_count_tables();
		$this->aDBc_print_page_content();
    }

	/** Prepare tables to display and count tables for each category */
	function aDBc_prepare_and_count_tables(){

		// Process bulk action if any before preparing tables to display
		if(!isset($_POST['aDBc_new_search_button'])){
			$this->process_bulk_action();
		}

		// Prepare data
		aDBc_prepare_items_to_display($this->aDBc_tables_to_display, $this->aDBc_tables_categories_info, "tables");

		// Call WP prepare_items function
		$this->prepare_items();
	}

	/** WP: Get columns */
	function get_columns(){
		$aDBc_belongs_to_toolip = "<a class='aDBc-tooltips'>
									<img class='aDBc-margin-l-3' src='".  ADBC_PLUGIN_DIR_PATH . '/images/notice.png' . "'/>
									<span>" . __('Indicates the creator of the option. It can be a plugin name, a theme name or WordPress itself.','advanced-db-cleaner') ." </span>
								  </a>";	
		$columns = array(
			'cb'          		=> '<input type="checkbox" />',
			'table_prefix' 		=> __('Prefix','advanced-db-cleaner'),
			'table_name' 		=> __('Table name','advanced-db-cleaner'),
			'table_rows' 		=> __('Rows','advanced-db-cleaner'),
			'table_size' 		=> __('Size','advanced-db-cleaner'),
			'site_id'   		=> __('Site id','advanced-db-cleaner'),
			'table_belongs_to'  => __('Belongs to','advanced-db-cleaner') . $aDBc_belongs_to_toolip
		);
		return $columns;
	}

	/** WP: Prepare items to display */
	function prepare_items() {
		$columns = $this->get_columns();
		$hidden = $this->get_hidden_columns();
		$sortable = array();
		$this->_column_headers = array($columns, $hidden, $sortable);
		$per_page = 50;
		$current_page = $this->get_pagenum();
		// Prepare sequence of tables to display
		$display_data = array_slice($this->aDBc_tables_to_display,(($current_page-1) * $per_page), $per_page);
		$this->set_pagination_args( array(
			'total_items' => count($this->aDBc_tables_to_display),
			'per_page'    => $per_page
		));
		$this->items = $display_data;
	}

	/** WP: Get columns that should be hidden */
    function get_hidden_columns(){
		// If MU, nothing to hide, else hide Side ID column
		if(function_exists('is_multisite') && is_multisite()){
			return array();
		}else{
			return array('site_id');
		}
    }

	/** WP: Column default */
	function column_default($item, $column_name){
		switch($column_name){
			case 'table_prefix':
			case 'table_name':
			case 'table_rows':
			case 'table_size':
			case 'site_id':
			case 'table_belongs_to':
			  return $item[$column_name];
			default:
			  return print_r($item, true) ; //Show the whole array for troubleshooting purposes
		}
	}

	/** WP: Column cb for check box */
	function column_cb($item) {
		return sprintf('<input type="checkbox" name="aDBc_tables_to_delete[]" value="%s" />', $item['table_prefix']."|".$item['table_name']);
	}

	/** WP: Get bulk actions */
	function get_bulk_actions() {
		$actions = array(
			'delete'    => __('Delete','advanced-db-cleaner')
		);
		return $actions;
	}

	/** WP: Message to display when no items found */
	function no_items() {
		if($_GET['aDBc_cat'] == "all"){
			_e('No tasks found!','advanced-db-cleaner');
		}else{
			_e('Available in Pro version!', 'advanced-db-cleaner');
		}
	}

	/** WP: Process bulk actions */
    public function process_bulk_action() {
        // security check!
        if (isset($_POST['_wpnonce']) && !empty($_POST['_wpnonce'])){
            $nonce  = filter_input(INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING);
            $action = 'bulk-' . $this->_args['plural'];
            if (!wp_verify_nonce( $nonce, $action))
                wp_die('Security check failed!');
        }
        $action = $this->current_action();

        if($action == 'delete'){
			// If the user wants to clean the tables he/she selected
			if(isset($_POST['aDBc_tables_to_delete'])){
				global $wpdb;
				foreach($_POST['aDBc_tables_to_delete'] as $table) {
					$table_info = explode("|", $table);
					$wpdb->query("DROP TABLE " . $table_info[0].$table_info[1]);
				}
				// Update the message to show to the user
				$this->aDBc_message = __('Selected tables cleaned successfully!', 'advanced-db-cleaner');
			}
        }
    }

	/** Print the page content */
	function aDBc_print_page_content(){
		// Print a message if any
		if($this->aDBc_message != ""){
			echo '<div id="aDBc_message" class="' . $this->aDBc_class_message . ' notice is-dismissible"><p>' . $this->aDBc_message . '</p></div>';
		}
		?>
		<div class="aDBc-content-max-width">
			<form id="aDBc_form" action="" method="post">
				<?php
				$aDBc_new_URI = $_SERVER['REQUEST_URI'];
				// Remove the paged parameter to start always from the first page when selecting a new category of tables
				$aDBc_new_URI = remove_query_arg('paged', $aDBc_new_URI);
				?>
				<!-- Print numbers of tables found in each category -->
				<div class="aDBc-category-counts">
					<?php
					$iterations = 0;
					foreach($this->aDBc_tables_categories_info as $abreviation => $category_info){
						$iterations++;
						$aDBc_new_URI = add_query_arg('aDBc_cat', $abreviation, $aDBc_new_URI);?>
						<span class="<?php echo $abreviation == $_GET['aDBc_cat'] ? 'aDBc-selected-category' : ''?>" style="<?php echo $abreviation == $_GET['aDBc_cat'] ? 'border-bottom: 1px solid ' . $category_info['color'] : '' ?> ">
							<a href="<?php echo $aDBc_new_URI; ?>" class="aDBc-category-counts-links" style="color:<?php echo $category_info['color']; ?>">
								<span class="aDBc-category-color" style="background: <?php echo $category_info['color']; ?>"></span>
								<span><?php echo $category_info['name']; ?> : </span>
								<span><?php echo $category_info['count'];?></span>
							</a>	
						</span>
						<?php
						if($iterations < 5){
							echo '<span class="aDBc-category-separator"></span>';
						}
					}?>
				</div>

				<div class="aDBc-clear-both"></div>

				<!-- Code for "run new search" button + Show loading image -->
				<div class="aDBc-margin-t-20">
					<input id="aDBc_new_search_button" type="submit" class="button-primary aDBc-run-new-search" value="<?php _e('Detect orphan tables','advanced-db-cleaner'); ?>"  name="aDBc_new_search_button"/>

					<div id="aDBc-please-wait">
						<div class="aDBc-loading-gif"></div>
						<?php 
						//_e('Searching...Please wait! If your browser stops loading without refreshing, please refresh this page.','advanced-db-cleaner');
						_e('Please wait!','advanced-db-cleaner');
						?>
					</div>
				</div>

				<div class="aDBc-clear-both aDBc-margin-b-20"></div>

				<!-- Print a notice/warning according to each type of tables -->
				<?php
				if($_GET['aDBc_cat'] == 'all' && $this->aDBc_tables_categories_info['all']['count'] > 0){
					echo '<div class="aDBc-box-warning">' . __('Below the list of all your tables. Please do not delete any table unless you really know what you are doing!','advanced-db-cleaner') . '</div>';
				}

				if($_GET['aDBc_cat'] != 'all'){
					echo '<div class="aDBc-upgrade-msg notice is-dismissible"><p>' . __('This feature is available in Pro version only.') . ' <a href="?page=advanced_db_cleaner&aDBc_tab=premium">' . __('Please upgrade to pro version') . "</a>" . '</p></div>';
				}

				// Print the tables
				$this->display();

				?>
			</form>
		</div>
		<div id="aDBc_dialog1" title="<?php _e('Cleaning...','advanced-db-cleaner'); ?>" class="aDBc-jquery-dialog">
			<p class="aDBc-box-warning">
				<?php _e('You are about to clean some of your tables. This operation is irreversible. Don\'t forget to make a backup first.','advanced-db-cleaner'); ?>
			</p>
			<p>
				<?php _e('Are you sure to continue?','advanced-db-cleaner'); ?>
			</p>
		</div>
		<div id="aDBc_dialog2" title="<?php _e('Action required','advanced-db-cleaner'); ?>" class="aDBc-jquery-dialog">
			<p class="aDBc-box-info">
				<?php _e('Please select an action!','advanced-db-cleaner'); ?>
			</p>
		</div>
	<?php
	}
}

new ADBC_Tables_List();

?>