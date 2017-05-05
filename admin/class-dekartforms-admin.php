<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://aramkhachikyan.com
 * @since      1.0.0
 *
 * @package    Dekartforms
 * @subpackage Dekartforms/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dekartforms
 * @subpackage Dekartforms/admin
 * @author     Aram Khachikyan <aram.khachikyan.a@gmail.com>
 */
class Dekartforms_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		add_action( 'admin_menu', array($this,'my_admin_menu') );

	}
	
	public function my_admin_menu() {
		add_menu_page( 'Dekart Forms', 'Dekart Forms', 'manage_options', 'dekartforms', array($this,'router'), 'dashicons-tickets', 25  );
	}

	public function router(){
		$task = filter_input(INPUT_GET, 'task', FILTER_SANITIZE_STRING);
		$form_id = (filter_input(INPUT_GET, 'form_id', FILTER_SANITIZE_STRING)) ? filter_input(INPUT_GET, 'form_id', FILTER_SANITIZE_STRING) : NULL; 
		
		switch($task) {
			case "add_form": 
				$this->add_form();
				break;
			case "edit_form":
				echo 'edit';
				break;
			case "delete_form":
				$this->delete_form($form_id);
				break;
			case "form_entries":
				$this->form_entries($form_id );
				break;
			case "single_entry":
				echo "single";
				break;
			case "delete_entry":
				echo 'delete entry';
				break;
			default: 
				$this->show_forms();
		}
	}
	
	/**
	 * Delete form
	 *
	 * @since    1.0.0
	 */	
	public function delete_form($form_id) {
		global $table_prefix, $wpdb;
		
		$table = $table_prefix . 'dekart_forms';
		$results = $wpdb->delete( $table, array( 'id' => $form_id ));

		?>
		<script>
			window.location.href = "<?php echo add_query_arg( array( 'task' => 'return')); ?>"
		</script>			
		<?php 
		exit;
	}	
	
	/**
	 * Add new form 
	 *
	 * @since    1.0.0
	 */	
	public function add_form() {
		
		global $table_prefix, $wpdb;
		
		if($_POST['dekartFormCreate']) {

			$wpdb->insert($table_prefix . 'dekart_forms', array(
				'title' => $_POST['form_title'],
			));	
			
			
			foreach($_POST['title'] as $key=>$value) {
				$fields[] = "({$wpdb->insert_id},'{$value}','type',{$key})";
			}
			
			$fields_str = implode(",\n",$fields);
			
			$wpdb->query("INSERT INTO {$table_prefix}dekart_fields
            (form_id, name, type, ord)
            VALUES
            {$fields_str}");	
			
			?>
			<script>
				window.location.href = "<?php echo add_query_arg( array( 'task' => 'return')); ?>"
			</script>			
			<?php 
			exit;
						
		}

		require_once plugin_dir_path( __DIR__ ) . 'admin/partials/dekartforms-admin-add-form.php';
	}	
	
	/**
	 * Show form list
	 *
	 * @since    1.0.0
	 */	
	public function show_forms() {
		
		
		global $table_prefix, $wpdb;
		
		$table = $table_prefix . 'dekart_forms';
		$results = $wpdb->get_results( 'SELECT * FROM ' . $table, OBJECT );

		require_once plugin_dir_path( __DIR__ ) . 'admin/partials/dekartforms-admin-forms.php';
	}
	
	/**
	 * Show form list
	 *
	 * @since    1.0.0
	 */	
	public function form_entries($form_id ) {
		
		global $table_prefix, $wpdb;
		
		$table = $table_prefix . 'dekart_entries';
		$form_table = $table_prefix . 'dekart_forms';
		$form = $wpdb->get_row( 'SELECT * FROM ' . $form_table . ' WHERE id=' . $form_id, OBJECT );
		
		$results = $wpdb->get_results( 'SELECT * FROM ' . $table . ' WHERE form_id=' . $form_id, OBJECT );

		require_once plugin_dir_path( __DIR__ ) . 'admin/partials/dekartforms-admin-form-entries.php';
	}	

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dekartforms_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dekartforms_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dekartforms-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dekartforms_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dekartforms_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dekartforms-admin.js', array( 'jquery' ), $this->version, false );
		
		wp_enqueue_script("jquery-effects-core-ui",'https://code.jquery.com/ui/1.12.1/jquery-ui.js', array('jquery'),$this->version, false);	

	}

}
