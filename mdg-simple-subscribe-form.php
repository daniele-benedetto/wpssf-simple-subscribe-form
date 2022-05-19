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
            $mobile = sanitize_text_field($_POST['mobile']);
            $company = sanitize_text_field($_POST['company']);
            $role = sanitize_text_field($_POST['role']);
            $partner = sanitize_text_field($_POST['partner']);
            $privacy = sanitize_text_field($_POST['privacy']);
            $newsletter = sanitize_text_field($_POST['newsletter']);

            update_field('subscribes_name',$name,$result);
            update_field('subscribes_surname',$surname,$result);
            update_field('subscribes_email',$email,$result);
            update_field('subscribes_mobile',$mobile,$result);
            update_field('subscribes_company',$company,$result);
            update_field('subscribes_role',$role,$result);
            update_field('subscribes_partner',$partner,$result);
            update_field('documents_privacy',$privacy,$result);
            update_field('subscribes_newsletter',$newsletter,$result);

            if($partner == 'true') {
              function sendEmail() {
                 $to = sanitize_text_field($_POST['email']);
                 $subject = 'Conferma registrazione Assemblea annuale dei soci SMA 2022';
                 $body = "
                           <div style='max-width: 800px; width:100%; margin: 0 auto; background-color: #f2f2f5;'>
                              <img style='width:100%;' src='https://dev8.mediagroup98.com/sma2022/wp-content/uploads/2022/05/SMA-SMA-Banner-assemblea-dei-soci_definitivo.jpg' alt='Assemblea dei soci 2022'>
                              <div style='padding:20px;'>
                                  Grazie per aver confermato la partecipazione all'Assemblea annuale dei Soci di SMA - Società di Mutua Assistenza. L'assemblea avrà luogo venerdì 10 giugno a partire dalle ore 11.00.<br/><br/>
                                  <b>Programma:</b>
                                  <ul>
                                      <li>Saluti istituzionali<br/>
                                      Alberto Papotti Direttore Generale CNA Modena<br/>
                                      Placido Putzolu Presidente FIMIV<br/>
                                      Andrea Benini Presidente Legacoop Estense<br/>
                                      Alberto Alberani Legacoop Sociali E.R.
                                      </li></b><br/>
                                      <li>
                                        Tavola rotonda: “Chi si prende cura di chi cura?”<br/>
                                        tra crisi del mercato del lavoro, evoluzione delle professionalità e nuovi bisogni emergenti.<br/>
                                        Intervengono:<br/>
                                        Mattia Lamberti Pedagogista e collaboratore Università Cattolica Milano<br/>
                                        Carlotta Pizzi Pedagogista e Formatrice, referente CASCO<br/>
                                        Giorgia Silvestri Educatrice Professionale e Coordinatrice – Aliante<br/>
                                        Moderatore:<br/>
                                        Chiara Marando Giornalista
                                      </li></b><br/>
                                      <li>
                                      Bilancio Sociale 2021<br/>
                                      Presentazione
                                      </li></b><br/>
                                      <li>
                                      Apertura Assemblea dei soci: Bilancio d'Esercizio 2021<br/>
                                      Presentazione e votazione
                                      </li></b><br/>
                                  </ul>
                                  Per connettersi utilizzi questo link:
                                  <a style='color:#D74E16; font-weight:bold; text-decoration: none;' href='https://teams.microsoft.com/l/meetup-join/19%3ameeting_M2I2NzNjZTYtMWE4MC00ZWY1LTkzODgtMGUyODMyODIxZmI5%40thread.v2/0?context=%7b%22Tid%22%3a%22a33de1f7-8e2c-4b27-a515-de9c92fb82f7%22%2c%22Oid%22%3a%225f35d2e7-75f3-4a77-afa8-4fa52bb2adbe%22%7d'>PARTECIPA ALL'ASSEMBLEA</a><br/><br/>
                                  <div style='display: flex; justify-content:space-between;'>
                                      <a style='padding: 10px; background-color: #D74E16; color: white; text-decoration: none;' href='https://dev8.mediagroup98.com/sma2022/wp-content/uploads/2022/05/Bilancio-SMA-Modena_2021_web-singola.pdf'>Scarica il bilancio socialie</a>
                                      <a style='padding: 10px; background-color: #D74E16; color: white; text-decoration: none;' href='https://dev8.mediagroup98.com/sma2022/wp-content/uploads/2022/05/INVITO-21x10-riservato.pdf'>Scarica il Save the Date</a>
                                      <a style='padding: 10px; background-color: #D74E16; color: white; text-decoration: none;' href='https://dev8.mediagroup98.com/sma2022/wp-content/uploads/2022/05/CONVOCAZIONE-ODG-ASSEMBLEA-Copia.pdf'>Scarica Ordine del giorno</a>
                                  </div>
                                  <div style='display: flex; margin-top:80px; justify-content:center;'>
                                    <a style='padding: 20px; background-color: #D74E16; color: white; text-decoration: none;' title='Add to Calendar' class='addeventatc' data-id='AS13774866' href='https://www.addevent.com/event/AS13774866' target='_blank' rel='nofollow'>Aggiungi l'evento al calendario</a>
                                    <script type='text/javascript' src='https://cdn.addevent.com/libs/atc/1.6.1/atc.min.js' async defer></script>
                                  </div>
                                  <div style='text-align:center; padding-top:20px; margin-top:20px; border-top: 1px solid #264B8D; color: #264B8D; font-weight:bold;'>
                                  SMA 2022 - Tutti I Diritti Riservati<br/>
                                  Società di Mutua Assistenza – Società di Mutuo Soccorso – Largo Aldo Moro 1, 41124 Modena (MO)<br/>
                                  Tel: 059 7100555 | info@smamodena.it<br/>
                                  </div>
                              </div>
                          </div>
                        ";
                 $headers = array('Content-Type: text/html; charset=UTF-8');
                 wp_mail( $to, $subject, $body, $headers );
              }
              sendEmail();
            } elseif ($partner == 'false') {
              function sendEmail() {
                 $to = sanitize_text_field($_POST['email']);
                 $subject = 'Conferma registrazione Assemblea annuale dei soci SMA 2022';
                 $body = "
                          <div style='max-width: 800px; width:100%; margin: 0 auto; background-color: #f2f2f5;'>
                              <img style='width:100%;' src='https://dev8.mediagroup98.com/sma2022/wp-content/uploads/2022/05/SMA-SMA-Banner-assemblea-dei-soci_definitivo.jpg' alt='Assemblea dei soci 2022'>
                              <div style='padding:20px;'>
                                  Grazie per aver confermato la partecipazione all'Assemblea annuale dei Soci di SMA - Società di Mutua Assistenza. L'assemblea avrà luogo venerdì 10 giugno a partire dalle ore 11.00.<br/><br/>
                                  <b>Programma:</b><br/>
                                  <ul>
                                      <li>Saluti istituzionali<br/>
                                      Alberto Papotti Direttore Generale CNA Modena<br/>
                                      Placido Putzolu Presidente FIMIV<br/>
                                      Andrea Benini Presidente Legacoop Estense<br/>
                                      Alberto Alberani Legacoop Sociali E.R.
                                      </li></b><br/>
                                      <li>
                                        Tavola rotonda: “Chi si prende cura di chi cura?”<br/>
                                        tra crisi del mercato del lavoro, evoluzione delle professionalità e nuovi bisogni emergenti.<br/>
                                        Intervengono:<br/>
                                        Mattia Lamberti Pedagogista e collaboratore Università Cattolica Milano<br/>
                                        Carlotta Pizzi Pedagogista e Formatrice, referente CASCO<br/>
                                        Giorgia Silvestri Educatrice Professionale e Coordinatrice – Aliante<br/>
                                        Moderatore:<br/>
                                        Chiara Marando Giornalista
                                      </li></b><br/>
                                      <li>
                                      Bilancio Sociale 2021<br/>
                                      Presentazione
                                      </li></b><br/>
                                      <li>
                                      Apertura Assemblea dei soci: Bilancio d'Esercizio 2021<br/>
                                      Presentazione e votazione
                                      </li></b><br/>
                                  </ul>
                                  Per connettersi utilizzi questo link:
                                  <a style='color:#D74E16; font-weight:bold; text-decoration: none;' href='https://teams.microsoft.com/l/meetup-join/19%3ameeting_M2I2NzNjZTYtMWE4MC00ZWY1LTkzODgtMGUyODMyODIxZmI5%40thread.v2/0?context=%7b%22Tid%22%3a%22a33de1f7-8e2c-4b27-a515-de9c92fb82f7%22%2c%22Oid%22%3a%225f35d2e7-75f3-4a77-afa8-4fa52bb2adbe%22%7d'>PARTECIPA ALL'ASSEMBLEA</a><br/><br/>
                                  <div style='display: flex; justify-content:space-around;'>
                                      <a style='padding: 10px; background-color: #D74E16; color: white; text-decoration: none;' href='https://dev8.mediagroup98.com/sma2022/wp-content/uploads/2022/05/Bilancio-SMA-Modena_2021_web-singola.pdf'>Scarica il bilancio socialie</a>
                                      <a style='padding: 10px; background-color: #D74E16; color: white; text-decoration: none;' href='https://dev8.mediagroup98.com/sma2022/wp-content/uploads/2022/05/INVITO-21x10-riservato.pdf'>Scarica il Save the Date</a>
                                  </div>
                                  <div style='display: flex; margin-top:80px; justify-content:center;'>
                                    <a style='padding: 20px; background-color: #D74E16; color: white; text-decoration: none;' title='Add to Calendar' class='addeventatc' data-id='AS13774866' href='https://www.addevent.com/event/AS13774866' target='_blank' rel='nofollow'>Aggiungi l'evento al calendario</a>
                                    <script type='text/javascript' src='https://cdn.addevent.com/libs/atc/1.6.1/atc.min.js' async defer></script>
                                  </div>
                                  <div style='text-align:center; padding-top:20px; margin-top:20px; border-top: 1px solid #264B8D; color: #264B8D; font-weight:bold;'>
                                  SMA 2022 - Tutti I Diritti Riservati<br/>
                                  Società di Mutua Assistenza – Società di Mutuo Soccorso – Largo Aldo Moro 1, 41124 Modena (MO)<br/>
                                  Tel: 059 7100555 | info@smamodena.it<br/>
                                  </div>
                              </div>
                          </div>
                          ";
                 $headers = array('Content-Type: text/html; charset=UTF-8');
                 wp_mail( $to, $subject, $body, $headers );
              }
              sendEmail();
            }
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
        <strong style="text-align:center;">
          Grazie per aver confermato la presenza alla<br/>
          Assemblea generale dei soci<br/>
          Ti abbiamo inviato una mail di riepilogo registrazione
      </strong><br/
      <i style="text-align:center">
        Se non trovi la mail, verifica nella cartella Indesiderata o Spam o,
        se utilizzi Gmail, puoi controllare anche il tab Promozioni
    </i>
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
        <div class="col-sm-6">
          <label for="subscribes_role">Ruolo:</label>
          <input type="text" id="subscribes_role" name="subscribes_role">
        </div>
        <div class="col-sm-6">
          <fieldset>
              <legend>Sei socia/socio?*</legend>
              <div>
                <input type="radio" id="subscribes_partner" name="subscribes_partner" value="Si" checked>
                <label for="subscribes_partner">Si</label>
              </div>
              <div>
                <input type="radio" id="" name="subscribes_partner" value="No">
                <label for="louie">No</label>
              </div>
          </fieldset>
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
  <script>


  jQuery('#subscribe_form').on('click',function(event)
  {
    event.preventDefault();
    var name = jQuery('#subscribes_name').val()
    var surname = jQuery('#subscribs_surname').val()
    var email = jQuery('#subscribes_email').val()
    var mobile = jQuery('#subscribes_mobile').val()
    var company = jQuery('#subscribes_company').val()
    var role = jQuery('#subscribes_role').val()
    var partner = document.getElementById('subscribes_partner').checked
    var privacy = document.getElementById('documents_privacy').checked
    var newsletter = document.getElementById('subscribes_newsletter').checked
    var adminAjax = '<?php  echo admin_url('admin-ajax.php');?>';

    if(document.getElementById('documents_privacy').checked &&  name != "" && name != undefined && surname != "" && surname != undefined && email != "" && email != undefined && company != "" && company != undefined)
    {
      jQuery.ajax({
          type: 'POST',
          url: adminAjax,
          data: {
              'name': name,
              'surname': surname,
              'email': email,
              'mobile' : mobile,
              'company' : company,
              'privacy' : privacy,
              'partner' : partner,
              'role' : role,
              'newsletter' : newsletter,
              'action': 'newFormSubscriber', //this is the name of the AJAX method called in WordPress
          }, success: function (result) {
            if(result)
            {
              jQuery('#alert_incomplete_field').hide('fast')
              jQuery('#alert_failure').hide('fast')
              jQuery('#alert_success').show('fast')
              jQuery('#subscribes_form').hide('fast')
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
      //role
      $value_role = get_post_meta( $post->ID, 'subscribes_role', true );
      echo '<label style="font-weight:bold;" for="subscribes_role">';
      _e( 'Ruolo', 'subscribes_role' );
      echo '</label> ';
      echo '<input style="width:100%; margin:10px 0;" type="text" id="subscribes_role" name="subscribes_role" value="' . esc_attr( $value_role ) . '" />';
      echo '<hr/>';
      //partner
      $value_partner = get_post_meta( $post->ID, 'subscribes_partner', true );
      $is_partner = '';
      if($value_partner == 'true'){
        $is_partner = 'Si';
      } else {
        $is_partner = 'No';
      }
      echo '<label style="font-weight:bold;" for="subscribes_partner">';
      _e( 'è un socio?', 'subscribes_company' );
      echo '</label> ';
      echo '<input style="width:100%; margin:10px 0;" type="text" id="subscribes_partner" name="subscribes_partner" value="' . esc_attr( $is_partner ) . '" />';
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
      $role = sanitize_text_field($_POST['subscribes_role']);
      $partner = sanitize_text_field($_POST['subscribes_partner']);
      $privacy = sanitize_text_field($_POST['documents_privacy']);
      $newsletter = sanitize_text_field($_POST['subscribes_newsletter']);

      update_post_meta( $post_id, 'subscribes_name', $name );
      update_post_meta( $post_id, 'subscribes_surname', $surname );
      update_post_meta( $post_id, 'subscribes_email', $email );
      update_post_meta( $post_id, 'subscribes_mobile', $mobile );
      update_post_meta( $post_id, 'subscribes_company', $company );
      update_post_meta( $post_id, 'subscribes_role', $role );
      update_post_meta( $post_id, 'subscribes_partner', $partner );
      update_post_meta( $post_id, 'documents_privacy', $privacy );
      update_post_meta( $post_id, 'subscribes_newsletter', $newsletter );
  }
