<?php
/*
  Plugin Name: What The File
  Plugin URI: http://www.cageworks.nl/what-the-file/
  Description: Find out what template file (PHP) is used on the current page. What The File will be visible in the Toolbar when viewing your website.
  Version: 1.0
  Author: Barry Kooij
  Author URI: http://www.barrykooij.nl/
*/

class WhatTheFile
{
  private $template_name    = "";
  
  public function __construct()
  {
    if(!function_exists('wp_get_current_user')){include(ABSPATH . "wp-includes/pluggable.php");}
    if(!is_super_admin() || !is_admin_bar_showing()){return false;}
    if(is_admin()){return false;}
    $this->setup();
  }
  
  private function setup()
  {
    add_action('wp_enqueue_scripts',  array(&$this, 'enqueue_style'));
    add_filter('template_include',    array(&$this, 'save_current_page'), 1000);
    add_action('admin_bar_menu',     array( &$this, 'admin_bar_menu' ), 1000);
  }
  
  private function get_current_page()
  {
    return $this->template_name;
  }
  
  public function save_current_page($template_name)
  {
    $this->template_name = basename($template_name);
    return $template_name;
  }

  public function admin_bar_menu() {
    global $wp_admin_bar;      
    $wp_admin_bar->add_menu( array( 'id' => 'wtf-bar', 'parent' => 'top-secondary', 'title' => __('What The File', 'what-the-file'), 'href' => FALSE ) );
    $wp_admin_bar->add_menu( array( 'id' => 'wtf-bar-sub', 'parent' => 'wtf-bar', 'title' => $this->get_current_page(), 'href' => '/wp-admin/theme-editor.php?file='.$this->get_current_page().'&theme='.strtolower(wp_get_theme()) ) );
  }
  
  public function enqueue_style()
  {
    wp_register_style('wtf_css', plugins_url('what-the-file.css', __FILE__));
    wp_enqueue_style( 'wtf_css');
  }
  
}
new WhatTheFile();
?>