<?php
  /**
   * Plugin Name:			Mdg - Simple Form Subscribe
   * Description:			Form integrabile da shortcode per poter permettere l'utente di registrarsi. Rimuovo le dipendenze da CPT UI E ACF ** SHORTCODE -> [form_subscribe] **
   * Version:				1.0.0
   * Author:				Daniele Benedetto
   */

   require_once('export-subscribe.php');

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

            update_field('subscribes_name',$name,$result);
            update_field('subscribes_surname',$surname,$result);
            update_field('subscribes_email',$email,$result);

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
      <form action="" method="post">
        <div class="col-sm-6">
          <label for="subscribes_name">Nome:</label>
          <input type="text" id="subscribes_name" name="subscribes_name" required>
        </div>
        <div class="col-sm-6">
          <label for="subscribs_surname">Cognome:</label>
          <input type="text" id="subscribs_surname" name="subscribs_surname" required>
        </div>
        <div class="col-sm-6">
          <label for="subscribes_email">Email:</label>
          <input type="email" id="subscribes_email" name="subscribes_email" required>
        </div>
        <div class="col-sm-12">
          <label style="color:black">
            <input type="checkbox" id="documents_privacy" name="documents_privacy" required>
            <small>
              Acconsento al trattamento dei dati personali.
              <a href="<?php echo get_home_url(); ?>/privacy-policy/" target="_blank">
                Leggi l'informativa
              </a> resa ai sensi dell'art. 13 del RGPD (Regolamento Generale Protezione Dati) 2016/679 - il mancato consenso non permette il download dei materiali.
            </small>
          </label>
        </div>
        <div class="col-sm-12">
        <input style="margin-top:25px;border-color:white" id="subscribe_form" type="submit" name="submit" value="Invia e Scarica">
        </div>
      </form>

  <!-- Script di Invio -->
  <script>

  jQuery('#subscribe_form').on('click',function(event)
  {
    event.preventDefault();

    var name = jQuery('#subscribes_name').val()
    var surname = jQuery('#subscribs_surname').val()
    var email = jQuery('#subscribes_email').val()


    if(document.getElementById('documents_privacy').checked && name != "" && name != undefined && surname != "" && surname != undefined && email != "" && email != undefined )
    {

      jQuery.ajax({
          type: 'POST',
          url: '<?php echo admin_url('admin-ajax.php'); ?>',
          data: {
              'name': name,
              'surname': surname,
              'email': email,
              'action': 'newFormSubscriber' //this is the name of the AJAX method called in WordPress
          }, success: function (result) {
            if(result)
            {
              jQuery('#alert_incomplete_field').hide('fast')
              jQuery('#alert_failure').hide('fast')
              jQuery('#alert_success').show('fast')
              downloadItems();
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
  /* The Modal (background) */
  .modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 100; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
  }

  /* Modal Content/Box */
  .modal-content {
    background-color: #fefefe;
    margin: 10% auto; /* 15% from the top and centered */
    padding: 20px;
    display: flow-root;
    border: 1px solid #888;
    width: 50%; /* Could be more or less, depending on screen size */
  }

  /* The Close Button */
  .close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
  }

  .close:hover,
  .close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
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
                  'name'          => __( 'Registrazioni', 'subscribes' ),
                  'singular_name' => __( 'Registro', 'subscribe' ),
              ),
              'public'            => true,
              'public_quaryable'  => true,
              'menu_icon'         => 'dashicons-book',
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
      echo '<input style="width:100%; margin:10px 0;" type="text" id="subscribes_surname" name="wpmdg_surname" value="' . esc_attr( $value_surname ) . '" />';
      echo '<hr/>';
      //email
      $value_email = get_post_meta( $post->ID, 'subscribes_email', true );
      echo '<label style="font-weight:bold;" for="subscribes_email">';
      _e( 'Email', 'subscribes_email' );
      echo '</label> ';
      echo '<input style="width:100%; margin:10px 0;" type="text" id="subscribes_email" name="subscribes_email" value="' . esc_attr( $value_email ) . '" />';
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

      update_post_meta( $post_id, 'subscribes_name', $name );
      update_post_meta( $post_id, 'subscribes_surname', $surname );
      update_post_meta( $post_id, 'subscribes_email', $email );
  }
