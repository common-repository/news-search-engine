<?php
if ( !class_exists( 'NewsSearchEngineAdmin' ) ) {

    class NewsSearchEngineAdmin {

        private $model;

        public function __construct(){
            add_action( 'admin_menu', array( $this, 'adminMenu' ) );
            add_action( 'admin_post', array( $this, 'adminSave' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'adminStyle' ) );
            // check if php version is OK!
            if (defined('PHP_MAJOR_VERSION') && PHP_MAJOR_VERSION < 7){
                add_action( 'admin_notices', array( $this, 'adminError' ) );
            }

            $this->model = new NewsSearchEngineModel;
        }

        // create menu items
        public function adminMenu() {

            add_menu_page(
                'News Search Engine', 
                'News Search Engine', 
                'manage_options', 
                'news_search_engine', 
                array( $this, 'adminFromPage' ), 
                'dashicons-id', 
                90
            );
            
            add_submenu_page(
                'news_search_engine',
                'News Search Engine Settings', 
                'News Search Engine Settings', 
                'manage_options',
                'news_search_engine_settings',
                array( $this, 'adminSettingsPage' )
            ); 

        }

        // display error message
        public function adminError(){
            ?>
            <div class="error notice">
                <p><?php _e( 'News Search Engine Plugin error: your php version is ' . PHP_VERSION . '. PHP 7 or above is required. This plugin won\'t work!' ); ?></p>
            </div>
            <?php           
        }

        // create  form page url
        public function adminFromPage() {
            //show the form
            include_once( PLUGIN_NSE_PATH . 'views/admin-form.php' );
        }
        
        // create setting page url
        public function adminSettingsPage() {
            //show the settings form
            include_once( PLUGIN_NSE_PATH . 'views/admin-settings.php' );
        }

        // save setting form
        public function adminSave(){

            // check if the post is comming from setting page
            if(sanitize_text_field( $_POST['submit'] ) == 'Save Settings'){
                $this->model->setData($_POST);

                // save api key
                if($_POST['news-search-engine-api-key']){
                    $this->model->setMessage('Settings saved.');
                    $this->adminRedirect();
                    return true;
                }

                $this->model->setMessage('Please insert API key.');
                $this->adminRedirect();
                return false;
            }

        }

        // redirect to the previous page after saving form
        public function adminRedirect() {
            // redirect at the end of the process
            if(isset( $_POST['_wp_http_referer'] )){
                // redirect the user to the appropriate page
                $url = sanitize_text_field(
                    wp_unslash( $_POST['_wp_http_referer'] ) // Input var okay.
                );
                // Finally, redirect back to the admin page.
                wp_safe_redirect( urldecode( $url ) );
                exit;
            }
            else{
                wp_safe_redirect( urldecode( '/wp-admin' ) );
                exit;
            }
        }

        // import CSS and JS
        public function adminStyle() {
            wp_enqueue_script('news-search-engine-admin', PLUGIN_NSE_URL . 'assets/admin.min.js', array( 'jquery' ), '1.0.0', true );
            wp_enqueue_style('news-search-engine-admin', PLUGIN_NSE_URL . 'assets/admin.min.css', array(), null, 'all' );
        }

        // callback, not in use
        public function adminCallback() { // Section Callback
            echo '<p>This section is part of News Search Engine Plugin</p>';
        }

    }
}
