<?php
// Integration of EZBBC Toolbar script in posting, editing and view topic pages
if ((isset($cur_post) && $cur_post) || (isset($cur_posting) && $cur_posting) || (isset($cur_topic) && $cur_topic)):

// Language file load
$ezbbc_language_folder = file_exists(PUN_ROOT.'plugins/ezbbc/lang/'.$pun_user['language'].'/ezbbc_plugin.php') ? $pun_user['language'] : 'English';    
require PUN_ROOT.'plugins/ezbbc/lang/'.$ezbbc_language_folder.'/ezbbc_plugin.php';

// Retrieving style folder
$config_content = trim(file_get_contents(PUN_ROOT.'plugins/ezbbc/config.php'));
$config_item = explode(";", $config_content);
$ezbbc_style_folder = $config_item[2];
?>

<!-- EZBBC Toolbar integration -->
<link rel="stylesheet" type="text/css" href="plugins/ezbbc/style/<?php echo $ezbbc_style_folder ?>/ezbbc.css" />
<script type="text/javascript">
/* <![CDATA[ */
function insertTag(startTag, endTag, tagType) {
        var field  = document.getElementsByName('req_message')[0]; 
        var scroll = field.scrollTop;
        field.focus();
        
        /* === Part 1: get the selection === */
        if (window.ActiveXObject) { //For IE
                var textRange = document.selection.createRange();            
                var currentSelection = textRange.text;
        } else { //For other browsers
                var startSelection   = field.value.substring(0, field.selectionStart);
                var currentSelection = field.value.substring(field.selectionStart, field.selectionEnd);
                var endSelection     = field.value.substring(field.selectionEnd);               
        }
        
        /* === Part 2: what Tag type ? === */
        if (tagType) {
                switch (tagType) {
                   	case "link":
       				if (currentSelection) { // Something is selected
       				        if (currentSelection.indexOf('http://') == 0 || currentSelection.indexOf('https://') == 0 || currentSelection.indexOf('ftp://') == 0 || currentSelection.indexOf('www.') == 0) {
       				                // The selection seems to be a link
       				                startTag = '[url=' + currentSelection + ']';
       				        } else {
       				                // The selection is not a link, so it is the label. We ask for the URL
       				                var URL = prompt("<?php echo$lang_ezbbc['Ask url'] ?>", "");
       				                if (URL != '' && URL != null && (URL.indexOf('http://') == 0 || URL.indexOf('https://') == 0 || URL.indexOf('ftp://') == 0 || URL.indexOf('www.') == 0)) {
       				                        startTag = '[url=' + URL + ']';
       				                } else {
       				                        startTag = endTag = '';
       				                }
       				        }
       				} else { // No selection, we ask for the URL and the label
       				         var URL = prompt("<?php echo $lang_ezbbc['Ask url'] ?>", "");
       				         if (URL != '' && URL != null && (URL.indexOf('http://') == 0 || URL.indexOf('https://') == 0 || URL.indexOf('ftp://') == 0 || URL.indexOf('www.') == 0)) {
       				                 var label = prompt("<?php echo $lang_ezbbc['Ask label'] ?>", "");
       				                 if (label != '') {
       				                         startTag = '[url=' + URL + ']';
       				                         currentSelection = label;
       				                 } else {
       				                         startTag = '[url=' + URL + ']';
       				                         currentSelection = URL;
       				                 }
       				         } else {
       				                 startTag = endTag = '';
       				         }
       				}
       			break;
                      	case "quote":
                       	       if (currentSelection) { // Something is selected
                       	               var author = prompt("<?php echo $lang_ezbbc['Ask author'] ?>", "");
        			        if (author != '' && author != null) {
        			        	startTag = '[quote=' + author + ']';
        			        }        			        
        			} else { // Nothing is selected
        			        var citation = prompt("<?php echo $lang_ezbbc['Ask quotation'] ?>", "");
        			        if (citation != '' && citation != null) {
        			                var author = prompt("<?php echo $lang_ezbbc['Ask author'] ?>", "");
        			                if (author != '' && author != null) {
        			                        startTag = '[quote=' + author + ']';
        			                        currentSelection = citation;
        			                } else {
        			        	  	currentSelection = citation;
        			        	}
        			        } else {
        			                startTag = endTag = '';
        			        }
        			}
        		break;
        		case "color":
                       	       if (currentSelection) { // Something is selected
        				var color = prompt("<?php echo $lang_ezbbc['Ask color'] ?> (<?php echo $lang_ezbbc['Ask color explanation'] ?>)", "");
        			        if (color != '' && color != null) {
        			        	startTag = '[color=' + color + ']';
        			        } else {
        			                startTag = endTag = '';
        			        }
        			        
        			} else { // Nothing is selected
        			        var color = prompt("<?php echo $lang_ezbbc['Ask color'] ?> (<?php echo $lang_ezbbc['Ask color explanation'] ?>)", "");
        			        if (color != '' && color != null) {
        			                var text = prompt("<?php echo $lang_ezbbc['Ask colorized text'] ?>", "");
        			                if (text != '' && color !=null) {
        			                        startTag = '[color=' + color + ']';
        			                        currentSelection = text;
        			                } else {
        			                        startTag = '[color=' + color + ']';
        			                        currentSelection = "<?php echo$lang_ezbbc['Ask colorized text'] ?>";
        			                } 
        			        } else {
        			                startTag = endTag = '';
        			        }
        			}
        		break;
        		case "heading":
                       	       if (!currentSelection) { // Nothing is selected
        			       var title = prompt("<?php echo $lang_ezbbc['Ask title'] ?>", "");
        			       if (title != '' && title != null) {
        			               currentSelection = title;
        			       } else if (title != null) {
        			               currentSelection = "<?php echo $lang_ezbbc['Ask title'] ?>";
        			       } else {
        			               startTag = endTag = '';
        			       }
        			}
        		break;
        		case "email":
       				if (currentSelection) { // Something is selected
       				        if (currentSelection.indexOf('@') == -1) {
       				                // The selection is not an E-mail address, so it is the label. We ask for the E-mail
       				                var email = prompt("<?php echo $lang_ezbbc['Ask email'] ?>", "");
       				                if (email != '' && email != null && email.indexOf('@') != -1) {
       				                        startTag = '[email=' + email + ']';
       				                } else {
       				                        startTag = endTag = '';
       				                }
       				        } else {// The selection seems to be an E-mail address
       				                startTag = '[email=' + currentSelection + ']';
       				        }
       				                
       				} else { // No selection, we ask for the URL and the label
       				        var email = prompt("<?php echo $lang_ezbbc['Ask email'] ?>", "");
       				        if (email !='' && email != null && email.indexOf('@') != -1) {
       				                var label = prompt("<?php echo $lang_ezbbc['Ask label'] ?>", "");
       				                if (label != '') {
       				                        startTag = '[email=' + email + ']';
       				                        currentSelection = label;
       				                } else {
       				                        currentSelection = email;
       				                }
       				        } else {
       				                startTag = endTag = '';
       				        }
       				}
       			break;
       			case "img":
       				if (currentSelection) { // Something is selected
       				        if (currentSelection.indexOf('http://') == 0) {
       				                // The selection seems to be a link, we ask for the alt text
       				                var alt = prompt("<?php echo $lang_ezbbc['Ask alt'] ?>", "");
       				                if (alt != '' && alt != null) {
       				                        startTag = '[img=' + alt + ']';
       				                }
       				        } else {
       				                // The selection is not a link, so it is the alt text. We ask for the URL
       				                var URL = prompt("<?php echo $lang_ezbbc['Ask url img'] ?>", "");
       				                if (URL != '' && URL !=null && URL.indexOf('http://') == 0) {
       				                        startTag = '[img=' + currentSelection + ']';
       				                        currentSelection = URL;
       				                } else {
       				                        startTag = endTag = '';
       				                }
       				        }
       				} else { // No selection, we ask for the URL and the alt text
       				        var URL = prompt("<?php echo $lang_ezbbc['Ask url'] ?>", "");
       				         if (URL != '' && URL != null && currentSelection.indexOf('http://') != 0) {
       				                 var alt = prompt("<?php echo $lang_ezbbc['Ask alt'] ?>", "");
       				                 if (alt !='' && alt != null) {
       				                         startTag = '[img=' + alt + ']';
       				                         currentSelection = URL;
       				                 } else {
       				                         currentSelection = URL;
       				                 }
       				         } else {
       				                  startTag = endTag = '';
       				         }
       				}
       			break;
       			case "unorderedlist":
       				if (currentSelection) { // Something is selected
       				        var item = currentSelection.split('\n');
       				        for(i=0;i<item.length;i++) {
       				                item[i] = '[*]' + item[i] + '[/*]';
					}
					currentSelection = '\n' + item.join("\n") + '\n';
       				} else { // No selection, we ask for the different list items
       				        var item = new Array();
       				        var i = 0;
       				        do {
       				               var itemCount = i+1;
       				               if (itemCount == 2){
       				                       alert("<?php echo $lang_ezbbc['Ask item explanation'] ?>");
       				               }
       				               item[i] = prompt("<?php echo $lang_ezbbc['Ask item'] ?>" + itemCount, "");
       				               i+=1;
       				        }
       				        while (item[i-1] != '' && item[i-1] != null);
       				        var count = item.length-1; // To avoid taking in account the last empty item
       				         if (count != 0 && item[i-1] != null) {
       				                for (i=0;i<count;i++) {
       				                        item[i] = '[*]' + item[i] + '[/*]';
       				                }
       				                currentSelection = '\n' + item.join("\n");
       				        } else {
       				                startTag = endTag = '';
       				        }                   
       				}
       			break;
       			case "orderedlist":
       				if (currentSelection) { // Something is selected
       				        var item = currentSelection.split('\n');
       				        for(i=0;i<item.length;i++) {
       				                item[i] = '[*]' + item[i] + '[/*]';
					}
					currentSelection = '\n' + item.join("\n") + '\n';
       				} else { // No selection, we ask for the different list items
       				        var item = new Array();
       				        var i = 0;
       				        do {
       				               var itemCount = i+1;
       				               if (itemCount == 2){
       				                       alert("<?php echo $lang_ezbbc['Ask item explanation'] ?>");
       				               }
       				                 item[i] = prompt("<?php echo $lang_ezbbc['Ask item'] ?>" + itemCount, "");
       				               i+=1;
       				        }
       				        while (item[i-1] != '' && item[i-1] != null);
       				        var count = item.length-1; // To avoid taking in account the last empty item
       				        if (count != 0 && item[i-1] != null) {
       				                for (i=0;i<count;i++) {
       				                        item[i] = '[*]' + item[i] + '[/*]';
       				                }
       				                currentSelection = '\n' + item.join("\n");
       				        } else {
       				                startTag = endTag = '';
       				        }
       				}
       			break;
       			case "alphaorderedlist":
       				if (currentSelection) { // Something is selected
       				        var item = currentSelection.split('\n');
       				        for(i=0;i<item.length;i++) {
       				                item[i] = '[*]' + item[i] + '[/*]';
					}
					currentSelection = '\n' + item.join("\n") + '\n';
       				} else { // No selection, we ask for the different list items
       				        var item = new Array();
       				        var i = 0;
       				        do {
       				               var itemCount = i+1;
       				               if (itemCount == 2){
       				                       alert("<?php echo $lang_ezbbc['Ask item explanation'] ?>");
       				               }
       				               item[i] = prompt("<?php echo $lang_ezbbc['Ask item'] ?>" + itemCount, "");
       				               i+=1;
       				        }
       				        while (item[i-1] != '' && item[i-1] != null);
       				        var count = item.length-1; // To avoid taking in account the last empty item
       				        if (count != 0 && item[i-1] != null) {
       				                for (i=0;i<count;i++) {
       				                        item[i] = '[*]' + item[i] + '[/*]';
       				                }
       				                currentSelection = '\n' + item.join("\n");
       				        } else {
       				                startTag = endTag = '';
       				        }                    
       				}
       			break;
                }
        }
    
        /* === Part 3: adding what was produced === */
        if (window.ActiveXObject) { //For IE
                textRange.text = startTag + currentSelection + endTag;
                textRange.moveStart('character', -endTag.length - currentSelection.length);
                textRange.moveEnd('character', -endTag.length);
                textRange.select();     
        } else { //For other browsers
                field.value = startSelection + startTag + currentSelection + endTag + endSelection;
                field.focus();
                field.setSelectionRange(startSelection.length + startTag.length, startSelection.length + startTag.length + currentSelection.length);
        } 

        field.scrollTop = scroll;     
}
/* ]]> */
</script>
<!-- EZBBC Toolbar integration end -->
<?php endif; ?>
