<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://aramkhachikyan.com
 * @since      1.0.0
 *
 * @package    Dekartforms
 * @subpackage Dekartforms/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Dekartforms
 * @subpackage Dekartforms/includes
 * @author     Aram Khachikyan <aram.khachikyan.a@gmail.com>
 */
class Dekartforms {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Dekartforms_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'dekartforms';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		
		// Call router
		$this->router();
		
		// Adding shortcode into WordPress
		add_shortcode('dekartform', array($this,'dekartform_shortcode'));
		
		// Triggering submission function
		$this->dekartform_submission();
	}
	
	
	// Dekartform shortcode
	public function dekartform_shortcode($data=null) {
		global $table_prefix, $wpdb;
		
		if(isset($data['form']) && !empty($data['form'])) {
				
			$form_table = $table_prefix . 'dekart_forms';		
			$form = $wpdb->get_row( 'SELECT * FROM ' . $form_table . ' WHERE id=' . $data['form'], OBJECT );
			
			$fields_table = $table_prefix . 'dekart_fields';		
			$fields = $wpdb->get_results( 'SELECT * FROM ' . $fields_table . ' WHERE form_id=' .$data['form']. ' ORDER BY ord ASC', OBJECT );		
		
			$str = "<h3>{$form->title}</h3><form method='post' action='' class='dekartFormFront' id='dekartFormFront_{$data[form]}'> ";
			foreach($fields as $single_field) {
				$str .= "<p><label>{$single_field->label}<input type='text' name='dekart_fields[$single_field->id]'></label></p>";
			}
			
			$str .= "<p><input type='hidden' name='dekart_form_id' value='{$form->id}' ><button type='submit'>Submit</button></p>";
			$str .= "</form>";
			
			return $str;
		} else {
			return false;
		}
	}
	
	// Doing what is needed after form submission
	public function dekartform_submission() {
		global $table_prefix, $wpdb;
		
		if(isset($_POST['dekart_form_id']) && !empty($_POST['dekart_form_id'])) {
			$wpdb->insert($table_prefix . 'dekart_entries', array(
				'form_id' => $_POST['dekart_form_id'],
			));
			
			$entry_id = $wpdb->insert_id;
			
			foreach($_POST['dekart_fields'] as $key=>$value) {
				$wpdb->insert($table_prefix . 'dekart_entries_fields', array(
					'field_id' => $key,
					'entry_id' => $entry_id,
					'content' => $value,
				));				
			}
			
		}
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Dekartforms_Loader. Orchestrates the hooks of the plugin.
	 * - Dekartforms_i18n. Defines internationalization functionality.
	 * - Dekartforms_Admin. Defines all hooks for the admin area.
	 * - Dekartforms_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dekartforms-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dekartforms-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-dekartforms-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-dekartforms-public.php';

		$this->loader = new Dekartforms_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Dekartforms_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Dekartforms_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Dekartforms_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Dekartforms_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Dekartforms_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	
	// Add a form for dekart forms
	public function insert_form() {
		
		global $table_prefix, $wpdb;
		
		if($_POST['dekartFormCreate']) {
			$wpdb->insert($table_prefix . 'dekart_forms', array(
				'title' => $_POST['form_title'],
			));	
			
			foreach($_POST['data'] as  $key=>$value) {
				$fields[] = "({$wpdb->insert_id}, '{$value[label]}', 'text',{$key})";
			}
			

			$fields_str = implode(",\n",$fields);
			
			$res = $wpdb->query("INSERT INTO {$table_prefix}dekart_fields
            (form_id, label, type, ord)
            VALUES
            {$fields_str}");	
			
			if($res) {
				echo json_encode(array('status' => 'success'));
			}

			exit;
						
		}		
	}
	
	// Reorderind fields
	public function reorder_fields() {
		global $table_prefix, $wpdb;
		
		foreach($_POST['data'] as  $key=>$value) {
			$res = $wpdb->update(
				$table_prefix . 'dekart_fields', 
				array( 
					'ord' => $key,	// string
						// integer (number) 
				), 
				array( 'id' => $value['id'] )
			);
			
		}
		exit;
	}
	
	// Insert a new field
	public function insert_field() {
		global $table_prefix, $wpdb;


		$wpdb->insert($table_prefix . 'dekart_fields', array(
			'form_id' => $_POST['data']['form_id'],
			'label' => "",
			'type' => "text",
			'ord' => 0
		));	
		echo json_encode(array('insert' => $wpdb->insert_id));
		exit;
	}

	
	// Edit form title
	public function edit_form_name() {
		global $table_prefix, $wpdb;
		
		$form_name = $_POST['form_name'];
		$form_id = $_POST['form_id'];
		
		$res = $wpdb->update(
			$table_prefix . 'dekart_forms', 
			array( 
				'title' => $form_name,
			), 
			array( 'id' => $form_id )
		);
		exit;
	}	
	
	
	// Edit single field label
	public function edit_field_label() {
		global $table_prefix, $wpdb;
		$fieldlabel = $_POST['label'];
		$id = $_POST['id'];
		$res = $wpdb->update(
			$table_prefix . 'dekart_fields', 
			array( 
				'label' => $fieldlabel,
			), 
			array( 'id' => $id )
		);
		exit;
	}

	
	// Delete a field
	public function delete_field() {
		global $table_prefix, $wpdb;
		
		$id = $_POST['id'];
		
		$res = $wpdb->delete(
			$table_prefix . 'dekart_fields', 
			array( 'id' => $id )
		);
		exit;
	}

	
	// Delete an entry
	public function delete_entry() {
		global $table_prefix, $wpdb;
		
		$id = $_GET['entry_id'];
		
		$res = $wpdb->delete(
			$table_prefix . 'dekart_entries', 
			array( 'id' => $id )
		);
		?>
		<script>
			window.location.href = "<?php echo add_query_arg( array( 'form_id' => $_GET['form_id'], 'task' => 'form_entries')); ?>"
		</script>			
		<?php		
		exit;
	}	
	
	
	// Router function that delegate functionality
	public function router() {
		$task = filter_input(INPUT_GET, 'task', FILTER_SANITIZE_STRING);
		$form_id = (filter_input(INPUT_GET, 'form_id', FILTER_SANITIZE_STRING)) ? filter_input(INPUT_GET, 'form_id', FILTER_SANITIZE_STRING) : NULL; 
		
		switch($task) {
			case "insert_form": 
				$this->insert_form();
				break;
			case "reorder_fields": 
				$this->reorder_fields();
				break;
			case "insert_field": 
				$this->insert_field();
				break;
			case "edit_field_label": 
				$this->edit_field_label();
				break;
			case "delete_field": 
				$this->delete_field();
				break;
			case "edit_form_name": 
				$this->edit_form_name();
				break;				
			case "delete_entry":
				$this->delete_entry();
				break;				
			default: 
				
		}		
	}

}
