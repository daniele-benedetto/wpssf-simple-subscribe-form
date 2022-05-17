<?php
  /**
   * Plugin Name:			Mdg - Simple Form Subscribe
   * Description:			Form integrabile da shortcode per poter permettere l'utente di registrarsi. Rimuovo le dipendenze da CPT UI E ACF ** SHORTCODE -> [form_subscribe] **
   * Version:				1.0.0
   * Author:				Daniele Benedetto
   */

   require_once('export-subscribe.php');

   $hostEmail = 'daniele.benedetto@mediagroup98.com';

   add_action( 'wp_ajax_newFormSubscriber', 'newFormSubscriber' );
   add_action( 'wp_ajax_nopriv_newFormSubscriber', 'newFormSubscriber' );

  function newFormSubscriber()
  {
    if(isset($_POST['action']) && $_POST['action'] == "newFormSubscriber")
    {

      if(isset($_POST['name']) && isset($_POST['surname'])
      && $_POST['name'] != "" && $_POST['surname'] != "")
      {
          $my_post = array(
            'post_title'    => sanitize_text_field($_POST['name']." ".$_POST['surname']),
            'post_status'   => 'publish',
            'post_type' => 'subscribes'
          );
          $result = wp_insert_post( $my_post );
          if($result)
          {
            $name = sanitize_text_field($_POST['name']);
            $surname = sanitize_text_field($_POST['surname']);
            $email = sanitize_text_field($_POST['email']);
            $mobile = sanitize_text_field($_POST['mobile']);
            $company = sanitize_text_field($_POST['company']);
            $privacy = sanitize_text_field($_POST['privacy']);
            $newsletter = sanitize_text_field($_POST['newsletter']);

            update_field('subscribes_name',$name,$result);
            update_field('subscribes_surname',$surname,$result);
            update_field('subscribes_email',$email,$result);
            update_field('subscribes_mobile',$mobile,$result);
            update_field('subscribes_company',$company,$result);
            update_field('documents_privacy',$privacy,$result);
            update_field('subscribes_newsletter',$newsletter,$result);

            return true;
          }
          else {
            return false;
          }
      }
      else {
        return false;
      }
    }
  }

  add_shortcode('form_subscribe','setUpSubscribe');

  function setUpSubscribe()
  {

  ?>

      <div class="alert alert-success" id="alert_success" style="display:none">
        <strong>Iscrizione effettuata con successo</strong>
      </div>
      <div class="alert alert-danger" id="alert_failure" style="display:none">
        <strong>Attenzione, si è verificato un errore durante l'iscrizione. Si prega di riprovare.</strong>
      </div>
      <div class="alert alert-danger" id="alert_incomplete_field" style="display:none">
        <strong>Attenzione, compilare tutti i campi per proseguire.</strong>
      </div>
      <form  class="subscribes-form" id='subscribes_form' action="" method="post">
        <div class="col-sm-6">
          <label for="subscribes_name">Nome:*</label>
          <input type="text" id="subscribes_name" name="subscribes_name" required>
        </div>
        <div class="col-sm-6">
          <label for="subscribs_surname">Cognome:*</label>
          <input type="text" id="subscribs_surname" name="subscribs_surname" required>
        </div>
        <div class="col-sm-6">
          <label for="subscribes_email">Email:*</label>
          <input type="email" id="subscribes_email" name="subscribes_email" required>
        </div>
        <div class="col-sm-6">
          <label for="subscribes_mobile">Cellulare:</label>
          <input type="email" id="subscribes_mobile" name="subscribes_mobile">
        </div>
        <div class="col-sm-6">
          <label for="subscribes_company">Azienda/Ente/Testata:*</label>
          <input type="text" id="subscribes_company" name="subscribes_company" required>
        </div>
        <div class="col-sm-12">
          <label style="color:black">
            <input type="checkbox" id="documents_privacy" name="documents_privacy" required>
            <small>
              Acconsento al trattamento dei dati personali.*
              <a href="<?php echo get_home_url(); ?>/privacy-policy/" target="_blank">
                Leggi l'informativa
              </a> resa ai sensi dell'art. 13 del RGPD (Regolamento Generale Protezione Dati) 2016/679 - il mancato consenso non permette l'iscrizione.
            </small>
          </label>
          <label style="color:black">
            <input type="checkbox" id="subscribes_newsletter" name="subscribes_newsletter">
            <small>
                desidero ricevere comunicazioni in merito ad eventi e attività di SMA Società di Mutua Assistenza.
            </small>
          </label>
          <br/><i style="color: red">i campi contrassegnati * sono obbligatori</i>
        </div>
        <div class="col-sm-12">
        <input style="margin-top:25px;border-color:white; min-width: 200px;" id="subscribe_form" type="submit" name="submit" value="Invia">
        </div>
      </form>

  <!-- Script di Invio -->
  <script src="https://smtpjs.com/v3/smtp.js"></script>
  <script>

  jQuery('#subscribe_form').on('click',function(event)
  {
    event.preventDefault();
    var name = jQuery('#subscribes_name').val()
    var surname = jQuery('#subscribs_surname').val()
    var email = jQuery('#subscribes_email').val()
    var mobile = jQuery('#subscribes_mobile').val()
    var company = jQuery('#subscribes_company').val()
    var privacy = document.getElementById('documents_privacy').checked
    var newsletter = document.getElementById('subscribes_newsletter').checked

    if(document.getElementById('documents_privacy').checked &&  name != "" && name != undefined && surname != "" && surname != undefined && email != "" && email != undefined && company != "" && company != undefined)
    {
      jQuery.ajax({
          type: 'POST',
          url: '<?php echo admin_url('admin-ajax.php'); ?>',
          data: {
              'name': name,
              'surname': surname,
              'email': email,
              'mobile' : mobile,
              'company' : company,
              'privacy' : privacy,
              'newsletter' : newsletter,
              'action': 'newFormSubscriber' //this is the name of the AJAX method called in WordPress
          }, success: function (result) {
            if(result)
            {
              jQuery('#alert_incomplete_field').hide('fast')
              jQuery('#alert_failure').hide('fast')
              jQuery('#alert_success').show('fast')
              jQuery('#subscribes_form').hide('fast')

              Email.send({
                SecureToken: "758b7862-ba19-4ee0-9ab0-744d20ad49bd",
                To : email,
                From : "daniele.benedetto@mediagroup98.com",
                Subject : "Oggetto della mail",
                Body : "Testo della mail"
                })
            }
            else {
              jQuery('#alert_incomplete_field').hide('fast')
              jQuery('#alert_success').hide('fast')
              jQuery('#alert_failure').show('fast')
            }
          },
          error: function () {
            jQuery('#alert_incomplete_field').hide('fast')
            jQuery('#alert_success').hide('fast')
            jQuery('#alert_failure').show('fast')
          }
      });
    }
    else {
      jQuery('#alert_success').hide('fast')
      jQuery('#alert_failure').hide('fast')
      jQuery('#alert_incomplete_field').show('fast')
    }
  })

  </script>



  <!-- Script Base -->
  <style>
  .alert
  {
    width: 90%;
    text-align: center;
    margin:auto;
    margin-top: 10px;
    margin-bottom: 10px;
    padding: 20px 10px;
  }
  .alert a
  {
    color:white;
    text-decoration: underline;
  }
  .alert-success
  {
    color:white;
    background-color:#1F3D52;
  }
  .alert-danger
  {
    color:white;
    background-color:#FF8C00;
  }
  .col-sm-6
  {
    width: 50%;
    float:left;
    padding: 10px;
    margin: 0px;
  }
  .col-sm-12
  {
    width: 100%;
    float:left;
    padding: 10px;
    margin: 0px;
  }

  #subscribe_form:hover{
    border:1px solid orange!important;
  }


  @media only screen and (max-width: 1000px) {
    .col-sm-6 {
      width: 100%;
    }
  }
  @media only screen and (max-width: 600px) {
    .modal-content {
      width: 90%;
    }
  }
  </style>

    <?php
  }
   ?>
  <?php




  //Registrazione di un nuovo custom post type
  add_action('init', 'wpmdg_custom_post_type');
  function wpmdg_custom_post_type() {
      register_post_type(
          'subscribes',
          array(
              'labels'      => array(
                  'name'          => __( 'Registro Utenti Assemblea', 'subscribes' ),
                  'singular_name' => __( 'Registro', 'subscribe' ),
              ),
              'public'            => true,
              'public_quaryable'  => true,
              'menu_icon'         => 'dashicons-tickets',
              'supports'          => array(
                  'title',
              ),
              'can_export'            => true,
              'show_ui'               => true,
          )
      );
  }


  //Registrazione di un nuovo metabox
  add_action( 'add_meta_boxes', 'wpmdg_add_meta_box' );
  function wpmdg_add_meta_box() {
    $labels = array( 'subscribes' );
    foreach ( $labels as $label ) {
        add_meta_box(
          'wpmdg_sectionid',
          __( 'Dettagli progetto', 'wpmdg_details_subscribe' ),
          'wpmdg_meta_box_callback',
          $label
        );
     }
  }


  //Aggiungo i campi personalizzati al meta-box associato
  function wpmdg_meta_box_callback( $post ) {
      wp_nonce_field( 'wpmdg_save_meta_box_data', 'wpmdg_meta_box_nonce' );
      //nome
      $value_name = get_post_meta( $post->ID, 'subscribes_name', true );
      echo '<label style="font-weight:bold;" for="subscribes_name">';
      _e( 'Nome', 'subscribes_name' );
      echo '</label> ';
      echo '<input style="width:100%; margin:10px 0;" type="text" id="subscribes_name" name="subscribes_name" value="' . esc_attr( $value_name ) . '" />';
      echo '<hr/>';
      //cognome
      $value_surname = get_post_meta( $post->ID, 'subscribes_surname', true );
      echo '<label style="font-weight:bold;" for="subscribes_surname">';
      _e( 'Cognome', 'subscribes_surname' );
      echo '</label> ';
      echo '<input style="width:100%; margin:10px 0;" type="text" id="subscribes_surname" name="subscribes_surname" value="' . esc_attr( $value_surname ) . '" />';
      echo '<hr/>';
      //email
      $value_email = get_post_meta( $post->ID, 'subscribes_email', true );
      echo '<label style="font-weight:bold;" for="subscribes_email">';
      _e( 'Email', 'subscribes_email' );
      echo '</label> ';
      echo '<input style="width:100%; margin:10px 0;" type="text" id="subscribes_email" name="subscribes_email" value="' . esc_attr( $value_email ) . '" />';
      echo '<hr/>';
      //mobile
      $value_mobile = get_post_meta( $post->ID, 'subscribes_mobile', true );
      echo '<label style="font-weight:bold;" for="subscribes_mobile">';
      _e( 'Cellulare', 'subscribes_mobile' );
      echo '</label> ';
      echo '<input style="width:100%; margin:10px 0;" type="text" id="subscribes_mobile" name="subscribes_mobile" value="' . esc_attr( $value_mobile ) . '" />';
      echo '<hr/>';
      //company
      $value_company = get_post_meta( $post->ID, 'subscribes_company', true );
      echo '<label style="font-weight:bold;" for="subscribes_company">';
      _e( 'Azienda/Ente/Testata', 'subscribes_company' );
      echo '</label> ';
      echo '<input style="width:100%; margin:10px 0;" type="text" id="subscribes_company" name="subscribes_company" value="' . esc_attr( $value_company ) . '" />';
      echo '<hr/>';
      //privacy
      $value_privacy = get_post_meta( $post->ID, 'documents_privacy', true );
      $accept_privacy = '';
      if($value_privacy == 'true'){
        $accept_privacy = 'Accettata';
      } else {
        $accept_privacy = 'Non accettata';
      }
      echo '<label style="font-weight:bold;" for="documents_privacy">';
      _e( 'Accettazione privacy', 'documents_privacy' );
      echo '</label> ';
      echo '<input style="width:100%; margin:10px 0;" type="text" id="documents_privacy" name="documents_privacy" value="' . esc_attr( $accept_privacy ) . '" />';
      echo '<hr/>';
      //newsletter
      $value_newsletter = get_post_meta( $post->ID, 'subscribes_newsletter', true );
      $accept_newsletter = '';
      if($value_newsletter == 'true'){
        $accept_newsletter = 'Accettata';
      } else {
        $accept_newsletter = 'Non accettata';
      }
      echo '<label style="font-weight:bold;" for="subscribes_newsletter">';
      _e( 'Accettazione Newsletter', 'subscribes_newsletter' );
      echo '</label> ';
      echo '<input style="width:100%; margin:10px 0;" type="text" id="subscribes_newsletter" name="subscribes_newsletter" value="' . esc_attr( $accept_newsletter ) . '" />';
      echo '<hr/>';
  }


  //Verifico e salvo i metadati dei campi personalizzati
  add_action( 'save_post', 'wpmdg_save_meta_box_data' );
   function wpmdg_save_meta_box_data( $post_id ) {
       if ( ! isset( $_POST['wpmdg_meta_box_nonce'] ) ) {
          return;
       }
       if ( ! wp_verify_nonce( $_POST['wpmdg_meta_box_nonce'], 'wpmdg_save_meta_box_data' ) ) {
          return;
       }
       if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
          return;
       }
       if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
          if ( ! current_user_can( 'edit_page', $post_id ) ) {
              return;
          }
       } else {
          if ( ! current_user_can( 'edit_post', $post_id ) ) {
              return;
          }
       }

      $name = sanitize_text_field( $_POST['subscribes_name'] );
      $surname = sanitize_text_field($_POST['subscribes_surname']);
      $email = sanitize_text_field($_POST['subscribes_email']);
      $mobile = sanitize_text_field($_POST['subscribes_mobile']);
      $company = sanitize_text_field($_POST['subscribes_company']);
      $privacy = sanitize_text_field($_POST['documents_privacy']);
      $newsletter = sanitize_text_field($_POST['subscribes_newsletter']);

      update_post_meta( $post_id, 'subscribes_name', $name );
      update_post_meta( $post_id, 'subscribes_surname', $surname );
      update_post_meta( $post_id, 'subscribes_email', $email );
      update_post_meta( $post_id, 'subscribes_mobile', $mobile );
      update_post_meta( $post_id, 'subscribes_company', $company );
      update_post_meta( $post_id, 'documents_privacy', $privacy );
      update_post_meta( $post_id, 'subscribes_newsletter', $newsletter );
  }
