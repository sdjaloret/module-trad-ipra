<?php
/**
Plugin Name: Traduction IPRA
Plugin URI: 
Description: Plugin to interface automatic translation
Version: 1.0
Author: Stéphane Loret, Diane Moussa
Author URI: http://ipra.eu/
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: traduction-ipra
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// enqueue the js script
add_action('wp_enqueue_scripts', 'plugintrad_enqueuescripts');
function plugintrad_enqueuescripts(){
	wp_register_script ('ajax-script', plugins_url( '/js/ajax-script.js',  __FILE__ ), array( 'jquery' ),'1',true);
	// passing the url of admin-ajx.php
	wp_localize_script( 'ajax-script', 'ajaxScript', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	wp_enqueue_script('ajax-script');	
}


// form to send source language, target language and the text to translate
// text area to receive the translated text
function plugintrad_form(){
	$handle = 'ajax-script';
	$list = 'enqueued';
		if (wp_script_is($handle, $list)) {
		print 'true';
		}else {
		print 'false';
	 }
	?>
	<form method="post" action="" id="form">
		<label for="source">Langue source</label>
			<select id="source" name="source">
				<option value="fr">Français</option>
				<option value="en" selected="selected">Anglais</option>
				<option value="ar">Arabe</option>
			</select>
		
		
		<label for="target">Langue cible</label>
			<select id="target" name="target">
				<option value="fr">Français</option>
				<option value="en">Anglais</option>
				<option value="ar">Arabe</option>
			</select>
		
		
		<p>
		<label for="textatrad">Saisissez le texte à traduire</label>
		<br />      
		<textarea name="textatrad" id="textatrad" rows="10" cols="50" maxlength ="255"></textarea>  
		</p>
		
		</form>
		<div>
			<textarea name="traduction" id="rep" rows="10" cols="50"></textarea>
		</div>
	<?php
} ?>

<?php 
//creating Ajax call for Wordpress
add_action('wp_ajax_nopriv_moduletrad_ajax_handler','moduletrad_ajax_handler');
add_action('wp_ajax_moduletrad_ajax_handler','moduletrad_ajax_handler');

// function to handle the info obtained with the form (url-ification, sending to the server hosting the translation program, retrieving the translated text)
function moduletrad_ajax_handler (){
	$params = array(
		'q' => urlencode(htmlspecialchars($_POST["q"])),
		'key' => "bla",
		'target' => $_POST["target"],
		'source' => $_POST["source"],
		);
	
	function httpPost($url,$params){
	$postData = '';
   //crée les paires nom-valeur séparées par &
   foreach($params as $k => $v) 
   { 
      $postData .= $k . '='.$v.'&'; 
   }
   $postData = rtrim($postData, '&'); //enlève le dernier & pour que la fin de l'url soit correcte
   $proxy = "172.20.12.74";
   $proxyport = "3128";

   $link = $url .'?'. $postData;
   //curl 
	$ch = curl_init(); // initialise la session curl
	
	// réglage des options curl
	curl_setopt($ch,CURLOPT_URL,$link); // url à récupérer
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true); // return la réponse du serveur
	/*curl_setopt($ch,CURLOPT_HEADER,false); // n'inclue pas l'en-tête dans la valeur de retour*/
	curl_setopt($ch,CURLOPT_PROXY, $proxy);
	curl_setopt($ch,CURLOPT_PROXYPORT, $proxyport);
	curl_setopt($cURL,CURLOPT_HTTPHEADER,array (
        "Content-type: application/json",
		"Accept: application/json"
    ));
		
	$output = curl_exec($ch);
	
	curl_close($ch); // ferme la session curl
	return trim($output);
}

$data = httpPost("http://galadriel.univ-lemans.fr:8002/translate", $params);
$data = json_decode($data, true);
$data = $data['data']['translations'][0]['translatedText'];
wp_send_json($data);
}

// adding the widget 
add_action('widgets_init','moduletrad_init');

function moduletrad_init(){
	register_widget("moduletrad_widget");
}

class moduletrad_widget extends WP_widget{
	
	function moduletrad_widget(){
		$widget_ops = array(
		'classname'		=> 'traduction-ipra',
		'description'	=> 'Automatic translation of religious terms'
		);
		parent::__construct('widget-moduletrad','Widget de traduction IPRA', $widget_ops);
	}
	
	function widget($args,$instance){
		extract($args);
		echo $before_widget;
		echo $before_title.$instance["titre"].$after_title;
		echo $after_widget;
		echo plugintrad_form();
	}
	
	
	function update($new,$old){
		return $new;
	}
	
	function form($instance){
	?>
	<p>
		<label for="<?php echo $this->get_field_id("titre"); ?>">Titre :</label>
		<input value="<?php echo $instance["titre"]; ?>" name="<?php echo $this->get_field_name("titre"); ?>" id="<?php echo $this->get_field_id("titre"); ?>" type="text"/>
	</p>
	<?php
	}
} 	
