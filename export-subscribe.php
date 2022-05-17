<?php
  if ( ! class_exists( 'Mdg_Csv_Export_Subscribe' ) )
  {

    define( 'MDG_CSV_EXPORT_SUBSCRIBE_VERSION', '0.0.1' );
    if ( is_admin() ) {
        add_action( 'init', array ( 'Mdg_Csv_Export_Subscribe', 'get_instance' ), 0 );
    }

    class Mdg_Csv_Export_Subscribe{

        private static $instance = null;
        public $text_domain = 'wp-subscribe-exporter';

        public static function get_instance() {
            if ( null == self::$instance ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        private function __construct()
        {
            if (is_admin() ) {
                add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );
                add_action( 'init', array( $this, 'mdg_subscribe_generate_data' ) );
            }
        }

        public function load_plugin_textdomain()
        {
            load_plugin_textdomain( $this->text_domain, false, basename( dirname( __FILE__ ) ) . '/languages' );
        }

        public function add_admin_pages()
        {
            add_submenu_page( 'edit.php?post_type=subscribes', __( 'Esporta Registrati'  ), __( 'Esporta Registrati'  ), 'list_users', $this->text_domain, array( $this, 'mdg_subscribe_export_page' ) );
        }

        public function sanitize( $value )
        {
            $value = str_replace("\r", '', $value);
            $value = str_replace("\n", '', $value);
            $value = str_replace("\t", '', $value);
            return $value;
        }

        public function mdg_subscribe_export_page()
        {
            if ( ! current_user_can( 'list_users' ) ) {
                wp_die( __( 'You do not have sufficient permissions to access this page.', $this->text_domain ) );
            }
        ?>
            <div class="wrap">
                <h2>Esportazione Registrati</h2>
                <form method="post" name="subscribe_csv_exporter_form" action="" enctype="multipart/form-data">
                    <?php wp_nonce_field( 'subscribe-page_export', '_wpnonce-subscribe-page_export' ); ?>
                    <h3>Esporta Registrati</h3>
                     <p class="input">
                        <label>Seleziona formato</label><br />
                        <select name="format" >
                            <option value="csv" >CSV</option>
                        </select>
                    </p>
                    <input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI'] ?>" />
                    <p class="submit"><input type="submit" class="button button-primary" name="Submit" value="Esporta registrati" /></p>
                </form>
            </div>
          <?php
        }

        public function mdg_subscribe_generate_data() {

            global $wpdb;

            if ( ! isset( $_POST['_wpnonce-subscribe-page_export'] ) ) {
                return false;
            }

            check_admin_referer( 'subscribe-page_export', '_wpnonce-subscribe-page_export' );

            $export_method = 'csv';

            if ( isset( $_POST['format'] ) && $_POST['format'] != '' ) {
                $export_method = sanitize_text_field( $_POST['format'] );
            }

            $filename = 'Export_registrati_' . date( 'Y-m-d-H-i-s' );

            switch ( $export_method ) {

                case "csv":

                    header( 'Content-Description: File Transfer' );
                    header( 'Content-Disposition: attachment; filename='.$filename.'.csv' );
                    header( 'Content-Type: text/csv; charset=' . get_option( 'blog_charset' ), true );

                    $is_csv = true;
                    $doc_begin  = '';
                    $pre        = '';
                    $separetor = ',';
                    $breaker = "\n";
                    $doc_end  = '';
                    break;
            }

            $args = [
                    'post_type'         => array( 'subscribes' ),
                    'post_status'       => array( 'publish' ),
                    'posts_per_page'    => -1,
                    'order'             => 'ASC',
                    'orderby'           => 'post_date'
                    ];

            $subscribes = get_posts($args);
            echo $doc_begin;
            $headers = array('ID', 'Nome', 'Cognome', 'Email', 'Cellulare','Azienda/Ente/Testata', 'Privacy', 'Newsletter');
            echo $pre . implode( $separetor, $headers ) . $breaker;

                foreach($subscribes as $subscribe):
                    $data = array();
                    $subscribe_name = get_post_meta( $subscribe->ID, 'subscribes_name', true );
                    $subscribe_surname = get_post_meta( $subscribe->ID, 'subscribes_surname', true );
                    $subscribe_email = get_post_meta( $subscribe->ID, 'subscribes_email', true );
                    $subscribe_mobile = get_post_meta( $subscribe->ID, 'subscribes_mobile', true );
                    $subscribe_company = get_post_meta( $subscribe->ID, 'subscribes_company', true );
                    $documents_privacy = get_post_meta( $subscribe->ID, 'documents_privacy', true );
                    $subscribe_newsletter = get_post_meta( $subscribe->ID, 'subscribes_newsletter', true );

                    $accept_privacy = '';
                    $accept_newsletter = '';

                    if($documents_privacy == 'true') {
                      $accept_privacy = 'Accettata';
                    } else {
                      $accept_privacy = 'Non accettata';
                    }

                    if($subscribe_newsletter == 'true') {
                      $accept_newsletter = 'Accettata';
                    } else {
                      $accept_newsletter = 'Non accettata';
                    }

                    if($is_csv){
                        $data[0] = '"' .  $subscribe->ID . '"';
                        $data[1] = '"' .  $subscribe_name . '"';
                        $data[2] = '"' .  $subscribe_surname . '"';
                        $data[3] = '"' .  $subscribe_email . '"';
                        $data[4] = '"' .  $subscribe_mobile . '"';
                        $data[5] = '"' .  $subscribe_company . '"';
                        $data[6] = '"' .  $accept_privacy . '"';
                        $data[7] = '"' .  $accept_newsletter . '"';
                    }

                    echo $pre . implode( $separetor, $data ) . $breaker;
                endforeach;
            echo $doc_end;
          exit;
        }
    }
}
