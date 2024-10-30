<?php

@set_time_limit(0);

add_action( 'admin_menu', 'bubok_admin_menu' );


function bubok_seller_conf() {

	wp_enqueue_script("jquery");

	$nameCampoGuardarConfig="librosBubokSeller";

	if(isset($_POST['enlaceLibroBubokSeller']) || (isset($_POST['submit']) && $_POST['addotro']=="")){
		$libros = array();
		if(isset($_POST['enlaceLibroBubokSeller'])) foreach($_POST['enlaceLibroBubokSeller'] as $key => $enlace){
			$libros[$key] = array(
				'url' => $enlace,
				'acabado' => $_POST['acabadoBubokSeller'][$key],
				'texto' => $_POST['textoEnlaceBubokSeller'][$key],
				'tipo' => $_POST['tipoBubokSeller'][$key]
			);
		}

		if ( get_option( $nameCampoGuardarConfig ) !== false ) {
		    update_option( $nameCampoGuardarConfig, serialize($libros) );
		} else {
		    add_option( $nameCampoGuardarConfig, serialize($libros), null, "yes" );
		}

	}else{
		$libros = get_option( $nameCampoGuardarConfig );
		$libros = unserialize($libros);
	}

	if($libros=="" || sizeof($libros)==0){
		$libros = array();
		$libros[1] = array(
			'url' => '',
			'acabado' => '',
			'texto' => 'Comprar libro',
			'tipo' => 'boton'
		);
	}

	if(isset($_POST['addotro']) && $_POST['addotro']==1){
		$libros[] = array(
			'url' => '',
			'acabado' => '',
			'texto' => 'Comprar libro',
			'tipo' => 'boton'
		);
	}
	
	/*$acabadoVenta = 'acabadoBubokSeller' ;
	if(isset($_POST[$acabadoVenta])){
		if ( get_option( $acabadoVenta ) !== false ) {
		    update_option( $acabadoVenta, $_POST[$acabadoVenta] );
		} else {
		    $deprecated = null;
		    $autoload = 'yes';
		    add_option( $acabadoVenta, $_POST[$acabadoVenta], $deprecated, $autoload );
		}
	}*/

	?>
	<br>
	
    <form action="" method="post">
	<div class="bubok wrap"> 
		<h2 style="margin-bottom:20px">
			<img title="Bubok" height="30" style="margin-top: -2px;float: left;margin-right: 6px;" src="https://www.bubok.es/img/general_bubok/dominios/bubok.es/logo.png"/> - 
			<?php echo esc_html( __('Configuración de Bubok Seller Plugin') ); ?>
		</h2>
		
		<div class="bubok-alerts">
			<h3><?php _e('A continuación podrás crear todos los enlaces que necesites a tus libros en Bubok. Recuerda que has de poner el shortcode donde quieras que aparezca el enlace de compra al visitante de tu web.')?><br>
			<?php _e('Cuando el usuario pulse sobre el enlace aparecerá una pantalla donde podrá adquirir el libro en el formato elegido y abonarlo, todo ello sin salir de tu página web.') ?>
			</h3>

			<table>
				<thead>
					<tr>
						<th></th>
						<th>
							<span class="tooltip">
								<?php _e('Enlace público a la ficha del libro')?> <img width="15" src="<?=plugins_url("info.png",__FILE__ )?>"/>
								<span class="tooltiptext"><?php _e('Se requiere la inserción del enlace correspondiente a la ficha pública de tu libro en la plataforma de Bubok.')?></span>
							</span>
						</th>
						<th>
							<span class="tooltip">
								<?php _e('Formato de venta')?> <img width="15" src="<?=plugins_url("info.png",__FILE__ )?>"/>
								<span class="tooltiptext"><?php _e('En Bubok es posible subir un libro en multiples formatos, en esta opción, podrás elegir el formato que quieres vender al pulsar el enlace de compra.')?></span>
							</span>
						</th>
						<th>
							<span class="tooltip">
								<?php _e('Texto enlace de compra')?> <img width="15" src="<?=plugins_url("info.png",__FILE__ )?>"/>
								<span class="tooltiptext"><?php _e('Define aquí el texto que quieres que aparezca en el enlace o botón de compra')?></span>
							</span>
						</th>
						<th>
							<span class="tooltip">
								<?php _e('¿Enlace o botón?')?> <img width="15" src="<?=plugins_url("info.png",__FILE__ )?>"/>
								<span class="tooltiptext"><?php _e('Elige si quieres motrar un enlace o un botón al estilo Bubok para vender tu libro')?></span>
							</span>
						</th>
						<th>
							<span class="tooltip">
								<?php _e('Shortcode')?> <img width="15" src="<?=plugins_url("info.png",__FILE__ )?>"/>
								<span class="tooltiptext"><?php _e('Este es el código que debes copiar y pegar donde quieras que aparezca el enlace en tu web o post')?></span>
							</span>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($libros as $id => $libro){ ?>
					<tr id="linea_<?=$id?>" data-id="<?=$id?>">
						<td>
							<a href="javascript:eliminar(<?=$id?>)">
								<img title="Eliminar" alt="Eliminar" src="<?=plugins_url("trash.png",__FILE__ )?>"/>
							</a>
						</td>
						<td>
							<input required="required" placeholder="<?php _e("Enlace público de tu libro") ?>" onchange="cargarTiposLibros(<?=$id?>,'')" style="width:500px" type="text" name="enlaceLibroBubokSeller[<?=$id?>]" id="enlaceLibroBubokSeller_<?=$id?>" value="<?=$libro['url']?>"/>	
						</td>
						<td>
							<select data-valload="<?=$libro['acabado']?>" class="acabado" required="required" id="acabadoBubokSeller_<?=$id?>" name="acabadoBubokSeller[<?=$id?>]"></select>	
						</td>
						<td>
							<input required="required" placeholder="<?php _e("Texto del botón de compra") ?>" style="width:200px" type="text" name="textoEnlaceBubokSeller[<?=$id?>]" value="<?=$libro['texto']?>"/>
						</td>
						<td>
							<select id="tipoBubokSeller_<?=$id?>" name="tipoBubokSeller[<?=$id?>]">
								<option value="boton" <?=$libro['tipo']=="boton" ? 'selected="selected"' : ''?>><? _e('Botón')?></option>
								<option value="enlace" <?=$libro['tipo']=="enlace" ? 'selected="selected"' : ''?>><? _e('Enlace')?></option>
							</select>	
						</td>
						<td>
							<b style="color:#8bbc44">[bubok id="<?=$id?>"]</b>
						</td>
					</tr>
					<?php } ?>
					<tr>
						<td colspan="5"></td>
						<td colspan="1"> 
							<a id="otroMas" class="otroMas" href="javascript:addOtro()">+ otro libro</a>
						</td>
					</tr>
				</tbody>
			</table>
			
			<input type="hidden" name="addotro" id="addotro" value=""/>

			<?php 
				submit_button( __('Guardar configuración') );
			?>
		</div>

		<div class="bubok-alerts" style="font-size:15px;background-color:#edf2e6">
			Para utilizar el botón de venta de tu libro con la configuración que has realizado, simplemente debes insertar el shortcode correspondiente al formato a verder ( ejemplo: <b>[bubok id="1"]</b> ) en la ubicación deseada de tu página web. De esta manera, tus visitantes podrán adquirir tu libro sin abandonar tu sitio.
		</div>
		
		
		</form>
	</div>	

	<style>
		.bubok-alerts {
			box-shadow: 0 1px 2px rgba(0,0,0,.2);
			background-color: #fff;
			padding: 20px;
			margin-bottom: 15px;
		}
		.otroMas{
			display: block;
			background-color: #8bbc44;
			border-radius: 3px;
			color: #ffffff;
			text-decoration: none;
			width: 100px;
			text-align: center;
			padding: 5px;
		}
		#submit{
			width:100%;
			font-size:16px;
		}
		.acabado{
			width:100%;
		}


		/* Tooltip text */
		.tooltip .tooltiptext {
			visibility: hidden;
			max-width: 200px;
			background-color: #e1edd0;
			color: #000;
			text-align: center;
			padding: 5px;
			border-radius: 6px;
			position: absolute;
			z-index: 1;
			font-weight: normal;
		}

		/* Show the tooltip text when you mouse over the tooltip container */
		.tooltip:hover .tooltiptext {
			visibility: visible;
		}
	</style>

	<script>

		function eliminar(id){
			jQuery("#linea_"+id).remove();
		}

		function addOtro(){
			document.getElementById('addotro').value=1;
			document.getElementById("submit").click();
		}

		function addOptionToSelect(value,text,id){
			select = document.getElementById('acabadoBubokSeller_'+id);

			option = document.createElement('option');
			option.value= value;
			option.innerHTML = text;

			select.appendChild(option);
		}

		function limpiarSelect(id){
			select = document.getElementById('acabadoBubokSeller_'+id);
			select.innerHTML = "";
		}

		function cargarTiposLibros(id,selected){
			
			limpiarSelect(id);

			addOptionToSelect("","Selecciona un formato...",id);
			
			getTiposPortadas(document.getElementById('enlaceLibroBubokSeller_'+id).value,selected,id);
		}
		function getTiposPortadas(url,selected,id){

			document.getElementById('submit').style.visibility="visible";
			document.getElementById('otroMas').style.visibility="visible";

			if(url.trim()=="") return true;

			libroId = url.split("/")[4];
			dominio = url.split("/")[2];
			url = "https://"+dominio+"/libro/get_acabados_of/"+libroId;

			fetch(url)
			.then((response) => {
				return response.json();
			}).then((data) => {
				limpiarSelect(id);
				data.map(function(tipo) {
					addOptionToSelect(tipo.id,tipo.nombre_formal,id);
				});
				if(selected!="" && selected!=null){
					document.getElementById('acabadoBubokSeller_'+id).value=selected;
				}
				
			}).catch(rejected => {
				document.getElementById('submit').style.visibility="hidden";
				document.getElementById('otroMas').style.visibility="hidden";
				alert("El enlace de la ficha pública del libro no es correcta, podría contener errores. Por favor, revísalo y vuelve a escribirlo.");
				
			});
		}

		jQuery(function(){
			jQuery( "tbody tr" ).each(function( index ) {
				if(jQuery( this ).attr("data-id")!="undefined"){
					cargarTiposLibros(jQuery( this ).attr("data-id"),jQuery("#acabadoBubokSeller_"+jQuery( this ).attr("data-id")).attr("data-valload"));
				}
			});
		});
	</script>
<?php
}


function bubok_admin_menu() {
	if ( class_exists( 'Jetpack' ) ) {
		add_action( 'jetpack_admin_menu', 'bubok_load_menu' );
	} else {
		bubok_load_menu();
	}
}

function bubok_load_menu() {
	$page = add_utility_page(__( 'Bubok Seller'), __( 'Bubok Seller'), 'administrator', 'bubok-seller', 'bubok_seller_conf',plugin_dir_url( __FILE__ ).'icon.png');	
}
