<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.webkomplet.cz/
 * @since      1.0.0
 *
 * @package    Mail_Komplet
 * @subpackage Mail_Komplet/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Mail_Komplet
 * @subpackage Mail_Komplet/public
 * @author     Webkomplet, s.r.o. <martin.polak@webkomplet.cz>
 */
class Mail_Komplet_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mail_Komplet_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mail_Komplet_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mail-komplet-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mail_Komplet_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mail_Komplet_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mail-komplet-public.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Send data from customer registration to Mail Komplet via API
	 * 
	 * @param int customer_id - customer ID in wp_users
	 * @param array new_customer_data
	 * @param bool password_generated
	 * 
	 * @since    1.0.0.
	 */
	function woocommerce_created_customer($customer_id, $new_customer_data, $password_generated) {
	    $email = $new_customer_data['user_email'];
	    
	    $options = get_option($this->plugin_name);

	    $api_key = (isset($options['api-key']) ? $options['api-key'] : '');
	    $base_crypt = (isset($options['base-crypt']) ? $options['base-crypt'] : '');
	    $mailing_list_id = (isset($options['mailing-list-id']) ? $options['mailing-list-id'] : null);
        
	    $data = array(
	        'email' => $email,
	        'mailingListIds' => array(0 => $mailing_list_id),
	    );
	    
	    Mail_Komplet_Api_Caller::mail_komplet_api_call($api_key, $base_crypt, 'POST', 'contacts/', $data);
	}
	
	/**
	 * Send data after order creation to Mail Komplet via API
	 *
	 * @param int order_id - order ID
	 * @param array data
	 *
	 * @since    1.0.0.
	 */
	function woocommerce_checkout_update_order_meta($order_id, $data) {
	    $options = get_option($this->plugin_name);
	    
	    $api_key = (isset($options['api-key']) ? $options['api-key'] : '');
	    $base_crypt = (isset($options['base-crypt']) ? $options['base-crypt'] : '');
	    $mailing_list_id = (isset($options['mailing-list-id']) ? $options['mailing-list-id'] : null);
	    
        $order = wc_get_order($order_id);
        $order_items = $this->get_order_items($order);
        $order_date = $order->get_date_created()->date('Y-m-d H:i:s');
        $order_total_price = $order->get_total();
	    
	    $contact_data = array(
	        'email' => $data['billing_email'],
	        'name' => $data['billing_first_name'],
	        'surname' => $data['billing_last_name'],
	        'mailingListIds' => array(0 => $mailing_list_id),
	    );
	    
	    $order_data = array(
	        array(
    	        'email' => $data['billing_email'],
    	        'created' => $order_date,
    	        'items' => $order_items,
                'orderId' => $order_id,
	            'totalPrice' => $order_total_price,
	            'finished' => $order_date
	        )
	    );

	    Mail_Komplet_Api_Caller::mail_komplet_api_call($api_key, $base_crypt, 'POST', 'contacts', $contact_data);
	    
	    Mail_Komplet_Api_Caller::mail_komplet_api_call($api_key, $base_crypt, 'POST', 'orders/json', $order_data);
	}
	
	private function get_order_items($order) {
	    $order_items = array();
	    foreach ($order->get_items() as $order_item) {
	        $product = $order_item->get_product();

	        // get category name by product ID
	        $terms = get_the_terms($product->get_id(), 'product_cat');
	        $category = null;
	        if (!empty($terms)) {
    	        foreach ($terms as $term) {
    	            $category = $term->name;
    	            break;
    	        }
	        }
	        $amount = wc_stock_amount($order_item['quantity']);
	        $priceVat = $order_item->get_total() / $amount;
	        
	        // set up order item properties
	        $order_item = array(
	            'name' => $product->get_name(),
	            'amount' => $amount,
	            'category' => $category,
	            'price' => $priceVat,
                'productUrl' => get_permalink($product->id)
	        );

	        $order_items[] = $order_item;
	    }
	    
	    return $order_items;
	}
	
	/**
	 * Send data after account update to Mail Komplet via API
	 *
	 * @param int user_id - user ID
	 *
	 * @since    1.0.0.
	 */
	function woocommerce_save_account_details($user_id) {
	    $options = get_option($this->plugin_name);
	    
	    $api_key = (isset($options['api-key']) ? $options['api-key'] : '');
	    $base_crypt = (isset($options['base-crypt']) ? $options['base-crypt'] : '');
	    $mailing_list_id = (isset($options['mailing-list-id']) ? $options['mailing-list-id'] : null);
	    
        $customer = new WC_Customer($user_id);
        
        $data = array(
            'email' => $customer->get_email(),
            'name' => $customer->get_first_name(),
            'surname' => $customer->get_last_name(),
            'mailingListIds' => array(0 => $mailing_list_id),
        );
        
        Mail_Komplet_Api_Caller::mail_komplet_api_call($api_key, $base_crypt, 'POST', 'contacts/', $data);
	}
}
