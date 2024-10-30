<?php
/**
 * @package Bubok Seller
 */
/*
Plugin Name: Bubok Seller
Plugin URI: http://www.bubok.es
Description: Con este plugin puedes vender todos tus libros publicados en Bubok directamente desde tu blog, sin que el comprador de tu libro tenga que ir hasta la web de Bubok a finalizar el pago de la compra.
Version: 2.0
Author: bubok.es
License: Private
*/

if (!function_exists( 'add_action' )) {
	echo 'El plugin bubok no puede ser llamado directamente';
	exit;
}

define('BUBOK_SELL_VERSION', '1.0');

if (is_admin())
	require_once dirname( __FILE__ ) . '/admin.php';

$yaHeaderBubok = false;
$yaHeaderBubokCSS = false;

function shortcode_bubok($attrs) {
	global $yaHeaderBubok, $yaHeaderBubokCSS;

	if(!$yaHeaderBubokCSS){
		echo'<style>
		.btnCompraBubok{
			background-image: url('.plugins_url("bubok-white.png",__FILE__ ).');
			background-repeat: no-repeat;
			border-radius: 3px;
			color: #fff !important;
			padding: 8px 20px 8px 42px;
			background-color: #9abb4f;
			border: 1px solid #9abb4f;
			font-size: 14px;
			background-size: 25px;
			background-position: 10px 5px;
			display: inline-block;
			margin-bottom: 10px;
		}
		.btnCompraBubok:hover{
			background-color: #aec972;
    		border: 1px solid #aec972;
		}
		</style>';
		$yaHeaderBubokCSS=true;
	}


	$libros = unserialize(get_option( "librosBubokSeller" ));

	if(isset($libros[$attrs['id']])){
		$libro=$libros[$attrs['id']];

		$explodeUrl = explode("/",$libro['url']);
		$libroId = $explodeUrl[4];
		$dominio = $explodeUrl[2];

		if(!$yaHeaderBubok){
			echo '<script src="https://www.bubok.es/tienda/externstore/file_v2.js"></script>';
			$yaHeaderBubok=true;
		}

		if($libro['tipo']=="boton") $class="btnCompraBubok";
		else $class="";

		return '<a class="'.$class.'" title="'.$libro['texto'].'" alt="'.$libro['texto'].'" href="javascript:comprarLibroBubok('.$libroId.',\''.$libro['acabado'].'\',1,\''.$dominio.'\')">'.$libro['texto'].'</a>';

	}else{
		return "";
	}
	
	
}
add_shortcode('bubok', 'shortcode_bubok');
