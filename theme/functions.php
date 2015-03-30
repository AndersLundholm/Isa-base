<?php
/**
 * Theme related functions. 
 *
 */
 
/**
 * Get title for the webpage by concatenating page specific title with site-wide title.
 *
 * @param string $title for this page.
 * @return string/null wether the favicon is defined or not.
 */
function get_title($title) {
  global $isa;
  return $title . (isset($isa['title_append']) ? $isa['title_append'] : null);
}


/**
 * Generate menu from passed array of menu items.
 *
 * @param array $items
 * @return string $html with the complete html encoded string.
 */
function generate_menu($items) {
    $html = "<nav><ul class='menu'>\n";
    foreach($items as $item) {
        $html .= "<li><a href='{$item['url']}' title='{$item['title']}'>{$item['text']}</a>";
        if(isset($item['submenu'])) {
        	$html .= "<ul>\n";
        	foreach($item['submenu'] as $subitem) {
        		$html .= "<li><a href='{$subitem['url']}' title='{$subitem['title']}'>{$subitem['text']}</a></li>\n";
        	}
        $html .= "</ul>\n";
        }
        $html .= "</li>\n";
        

    }
    $html .= "</ul></nav>\n";
    return $html;
 }