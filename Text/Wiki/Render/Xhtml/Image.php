<?php
class Text_Wiki_Render_Xhtml_Image extends Text_Wiki_Render {

	var $conf = array(
		'base' => '/'
	);
	
	
    /**
    * 
    * Renders a token into text matching the requested format.
    * 
    * @access public
    * 
    * @param array $options The "options" portion of the token (second
    * element).
    * 
    * @return string The text rendered from the token options.
    * 
    */
    
    function token($options)
    {
    	$src = '"' .
    		$this->getConf('base', '/') .
    		$options['src'] . '"';
    	
    	if (isset($options['attr']['link'])) {
			// this image has a wikilink
    		$href = $this->_wiki->getRuleConf('wikilink', 'view_url') .
    			$options['attr']['link'];
    	} else {
    		// image is not linked
    		$href = null;
    	}
    	
    	// unset these so they don't show up as attributes
    	unset($options['attr']['link']);
    	
    	$attr = '';
    	foreach ($options['attr'] as $key => $val) {
    		$attr .= "$key=\"$val\" ";
    	}
    	
    	if ($href) {
    		return "<a href=\"$href\"><img src=$src$attr/></a>";
    	} else {
	    	return "<img src=$src$attr/>";
	    }
	}
}
?>