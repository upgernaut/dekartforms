<?php

/**
 * Fired during plugin activation
 *
 * @link       http://aramkhachikyan.com
 * @since      1.0.0
 *
 * @package    Dekartforms
 * @subpackage Dekartforms/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Dekartforms
 * @subpackage Dekartforms/includes
 * @author     Aram Khachikyan <aram.khachikyan.a@gmail.com>
 */
class Dekartforms_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		
		// Add necessary tables into database on activation
		Dekartforms_Activator::add_necessary_tables();
	}
	
	public static function add_necessary_tables() {
		global $table_prefix, $wpdb;

		require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
		
		$tblname = 'dekart_forms';
		$wp_track_table = $table_prefix . "$tblname ";
		
		if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) 
		{
		
			$sql = "CREATE TABLE ". $wp_track_table . " ( ";
			$sql .= "  id  int(11)   NOT NULL auto_increment, ";
			$sql .= "  title  varchar(255)   NOT NULL, ";
			$sql .= "  PRIMARY KEY  `id`  (`id`) "; 
			$sql .= ") ENGINE=InnoDB " . $charset_collate . " AUTO_INCREMENT=1 ; ";
			
			$res = dbDelta($sql);
	
		}

		$tblname = 'dekart_fields';
		$wp_track_table = $table_prefix . "$tblname ";
		
		if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) 
		{
		
			$sql = "CREATE TABLE ". $wp_track_table . " ( ";
			$sql .= "  id  int(11)   NOT NULL auto_increment, ";
			$sql .= "  form_id  int(11)   NOT NULL, ";
			$sql .= "  label  varchar(255)  NOT NULL, ";
			$sql .= "  type  varchar(255)   NOT NULL, ";
			$sql .= "  ord  int(11)   NOT NULL, ";
			$sql .= "  PRIMARY KEY  id  (id),  "; 
			$sql .= "  FOREIGN KEY  (form_id)  REFERENCES  " . $table_prefix . "dekart_forms  (id) ";
			$sql .= "  ON DELETE CASCADE  ";
			$sql .= "  ON UPDATE CASCADE  ";
			$sql .= ") ENGINE=InnoDB " . $charset_collate . " AUTO_INCREMENT=1 ; ";
			
			$res = dbDelta($sql);
			
			// var_dump($wpdb->last_error); die;
		}	
		
		$tblname = 'dekart_entries';
		$wp_track_table = $table_prefix . "$tblname ";
		
		if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) 
		{
		
			$sql = "CREATE TABLE ". $wp_track_table . " ( ";
			$sql .= "  id  int(11)   NOT NULL auto_increment, ";
			$sql .= "  form_id  int(11)   NOT NULL, ";
			$sql .= "  submitted  TIMESTAMP   DEFAULT  CURRENT_TIMESTAMP, ";
			$sql .= "  PRIMARY KEY  id  (id),  "; 
			$sql .= "  FOREIGN KEY  (form_id)  REFERENCES  " . $table_prefix . "dekart_forms  (id) ";
			$sql .= "  ON DELETE CASCADE  ";
			$sql .= "  ON UPDATE CASCADE  ";
			$sql .= ") ENGINE=InnoDB " . $charset_collate . " AUTO_INCREMENT=1 ; ";
			
			$res = dbDelta($sql);
	
		}

		$tblname = 'dekart_entries_fields';
		$wp_track_table = $table_prefix . "$tblname ";
		
		if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) 
		{
		
			$sql = "CREATE TABLE ". $wp_track_table . " ( ";
			$sql .= "  id  int(11)   NOT NULL auto_increment, ";
			$sql .= "  field_id  int(11)   NOT NULL, ";
			$sql .= "  entry_id  int(11)   NOT NULL, ";
			$sql .= "  content  text  NOT NULL, ";
			$sql .= "  PRIMARY KEY  id  (id),  "; 
			$sql .= "  FOREIGN KEY  (field_id)  REFERENCES  " . $table_prefix . "dekart_fields  (id) ";
			$sql .= "  ON DELETE CASCADE  ";
			$sql .= "  ON UPDATE CASCADE,  ";			
			$sql .= "  FOREIGN KEY  (entry_id)  REFERENCES  " . $table_prefix . "dekart_entries  (id) ";
			$sql .= "  ON DELETE CASCADE  ";
			$sql .= "  ON UPDATE CASCADE  ";
			$sql .= ") ENGINE=InnoDB " . $charset_collate . " AUTO_INCREMENT=1 ; ";
			
			$res = dbDelta($sql);
	
		}			
				
	}

}
