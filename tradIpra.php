<?php
/**
 * @package Traduction_IPRA
 * @version 1.0
 */
/*
Plugin Name: Traduction IPRA
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: Plugin to interface automatic translation
Version: 1.0
Author : Stéphane Loret, Diane Moussa
Author URI: http://ipra.eu/
License URI:	https://www.gnu.org/licenses/gpl-2.0.html
Domain Path:	/languages
Text Domain:	traduction-ipra
*
*		Copyright 2016 MSH Ange-Guépin / IPRA
*
* 		This program is free software; you can redistribute it and/or modify
* 		it under the terms of the GNU General Public License, version 2, as
* 		published by the Free Software Foundation.
*
* 		This program is distributed in the hope that it will be useful,
* 		but WITHOUT ANY WARRANTY; without even the implied warranty of
* 		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* 		GNU General Public License for more details.
*
* 		You should have received a copy of the GNU General Public License
* 		along with this program; if not, write to the Free Software
* 		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class Traduction_Ipra extends WP_Widget {

  /**
   * Configuration du widget
   *
   */
  function Traduction_Ipra() {
    $widget_ops = array(
      'classname'		=> 'traduction-ipra',
      'description'	=> __( 'Automatic translation of religious terms.', 'traduction-ipra' )
    );
    $control_ops = array(
      'width'		=> '100%',
      'height'	=> 350,
      'id_base'	=> 'traduction-ipra'
    );
    //create the widget (since 4.3)
    parent::__construct(
      'traduction-ipra',
      'Traduction IPRA',
      $widget_ops,
      $control_ops
    );

    add_action( 'init', array( $this, 'load_traduction_ipra_plugin_textdomain' ));

  }

  /**
   * Chargement des fichiers de langues
   *
   */
  function load_traduction_ipra_plugin_textdomain() {
    load_plugin_textdomain( 'traduction-ipra', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
  }




}
