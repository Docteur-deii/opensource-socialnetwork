<?php
/**
 * Open Source Social Network
 *
 * @package   Open Source Social Network
 * @author    Open Social Website Core Team <info@informatikon.com>
 * @copyright 2014 iNFORMATIKON TECHNOLOGIES
 * @license   General Public Licence http://www.opensource-socialnetwork.org/licence
 * @link      http://www.opensource-socialnetwork.org/licence
 */
 
define('__OSSN_EMBED__', ossn_route()->com . 'OssnEmbed/');
require_once(__OSSN_EMBED__ . 'libraries/ossnembed.lib.php');
require_once(__OSSN_EMBED__ . 'vendors/linkify/linkify.php');

/**
 * Initialize Ossn Embed component
 *
 * @note Please don't call this function directly in your code.
 * 
 * @return void
 * @access private
 */
function ossn_embed_init() {	
 	ossn_add_hook('wall', 'templates:item', 'ossn_embed_wall_template_item');
}
/**
 * Replace videos links and simple url to html url.
 *
 * @note Please don't call this function directly in your code.
 * 
 * @param string $hook Name of hook
 * @param string $type Hook type
 * @param array|object $return Array or Object
 * @params array $params Array contatins params
 *
 * @return array
 * @access private
 */
function ossn_embed_wall_template_item($hook, $type, $return, $params){
	$patterns = array(	'#(((https?://)?)|(^./))(((www.)?)|(^./))youtube\.com/watch[?]v=([^\[\]()<.,\s\n\t\r]+)#i',
						'#(((https?://)?)|(^./))(((www.)?)|(^./))youtu\.be/([^\[\]()<.,\s\n\t\r]+)#i',
						'/(https?:\/\/)(www\.)?(vimeo\.com\/groups)(.*)(\/videos\/)([0-9]*)/',
						'/(https?:\/\/)(www\.)?(vimeo.com\/)([0-9]*)/',
						'/(https?:\/\/)(www\.)?(metacafe\.com\/watch\/)([0-9a-zA-Z_-]*)(\/[0-9a-zA-Z_-]*)(\/)/',
						'/(https?:\/\/www\.dailymotion\.com\/.*\/)([0-9a-z]*)/',
						);
	$regex = "/<a[\s]+[^>]*?href[\s]?=[\s\"\']+"."(.*?)[\"\']+.*?>"."([^<]+|.*?)?<\/a>/";
	
	$params['text'] = linkify($params['text']);
	if(preg_match_all($regex, $params['text'], $matches, PREG_SET_ORDER)){
	foreach($matches as $match){
			foreach ($patterns as $pattern){
				if (preg_match($pattern, $match[2]) > 0){
					$params['text'] = str_replace($match[0], ossn_embed_create_embed_object($match[2], uniqid('videos_embed_'), 500), $params['text']);
				}				
			}
		}
	}
	return $params;
}
//initilize ossn wall
ossn_register_callback('ossn', 'init', 'ossn_embed_init');
