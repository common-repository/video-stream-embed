<?php
/*
Plugin Name: StreamDefence
Plugin URI: http://www.streamdefence.com
Description: streamdefence changes iframes to encrypted with streamdefence.com API
Version: 1.8.1
Author: Streamdefence
Author URI: http://www.steamdefence.com
License: GPLv2
*/


function plgsdapi_ReplaceShortcode($atts, $content = '') 
{
    $html = "";
    
    $atts = shortcode_atts( array(
    		'video_id' => ''
    	), $atts, 'streamdefence' );
    
    $video_id = $atts['video_id'];
    
    if ($video_id != '')
    {
        $params = plgsdapi_GetExtraParams();
        $params['ref'] = $video_id;
        $params['output'] = 'shortcode';
        
        $url = 'https://sdefx.cloud/index.php';
        $response = wp_remote_post( $url, array(
        	'method' => 'POST',
        	'timeout' => 45,
        	'redirection' => 5,
        	'httpversion' => '1.0',
        	'blocking' => true,
        	'headers' => array(),
        	'body' => $params,
        	'cookies' => array()
            )
        );
        
        $html = $response['body'];
    }
    else $html = 'Error video_id';
    
    return $html;
}

add_shortcode('streamdefence', 'plgsdapi_ReplaceShortcode');



if( is_admin() ) {

    function plgsdapi_checkupdate_post( $post_id, $post, $update ) 
    {
        if (!function_exists('str_get_html') && !class_exists('simple_html_dom_node')) include_once(dirname(__FILE__)."/simple_html_dom.php");
        
        $params = plgsdapi_GetExtraParams();
		
        if (isset($params['extra_template']) && $params['extra_template'] != 'none')
        {
            
            switch ($params['extra_template'])
            {
                case 'novavideo':
                    $post_meta = get_post_meta($post_id);
                    if (isset($post_meta['code']) && trim($post_meta['code'][0]) != '') 
                    {
                        $flag_update = false;
                        
                        $content = $post_meta['code'][0];
                        $content = plgsdapi_PrepareIframeCode($content);
                        $html = str_get_html($content);
                        if ($html !== false)
                        {
                            $code = '';
                            foreach($html->find('iframe') as $el) 
                            {
                                $src = $el->src;
                                if (stripos($src, "strdef.world") !== false || stripos($src, "sdefc.icu") !== false || stripos($src, "sdefx.cloud") !== false) continue;
                                
                                $new_iframe = plgsdapi_GenerateIFrameHTML($el->outertext);
                                //plgsdapi_SaveLog($new_iframe);
                                
                                $content = str_replace($el, $new_iframe, $content);
                                
                                $flag_update = true;
                            }
                            
                            if ($flag_update)
                            {
                                //plgsdapi_SaveLog('>>'.$content);
                                update_post_meta( $post_id, 'code', $content );
                            }
                        }
                    }
                    break;
					
                case 'retrotube':
                    $post_meta = get_post_meta($post_id);
                    if (isset($post_meta['embed']) && trim($post_meta['embed'][0]) != '') 
                    {
                        $flag_update = false;
                        
                        $content = $post_meta['embed'][0];
                        $content = plgsdapi_PrepareIframeCode($content);
                        $html = str_get_html($content);
                        if ($html !== false)
                        {
                            $code = '';
                            foreach($html->find('iframe') as $el) 
                            {
                                $src = $el->src;
                                if (stripos($src, "strdef.world") !== false || stripos($src, "sdefc.icu") !== false || stripos($src, "sdefx.cloud") !== false) continue;
                                
                                $new_iframe = plgsdapi_GenerateIFrameHTML($el->outertext);
                                //plgsdapi_SaveLog($new_iframe);
                                
                                $content = str_replace($el, $new_iframe, $content);
                                
                                $flag_update = true;
                            }
                            
                            if ($flag_update)
                            {
                                //plgsdapi_SaveLog('>>'.$content);
                                update_post_meta( $post_id, 'embed', $content );
                            }
                        }
                    }
                    break;
                    
                case 'dooplay':
                    $post_meta = get_post_meta($post_id);
                    if (isset($post_meta['repeatable_fields']) && trim($post_meta['repeatable_fields'][0]) != '') 
                    {
                        $flag_update = false;
                        
                        $content = $post_meta['repeatable_fields'][0];
                        $content = plgsdapi_PrepareIframeCode($content);
						//plgsdapi_SaveLog($content);
                        $content = unserialize($content);
                        
                        foreach ($content as $k => $data)
                        {
							//plgsdapi_SaveLog($data['url']);
                            if (stripos($data['url'], "strdef.world") !== false || stripos($src, "sdefc.icu") !== false || stripos($src, "sdefx.cloud") !== false) continue;
                            
                            if (isset($data['url']) && trim($data['url']) != '')
                            {
                                $html = '<iframe src="'.$data['url'].'" height="100%" width="100%"></iframe>';
                                $new_url = plgsdapi_GenerateRawlink($html);
                                
                                $content[$k]['url'] = $new_url;
                                
                                $flag_update = true;
                            }
                        }
                        
                        if ($flag_update)
                        {
                            //$content = serialize($content);
							//plgsdapi_SaveLog($content);
                            update_post_meta( $post_id, 'repeatable_fields', $content );
                        }
                    }
                    break;


                case 'animestream':
                    $post_meta = get_post_meta($post_id);
                    if (isset($post_meta['ab_embedgroup']) && trim($post_meta['ab_embedgroup'][0]) != '') 
                    {
                        $flag_update = false;
                        
                        $content = $post_meta['ab_embedgroup'][0];
                        $content = plgsdapi_PrepareIframeCode($content);
                        $html = str_get_html($content);
                        if ($html !== false)
                        {
                            $code = '';
                            foreach($html->find('iframe') as $el) 
                            {
                                $src = $el->src;
                                if (stripos($src, "strdef.world") !== false || stripos($src, "sdefc.icu") !== false || stripos($src, "sdefx.cloud") !== false) continue;
                                
                                $new_iframe = plgsdapi_GenerateIFrameHTML($el->outertext);
                                //plgsdapi_SaveLog($new_iframe);
                                
                                $content = str_replace($el, $new_iframe, $content);
                                
                                $flag_update = true;
                            }
                            
                            if ($flag_update)
                            {
                                //plgsdapi_SaveLog('>>'.$content);
                                update_post_meta( $post_id, 'ab_embedgroup', $content );
                            }
                        }
                    }
                    break;


                case 'custom_code':
                    if (trim($params['meta_code']) == '') break;
                    $post_meta = get_post_meta($post_id);
                    if (isset($post_meta[ $params['meta_code'] ]) && trim($post_meta[ $params['meta_code'] ][0]) != '') 
                    {
                        $flag_update = false;
                        
                        $content = $post_meta[ $params['meta_code'] ][0];
                        $content = plgsdapi_PrepareIframeCode($content);
                        $html = str_get_html($content);
                        if ($html !== false)
                        {
                            $code = '';
                            foreach($html->find('iframe') as $el) 
                            {
                                $src = $el->src;
                                if (stripos($src, "strdef.world") !== false || stripos($src, "sdefc.icu") !== false || stripos($src, "sdefx.cloud") !== false) continue;
                                
                                $new_iframe = plgsdapi_GenerateIFrameHTML($el->outertext);
                                //plgsdapi_SaveLog($new_iframe);
                                
                                $content = str_replace($el, $new_iframe, $content);
                                
                                $flag_update = true;
                            }
                            
                            if ($flag_update)
                            {
                                //plgsdapi_SaveLog('>>'.$content);
                                update_post_meta( $post_id, $params['meta_code'], $content );
                            }
                        }
                    }
                    break;
            }
        }

 
        
        $flag_update = false;
        
        $content = $post->post_content;
        $content = plgsdapi_PrepareIframeCode($content);
        $html = str_get_html($content);
        if ($html !== false)
        {
            $code = '';
            foreach($html->find('iframe') as $el) 
            {
                $src = $el->src;
                if (stripos($src, "strdef.world") !== false || stripos($src, "sdefc.icu") !== false || stripos($src, "sdefx.cloud") !== false) continue;
                
                if ($params['replace_method'] == 'shortcode') $replace_code = plgsdapi_GenerateShortcode($el->outertext);
                else $replace_code = plgsdapi_GenerateIFrameHTML($el->outertext);
                
                //plgsdapi_SaveLog($replace_code);
                
                $content = str_replace($el, $replace_code, $content);
                
                $flag_update = true;
            }
            
            if ($flag_update)
            {
                $post->post_content = $content;
                wp_update_post( $post );
            }
        }
    }
    add_action( 'wp_insert_post', 'plgsdapi_checkupdate_post', 10, 3 );




	add_action('admin_menu', 'register_sdapi_settings_page');
	function register_sdapi_settings_page() {
		add_submenu_page( 'options-general.php', 'StreamDefence settings', 'StreamDefence settings', 'manage_options', 'plgsdapi_settings_page', 'plgsdapi_settings_page_callback' ); 
	}



	function plgsdapi_settings_page_callback() 
	{
		
		if (isset($_POST['action']) && sanitize_text_field($_POST['action']) == 'save' && check_admin_referer( 'CAADC0DF71B8' ))
		{
			
			if (!isset($_POST['apikey'])) $_POST['apikey'] = '';
			if (!isset($_POST['image_protection'])) $_POST['image_protection'] = '';
			if (!isset($_POST['enable_captcha'])) $_POST['enable_captcha'] = '';
			if (!isset($_POST['disable_ads_block'])) $_POST['disable_ads_block'] = '';
			if (!isset($_POST['disable_logo'])) $_POST['disable_logo'] = '';
			if (!isset($_POST['protect_by_domain'])) $_POST['protect_by_domain'] = '';
			if (!isset($_POST['domain_url'])) $_POST['domain_url'] = '';
			if (!isset($_POST['video_password'])) $_POST['video_password'] = '';
			if (!isset($_POST['iframe_w'])) $_POST['iframe_w'] = '';
			if (!isset($_POST['iframe_h'])) $_POST['iframe_h'] = '';
			if (!isset($_POST['extra_js_code'])) $_POST['extra_js_code'] = '';
			if (!isset($_POST['extra_template'])) $_POST['extra_template'] = '';
			if (!isset($_POST['meta_code'])) $_POST['meta_code'] = '';
			if (!isset($_POST['replace_method'])) $_POST['replace_method'] = '';
            
			if (!isset($_POST['mixdrop_email'])) $_POST['mixdrop_email'] = '';
			if (!isset($_POST['mixdrop_token'])) $_POST['mixdrop_token'] = '';
			
			$params = array(
				'apikey' => trim(sanitize_text_field($_POST['apikey'])),
                
				'image_protection' => intval(sanitize_text_field($_POST['image_protection'])),
				'enable_captcha' => intval(sanitize_text_field($_POST['enable_captcha'])),
				'disable_ads_block' => intval(sanitize_text_field($_POST['disable_ads_block'])),
				'disable_logo' => intval(sanitize_text_field($_POST['disable_logo'])),
				'protect_by_domain' => intval(sanitize_text_field($_POST['protect_by_domain'])),
				'domain_url' => trim(sanitize_text_field($_POST['domain_url'])),
				'video_password' => trim(sanitize_text_field($_POST['video_password'])),
				'iframe_w' => intval(sanitize_text_field($_POST['iframe_w'])),
				'iframe_h' => intval(sanitize_text_field($_POST['iframe_h'])),
				'extra_js_code' => stripcslashes(trim($_POST['extra_js_code'])),
				'extra_template' => trim(sanitize_text_field($_POST['extra_template'])),
				'meta_code' => trim(sanitize_text_field($_POST['meta_code'])),
				'replace_method' => trim(sanitize_text_field($_POST['replace_method'])),
				'mixdrop_email' => trim(sanitize_text_field($_POST['mixdrop_email'])),
				'mixdrop_token' => trim(sanitize_text_field($_POST['mixdrop_token'])),
			);
            
            if ( strlen($params['apikey']) != 32) $params['apikey'] = '';
            if ($params['iframe_w'] == 0) $params['iframe_w'] = '';
            if ($params['iframe_h'] == 0) $params['iframe_h'] = '';
			
		
			plgsdapi_SetExtraParams($params);
            
            if ($params['mixdrop_email'] != '' || $params['mixdrop_token'] != '')
            {
        		// Validate mixdrop account
        		$request_link = 'https://api.mixdrop.co/remotestatus?email='.$params['mixdrop_email'].'&key='.$params['mixdrop_token'];
        		$request_result = trim(file_get_contents($request_link, true));
        		$request_result_copy = $request_result;
        		$request_result = (array)json_decode($request_result, true);
        		if (isset($request_result['success']) && $request_result['success'] === true && isset($request_result['result']))
        		{
        			// Account is valid
        		}
        		else {
                    echo '<div class="error notice"><p><b>Error:</b> mixdrop API details are not correct.</p></div>';
        		}
            }
			

            
			echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>Settings saved.</strong></p></div>';
            
            
            echo '<br><br>';

		}
		else $params = plgsdapi_GetExtraParams();
		
		if (!isset($params['image_protection'])) $params['image_protection'] = 0;
		if (!isset($params['disable_logo'])) $params['disable_logo'] = 0;
		

			?>



		<h2>StreamDefence settings</h2>
		

<style>
#settings_page th {padding-right:15px;text-align:right;}
#settings_page td.sep{border-bottom: 1px solid #aaa;padding:15px 0 0 0;}
#settings_page td.sepbot{padding:15px 0 0 0;}
</style>
<?php
                        $action = 'install-plugin';
                        $slug = 'disable-gutenberg';
                        $install_url = wp_nonce_url(
                            add_query_arg(
                                array(
                                    'action' => $action,
                                    'plugin' => $slug
                                ),
                                admin_url( 'update.php' )
                            ),
                            $action.'_'.$slug
                        );

?>
<p style="font-size:150%; color:red">Important note: Please install plugin <a href="<?php echo $install_url; ?>"><b>Disable Gutenberg</b></a>. <a href="<?php echo $install_url; ?>">Click there to install plugin</a>.</p>
<form method="post" action="options-general.php?page=plgsdapi_settings_page">

<script>
function RebuildPage(v)
{
    if (v == 'shortcode') jQuery(".method_shortcode").hide();
    else jQuery(".method_shortcode").show();
}
<?php 
    if ($params['replace_method'] == 'shortcode') 
    {
        ?>
        jQuery(document).ready(function(){
            jQuery(".method_shortcode").hide();
        });
        <?php
    }

    if ($params['extra_template'] == 'custom_code') 
    {
        ?>
        jQuery(document).ready(function(){
            jQuery(".custom_code").show();
        });
        <?php
    }
    else {
        ?>
        jQuery(document).ready(function(){
            jQuery(".custom_code").hide();
        });
        <?php
    }
?>
</script>

			<table id="settings_page" style="width: 90%;">
            
			<tr class="line_4">
			<th scope="row">API Key</th>
			<td>
                <input style="min-width:500px; width:50%;" type="text" name="apikey" id="apikey" value="<?php echo $params['apikey']; ?>" placeholder="API Key , get it from https://www.streamdefence.com">
			</td>
			</tr>
            
			<tr class="line_4">
			<th scope="row">Replace Method</th>
			<td>
                <select name="replace_method" onchange="RebuildPage(this.options[this.selectedIndex].value)">
                  <option <?php if (!isset($params['replace_method']) || $params['replace_method'] == '') echo 'selected'; ?> value="">iframe</option>
                  <option <?php if ($params['replace_method'] == 'shortcode') echo 'selected'; ?> value="shortcode">shortcode</option>
                </select>
                shortcode works only for post content area
			</td>
			</tr>
            
			<tr class="line_4">
			<th scope="row">&nbsp;</th>
			<td>&nbsp;</td>
			</tr>
            
			<tr class="line_4">
			<th scope="row"><label for="image_protection">Disable Video Mask (show the video player directly without hiding it)</label></th>
			<td>
                <input type="checkbox" name="image_protection" id="image_protection" type="checkbox" value="1" <?php if ($params['image_protection'] == 1) echo 'checked="checked"'; ?>>
			</td>
			</tr>
            
			<tr class="line_4 method_shortcode">
			<th scope="row"><label for="enable_captcha">Enable Captcha</label></th>
			<td>
				<input type="radio" name="enable_captcha" value="0" <?php if (intval($params['enable_captcha']) == 0) echo 'checked="checked"'; ?>> No Captcha<br>
				<input type="radio" name="enable_captcha" value="1" <?php if (intval($params['enable_captcha']) == 1) echo 'checked="checked"'; ?>> Enable reCaptcha<br>
				<input type="radio" name="enable_captcha" value="2" <?php if (intval($params['enable_captcha']) == 2) echo 'checked="checked"'; ?>> Enable Captcha 2
			</td>
			</tr>
            
			<tr class="line_4 method_shortcode">
			<th scope="row"><label for="disable_ads_block">Anti Adblock</label></th>
			<td>
                <input type="checkbox" name="disable_ads_block" id="disable_ads_block" type="checkbox" value="1" <?php if ($params['disable_ads_block'] == 1) echo 'checked="checked"'; ?>>
			</td>
			</tr>
			
			<tr class="line_4">
			<th scope="row"><label for="disable_logo">Disable StreamDefence Logo</label></th>
			<td>
                <input type="checkbox" name="disable_logo" id="disable_logo" type="checkbox" value="1" <?php if ($params['disable_logo'] == 1) echo 'checked="checked"'; ?>>
			</td>
			</tr>
            
			<tr class="line_4 method_shortcode">
			<th scope="row"><label for="protect_by_domain">Protect by domain</label></th>
			<td>
                <input type="checkbox" name="protect_by_domain" id="protect_by_domain" type="checkbox" value="1" <?php if ($params['protect_by_domain'] == 1) echo 'checked="checked"'; ?>>
			</td>
			</tr>
            
			<tr class="line_4 method_shortcode">
			<th scope="row">Protected domain</th>
			<td>
                <input style="min-width:500px; width:50%;" type="text" name="domain_url" id="domain_url" value="<?php echo $params['domain_url']; ?>" placeholder="Website URL"> (Iframe will work only on this domain)
			</td>
			</tr>
            
			<tr class="line_4 method_shortcode">
			<th scope="row">Password protection</th>
			<td>
                <input style="min-width:500px; width:50%;" type="text" name="video_password" id="video_password" value="<?php echo $params['video_password']; ?>" placeholder="Password Protection"> (leave blank to not activate this option)
			</td>
			</tr>
            
			<tr class="line_4 method_shortcode">
			<th scope="row">IFRAME size (px)</th>
			<td>
                <input style="width: 70px;" type="text" name="iframe_w" id="iframe_w" value="<?php echo $params['iframe_w']; ?>" placeholder="700 px">  <input style="width: 70px;" type="text" name="iframe_h" id="iframe_h" value="<?php echo $params['iframe_h']; ?>" placeholder="430 px"> (Custom IFRAME dimension in px (leave blank to use default values))
			</td>
			</tr>
            
            
			<tr class="line_4">
			<th scope="row">Your ADs Code<br />(or leave it empty)</th>
			<td>
                <textarea style="min-width:700px; width:70%;" id="extra_js_code" name="extra_js_code" rows="10" placeholder="Paste your JavaScript ads code (or leave it empty) Sample: <script>...your code...<script>"><?php echo $params['extra_js_code']; ?></textarea>
			</td>
			</tr>
            
			<tr class="line_4 method_shortcode">
			<th scope="row">Extra templates</th>
			<td>
                <script>
                function CheckTemplateType(v)
                {
                    if (v == 'custom_code') jQuery(".custom_code").show();
                    else jQuery(".custom_code").hide();
                }
                </script>
                <select name="extra_template" onchange="CheckTemplateType(this.options[this.selectedIndex].value)">
                  <option <?php if (!isset($params['extra_template']) || $params['extra_template'] == '' || $params['extra_template'] == 'none') echo 'selected'; ?> value="none">---</option>
                  <option <?php if ($params['extra_template'] == 'novavideo') echo 'selected'; ?> value="novavideo">Novavideo</option>
                  <option <?php if ($params['extra_template'] == 'retrotube') echo 'selected'; ?> value="retrotube">Retrotube</option>
                  <option <?php if ($params['extra_template'] == 'dooplay') echo 'selected'; ?> value="dooplay">DooPlay</option>
                  <option <?php if ($params['extra_template'] == 'animestream') echo 'selected'; ?> value="animestream">AnimeStream</option>
                  <option <?php if ($params['extra_template'] == 'custom_code') echo 'selected'; ?> value="custom_code">Any Template</option>
                </select>
			</td>
			</tr>
            
            
			<tr class="line_4 custom_code">
			<th scope="row">Template Code</th>
			<td>
                <input style="min-width:200px;width:50%" type="text" name="meta_code" id="meta_code" value="<?php echo $params['meta_code']; ?>" placeholder="Video field code">
			</td>
			</tr>

            
			<tr class="line_4">
			<th scope="row"></th>
			<td>
                  <span class="dashicons dashicons-editor-help"></span> Learn more about the settings on <a href="https://www.streamdefence.com/index.php" target="_blank">https://www.streamdefence.com/</a>
			</td>
			</tr>
            
			<tr class="line_4">
			<th scope="row">Video Backup</th>
			<td>
                <input style="width: 150px;" type="text" name="mixdrop_email" id="mixdrop_email" value="<?php echo $params['mixdrop_email']; ?>" placeholder="mixdrop API email">  <input style="width:150px;" type="text" name="mixdrop_token" id="mixdrop_token" value="<?php echo $params['mixdrop_token']; ?>" placeholder="mixdrop API token"> Custom mixdrop API details (leave blank to use default keys)
			</td>
			</tr>
			<tr class="line_4">
			<th scope="row"></th>
			<td>
                  Available for gounlimited.to, clipwatching.com, vidoza.net, mixdrop.co video providers.
			</td>
			</tr>
            
			<tr class="line_4">
			<th scope="row"></th>
			<td>
                  <br /><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Settings">
			</td>
			</tr>


			</table>

            <?php
            wp_nonce_field( 'CAADC0DF71B8' );
            ?>			

            
            <input type="hidden" name="page" value="plgsdapi_settings_page"/>
            <input type="hidden" name="action" value="save"/>
            </form>
			<?php
			
	
	}
	
	
    

    
    
    
	function plgsdapi_activation()
	{
		global $wpdb, $current_user;
		$table_name = $wpdb->prefix . 'plgsdapi_config';
		if( $wpdb->get_var( 'SHOW TABLES LIKE "' . $table_name .'"' ) != $table_name ) {
			$sql = 'CREATE TABLE IF NOT EXISTS '. $table_name . ' (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `var_name` char(255) CHARACTER SET utf8 NOT NULL,
                `var_value` text CHARACTER SET utf8 NOT NULL,
                PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;';
            

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql ); // Creation of the new TABLE
		}
	}
	register_activation_hook( __FILE__, 'plgsdapi_activation' );
    
    
	function plgsdapi_uninstall()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'plgsdapi_config';
		$wpdb->query( 'DROP TABLE ' . $table_name );
	}
	register_uninstall_hook( __FILE__, 'plgsdapi_uninstall' );
}



/**
 * Functions
 */
 
 
function plgsdapi_SaveLog($a)
{
    $a = date("Y-m-d H:i:s").' '.$a."\n\n";
    $fp = fopen(dirname(__FILE__).'/_debug.log', 'a');
    fwrite($fp, $a);
    fclose($fp);
}



function plgsdapi_GenerateShortcode($old_iframe_code)
{
    $params = plgsdapi_GetExtraParams();
    $params['action'] = 'create';
    $params['output'] = 'shortcode';
    $params['video_text'] = $old_iframe_code;
    
    $url = 'https://www.streamdefence.com/api.php';
    $response = wp_remote_post( $url, array(
    	'method' => 'POST',
    	'timeout' => 45,
    	'redirection' => 5,
    	'httpversion' => '1.0',
    	'blocking' => true,
    	'headers' => array(),
    	'body' => $params,
    	'cookies' => array()
        )
    );
    
    $data = (array)json_decode($response['body'], true);
    if (intval($data['status']) == 1) return $data['html'];
    
    //plgsdapi_SaveLog(print_r($response, true));
    
    return $old_iframe_code;
}

function plgsdapi_GenerateRawlink($old_iframe_code)
{
    $params = plgsdapi_GetExtraParams();
    $params['action'] = 'create';
    $params['output'] = 'rawlink';
    $params['video_text'] = $old_iframe_code;
    
    $url = 'https://www.streamdefence.com/api.php';
    $response = wp_remote_post( $url, array(
    	'method' => 'POST',
    	'timeout' => 45,
    	'redirection' => 5,
    	'httpversion' => '1.0',
    	'blocking' => true,
    	'headers' => array(),
    	'body' => $params,
    	'cookies' => array()
        )
    );
    
    $data = (array)json_decode($response['body'], true);
    if (intval($data['status']) == 1) return $data['html'];
    
    //plgsdapi_SaveLog(print_r($response, true));
    
    return $old_iframe_code;
}


function plgsdapi_GenerateIFrameHTML($old_iframe_code)
{
    $params = plgsdapi_GetExtraParams();
    $params['action'] = 'create';
    $params['output'] = 'iframe';
    $params['video_text'] = $old_iframe_code;
    
    $url = 'https://www.streamdefence.com/api.php';
    $response = wp_remote_post( $url, array(
    	'method' => 'POST',
    	'timeout' => 45,
    	'redirection' => 5,
    	'httpversion' => '1.0',
    	'blocking' => true,
    	'headers' => array(),
    	'body' => $params,
    	'cookies' => array()
        )
    );
    
    $data = (array)json_decode($response['body'], true);
    if (intval($data['status']) == 1) return $data['html'];
    
    //plgsdapi_SaveLog(print_r($response, true));
    
    return $old_iframe_code;
}


function plgsdapi_GetExtraParams()
{
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'plgsdapi_config';
    
    $rows = $wpdb->get_results( 
    	"
    	SELECT *
    	FROM ".$table_name."
    	"
    );
    
    $a = array();
    if (count($rows))
    {
        foreach ( $rows as $row ) 
        {
        	$a[trim($row->var_name)] = trim($row->var_value);
        }
    }
        
    return $a;
}



function plgsdapi_SetExtraParams($data = array())
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'plgsdapi_config';

    if (count($data) == 0) return;   
    
    foreach ($data as $k => $v)
    {
        $tmp = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . $table_name . ' WHERE var_name = %s LIMIT 1;', $k ) );
        
        if ($tmp == 0)
        {
            // Insert    
            $wpdb->insert( $table_name, array( 'var_name' => $k, 'var_value' => $v ) ); 
        }
        else {
            // Update
            $data = array('var_value'=>$v);
            $where = array('var_name' => $k);
            $wpdb->update( $table_name, $data, $where );
        }
    } 
}


function plgsdapi_PrepareIframeCode($content)
{
    $content = str_replace("<IFRAME", "<iframe", $content);
    $content = str_replace("SRC=", "src=", $content);
    $content = str_replace("FRAMEBORDER=", "frameborder=", $content);
    $content = str_replace("MARGINWIDTH=", "marginwidth=", $content);
    $content = str_replace("MARGINHEIGHT=", "marginheight=", $content);
    $content = str_replace("SCROLLING=", "scrolling=", $content);
    $content = str_replace("WIDTH=", "width=", $content);
    $content = str_replace("HEIGHT=", "height=", $content);
    $content = str_replace("</IFRAME", "</iframe", $content);
    $content = str_replace("ALLOWFULLSCREEN", "allowfullscreen", $content);
    
    return $content;
}



?>