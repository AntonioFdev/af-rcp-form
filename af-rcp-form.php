<?php

/*
Plugin Name: af-rcp-form
Plugin URI: https://antoniofernandezeb.es
Description: Plugin para añadir campos extra al formulario de Restrict Content Pro.
Version: 1.0
Author: Antonio Fernández
Author URI: https://antoniofernandezeb.es
License: GPL2
*/

/**
 * Displays on the invoice how much of the payment was for VAT. This assumes you've bundled
 * the VAT into the cost of the subscription level. This will calculate your chosen
 * VAT percentage (21% by default) from the full cost of the subscription level and
 * display the amount of money on the invoice right above the total amount.
 *
 * @param object $rcp_payment Payment object from the database.
 *
 * @return void
 */
function af_rcp_display_vat_on_invoice( $rcp_payment ) {
	// Don't show VAT if payment is free.
	if ( empty( $rcp_payment->amount ) ) {
		return;
	}

	$vat_percentage = 21; // This is 21% VAT by default. Adjust number here to change VAT percentage.

	// Calculate VAT amount.
	$total_price = $rcp_payment->amount;

	$vat = $rcp_payment->amount / ( 1 + ( $vat_percentage / 100 ) );
	$vat = round( ( $vat - $rcp_payment->amount ) * -1, 2 );

	$price_no_tax = $total_price - $vat;

	?>

	<tr>
		<td class="name"><?php echo "Precio sin IVA" ?></td>
		<td class="price">
			<?php
			echo rcp_currency_filter( $price_no_tax );
			?>
		</td>
	</tr>

	<tr>
		<td class="name"><?php printf( __( '%d%% IVA' ), $vat_percentage ); ?></td>
		<td class="price">
			<?php
			echo rcp_currency_filter( $vat );
			?>
		</td>
	</tr>
	<?php
}

add_action( 'rcp_invoice_items_before_total_price', 'af_rcp_display_vat_on_invoice' );

/**
 * Adds the custom fields to the registration form and profile editor
 *
 */
function af_rcp_add_user_fields() {

	$cif = get_user_meta( get_current_user_id(), 'rcp_cif', true );	
	$codpost   = get_user_meta( get_current_user_id(), 'rcp_codpost', true );
	$direccion   = get_user_meta( get_current_user_id(), 'rcp_direccion', true );
	$ciudad   = get_user_meta( get_current_user_id(), 'rcp_ciudad', true );
	$vatnumber   = get_user_meta( get_current_user_id(), 'rcp_vatnumber', true );


	?>
	<p>
		<label for="rcp_cif"><?php _e( 'DNI / CIF', 'rcp' ); ?></label>
		<input name="rcp_cif" id="rcp_cif" type="text" value="<?php echo esc_attr( $cif ); ?>"/>
	</p>

	<p>
		<label for="rcp_codpost"><?php _e( 'CÓDIGO POSTAL', 'rcp' ); ?></label>
		<input name="rcp_codpost" id="rcp_codpost" type="text" value="<?php echo esc_attr( $codpost ); ?>"/>
	</p>

	<p>
		<label for="rcp_ciudad"><?php _e( 'CIUDAD', 'rcp' ); ?></label>
		<input name="rcp_ciudad" id="rcp_ciudad" type="text" value="<?php echo esc_attr( $ciudad ); ?>"/>
	</p>

	<p>
		<label for="rcp_direccion"><?php _e( 'DIRECCIÓN', 'rcp' ); ?></label>
		<input name="rcp_direccion" id="rcp_direccion" type="text" value="<?php echo esc_attr( $direccion ); ?>"/>
	</p>

	<p>
		<label for="rcp_vatnumber"><?php _e( 'NÚMERO VAT (opcional, Si dispones de numero intracomunitario ponlo aquí)', 'rcp' ); ?></label>
		<input name="rcp_vatnumber" id="rcp_vatnumber" type="text" value="<?php echo esc_attr( $vatnumber ); ?>"/>
	</p>
	<?php
}
add_action( 'rcp_after_password_registration_field', 'af_rcp_add_user_fields' );
add_action( 'rcp_profile_editor_after', 'af_rcp_add_user_fields' );


/**
 * Adds the custom fields to the member edit screen
 *
 */
function af_rcp_add_member_edit_fields( $user_id = 0 ) {

	$cif = get_user_meta( $user_id, 'rcp_cif', true );	
	$codpost = get_user_meta( $user_id, 'rcp_codpost', true );
	$direccion   = get_user_meta( $user_id, 'rcp_direccion', true );
	$ciudad   = get_user_meta( $user_id, 'rcp_ciudad', true );
	$vatnumber = get_user_meta( $user_id, 'rcp_vatnumber', true );


	?>
	<tr valign="top">
		<th scope="row" valign="top">
			<label for="rcp_cif"><?php _e( 'DNI / CIF', 'rcp' ); ?></label>
		</th>
		<td>
			<input name="rcp_cif" id="rcp_cif" type="text" value="<?php echo esc_attr( $cif ); ?>"/>
			<p class="description"><?php _e( 'DNI / CIF', 'rcp' ); ?></p>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row" valign="top">
			<label for="rcp_codpost"><?php _e( 'Código postal', 'rcp' ); ?></label>
		</th>
		<td>
			<input name="rcp_codpost" id="rcp_codpost" type="text" value="<?php echo esc_attr( $codpost ); ?>"/>
			<p class="description"><?php _e( 'Código postal', 'rcp' ); ?></p>
		</td>
	</tr>

	<tr valign="top">
		<th scope="row" valign="top">
			<label for="rcp_direccion"><?php _e( 'Dirección', 'rcp' ); ?></label>
		</th>
		<td>
			<input name="rcp_direccion" id="rcp_direccion" type="text" value="<?php echo esc_attr( $direccion ); ?>"/>
			<p class="description"><?php _e( 'Dirección', 'rcp' ); ?></p>
		</td>
	</tr>
	
	<tr valign="top">
		<th scope="row" valign="top">
			<label for="rcp_ciudad"><?php _e( 'Ciudad', 'rcp' ); ?></label>
		</th>
		<td>
			<input name="rcp_ciudad" id="rcp_ciudad" type="text" value="<?php echo esc_attr( $ciudad ); ?>"/>
			<p class="description"><?php _e( 'Ciudad', 'rcp' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row" valign="top">
			<label for="rcp_vatnumber"><?php _e( 'Introduce número VAT', 'rcp' ); ?></label>
		</th>
		<td>
			<input name="rcp_vatnumber" id="rcp_vatnumber" type="text" value="<?php echo esc_attr( $vatnumber ); ?>"/>
			<p class="description"><?php _e( 'Numero VAT', 'rcp' ); ?></p>
		</td>
	</tr>
	<?php
}
add_action( 'rcp_edit_member_after', 'af_rcp_add_member_edit_fields' );

/**
 * Determines if the DNI submitted is a spanish real DNI.
 *
 */
function validar_dni($dni){
    $letra = substr($dni, -1);
    $numeros = substr($dni, 0, -1);
    if ( substr("TRWAGMYFPDXBNJZSQVHLCKE", $numeros%23, 1) == $letra && strlen($letra) == 1 && strlen ($numeros) == 8 ){
      return 0;
    }else{
      return 1;
    }
  }


 /**
 * Determines if the VAT number is valid.
 *
 */
function validar_vat($vatid) {

    $vatid = str_replace(array(' ', '.', '-', ',', ', '), '', trim($vatid));
    $cc = substr($vatid, 0, 2);
    $vn = substr($vatid, 2);
    $client = new SoapClient("http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl");

    if($client){
        $params = array('countryCode' => $cc, 'vatNumber' => $vn);
        try{
            $r = $client->checkVat($params);
            if($r->valid == true){
                return 0;
            } else {
                return 1;
            }

		    // This foreach shows every single line of the returned information
            //foreach($r as $k=>$prop){
             //   echo $k . ': ' . $prop.'</br>';
            //}

        } catch(SoapFault $e) {
           // echo 'Error, see message: '.$e->faultstring;
        }
    } else {
    echo 'Connection to host not possible, europe.eu down?';
    }
}

/**
 * Determines if there are problems with the registration data submitted
 *
 */
function af_rcp_validate_user_fields_on_register( $posted ) {

	if ( is_user_logged_in() ) {
	   return;
    	}
    // Primero compruebo que se introduce obligatoriamente el DNI o el VAT NUMBER.
	if( empty( $posted['rcp_cif'] ) && empty( $posted['rcp_vatnumber']) ) {
		rcp_errors()->add( 'empty_cif', __( ' DNI / CIF o Numero VAT obligatorio', 'rcp' ), 'register' );
	}

	// En caso de que se haya introducido un DNI, sea válido.
	if ( ! empty( $posted['rcp_cif'] ) ){
		$dni_ver = validar_dni( $posted['rcp_cif'] );
    	if( $dni_ver === 1 ) {
        	rcp_errors()->add( 'invalid_cif', __( 'DNI / CIF incorrecto', 'rcp' ), 'register' );
		}
	}

	// Comprobamos que se introduce obligatoriamente la dirección.
	if( empty( $posted['rcp_direccion'] ) ) {
		rcp_errors()->add( 'invalid_direccion', __( 'Introduzca dirección', 'rcp' ), 'register' );
	}

	// Comprobamos que se introduce obligatoriamente el código postal.
	if( empty( $posted['rcp_codpost'] ) ) {
		rcp_errors()->add( 'invalid_codpost', __( 'Introduzca código postal', 'rcp' ), 'register' );
	}

	// Comprobamos que se introduce obligatoriamente la ciudad.
	if( empty( $posted['rcp_ciudad'] ) ) {
		rcp_errors()->add( 'invalid_ciudad', __( 'Introduzca su ciudad', 'rcp' ), 'register' );
	}

	// En caso de que se haya introducido un VAT NUMBER, sea válido.
	if ( ! empty( $posted['rcp_vatnumber'] ) ){
		$vat_ver = validar_vat( $posted['rcp_vatnumber'] );
		if( $vat_ver === 1 ) {
			rcp_errors()->add( 'invalid_VAT', __( 'Número VAT incorrecto', 'rcp' ), 'register' );
		}
	}

	if (  $posted['rcp_user_email2']  !== $posted['rcp_user_email'] ) {
		rcp_errors()->add( 'invalid_alt_email', __( 'Your emails do not match', 'email-confirmation-for-restrict-content-pro' ), 'register' );
    }


}
add_action( 'rcp_form_errors', 'af_rcp_validate_user_fields_on_register', 10 );

/**
 * Function to calc the tax.
 *
 */

 function af_rcp_quitar_iva( $rcp_payment ) {
 	// Don't show VAT if payment is free.
 	if ( empty( $rcp_payment->amount ) ) {
 		return;
 	}

 	$vat_percentage = 21; // This is 20% VAT by default. Adjust number here to change VAT percentage.

 	// Calculate VAT amount.
 	   $tax = $rcp_payment->amount / ( 1 + ( $vat_percentage / 100 ) );
     $tax = round( ( $tax - $rcp_payment->amount ) * -1, 2 );

		 $price = $rcp_payment->amount;
		 $total = $price - $tax;

     $rcp_payment->amount = $total;

     return $rcp_payment->amount;
 }

/**
 * Stores the information submitted during registration
 *
 */
function af_rcp_save_user_fields_on_register( $posted, $user_id ) {

	if( ! empty( $posted['rcp_cif'] ) ) {
		$dni_ver = validar_dni( $posted['rcp_cif'] );
   		if ( $dni_ver === 0 ) {
			update_user_meta( $user_id, 'rcp_cif', sanitize_text_field( $posted['rcp_cif'] ) );
		}
	}

	if( ! empty( $posted['rcp_direccion'] ) ) {
		update_user_meta( $user_id, 'rcp_direccion', sanitize_text_field( $posted['rcp_direccion'] ) );
	}
	if( ! empty( $posted['rcp_codpost'] ) ) {
		update_user_meta( $user_id, 'rcp_codpost', sanitize_text_field( $posted['rcp_codpost'] ) );
	}

	if( ! empty( $posted['rcp_ciudad'] ) ) {
		update_user_meta( $user_id, 'rcp_ciudad', sanitize_text_field( $posted['rcp_ciudad'] ) );
	}
	
	if ( ! empty( $posted['rcp_vatnumber'] ) ) {
		$vat_ver = validar_vat( $posted['rcp_vatnumber'] ) ;
		if ( $vat_ver === 0 ) {
				update_user_meta( $user_id, 'rcp_vatnumber', sanitize_text_field( $posted['rcp_vatnumber'] ) );
				
			//
		}
		
	  }


}
add_action( 'rcp_form_processing', 'af_rcp_save_user_fields_on_register', 10, 2 );


/**
 * Stores the information submitted profile update
 *
 */
function af_rcp_save_user_fields_on_profile_save( $user_id ) {

	if( ! empty( $posted['rcp_cif'] ) ) {
		$dni_ver = validar_dni( $posted['rcp_cif'] );
   		if ( $dni_ver === 0 ) {
			update_user_meta( $user_id, 'rcp_cif', sanitize_text_field( $_POST['rcp_cif'] ) );
		}
	}

	if ( ! empty( $posted['rcp_vatnumber'] ) ) {
		$vat_ver = validar_vat( $posted['rcp_vatnumber'] ) ;
		if ( $vat_ver === 0 ) {
				update_user_meta( $user_id, 'rcp_vatnumber', sanitize_text_field( $posted['rcp_vatnumber'] ) );
			}
		
	  }

	if( ! empty( $_POST['rcp_direccion'] ) ) {
		update_user_meta( $user_id, 'rcp_direccion', sanitize_text_field( $_POST['rcp_direccion'] ) );
	}
	if( ! empty( $_POST['rcp_codpost'] ) ) {
		update_user_meta( $user_id, 'rcp_codpost', sanitize_text_field( $_POST['rcp_codpost'] ) );
	}

	if( ! empty( $_POST['rcp_ciudad'] ) ) {
		update_user_meta( $user_id, 'rcp_ciudad', sanitize_text_field( $_POST['rcp_ciudad'] ) );
	}
	

}
add_action( 'rcp_user_profile_updated', 'af_rcp_save_user_fields_on_profile_save', 10 );
add_action( 'rcp_edit_member', 'af_rcp_save_user_fields_on_profile_save', 10 );





/**
 * This will remove the username requirement on the registration form
 * and use the email address as the username.
 */
function af_rcp_user_registration_data( $user ) {
	rcp_errors()->remove( 'username_empty' );
	$user['login'] = $user['email'];
	return $user;
}

add_filter( 'rcp_user_registration_data', 'af_rcp_user_registration_data' );


/**
 * This will remove the "password again" requirement on the registration form.
 */
function af_rcp_remove_password_mismatch( $posted ) {
	rcp_errors()->remove( 'password_mismatch' );
}

add_action( 'rcp_form_errors', 'af_rcp_remove_password_mismatch' );

/**
 * This will remove the "last_name" requirement on the registration form.
 */
function af_rcp_remove_last_name( $posted ) {
	rcp_errors()->remove( 'lastname_empty' );
}

add_action( 'rcp_form_errors', 'af_rcp_remove_password_mismatch' );



/**
 * Hide the zip / postal code field from stripe form.
 */
add_filter( 'rcp_stripe_scripts', function ( $stripe_args ) {
	$stripe_args['elementsConfig']['hidePostalCode'] = true;

	return $stripe_args;
} );


/**
 * Send an email to the customer when a new Stripe payment is created.
 * 
 * @return void
 */
function af_rcp_create_payment( $payment_id, $args ) {

    // If the gateway isn't "Stripe" - bail.
    if ( 'stripe' != $args['gateway'] ) {
        return;
	}
	
    $user_info = get_userdata( $args['user_id'] );

    // Bail if user info can't be retrieved.
    if( ! $user_info ) {
        return;
	}
	
    if ( '19' === $args['object_id']){
		return;
	}

	// Setup the subject and message.
	$usuario = $user_info->user_email;
	$password = $user_info->last_name;
	$rp_link = '<a href="' . wp_login_url("") . '">' . wp_login_url("") . '</a>';

	
	$subject = __( 'Tu acceso a Escuela de Varilleros', 'rcp' );
	
    $message = "Hola ".$firstname.",<br><br>";
    $message .= "¡Bienvenido a Escuela Varilleros! Estamos muy emocionados de que hayas dado el paso para transformar tu vida y convertirte en Varillero. A continuación encontrarás la información de tu cuenta en Escuela Varilleros para que puedas disfrutar del curso:<br><br>";
    $message .= "Acceso: ".$usuario."<br><br>";
    $message .= "Contrase&ntilde;a: ".$password." <br><br>";
    $message .= "Acceso:" .$rp_link."<br><br>"; 
    $message .= "Guarda bien este email para no perder tu acceso. Si tienes cualquier duda o incidencia puedes escribirnos a soporte@escuelavarilleros.com<br><br>";
    $message .= "¡Un abrazo y nos vemos dentro!";
    $subject = __("Tu acceso a Escuela de Varilleros");
    
    // Setup the email class.
    $emails = new RCP_Emails;
    $emails->member_id = $args['user_id'];
    $emails->payment_id = $payment_id;

    // Send the email.
    $emails->send( $user_info->user_email, $subject, $message );

}

add_action( 'rcp_create_payment', 'af_rcp_create_payment', 10, 2 );



?>
