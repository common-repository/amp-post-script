<?php
/*!
 * @wordpress-plugin
 * Plugin Name:	AMP Post Script 
 * Plugin URI:		https://www.p-stevenson.com
 * Description:		Modify the AMP plugin for WordPress
 * Version:			1.7.5
 * Author:			Peter Stevenson
 * Author URI:		https://www.p-stevenson.com
 * License: 		GPL-2.0+
 * License URI:		http://www.gnu.org/licenses/gpl-2.0.txt
 **/
namespace Plugins\WPPostScript;

defined('ABSPATH') or die("Cannot Access This File Directly");
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if( is_plugin_active('amp/amp.php') ):

	class CustomizeAMP {

		/**
		 * Initializes Theme Specific Items
		 **/
		public function _initTheme() {

			add_filter('amp_post_template_css', array(__CLASS__, 'additionalCSS') );
			add_filter('amp_post_template_metadata', array(__CLASS__, 'modifyPostMetaData'), 10, 2 );
			// add_filter('amp_schemaorg_metadata', array(__CLASS__, 'modifyMetaData'), 10 );
			add_filter('amp_post_template_file', array(__CLASS__, 'setCustomTemplate'), 10, 3 );
			
			add_action('init', array($this, 'setupProperties') );
			add_action('init', array($this, 'addPostTypeSupport'),100 );
			add_action('the_content', array(__CLASS__, 'removeShortcodes'),0 );
			add_action('the_content', array($this, 'addRelatedPosts'),75 );
			add_action('the_content', array(__CLASS__, 'addButtonToContent' ),100 );
			add_action('amp_post_template_head', array($this, 'addToHead') );
			add_action('amp_post_template_footer', array($this, 'addToFooter') );
			add_action('after_setup_theme', array(__CLASS__, 'addCustomMenu') );
			add_action('amp_post_template_footer', array(__CLASS__, 'removeWordPressFooter') );

		}

		/**
		 * Sets up the default properties used by theme
		 **/
		public function setupProperties(){
			$this->gaCode = '';
			$this->relatedTaxonomy = 'category';
			$this->additionalPostTypes = explode(',','events,ps-event,news,locations,ps-location,wpseo_locations,tribe_events');
			$this->disableRelatedPosts = false;

			if(defined('PS_AMP_GA_ANALYTICS')):
				$this->gaCode = PS_AMP_GA_ANALYTICS;
			endif;
			if(defined('PS_AMP_RELATED_TAXONOMY')):
				$this->relatedTaxonomy = PS_AMP_RELATED_TAXONOMY;
			endif;
			if(defined('PS_AMP_ADDITIONAL_POST_TYPES')):
				$this->additionalPostTypes = explode(',',PS_AMP_ADDITIONAL_POST_TYPES);
			endif;
			if(defined('PS_AMP_DISABLE_RELATED_POSTS')):
				$this->disableRelatedPosts = PS_AMP_DISABLE_RELATED_POSTS;
			endif;
		}

		/**
		 * Removes the footer from AMP pages ( if it theme loads one )
		 **/
		static public function removeWordPressFooter(){
			if(is_amp_endpoint()):
				remove_all_actions('wp_footer');
			endif;
		}

		/**
		 * Disable these shortcodes from breaking AMP code
		 * @param string $content
		 * @return string
		 **/
		static public function removeShortcodes($content){
			 if(is_amp_endpoint()):
				remove_shortcode('wpseo_map');
			 	add_shortcode('wpseo_map',function(){return '';});
			endif;
			return $content;
		}

		/**
		 * Use custom layouts for the amp design
		* @param string $file
		* @param string $type
		* @param integer $post
		 * @return string
		 **/
		static public function setCustomTemplate($file, $type, $post){
			if( 'header-bar' === $type ):
				$file = __DIR__ . '/includes/ps-header-bar.php';
			endif;
			if( 'header' === $type ):
				$file = __DIR__ . '/includes/ps-header.php';
			endif;
			return $file;
		}

		/**
		 * Add CSS to overwrite base styles
		 **/
		static public function additionalCSS(){
			include( __DIR__ . '/includes/custom-styles.php' );
		}

		/**
		 * Add site icon as image for the post if no other image is present
		* @param array $metadata
		* @param integer $post
		 * @return array
		 **/
		static public function modifyPostMetaData($metadata,$post){
			if(function_exists('has_site_icon')&&function_exists ('get_site_icon_url')):
				if( !isset($metadata['image']) && has_site_icon() ):
					$metadata['image'] = array(
						'@type' => 'ImageObject',
						'url' => get_site_icon_url(),
						'height' => 512,
						'width' => 512,
					);
				endif;
			endif;
			return $metadata;
		}

		/**
		 * Fix Publisher Meta Icon
		* @param array $metadata
		 * @return array
		 **/
		// static public function modifyMetaData($metadata,$post){
		// 	if( isset($metadata['publisher']) && !is_array($metadata['publisher']['logo']) ):
		// 		$metadata['publisher']['logo'] = array(
		// 			'@type' => 'ImageObject',
		// 			'url' => get_site_icon_url(),
		// 			'height' => 512,
		// 			'width' => 512,
		// 		);
		// 	endif;
		// 	return $metadata;
		// }

		/**
		 * Support other post types for AMP layout
		 **/
		public function addPostTypeSupport(){
			add_rewrite_endpoint( AMP_QUERY_VAR, EP_PERMALINK | EP_PAGES);
			foreach($this->additionalPostTypes as $postType):
				if(post_type_exists($postType)):
					add_post_type_support($postType,AMP_QUERY_VAR);
				endif;	
			endforeach;
		}

		/**
		 * Add the AMP analytics tag
		 **/
		public function addToHead(){
			if($this->gaCode):
				echo '<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>';
			endif;
		}

		/**
		 * Defines the AMP analytics settings
		 **/
		public function addToFooter(){
			if($this->gaCode):
				?><amp-analytics type="googleanalytics" id="analytics1">
					<script type="application/json">{
						"vars": {
							"account": "<?php echo $this->gaCode; ?>"
						},"triggers": {
							"trackPageview": {
								"on": "visible",
								"request": "pageview",
								"vars": {
									"title": "<?php echo get_the_title(); ?>",
									"ampdocUrl": "<?php echo get_the_permalink(); ?>"
								}
							}
						}
					}</script>
				</amp-analytics><?php
			endif;
		}

		/**
		 * Setup a custom menu for use on the AMP pages
		 **/
		static public function addCustomMenu(){
			register_nav_menu('amp-menu',__( 'AMP Menu','postscript'));
		}

		/**
		 * Add a link before/after content to link to non-AMP page/content
		 * @param string $content
		 * @return string
		 **/
		static public function addButtonToContent($content){
			if( is_amp_endpoint() && in_array(get_post_type(), array('page','wpseo_locations','tribe_events')) ):
				$content = '<p style="text-align:right;"><a class="button" href="' . get_the_permalink() . '">' . __( 'View More Info','postscript') . ' &rarr;</a></p>'
					. $content
					. '<p style="text-align:right;"><a class="button" href="' . get_the_permalink() . '">' . __( 'View More Info','postscript') . ' &rarr;</a></p>';
			endif;
			return $content;
		}

		/**
		 * Find all related posts and add them to the end of the content
		 * @param string $content
		 * @return string
		 **/
		public function addRelatedPosts($content){
			if(is_amp_endpoint()&&!$this->disableRelatedPosts):
				
				$this->disableRelatedPosts = true;
				
				$relatedContent = self::relatedPosts(array(
					"type" => get_post_type(),
					"post_id" => get_the_id(),
					"count" => 4,
					"taxonomy" => $this->relatedTaxonomy,
				));
				if(!empty($relatedContent['data'])):
					$content .= '<h3 class="related-posts-title">' . __( 'Related Articles','postscript') . '</h3>';
					$content .= '<ul class="related-posts">';
					foreach($relatedContent['data'] as $value):
						$content .= '<li>';
							$feat_image = self::mainImageFromPost($value->ID,true);
							$content .= '<a href="'.get_the_permalink($value->ID).AMP_QUERY_VAR.'">';
								if($feat_image&&$feat_image['src']):
									$content .= '<amp-img src="'.$feat_image['src'].'" layout="responsive" '.$feat_image['size'][3].'></amp-img>';
								endif;
								$content .= '<h4>'.$value->post_title.'</h4>';
							$content .= '</a>';
						$content .= '</li>';
					endforeach;
					$content .= '</ul>';
				endif;

			endif;
			return $content;
		}

		/**
		 * Returns all posts related to each-other based on taxonomy
		 * @param array $data
		 * @return array
		 **/
		static public function relatedPosts( $data = array( ) ){

			$opts = array(
				"post_id"	=> '',
				"type"		=> 'post',
				"count"		=> 20,
				"taxonomy"	=> 'category',
				'status'    	=> 'publish',
			);
			$opts = array_merge($opts,$data);
			if( !$opts['post_id'] ): return 'Missing Required Parameters'; endif;

			// CREATE OUTPUT DATA
			$outputData = array();
			$output = '';

			$transientName = 'relatedPosts_' . $opts['post_id'] . '_' . $opts['type'] . '_' . $opts['count'] . '_' . $opts['taxonomy'] . '_' . $opts['status'];
			if(( $output = get_transient( $transientName )) === false ):

				$relatedTaxonomy = get_the_terms($opts['post_id'],$opts['taxonomy']);

				if($relatedTaxonomy&&count($relatedTaxonomy)>0):
					$loopCount = 0;
					foreach($relatedTaxonomy as $key => $value):
						$args = array(
							'post_type'		=> $opts['type'],
							'taxonomy'		=> $opts['taxonomy'],
							'term'			=> $value->slug,
							'post_status'    		=> $opts['status'],
						);
						$loop = new \WP_Query($args);
						
						while ($loop->have_posts()):
							$loop->the_post();

							if($opts['post_id']!=get_the_ID()):
								$item_key = 'none';
								if($loopCount):
									$item_key = array_search(get_the_ID(),self::array_key($outputData,'id'));
								endif;

								$outputData[$item_key] = array();

								if( is_numeric($item_key) ):
									$outputData[$item_key]['count']++;
								else:
									$outputData[$loopCount]['count'] = 1;
									$outputData[$loopCount]['id'] = get_the_ID();
									$outputData[$loopCount]['post'] = get_post(get_the_ID());
								endif;
								$loopCount++;
							endif;
						endwhile;
					endforeach;
				endif;

				$outputData = self::aasort_asc($outputData,'count');

				if(!empty($outputData)):
					$count = 0;
					foreach($outputData as $data):
						if($data&&$data['post']&&$count<$opts['count']):
							$output[$count] = $data['post'];
							$count++;
						endif;
					endforeach;
				endif;

				// SET TRANSIENT
				set_transient( $transientName, $output, 24 * HOUR_IN_SECONDS );
			endif;

			// RETURN		
			wp_reset_query();
			return array('data'=>$output);

		}

		/**
		 * Replicates the functionality of array_column() without php version 5.5
		 * ex: $item_key =  array_search($_GET['id'], $functions->array_key($array, 'uid'));
		 * @param string $string
		 * @param string $string
		 * @param string $string
		 * @return array
		 **/
		static public function array_key($input = null, $columnKey = null, $indexKey = null){
			$params = func_get_args();
			$paramsInput = $params[0];
			$paramsColumnKey = ($params[1] !== null) ? (string)$params[1] : null;
			$paramsIndexKey = null;
			
			if(isset($params[2])):
				if(is_float($params[2]) || is_int($params[2])):
					$paramsIndexKey = (int)$params[2];
				else:
					$paramsIndexKey = (string)$params[2];
				endif;
			endif;

			$returnArray = array();

			foreach($paramsInput as $row):
				$key = $value = null;
				$keySet = $valueSet = false;
				if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
					$keySet = true;
					$key = (string)$row[$paramsIndexKey];
				}
				if($paramsColumnKey === null):
					$valueSet = true;
					$value = $row;
				elseif(is_array($row) && array_key_exists($paramsColumnKey, $row)):
					$valueSet = true;
					$value = $row[$paramsColumnKey];
				endif;
				if($valueSet):
					if($keySet):
						$returnArray[$key] = $value;
					else:
						$returnArray[] = $value;
					endif;
				endif;
			endforeach;

			return $returnArray;
		}

		/**
		 * Gets the featured image, or the first image from any post object
		 * @param integer $postID
		 * @param function $siteIconFallback
		 * @return string
		 **/
		static public function mainImageFromPost($postID,$siteIconFallback=false){
			$data = wp_get_attachment_image_src(get_post_thumbnail_id($postID),'full');
			if(!empty($data[0])):
				$data['src'] = $data[0];
			else:
				$first_img = '';
				preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_post($postID)->post_content, $matches);
				$first_img = ( $matches && $matches[1] && $matches[1][0] ) ? $matches[1][0] : '' ;
				if(!empty($first_img)):
					$resizePattern = array ('/(.*)-([0-9]*)x([0-9]*).([a-zA-Z]*)/i');
					$fullReplace = array ('\1.\4');
					$first_img = preg_replace($resizePattern, $fullReplace, $first_img);
				else:
					$first_img = $siteIconFallback ? get_site_icon_url() : '';
				endif;
				$data['src'] = $first_img;
			endif;
			$data = array_merge($data,self::getImageSize($data['src']));

			return $data;
		}

		/**
		 * Gets size information about image
		 * @param string $imageSrc
		 * @return array
		 **/
		static public function getImageSize($imageSrc){
			$data['src_local'] = $imageSrc;		
			$data['src_local'] = parse_url($data['src_local']);			
			$data['src_local'] = $_SERVER['DOCUMENT_ROOT'] . $data['src_local']['path'];

			if($data['src_local']&&is_file($data['src_local'])&&is_readable($data['src_local'])):
				$data['size'] = getimagesize($data['src_local']);
			else:
				$data['size'] = array('','');
				$data['src_local'] = '';
			endif;

			return $data;
		}

		/**
		 * Sorts array by ASC order
		 * @param array $array
		 * @param string $key
		 * @return array
		 **/
		static public function aasort_asc($array, $key){
			if(!is_array($array)){
				return $array;
			}
			$sorter=array();
			$ret=array();
			reset($array);
			foreach ($array as $ii => $va) {
				$sorter[$ii]=$va[$key];
			}
			ksort($sorter);
			foreach ($sorter as $ii => $va) {
				$ret[$ii]=$array[$ii];
			}
			return $ret;
		}

	}

	$CustomizeAMP = new CustomizeAMP();
	$CustomizeAMP->_initTheme();

endif;