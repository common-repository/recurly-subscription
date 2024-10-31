<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://codepaladins.com
 * @since      1.0.0
 *
 * @package    Recurly_Subscription
 * @subpackage Recurly_Subscription/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Recurly_Subscription
 * @subpackage Recurly_Subscription/public
 * @author     CodePaladins <support@codepaladins.com>
 */
class Recurly_Subscription_Public {

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
		$this->rs_sub_domain=get_option('rs_subdomain');
		$this->rs_url = 'https://'.get_option('rs_subdomain').'.recurly.com';
		$this->rs_privatekey = get_option('rs_pri_key');
		$this->rs_publickey = get_option('rs_pub_key');
		$this->rs_subscription = get_option('rs_subs_amt');
		$this->rs_currency = get_option('rs_currency');
		$this->rs_recurring = get_option('rs_recurring');
		$this->rs_recurring_label = get_option('rs_recurring_label');
		$this->rs_paln = get_option('rs_plan');

        // Add Shortcodes
		add_shortcode('rs_subscriber_form', array($this, 'rs_subscriber_form_handler') );
		//Ajax Call Action
		add_action( 'wp_ajax_subscriber_form_callback', array($this,'subscriber_form_callback') );
        add_action( 'wp_ajax_nopriv_subscriber_form_callback', array($this,'subscriber_form_callback' ));

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
		 * defined in Recurly_Subscription_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Recurly_Subscription_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/recurly-subscription-public.css', array(), $this->version, 'all' );

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
		 * defined in Recurly_Subscription_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Recurly_Subscription_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/recurly-subscription-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'rs-js-validations', plugin_dir_url( __FILE__ ) . 'js/rs-jquery-validate.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'rs-js-methods', plugin_dir_url( __FILE__ ) . 'js/rs-additional-methods.js', array( 'jquery' ), $this->version, false );

		$js_var_array = array(
			'rs_ajax_url' 		=> 	admin_url( 'admin-ajax.php' ),
			'pluginsUrl'    =>  plugin_dir_url( __FILE__ ) 
		);
        wp_localize_script( $this->plugin_name, 'rs_public_script', $js_var_array );

	}
    function rs_subscriber_form_handler($atts, $content = null){
        extract( shortcode_atts( array(
				'title' => ''
			    ), $atts 
		   ) 
	    );
    $card_types	=	array(
						'A'	=>	'American Express',
						'P'	=>	'Alliance Private Label Participant',
						'B'	=>	'Beneficial',
						'C'	=>	'Check',
						'H'	=>	'Carte Blanche',
						'D'	=>	'Discover',
						'N'	=>	'Diners Club',
						'G'	=>	'GECC',
						'J'	=>	'JCB',
						'M'	=>	'Master Card',
						'T'	=>	'Maestro',
						'O'	=>	'SOLO',
						'E'	=>	'Visa Electron',
						'V'	=>	'Visa',
						''	=>	'Other'
					);
					
					$cc_exp_months	=	range(1,12,1);
					$cc_exp_years	=	range(date("Y"), date("Y")+30);
     ob_start();?>
     <h1><?php if(isset($title)){ echo $title; } ?></h1>
<form name="rs_subsc_form" id="rs_subsc_form" method="POST" action="">
	
	<div class="subscription_info">
		<div class="blockcls">  
    <?php if(!empty($this->rs_subscription)) { ?>
		<span class="clearleft"> 
			<label>Subscription Amount: <?php echo get_option("rs_subs_amt")." ".get_option("rs_currency"); ?></label> 
		</span>
    <?php }?>
		
	<?php if(!empty($this->rs_recurring)){ ?> 
		<span>
	     <label>
	  <input type="checkbox" id="rs_enable_recurring" name="rs_enable_recurring" value="<?php echo $this->rs_recurring; ?>" /><?php echo $this->rs_recurring_label; ?> </label> 
        </span>
     <?php }?>
	</div>
	</div>
	<div class="contact_info">
		<div class="title">Contact Info:</div>
	<div class="blockcls">  
	<span class="clearleft">                                   
<label>First Name</label> 
<input type="text" id="rs_fname" name="rs_fname" data-rule-required="true"/>
</span>
<span>
	<label>Last Name</label> 
<input type="text" id="rs_lname" name="rs_lname" data-rule-required="true"/>
</span>
</div>
<div  class="blockcls">
<label>Email</label>
<input type="email" id="rs_email" name="rs_email" data-rule-required="true"/>
</div>
</div>
<div class="billing_info"><div class="title">Billing Info:</div>
<div  class="blockcls">
    			<label>
						<select name="cc_type" id="cc_type" style="display:none;">
							<?php $cc_default_val='';?>
							<?php foreach($card_types as $key => $card_type){
								$selected = ($key == $cc_default_val) ? ' selected':'';
								echo '<option value="'.$key.'" '.$selected.'>'.$card_type.'</option>';
							}?>
						</select>
                               
                        </label>
      </div>                  
					<div class="blockcls"><label>Card Number</label> <span> <img id="selected_card_logo" src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/cards/default.svg"></span>
                        <input type="text" id="ccn_1" name="ccn_1" size="4" maxlength="4" class="cc_field" data-rule-required="true">
						<input type="text" id="ccn_2" name="ccn_2" size="4" maxlength="4" class="cc_field" data-rule-required="true">
						<input type="text" id="ccn_3" name="ccn_3" size="4" maxlength="4" class="cc_field" data-rule-required="true">
						<input type="text" id="ccn_4" name="ccn_4" size="4" maxlength="4" class="cc_field" data-rule-required="true">
					</div>
					
					<div  class="blockcls"> 
						<span class="clearleft"><label>Expiry Date</label>
						<select name="cc_exp_month" id="cc_exp_month" data-rule-required="true">
						<?php foreach($cc_exp_months as $cc_exp_month){
							echo '<option value="'.sprintf('%02d',$cc_exp_month).'">'.sprintf('%02d',$cc_exp_month).'</option>';
						}?>
						</select>
					
						<select name="cc_exp_year" id="cc_exp_year" data-rule-required="true">
						<?php foreach($cc_exp_years as $cc_exp_year){
							echo '<option value="'.$cc_exp_year.'">'.$cc_exp_year.'</option>';
						}?>
						</select>
</span><span><label>CVV</label>
						<input type="text" class="cc_field" name="cc_cvv" id="cc_cvv" maxlength="3" size="3"  data-rule-required="true">
						</span>
					</div>
					
                  <div class="blockcls">  
                  <span class="clearleft">                                   
<label>Address</label> 
<input type="text" id="rs_address" name="rs_address" data-rule-required="true"/>
</span>

<span> 
<label>Apt/Suite</label> 
<input type="text" id="rs_apt" name="rs_apt" />
</span>
</div>

<div class="blockcls">  
<span class="clearleft">                                   
<label>City</label> 
<input type="text" id="rs_city" name="rs_city" data-rule-required="true"/>
</span><span>
<label>State/Province</label> 
<input type="text" id="rs_state" name="rs_state" />	
</span>
</div>   

<div class="blockcls"> 
<span class="clearleft">                                    
<label>Zip/Postal</label> 
<input type="text" id="rs_zip" name="rs_zip" data-rule-required="true"/>
</span> 

<span>                                     
                                    
<label>Country</label> 
<select name="rs_country" id="rs_country" data-rule-required="true" class="rs_country_field">
	<option value="">Select Country</option><option value="">--------------</option>
	<option value="AF">Afghanistan</option><option value="AL">Albania</option><option value="DZ">Algeria</option><option value="AS">American Samoa</option><option value="AD">Andorra</option><option value="AO">Angola</option><option value="AI">Anguilla</option><option value="AQ">Antarctica</option><option value="AG">Antigua and Barbuda</option><option value="AR">Argentina</option><option value="AM">Armenia</option><option value="AW">Aruba</option><option value="AC">Ascension Island</option><option value="AU">Australia</option><option value="AT">Austria</option><option value="AZ">Azerbaijan</option><option value="BS">Bahamas</option><option value="BH">Bahrain</option><option value="BD">Bangladesh</option><option value="BB">Barbados</option><option value="BE">Belgium</option><option value="BZ">Belize</option><option value="BJ">Benin</option><option value="BM">Bermuda</option><option value="BT">Bhutan</option><option value="BO">Bolivia</option><option value="BA">Bosnia and Herzegovina</option><option value="BW">Botswana</option><option value="BV">Bouvet Island</option><option value="BR">Brazil</option><option value="BQ">British Antarctic Territory</option><option value="IO">British Indian Ocean Territory</option><option value="VG">British Virgin Islands</option><option value="BN">Brunei</option><option value="BG">Bulgaria</option><option value="BF">Burkina Faso</option><option value="BI">Burundi</option><option value="KH">Cambodia</option><option value="CM">Cameroon</option><option value="CA">Canada</option><option value="IC">Canary Islands</option><option value="CT">Canton and Enderbury Islands</option><option value="CV">Cape Verde</option><option value="KY">Cayman Islands</option><option value="CF">Central African Republic</option><option value="EA">Ceuta and Melilla</option><option value="TD">Chad</option><option value="CL">Chile</option><option value="CN">China</option><option value="CX">Christmas Island</option><option value="CP">Clipperton Island</option><option value="CC">Cocos [Keeling] Islands</option><option value="CO">Colombia</option><option value="KM">Comoros</option><option value="CD">Congo [DRC]</option><option value="CK">Cook Islands</option><option value="CR">Costa Rica</option><option value="HR">Croatia</option><option value="CU">Cuba</option><option value="CY">Cyprus</option><option value="CZ">Czech Republic</option><option value="DK">Denmark</option><option value="DG">Diego Garcia</option><option value="DJ">Djibouti</option><option value="DM">Dominica</option><option value="DO">Dominican Republic</option><option value="NQ">Dronning Maud Land</option><option value="TL">East Timor</option><option value="EC">Ecuador</option><option value="EG">Egypt</option><option value="SV">El Salvador</option><option value="EE">Estonia</option><option value="ET">Ethiopia</option><option value="FK">Falkland Islands [Islas Malvinas]</option><option value="FO">Faroe Islands</option><option value="FJ">Fiji</option><option value="FI">Finland</option><option value="FR">France</option><option value="GF">French Guiana</option><option value="PF">French Polynesia</option><option value="TF">French Southern Territories</option><option value="FQ">French Southern and Antarctic Territories</option><option value="GA">Gabon</option><option value="GM">Gambia</option><option value="GE">Georgia</option><option value="DE">Germany</option><option value="GH">Ghana</option><option value="GI">Gibraltar</option><option value="GR">Greece</option><option value="GL">Greenland</option><option value="GD">Grenada</option><option value="GP">Guadeloupe</option><option value="GU">Guam</option><option value="GT">Guatemala</option><option value="GG">Guernsey</option><option value="GW">Guinea-Bissau</option><option value="GY">Guyana</option><option value="HT">Haiti</option><option value="HM">Heard Island and McDonald Islands</option><option value="HN">Honduras</option><option value="HK">Hong Kong</option><option value="HU">Hungary</option><option value="IS">Iceland</option><option value="IN">India</option><option value="ID">Indonesia</option><option value="IE">Ireland</option><option value="IM">Isle of Man</option><option value="IL">Israel</option><option value="IT">Italy</option><option value="JM">Jamaica</option><option value="JP">Japan</option><option value="JE">Jersey</option><option value="JT">Johnston Island</option><option value="JO">Jordan</option><option value="KZ">Kazakhstan</option><option value="KE">Kenya</option><option value="KI">Kiribati</option><option value="KW">Kuwait</option><option value="KG">Kyrgyzstan</option><option value="LA">Laos</option><option value="LV">Latvia</option><option value="LS">Lesotho</option><option value="LY">Libya</option><option value="LI">Liechtenstein</option><option value="LT">Lithuania</option><option value="LU">Luxembourg</option><option value="MO">Macau</option><option value="MK">Macedonia [FYROM]</option><option value="MG">Madagascar</option><option value="MW">Malawi</option><option value="MY">Malaysia</option><option value="MV">Maldives</option><option value="ML">Mali</option><option value="MT">Malta</option><option value="MH">Marshall Islands</option><option value="MQ">Martinique</option><option value="MR">Mauritania</option><option value="MU">Mauritius</option><option value="YT">Mayotte</option><option value="FX">Metropolitan France</option><option value="MX">Mexico</option><option value="FM">Micronesia</option><option value="MI">Midway Islands</option><option value="MD">Moldova</option><option value="MC">Monaco</option><option value="MN">Mongolia</option><option value="ME">Montenegro</option><option value="MS">Montserrat</option><option value="MA">Morocco</option><option value="MZ">Mozambique</option><option value="NA">Namibia</option><option value="NR">Nauru</option><option value="NP">Nepal</option><option value="NL">Netherlands</option><option value="AN">Netherlands Antilles</option><option value="NT">Neutral Zone</option><option value="NC">New Caledonia</option><option value="NZ">New Zealand</option><option value="NI">Nicaragua</option><option value="NE">Niger</option><option value="NG">Nigeria</option><option value="NU">Niue</option><option value="NF">Norfolk Island</option><option value="VD">North Vietnam</option><option value="MP">Northern Mariana Islands</option><option value="NO">Norway</option><option value="OM">Oman</option><option value="QO">Outlying Oceania</option><option value="PC">Pacific Islands Trust Territory</option><option value="PK">Pakistan</option><option value="PW">Palau</option><option value="PS">Palestinian Territories</option><option value="PA">Panama</option><option value="PZ">Panama Canal Zone</option><option value="PY">Paraguay</option><option value="YD">People's Democratic Republic of Yemen</option><option value="PE">Peru</option><option value="PH">Philippines</option><option value="PN">Pitcairn Islands</option><option value="PL">Poland</option><option value="PT">Portugal</option><option value="PR">Puerto Rico</option><option value="QA">Qatar</option><option value="RO">Romania</option><option value="RU">Russia</option><option value="RW">Rwanda</option><option value="RE">Réunion</option><option value="BL">Saint Barthélemy</option><option value="SH">Saint Helena</option><option value="KN">Saint Kitts and Nevis</option><option value="LC">Saint Lucia</option><option value="MF">Saint Martin</option><option value="PM">Saint Pierre and Miquelon</option><option value="VC">Saint Vincent and the Grenadines</option><option value="WS">Samoa</option><option value="SM">San Marino</option><option value="SA">Saudi Arabia</option><option value="SN">Senegal</option><option value="RS">Serbia</option><option value="CS">Serbia and Montenegro</option><option value="SC">Seychelles</option><option value="SL">Sierra Leone</option><option value="SG">Singapore</option><option value="SK">Slovakia</option><option value="SI">Slovenia</option><option value="SB">Solomon Islands</option><option value="ZA">South Africa</option><option value="GS">South Georgia and the South Sandwich Islands</option><option value="KR">South Korea</option><option value="ES">Spain</option><option value="LK">Sri Lanka</option><option value="SR">Suriname</option><option value="SJ">Svalbard and Jan Mayen</option><option value="SZ">Swaziland</option><option value="SE">Sweden</option><option value="CH">Switzerland</option><option value="ST">São Tomé and Príncipe</option><option value="TW">Taiwan</option><option value="TJ">Tajikistan</option><option value="TZ">Tanzania</option><option value="TH">Thailand</option><option value="TG">Togo</option><option value="TK">Tokelau</option><option value="TO">Tonga</option><option value="TT">Trinidad and Tobago</option><option value="TA">Tristan da Cunha</option><option value="TN">Tunisia</option><option value="TR">Turkey</option><option value="TM">Turkmenistan</option><option value="TC">Turks and Caicos Islands</option><option value="TV">Tuvalu</option><option value="UM">U.S. Minor Outlying Islands</option><option value="PU">U.S. Miscellaneous Pacific Islands</option><option value="VI">U.S. Virgin Islands</option><option value="UG">Uganda</option><option value="UA">Ukraine</option><option value="AE">United Arab Emirates</option><option value="GB">United Kingdom</option><option value="US">United States</option><option value="UY">Uruguay</option><option value="UZ">Uzbekistan</option><option value="VU">Vanuatu</option><option value="VA">Vatican City</option><option value="VE">Venezuela</option><option value="VN">Vietnam</option><option value="WK">Wake Island</option><option value="WF">Wallis and Futuna</option><option value="EH">Western Sahara</option><option value="YE">Yemen</option><option value="ZM">Zambia</option><option value="AX">Åland Islands</option></select>
</span> </div>

</div>

<div  class="blockcls">
	<span class="clearleft">  
<input type="submit" value="Subscribe" id="subs_btn"/>
<img src="<?php echo plugin_dir_url( __FILE__ ); ?>/images/rsloading.gif" style="display: none;width: 16px;" id="rs_loading">
</span>
<span>
	<div id="rs_msg"></div>
</span>
</div>
</form>

     <?php
    $content = ob_get_contents();
    ob_end_clean();
	// this will display our message before the content of the shortcode
	return $content;
}
function subscriber_form_callback(){
	$output	=	new stdClass();	
	//echo "<pre>"; print_r($_REQUEST);exit;
	$rs_fname=$_REQUEST['rs_fname'];
	$rs_lname=$_REQUEST['rs_lname'];
	$rs_email=$_REQUEST['rs_email'];
	$ccn_1=$_REQUEST['ccn_1'];
	$ccn_2=$_REQUEST['ccn_2'];
	$ccn_3=$_REQUEST['ccn_3'];
	$ccn_4=$_REQUEST['ccn_4'];
	$cc_number=$ccn_1.$ccn_2.$ccn_3.$ccn_4;
	$cc_exp_month=$_REQUEST['cc_exp_month'];
	$cc_exp_year=$_REQUEST['cc_exp_year'];
	$cc_cvv=$_REQUEST['cc_cvv'];
	$cc_type=$_REQUEST['cc_type'];

	$rs_address=$_REQUEST['rs_address'];
	$rs_apt=$_REQUEST['rs_apt'];
	$rs_city=$_REQUEST['rs_city'];
	$rs_country=$_REQUEST['rs_country'];
	$rs_state=$_REQUEST['rs_state'];
	$rs_zip=$_REQUEST['rs_zip'];
if($this->rs_sub_domain=='' || $this->rs_currency=='' || $this->rs_subscription=='' || $this->rs_privatekey=='' || $this->rs_publickey==''){
                    $output->code	=	400;
					$output->message	=	'Connection Failed';
}else {

$amount_in_cents=$this->rs_subscription*100;
if(isset($_REQUEST['rs_enable_recurring'])){
	$subdomain=$this->rs_url.'/v2/subscriptions';
$body='<?xml version="1.0" encoding="UTF-8"?>
<subscription href="'.$subdomain.'">
<plan_code>'.$this->rs_paln.'</plan_code>
<currency>'.$this->rs_currency.'</currency>
<unit_amount_in_cents>'.$amount_in_cents.'</unit_amount_in_cents>
    <account>
      <account_code>'.$rs_email.'</account_code>
      <email>'.$rs_email.'</email>
      <first_name>'.$rs_fname.'</first_name>
      <last_name>'.$rs_lname.'</last_name>
      <company_name></company_name> 
  <address>
   <address1>'.$rs_address.'</address1>
   <address2 nil="nil"/>
   <city>'.$rs_city.'</city>
   <state>'.$rs_state.'</state>
   <zip>'.$rs_zip.'</zip>
   <country>'.$rs_country.'</country>
   <phone nil="nil"/>
 </address>
 <billing_info type="credit_card">
        <first_name>'.$rs_fname.'</first_name>
        <last_name>'.$rs_lname.'</last_name>
        <address1>'.$rs_address.'</address1>
        <address2 nil="nil"/>
        <city>'.$rs_city.'</city>
        <state>'.$rs_state.'</state>
        <zip>'.$rs_zip.'</zip>
        <country>'.$rs_country.'</country>
        <phone></phone>
        <vat_number nil="nil"/>
		<number>'.$cc_number.'</number>
        <year type="integer">'.$cc_exp_year.'</year>
        <month type="integer">'.$cc_exp_month.'</month>
		<verification_value>'.$cc_cvv.'</verification_value>
      </billing_info>
    </account>
</subscription>
';	
}else{
	$subdomain=$this->rs_url.'/v2/transactions';
$body='<?xml version="1.0" encoding="UTF-8"?>
<transaction href="'.$subdomain.'" type="credit_card">
  <account href="'.$this->rs_url.'/v2/accounts/'.$rs_email.'"/>
  <amount_in_cents type="integer">'.$amount_in_cents.'</amount_in_cents>
<currency>'.$this->rs_currency.'</currency>
  <payment_method>credit_card</payment_method>
    <account>
      <account_code>'.$rs_email.'</account_code>
	  <email>'.$rs_email.'</email>
	  <first_name>'.$rs_fname.'</first_name>
	  <last_name>'.$rs_lname.'</last_name>
	  <company_name></company_name>	  
      <billing_info type="credit_card">
        <first_name>'.$rs_fname.'</first_name>
        <last_name>'.$rs_lname.'</last_name>
        <address1>'.$rs_address.'</address1>
        <address2 nil="nil"/>
        <city>'.$rs_city.'</city>
        <state>'.$rs_state.'</state>
        <zip>'.$rs_zip.'</zip>
        <country>'.$rs_country.'</country>
        <phone></phone>
        <vat_number nil="nil"/>
		<number>'.$cc_number.'</number>
        <year type="integer">'.$cc_exp_year.'</year>
        <month type="integer">'.$cc_exp_month.'</month>
		<verification_value>'.$cc_cvv.'</verification_value>
      </billing_info>
    </account>

</transaction>
';
}
//print_r($body);exit;
$args = array(
'method'=>'POST',
  'headers' => array(
   "Authorization" => "Basic " . base64_encode($this->rs_privatekey)
  ),
  'body' => $body,
  "sslverify" => false
);
$response=wp_remote_request($subdomain,$args);
$result=$response['response'];
if($result['code']==201){
	$user_id = wp_insert_user(
	array(
		'user_login'	=>	$rs_email,
		'user_pass'	    =>	wp_generate_password ( 12, false ),
		'first_name'	=>	$rs_fname,
		'last_name'  	=>	$rs_lname,
		'user_email'	=>	$rs_email,
		'display_name'	=>	$rs_fname . ' ' . $rs_lname,
		'nickname'	    =>	$rs_fname . ' ' . $rs_lname,
		'role'		    =>	'recurly_user'
	)
);
}
$output=$response['response'];
}
//echo "<pre>";print_r($response['response']);print_r($response);
echo json_encode($output);
	wp_die();
}
}
