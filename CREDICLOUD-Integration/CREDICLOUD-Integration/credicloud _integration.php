<?php

/**

 

Plugin Name: Credicloud Integration 

Plugin URI: 

Description: Credicloud  API integration

Author: CrediCloud Team

Version: 1.0

Author URI: 

*/


//Create admin menu //////////////////////////////////

 add_action('admin_menu','create_sample_menu');



function create_sample_menu(){



//page title, menu title, capability, slug , function name

add_menu_page('Credicloud Integration','Credicloud Integration','manage_options','credicloud_integration_setting','credicloud_integration_setting');



   

 

}



function credicloud_integration_setting(){

global $wpdb;



  if(isset($_POST['submit'])){

    $api_endpoint=$_POST['api_endpoint'];

    update_option('api_endpoint',$api_endpoint,'yes');



    echo "Successfully Updated ! ";



  }



  $api_endpoint=get_option('api_endpoint');



?>

<h1>Credi Cloud API Setting</h1>



<form action="" method="post" enctype="multipart/form-data">

<table width="100%" border="0">

  <tr>

    <td width="25%">API End Point</td>

    <td width="75%"> <input  type="text" name="api_endpoint" value="<?php echo $api_endpoint; ?>" /> </td>

  </tr>

    <td>&nbsp;</td>

    <td><input type="submit" name="submit" value="Submit" /> </td>

  </tr>

</table>





   <br /><br />

   

<?php

  

    curl_calling();



}



function curl_calling()
{

  
    $api_endpoint=get_option('api_endpoint');
    $base_url='http://52.200.107.215/partner/api/login';


    $ch = curl_init();

    $logindata=json_encode(array(
    	'email'=>"user_ejecutivo@gmail.com",
    	'password'=>"admin"
    ));
    $postData = [

        "n_idSolicitud"=>"000120210010",

        "f_solicitud"=>"2021-01-31",

        "p_nombreU"=>"Karen Barrera",

        "cw_users_id"=>"4",

        "cw_tipo_credito_id"=>"1",

        "cw_tipo_solicitud_id"=>"1",

        "p_especificacionesProducto"=>"Samsung Galaxy s20",

        "p_nombre"=>"Cristian ",

        "p_p_apellido"=>"Alberto",

        "p_s_apellido"=>"",

        "p_emailcliente"=>"email@email.com",

        "p_dui"=>"1234080719961231",

        "p_nit"=>"123456789",

        "p_direccionfacturacion"=>"sample direction",

        "p_direccion"=>"sample direction",

        "p_telcasa"=>"12345678",

        "p_celular"=>"12345678",

        "p_teltrabajo"=>"",

        "p_fax"=>"",

        "n_cuotacasa"=> 0,

        "cw_tipo_vivienda_id"=>"propia",

        "p_tiemporesidir"=>"2 years",

        "p_forma_ingreso"=>"trabajo",

        "n_ingresomens"=>"300",

        "n_egresomens"=>"100",

        "p_lugartrabajo"=>"Remote",

        "p_tiempotrabajo"=>"2 years",

        "p_cargotrabajo"=>"Developer",

        "p_jefeinmediato"=>"Erick Chacon",

        "p_otro_ingreso"=> "",

        "p_nrc"=>"",

        "p_giro"=>"",

        "n_otros_ingresos"=> 0,

        "n_monto_credito"=>"400",

        "p_plazo_producto_cre"=>"",

        'ruta_DUI' => "path/dui.jpg",

        'ruta_NIT' => 'path/nit.jpg',

        'idPartner' => '21'

    ];

    curl_setopt($ch, CURLOPT_URL,$base_url);

    curl_setopt($ch, CURLOPT_POST, 1);

     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $logindata);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($logindata)));


    $result     = curl_exec ($ch);
    $data=json_decode($result);
   // print_r($result);
   //  $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    echo '<pre>';
    print_r($data);
    echo '</pre>';

    $access_token=$data->access_token;
    $partner=$data->partner;
    $id=$data->id;
    $name=$data->name;
    $base_url2="http://52.200.107.215/partner/api/historial/".$id;
    $ch = curl_init();
 	curl_setopt($ch, CURLOPT_URL,$base_url2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result2     = curl_exec ($ch);
    $data2=json_decode($result2);


    echo '<pre>';
    print_r($result2);
    echo '</pre>';

}

function loan_form()
{
	if(isset($_POST['loan_submit']))
	{

		$base_url='http://52.200.107.215/partner/api/login';


    $ch = curl_init();

    $logindata=json_encode(array(
    	'email'=>"user_ejecutivo@gmail.com",
    	'password'=>"admin"
    ));
    

    curl_setopt($ch, CURLOPT_URL,$base_url);

    curl_setopt($ch, CURLOPT_POST, 1);

     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $logindata);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($logindata)));


    $result     = curl_exec ($ch);
    $data=json_decode($result);

    // echo '<pre>';
    // print_r($data);
    // echo '</pre>';
    $access_token=$data->access_token;
    $partner=$data->partner;
    $id=$data->id;
    $name=$data->name;
    $base_url2="http://52.200.107.215/partner/api/nosolicitud/".$id."/".$partner."?token=".$access_token;
    $ch = curl_init();
 	curl_setopt($ch, CURLOPT_URL,$base_url2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result2     = curl_exec ($ch);
    $data2=json_decode($result2);
    // echo '<pre>';
    // print_r($data2);
    // echo '</pre>';
    $codigo=$data2[0]->codigo;

    $base_url3="http://52.200.107.215/partner/api/fechasolicitud/?token=".$access_token;
    $ch = curl_init();
 	curl_setopt($ch, CURLOPT_URL,$base_url3);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result3     = curl_exec ($ch);
    $data3=json_decode($result3);
    // echo '<pre>';
    // print_r($data3);
    // echo '</pre>';
    $fecha=$data3[0]->fecha;

    $postData = json_encode(array(

        "n_idSolicitud"=>$codigo,

        "f_solicitud"=>$fecha,

        "p_nombreU"=>$name,

        "cw_users_id"=>$id,

        "cw_tipo_credito_id"=>"1",

        "cw_tipo_solicitud_id"=>"1",

        "p_especificacionesProducto"=>"Samsung Galaxy s20",

        "p_nombre"=>"Cristian ",

        "p_p_apellido"=>"Alberto",

        "p_s_apellido"=>"",

        "p_emailcliente"=>"email@email.com",

        "p_dui"=>"1234080719961231",

        "p_nit"=>"123456789",

        "p_direccionfacturacion"=>"sample direction",

        "p_direccion"=>"sample direction",

        "p_telcasa"=>"12345678",

        "p_celular"=>"12345678",

        "p_teltrabajo"=>"",

        "p_fax"=>"",

        "n_cuotacasa"=> 0,

        "cw_tipo_vivienda_id"=>"propia",

        "p_tiemporesidir"=>"2 years",

        "p_forma_ingreso"=>"trabajo",

        "n_ingresomens"=>"300",

        "n_egresomens"=>"100",

        "p_lugartrabajo"=>"Remote",

        "p_tiempotrabajo"=>"2 years",

        "p_cargotrabajo"=>"Developer",

        "p_jefeinmediato"=>"Erick Chacon",

        "p_otro_ingreso"=> "",

        "p_nrc"=>"",

        "p_giro"=>"",

        "n_otros_ingresos"=> 0,

        "n_monto_credito"=>"400",

        "p_plazo_producto_cre"=>"",

        'ruta_DUI' => "path/dui.jpg",

        'ruta_NIT' => 'path/nit.jpg',

        'idPartner' => '3'

    ));

	$base_url4='http://52.200.107.215/partner/api/cliente/'.$partner.'/?token='.$access_token;


    $ch = curl_init();
    

    curl_setopt($ch, CURLOPT_URL,$base_url4);

    curl_setopt($ch, CURLOPT_POST, 1);

     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($postData)));


    $result     = curl_exec ($ch);
    $data=json_decode($result);
    echo '<pre>';
    print_r($result);
    print_r($data);
    echo '</pre>';
	}
	?>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
	<style type="text/css">
/*custom font*/
@import url(https://fonts.googleapis.com/css?family=Montserrat);


body {
    font-family: montserrat, arial, verdana;
    
}
h3.fs-subtitle {
    background: #ddd;
    padding: 10px;
    text-align: left;
}
/*form styles*/
#msform {
    text-align: left;
    position: relative;
    margin-top: 30px;
}

#msform fieldset {
    background: white;
    border: 0 none;
    border-radius: 0px;
    box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.4);
    padding: 20px 30px;
    box-sizing: border-box;
    width: 80%;
    margin: 0 10%;

    /*stacking fieldsets above each other*/
    position: relative;
}

/*Hide all except first fieldset*/
#msform fieldset:not(:first-of-type) {
    display: none;
}

/*inputs*/
#msform input, #msform textarea {
    padding: 15px;
    border: 1px solid #ccc;
    border-radius: 0px;
    margin-bottom: 10px;
    width: 100%;
    box-sizing: border-box;
    font-family: montserrat;
    color: #2C3E50;
    font-size: 13px;
}

#msform input:focus, #msform textarea:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border: 1px solid #03a9f4;
    outline-width: 0;
    transition: All 0.5s ease-in;
    -webkit-transition: All 0.5s ease-in;
    -moz-transition: All 0.5s ease-in;
    -o-transition: All 0.5s ease-in;
}

/*buttons*/
#msform .action-button {
    width: 100px;
    background: #03a9f4;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 25px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px;
}

#msform .action-button:hover, #msform .action-button:focus {
    box-shadow: 0 0 0 2px white, 0 0 0 3px #03a9f4;
}

#msform .action-button-previous {
    width: 100px;
    background: #C5C5F1;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 25px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px;
}

#msform .action-button-previous:hover, #msform .action-button-previous:focus {
    box-shadow: 0 0 0 2px white, 0 0 0 3px #C5C5F1;
}

/*headings*/
.fs-title {
    font-size: 18px;
    text-transform: uppercase;
    color: #2C3E50;
    margin-bottom: 10px;
    letter-spacing: 2px;
    font-weight: bold;
}

.fs-subtitle {
    font-weight: normal;
    font-size: 13px;
    color: #666;
    margin-bottom: 20px;
}

/*progressbar*/
#progressbar {
    margin-bottom: 30px;
    overflow: hidden;
    /*CSS counters to number the steps*/
    counter-reset: step;
}

#progressbar li {
    list-style-type: none;
    color: #100f0f;
    text-transform: uppercase;
    font-size: 9px;
    width: 33.33%;
    float: left;
    position: relative;
    letter-spacing: 1px;
    text-align: center;
}

#progressbar li:before {
    content: counter(step);
    counter-increment: step;
    width: 24px;
    height: 24px;
    line-height: 26px;
    display: block;
    font-size: 12px;
    color: #333;
    background: #bbb8b8;
    border-radius: 25px;
    margin: 0 auto 10px auto;
}

/*progressbar connectors*/
#progressbar li:after {
    content: '';
    width: 100%;
    height: 2px;
    background: #333;
    position: absolute;
    left: -50%;
    top: 9px;
    z-index: -1; /*put it behind the numbers*/
}

#progressbar li:first-child:after {
    /*connector not needed before the first step*/
    content: none;
}

/*marking active/completed steps green*/
/*The number of the step and the connector before it = green*/
#progressbar li.active:before, #progressbar li.active:after {
    background: #03a9f4;
    color: white;
}


/* Not relevant to this form */
.dme_link {
    margin-top: 30px;
    text-align: center;
}
.dme_link a {
    background: #FFF;
    font-weight: bold;
    color: #03a9f4;
    border: 0 none;
    border-radius: 25px;
    cursor: pointer;
    padding: 5px 25px;
    font-size: 12px;
}

.dme_link a:hover, .dme_link a:focus {
    background: #C5C5F1;
    text-decoration: none;
}

.group_block {
    display: inline-block;
    width: 100%;
}
	</style>
        <div class="">

            <div class="text-center darken-grey-text mb-4">
                <h1 class="font-bold mt-4 mb-3 h5" style="color: green;">¡Completa el Siguiente Formulario!</h1>
                <p>Al llenar los campos de este formulario, podrás aplicar a un crédito con el banco de tu elección
este servicio es necesario para completar el proceso de compra de tu dispositivo.
Al completarlo recibirás un correo con la información de aprobación o rechazo de tu solicitud de crédito.</p>
            </div>
            <div class="row">
            	<form id="msform" method="POST">
		            <!-- progressbar -->
		            <ul id="progressbar">
		                <li class="active">LLENAR DATOS</li>
		                <li>VERIFICAR</li>
		                <li>RESOLUCION</li>
		            </ul>
		            <!-- fieldsets -->
		            <fieldset>
		                <div class="group_block">
			                <h3 class="fs-subtitle">Datos de formulario</h3>
			                <div class="form-group col-md-6">
							    <label for="email">Ejecutivo</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
							<div class="form-group col-md-6">
							    <label for="email">Fecha</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
							<div class="form-group col-md-6">
							    <label for="email">Codigo vendedor</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
						</div>
						<div class="group_block">
							<h3 class="fs-subtitle">Datos Generales da la Solicitud</h3>
							<div class="form-group col-md-6">
							    <label for="email">No. de Solicitud</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
						</div>
						<div class="group_block">
							<h3 class="fs-subtitle">Datos del Cliente</h3>
							<div class="form-group col-md-6">
							    <label for="email">Nombers</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
							<div class="form-group col-md-6">
							    <label for="email">1er apelido</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
							<div class="form-group col-md-6">
							    <label for="email">1do apelido</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
							<div class="form-group col-md-6">
							    <label for="email">DUI sin guiones</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
							<div class="form-group col-md-6">
							    <label for="email">NIT</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
							<div class="col-md-offset-6 col-md-6"></div>
							<div class="form-group col-md-6">
							    <label for="email">Direccion</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
							<div class="form-group col-md-6">
							    <label for="email">Direccion de Facturacion</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
							<div class="form-group col-md-6">
							    <label for="email">Telefono</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
							<div class="form-group col-md-6">
							    <label for="email">Celular</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
							<div class="form-group col-md-6">
							    <label for="email">Tel. Trabajo</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
							<div class="form-group col-md-6">
							    <label for="email">Fax</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
							<div class="form-group col-md-6">
							    <label for="email">Tipo de Vivienda</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
							<div class="form-group col-md-6">
							    <label for="email">Cuota de Vivienda</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
							<div class="form-group col-md-6">
							    <label for="email">Tiempo de Residir</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
							<div class="form-group col-md-6">
							    <label for="email">Correo Electronice</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
						</div>
						<div class="group_block">
							<h3 class="fs-subtitle">Fuente de Ingresos y Refencias Personales</h3>
							<div class="form-group col-md-6">
							    <label for="email">Ingresos Mensuales</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
							<div class="form-group col-md-6">
							    <label for="email">Egresos Mensuales</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
							<div class="form-group col-md-6">
							    <label for="email">Lugar de trabajo</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
							<div class="form-group col-md-6">
							    <label for="email">Tiempo de Trabajo</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
							<div class="form-group col-md-6">
							    <label for="email">Cargo</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
							<div class="form-group col-md-6">
							    <label for="email">Jefe Inmediato</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
						</div>
						<div class="group_block">
							<h3 class="fs-subtitle">Especificaciones de Terminal y/o Plan</h3>
							<div class="form-group col-md-12">
							    <label for="email">Detalies</label>
							    <input type="textarea" class="form-control" name="Ejecutivo">
							</div>
							<div class="form-group col-md-6">
							    <label for="email">Monto $</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
							<div class="form-group col-md-6">
							    <label for="email">Plazo</label>
							    <select>
							    	<option>18 meses</option>
							    </select>
							</div>
						</div>
						<div class="group_block">
							<h3 class="fs-subtitle">Documentos del Cliente</h3>
							<div class="form-group col-md-6">
							    <input type="file" class="form-control" name="subir_dui">
							</div>
							<div class="form-group col-md-6">
							    <input type="file" class="form-control" name="subir_dui">
							</div>
						</div>
						<div class="group_block">
							<h3 class="fs-subtitle">Otros campos</h3>
							<div class="form-group col-md-12">
							    <label for="email">Nombre</label>
							    <input type="text" class="form-control" name="Ejecutivo">
							</div>
						</div>
		                <input type="button" name="next" class="next action-button" value="Continuar"/>
		            </fieldset>
		             <fieldset>
		                <h2 class="fs-title">VERIFICAR</h2>
		                <input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
		                <input type="button" name="next" class="next action-button" value="Next"/>
		            </fieldset>
		            <fieldset>
		                <h2 class="fs-title">RESOLUCION</h2>
		                <input type="button" name="previous" class="previous action-button-previous" value="Previous"/>
		                <input type="submit" name="submit" class="submit action-button" value="Submit"/>
		                
		            </fieldset>
		        </form>
                <!-- Grid column -->
                <!-- <div class="col-md-12 ">
                    <div class="card">
                        <div class="card-body">
                            Form contact -->
                            <!-- <form method="POST">
                                <h2 class="text-center py-4 font-bold font-up danger-text">Loan</h2>
                                <div class="md-form">
                                    <input type="text" id="form31" class="form-control" name="fname">
                                    <label for="form31">Your Fisrt Name</label>
                                </div>
                                 <div class="md-form">
                                    <input type="text" id="form31" class="form-control" name="lname">
                                    <label for="form31">Your Last Name</label>
                                </div>
                                <div class="md-form">
                                    
                                    <input type="text" id="form21" class="form-control" name="email">
                                    <label for="form21">Your email</label>
                                </div>
                                <div class="md-form">
                                    
                                    <input type="text" id="form21" class="form-control" name="product_name">
                                    <label for="form21">Product Name</label>
                                </div>
                                <div class="md-form">
                                    
                                    <input type="text" id="form21" class="form-control" name="product_name">
                                    <label for="form21">Product Name</label>
                                </div>
                                <div class="md-form">
                                    
                                    <input type="number" id="form21" class="form-control" name="dui">
                                    <label for="form21">DUI</label>
                                </div>
                                <div class="md-form">
                                    
                                    <input type="number" id="form21" class="form-control" name="nit">
                                    <label for="form21">NIT</label>
                                </div>
                                <div class="md-form">
                                    
                                    <input type="text" id="form21" class="form-control" name="nit">
                                    <label for="form21">Direction</label>
                                </div>
                                <div class="md-form">
                                    
                                    <input type="text" id="form21" class="form-control" name="nit">
                                    <label for="form21">Direction</label>
                                </div>
                                <div class="md-form">
                                    
                                    <input type="number" id="form21" class="form-control" name="telcasa">
                                    <label for="form21">telcasa</label>
                                </div>
                                <div class="md-form">
                                    
                                    <input type="number" id="form21" class="form-control" name="celular">
                                    <label for="form21">celular</label>
                                </div>
                                <div class="md-form">
                                    
                                    <input type="text" id="form21" class="form-control" name="vivienda_id">
                                    <label for="form21">vivienda id</label>
                                </div>
                                <div class="md-form">
                                    
                                    <select name="tiemporesidir">
                                    	<option value="2 years">2 years</option>
                                    	<option value="3 years">3 years</option>
                                    </select>
                                    <label for="form21">tiemporesidir</label>
                                </div>
                                <div class="md-form">
                                    
                                    <input type="text" id="form21" class="form-control" name="ingreso">
                                    <label for="form21">ingreso</label>
                                </div>
                                <div class="md-form">
                                    
                                    <input type="text" id="form21" class="form-control" name="ingresomens">
                                    <label for="form21">ingresomens</label>
                                </div>
                                <div class="md-form">
                                    
                                    <input type="text" id="form21" class="form-control" name="egresomens">
                                    <label for="form21">egresomens</label>
                                </div>
                                <div class="md-form">
                                    
                                    <input type="text" id="form21" class="form-control" name="lugartrabajo">
                                    <label for="form21">lugartrabajo</label>
                                </div>
                                <div class="md-form">
                                    
                                    <input type="text" id="form21" class="form-control" name="tiempotrabajo">
                                    <label for="form21">tiempotrabajo</label>
                                </div>
                                <div class="md-form">
                                    
                                    <input type="text" id="form21" class="form-control" name="cargotrabajo">
                                    <label for="form21">cargotrabajo</label>
                                </div>
                                <div class="md-form">
                                    
                                    <input type="text" id="form21" class="form-control" name="jefeinmediato">
                                    <label for="form21">jefeinmediato</label>
                                </div>
                                <div class="md-form">
                                    
                                    <input type="text" id="form21" class="form-control" name="otros_ingresos">
                                    <label for="form21">otros ingresos</label>
                                </div>
                                <div class="md-form">
                                    
                                    <input type="text" id="form21" class="form-control" name="monto_credito">
                                    <label for="form21">monto credito</label>
                                </div>
                                <div class="md-form">
                                    
                                    <input type="text" id="form21" class="form-control" name="producto_credito">
                                    <label for="form21">producto credito</label>
                                </div>

                                <div class="text-center">
                                    <button class="btn btn-outline-danger btn-lg" type="submit" name="loan_submit">Send</button>
                                </div>
                            </form> -->
                            <!-- Form contact -->
                      <!--   </div>
                    </div>
                </div> -->
            </div>
         

        </div>
        <script type="text/javascript">
		
			//jQuery time
			var current_fs, next_fs, previous_fs; //fieldsets
			var left, opacity, scale; //fieldset properties which we will animate
			var animating; //flag to prevent quick multi-click glitches

			$(".next").click(function(){
				if(animating) return false;
				animating = true;
				
				current_fs = $(this).parent();
				next_fs = $(this).parent().next();
				
				//activate next step on progressbar using the index of next_fs
				$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
				
				//show the next fieldset
				next_fs.show(); 
				//hide the current fieldset with style
				current_fs.animate({opacity: 0}, {
					step: function(now, mx) {
						//as the opacity of current_fs reduces to 0 - stored in "now"
						//1. scale current_fs down to 80%
						scale = 1 - (1 - now) * 0.2;
						//2. bring next_fs from the right(50%)
						left = (now * 50)+"%";
						//3. increase opacity of next_fs to 1 as it moves in
						opacity = 1 - now;
						current_fs.css({
			        'transform': 'scale('+scale+')',
			        'position': 'absolute'
			      });
						next_fs.css({'left': left, 'opacity': opacity});
					}, 
					duration: 800, 
					complete: function(){
						current_fs.hide();
						animating = false;
					}, 
					//this comes from the custom easing plugin
					easing: 'easeInOutBack'
				});
			});

			$(".previous").click(function(){
				if(animating) return false;
				animating = true;
				
				current_fs = $(this).parent();
				previous_fs = $(this).parent().prev();
				
				//de-activate current step on progressbar
				$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
				
				//show the previous fieldset
				previous_fs.show(); 
				//hide the current fieldset with style
				current_fs.animate({opacity: 0}, {
					step: function(now, mx) {
						//as the opacity of current_fs reduces to 0 - stored in "now"
						//1. scale previous_fs from 80% to 100%
						scale = 0.8 + (1 - now) * 0.2;
						//2. take current_fs to the right(50%) - from 0%
						left = ((1-now) * 50)+"%";
						//3. increase opacity of previous_fs to 1 as it moves in
						opacity = 1 - now;
						current_fs.css({'left': left});
						previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
					}, 
					duration: 800, 
					complete: function(){
						current_fs.hide();
						animating = false;
					}, 
					//this comes from the custom easing plugin
					easing: 'easeInOutBack'
				});
			});

			$(".submit").click(function(){
				return false;
			})
	</script>
	<?php
}
add_shortcode('loan_form','loan_form');

add_action( 'plugins_loaded', 'spyr_g4s_init', 0 );
function spyr_g4s_init() {
	// If the parent WC_Payment_Gateway class doesn't exist
	// it means WooCommerce is not installed on the site
	// so do nothing
	if ( ! class_exists( 'WC_Payment_Gateway' ) ) return;
	
	// If we made it this far, then include our Gateway Class
	include_once( 'woocommerce-credicloud.php' );

	// Now that we have successfully included our class,
	// Lets add it too WooCommerce
	add_filter( 'woocommerce_payment_gateways', 'spyr_add_g4s_aim_gateway' );
	
	function spyr_add_g4s_aim_gateway( $methods ) {
		$methods[] = 'SPYR_CREDI_AIM';
		return $methods;
	}
}

// Add custom action links
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'spyr_g4s_action_links' );
function spyr_g4s_action_links( $links ) {
	$plugin_links = array(
		'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout' ) . '">' . __( 'Settings', 'credicloud-gateway' ) . '</a>',
	);

	// Merge our new link with the default ones
	return array_merge( $plugin_links, $links );	
}

function credicloud_myaccount( $menu_links ){
 
    // we will hook "womanide-forum" later
    $new = array( 'credicloud-status' => 'Loan Status' );
 
    // or in case you need 2 links
    // $new = array( 'link1' => 'Link 1', 'link2' => 'Link 2' );
 
    // array_slice() is good when you want to add an element between the other ones
    $menu_links = array_slice( $menu_links, 0, 1, true ) 
    + $new 
    + array_slice( $menu_links, 1, NULL, true );
 
 
    return $menu_links;
 
 
}
add_filter ( 'woocommerce_account_menu_items', 'credicloud_myaccount' );

function credi_status()
{

}

add_shortcode('credi_status','credi_status');