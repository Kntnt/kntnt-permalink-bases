<?php

/**
 * Plugin main file.
 *
 * @wordpress-plugin
 * Plugin Name:       Kntnt's Permalink Bases plugin
 * Plugin URI:        https://www.kntnt.com/
 * Description:       Adds author base and date base to the permalink option page.
 * Version:           1.0.0
 * Author:            Thomas Barregren
 * Author URI:        https://www.kntnt.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       kntnt-permalink-bases
 * Domain Path:       /languages
 */
 
defined('WPINC') || die;

// Register installer. Uninstaller runs automatically on deletion.
register_activation_hook(__FILE__, function() {
	  require_once plugin_dir_path(__FILE__) . 'install.php';
});

// Bootstrap the plugin.
(new Kntnt_Permalink_Bases())->run();

// Plugin main class.
class Kntnt_Permalink_Bases {

  // This plugin's namespace
  private $ns;

  // Construct an object of this class.
  public function __construct() {
    $this->ns = basename(dirname(__FILE__));
    $this->load_dependencies();
    $this->load_textdomain();
  }
  
  // Setup public and administrative interfaces.
  public function run() {
    $cn = strtr(ucwords($this->ns, '-'), '-', '_');
    (new ReflectionClass("{$cn}_Public"))->newInstance($this->ns);
    (new ReflectionClass("{$cn}_Admin"))->newInstance($this->ns);
  }

  // Load public and administrative interfaces.
  private function load_dependencies() {
    require_once plugin_dir_path(__FILE__) . "public/class-{$this->ns}-public.php";
    require_once plugin_dir_path(__FILE__) . "admin/class-{$this->ns}-admin.php";
  }

  // Load localization.
  private function load_textdomain() {
    load_plugin_textdomain(
      $this->ns,
      false,
      "{$this->ns}/languages/"
    );
  }

}
