<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.webkomplet.cz/
 * @since      1.0.0
 *
 * @package    Mail_Komplet
 * @subpackage Mail_Komplet/admin/partials
 */
?>

<?php $image_dir = plugin_dir_url(__FILE__) . './../images/'?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap" id="mail-komplet-content">

    <fieldset style="padding: 30px 30px 0 30px; font-size:20px; border:none; width:700px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif">
        <div style="display: block">
            <a href="https://www.mail-komplet.cz/" target="_blank" style="cursor:pointer;"><img src="<?php echo $image_dir ?>logo-mk.png" alt="Logo Mail Komplet" style="width:auto;height:50px;margin-left:5px;"></a>
        </div>
        <div style="width:650px">    
            <div style="width: 340px; height: 205px; padding: 8px; margin-left: 5px; margin-top:-15px; display: inline-block;float:left;">
                <h3 style="color:#bf1f1f; padding-top:8px"><?php _e('Contact Mail Komplet', $this->plugin_name)?></h3>
                <div style="clear: both;"></div>
                <p style="margin-bottom: 1em">
                    <img src="<?php echo $image_dir ?>obalka.png" alt="e-mail icon" style="margin-right:10px;margin-top:5px;">
                    <a href="mailto:<?php _e('info@mail-komplet.cz', $this->plugin_name)?>" style="color:#bf1f1f;"><?php _e('info@mail-komplet.cz', $this->plugin_name)?></a>
                    <br/>
                    <img src="<?php echo $image_dir ?>telefon.png" alt="phone icon" style="margin-right:10px;margin-top:5px;"><?php _e('+420 517 070 000', $this->plugin_name)?>
                </p>
                <p>
                    <b><?php _e('Visit us for more info', $this->plugin_name)?>: </b>
                    <br/>
                    <a href="<?php _e('https://www.mail-komplet.cz/', $this->plugin_name)?>" target="_blank" style="color:#bf1f1f;"><b><?php _e('https://www.mail-komplet.cz/', $this->plugin_name)?></b></a>
                </p>
            </div>
            <div style="display: inline-block;float:right;">
                <a href="https://www.mail-komplet.cz/" target="_blank" style="cursor:pointer;"><img src="<?php echo $image_dir ?>computer.png" style="height:180px; width:222px" alt="App Mail Komplet"></a>
            </div>
        </div>
    </fieldset>

	<fieldset style="padding: 10px 30px 30px 30px; border:none; width:800px;border-radius: 60px;font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif">
        <form method="post" name="mail_komplet_options" action="options.php">
        
            <?php
                // Grab all options
                $options = get_option($this->plugin_name);
        
                $api_key = (isset($options['api-key']) ? $options['api-key'] : '');
                $base_crypt = (isset($options['base-crypt']) ? $options['base-crypt'] : '');
                $mailing_list_id = (isset($options['mailing-list-id']) ? $options['mailing-list-id'] : null);
    
        	   settings_fields($this->plugin_name);
        	   do_settings_sections($this->plugin_name);
        	?>
    
        	<!-- set API key -->
        	<fieldset style="margin-bottom: 10px">
        		<legend class="screen-reader-text"><span><?php _e('API key', $this->plugin_name)?></span></legend>
        		<label for="<?php echo $this->plugin_name?>-api-key">
        			<input type="text" size="50" id="<?php echo $this->plugin_name?>-api-key" name="<?php echo $this->plugin_name?>[api-key]" value="<?php if(!empty($api_key)) echo $api_key; ?>"/>
        			<span><?php _e('API key', $this->plugin_name)?></span>
        		</label>
        	</fieldset>
        	
        	<!-- set base crypt -->
        	<fieldset id="<?php echo $this->plugin_name?>-base-crypt-fieldset" style="margin-bottom: 10px">
        		<legend class="screen-reader-text"><span><?php _e('Base crypt', $this->plugin_name)?></span></legend>
        		<label for="<?php echo $this->plugin_name?>-base-crypt">
        			<input type="text" size="50" id="<?php echo $this->plugin_name?>-base-crypt" name="<?php echo $this->plugin_name?>[base-crypt]"value="<?php if(!empty($base_crypt)) echo $base_crypt; ?>" />
        			<span><?php _e('Base crypt', $this->plugin_name)?></span>
        		</label>
        	</fieldset>
        	
        	<!-- set mailing list id -->
        	<fieldset>
        		<legend class="screen-reader-text"><span><?php _e('Mailing List', $this->plugin_name)?></span></legend>
        		<label for="<?php echo $this->plugin_name?>-mailing-list-id">
        			<select id="<?php echo $this->plugin_name?>-mailing-list-id" name="<?php echo $this->plugin_name?>[mailing-list-id]" ></select>
        			<span><?php _e('Mailing List', $this->plugin_name)?></span>
        		</label>
        	</fieldset>
        	
        	<fieldset>
            	<input type="hidden" id="plugin-name" value="<?php echo $this->plugin_name?>" />
            	<input type="hidden" id="<?php echo $this->plugin_name?>-module-path" value="<?php echo $this->path?>" />
            	<input type="hidden" id="<?php echo $this->plugin_name?>-str-connect" value="<?php _e('Connect', $this->plugin_name)?>" />
            	<input type="hidden" id="<?php echo $this->plugin_name?>-str-connecting" value="<?php _e('Connecting', $this->plugin_name)?>" />
            	<input type="hidden" id="<?php echo $this->plugin_name?>-mailing-list-id_" value="<?php echo $mailing_list_id?>" />
            	<input type="hidden" id="<?php echo $this->plugin_name?>-str-ajax-error" value="<?php _e('Unable to download mailing lists. Probably API key or base crypt string is wrong. Try to set it again please', $this->plugin_name)?>" />
        	
        		<?php submit_button(__('Save all changes', $this->plugin_name), 'primary','submit', TRUE); ?>
        	</fieldset>
        </form>
    </fieldset>
</div>