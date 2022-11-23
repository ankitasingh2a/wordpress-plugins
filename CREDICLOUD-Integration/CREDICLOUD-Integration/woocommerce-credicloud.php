<?php
/* G4S AIM Payment Gateway Class */
class SPYR_CREDI_AIM extends WC_Payment_Gateway {

	// Setup our Gateway's id, description and other values
	function __construct() {

		// The global ID for this Payment method
		$this->id = "credicloud-gateway";

		// The Title shown on the top of the Payment Gateways Page next to all the other Payment Gateways
		$this->method_title = __( "Credicloud Gateway", 'credicloud-gateway' );

		// The description for this Payment Gateway, shown on the actual Payment options page on the backend
		$this->method_description = __( "Credicloud Payment Gateway Plug-in for WooCommerce", 'credicloud-gateway' );

		// The title to be used for the vertical tabs that can be ordered top to bottom
		$this->title = __( "CrediCloud Gateway", 'credicloud-gateway' );

		// If you want to show an image next to the gateway's name on the frontend, enter a URL to an image.
		$this->icon = null;

		// Bool. Can be set to true if you want payment fields to show on the checkout 
		// if doing a direct integration, which we are doing in this case
		$this->has_fields = true;

		// Supports the default credit card form
		$this->supports = array( 'default_credit_card_form' );

		// This basically defines your settings which are then loaded with init_settings()
		$this->init_form_fields();

		// After init_settings() is called, you can get the settings and load them into variables, e.g:
		// $this->title = $this->get_option( 'title' );
		$this->init_settings();
		
		// Turn these settings into variables we can use
		foreach ( $this->settings as $setting_key => $value ) {
			$this->$setting_key = $value;
		}
		
		// Include jQuery Payment
		add_action( 'wp_enqueue_scripts', array( $this, 'g4s_gateway_enqueue' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'g4s_gateway_enqueue_act' ) );
		
		// Lets check for SSL
		add_action( 'admin_notices', array( $this, 'do_ssl_check' ) );
		
		// Save settings
		if ( is_admin() ) {
			// Versions over 2.0
			// Save our administration options. Since we are not going to be doing anything special
			// we have not defined 'process_admin_options' in this class so the method in the parent
			// class will be used instead
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		}		
	} // End __construct()

	public function g4s_gateway_enqueue(){
		wp_enqueue_style( 'credicloud', plugin_dir_url(__FILE__)  . 'custom.css' );
   		// wp_enqueue_script( 'credicloud', plugin_dir_url(__FILE__)  . 'custom.css', array(), '1.0.0', true );
	}


	// Build the administration fields for this specific Gateway
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title'		=> __( 'Enable / Disable', 'g4s-gateway-aim' ),
				'label'		=> __( 'Enable this payment gateway', 'g4s-gateway-aim' ),
				'type'		=> 'checkbox',
				'default'	=> 'no',
			)
		);		
	}
	public function payment_fields() {
		if ( $this->description ) {
		// you can instructions for test mode, I mean test card numbers etc.
			if ( $this->testmode ) {
				$this->description .= ' TEST MODE ENABLED. In test mode, you can use the card numbers listed in <a href="#" target="_blank" rel="noopener noreferrer">documentation</a>.';
				$this->description  = trim( $this->description );
			}
			// display the description with <p> tags etc.
			echo wpautop( wp_kses_post( $this->description ) );
		}
	 
		// I will echo() the form, but you can close PHP tags and print it directly in HTML
		echo '<fieldset id="wc-' . esc_attr( $this->id ) . '-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;">';
	 
		// Add this action hook if you want your custom payment gateway to support it
		do_action( 'woocommerce_credit_card_form_start', $this->id );
	 
		// I recommend to use inique IDs, because other gateways could already use #ccNo, #expdate, #cvc
		echo '<div class="group_block">
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
			<div class="clear"></div>';
	 
		do_action( 'woocommerce_credit_card_form_end', $this->id );
	 
		echo '<div class="clear"></div></fieldset>';
	}
	
	// Submit payment and handle response
	public function process_payment( $order_id ) {
		global $woocommerce, $post;
		
		// Get this Order's information so that we know
		// who to charge and how much
		$Note_Details = ''; 
		$customer_order = wc_get_order( $order_id);			
	}

	

	// Validate fields
	public function validate_fields() {
		return true;
	}
	
	public function do_ssl_check() {
		if( $this->enabled == "yes" ) {
			if( get_option( 'woocommerce_force_ssl_checkout' ) == "no" ) {
				echo "<div class=\"error\"><p>". sprintf( __( "<strong>%s</strong> is enabled and WooCommerce is not forcing the SSL certificate on your checkout page. Please ensure that you have a valid SSL certificate and that you are <a href=\"%s\">forcing the checkout pages to be secured.</a>" ), $this->method_title, admin_url( 'admin.php?page=wc-settings&tab=checkout' ) ) ."</p></div>";	
			}
		}		
	}

} 
