<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "tinymce".
 *
 * Auto generated 19-06-2013 19:08
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'tinyMCE',
	'description' => 'tinymce sources with compressor and spellchecker',
	'category' => 'misc',
	'shy' => 0,
	'version' => '4.0.0',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Stefan Galinski',
	'author_email' => 'stefan.galinski@gmail.com',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'php' => '5.2.0-5.5.99',
			'typo3' => '4.5.0-6.2.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
	'_md5_values_when_last_written' => 'a:130:{s:17:"class.tinymce.php";s:4:"9f76";s:12:"ext_icon.gif";s:4:"b163";s:8:"VERSIONS";s:4:"03df";s:14:"doc/manual.sxw";s:4:"9ab1";s:19:"tinymce/license.txt";s:4:"045d";s:22:"tinymce/tinymce.min.js";s:4:"c326";s:19:"tinymce/langs/ar.js";s:4:"b84e";s:22:"tinymce/langs/bg_BG.js";s:4:"d436";s:19:"tinymce/langs/bs.js";s:4:"2d58";s:19:"tinymce/langs/ca.js";s:4:"f051";s:19:"tinymce/langs/cs.js";s:4:"01dd";s:19:"tinymce/langs/da.js";s:4:"46e4";s:19:"tinymce/langs/de.js";s:4:"71be";s:22:"tinymce/langs/de_AT.js";s:4:"f463";s:19:"tinymce/langs/el.js";s:4:"8592";s:19:"tinymce/langs/es.js";s:4:"e8f5";s:19:"tinymce/langs/fa.js";s:4:"f502";s:19:"tinymce/langs/fi.js";s:4:"b5fb";s:19:"tinymce/langs/fo.js";s:4:"f1e4";s:22:"tinymce/langs/fr_FR.js";s:4:"1c57";s:19:"tinymce/langs/gl.js";s:4:"6248";s:22:"tinymce/langs/he_IL.js";s:4:"8894";s:19:"tinymce/langs/hr.js";s:4:"74c3";s:22:"tinymce/langs/hu_HU.js";s:4:"35b8";s:19:"tinymce/langs/it.js";s:4:"c1b2";s:19:"tinymce/langs/ja.js";s:4:"8bbc";s:22:"tinymce/langs/ka_GE.js";s:4:"9ec8";s:22:"tinymce/langs/ko_KR.js";s:4:"df84";s:19:"tinymce/langs/lv.js";s:4:"577a";s:22:"tinymce/langs/nb_NO.js";s:4:"8580";s:19:"tinymce/langs/nl.js";s:4:"d0aa";s:19:"tinymce/langs/pl.js";s:4:"13e6";s:22:"tinymce/langs/pt_BR.js";s:4:"92f0";s:22:"tinymce/langs/pt_PT.js";s:4:"5062";s:23:"tinymce/langs/readme.md";s:4:"a803";s:19:"tinymce/langs/ro.js";s:4:"c10f";s:19:"tinymce/langs/ru.js";s:4:"34f8";s:22:"tinymce/langs/si_LK.js";s:4:"7d08";s:19:"tinymce/langs/sk.js";s:4:"2ed5";s:22:"tinymce/langs/sl_SI.js";s:4:"6817";s:19:"tinymce/langs/sr.js";s:4:"bbc6";s:22:"tinymce/langs/sv_SE.js";s:4:"cae3";s:22:"tinymce/langs/th_TH.js";s:4:"1f29";s:22:"tinymce/langs/tr_TR.js";s:4:"4eb4";s:19:"tinymce/langs/ug.js";s:4:"c056";s:19:"tinymce/langs/uk.js";s:4:"f551";s:22:"tinymce/langs/vi_VN.js";s:4:"8371";s:22:"tinymce/langs/zh_CN.js";s:4:"d5e5";s:22:"tinymce/langs/zh_TW.js";s:4:"266b";s:37:"tinymce/plugins/advlist/plugin.min.js";s:4:"2ac2";s:36:"tinymce/plugins/anchor/plugin.min.js";s:4:"461c";s:38:"tinymce/plugins/autolink/plugin.min.js";s:4:"001c";s:40:"tinymce/plugins/autoresize/plugin.min.js";s:4:"c031";s:38:"tinymce/plugins/autosave/plugin.min.js";s:4:"1bcc";s:36:"tinymce/plugins/bbcode/plugin.min.js";s:4:"1a72";s:37:"tinymce/plugins/charmap/plugin.min.js";s:4:"92c1";s:34:"tinymce/plugins/code/plugin.min.js";s:4:"0ae7";s:44:"tinymce/plugins/compat3x/editable_selects.js";s:4:"7908";s:38:"tinymce/plugins/compat3x/form_utils.js";s:4:"c561";s:34:"tinymce/plugins/compat3x/mctabs.js";s:4:"55cc";s:42:"tinymce/plugins/compat3x/tiny_mce_popup.js";s:4:"8164";s:36:"tinymce/plugins/compat3x/validate.js";s:4:"6814";s:41:"tinymce/plugins/contextmenu/plugin.min.js";s:4:"722b";s:44:"tinymce/plugins/directionality/plugin.min.js";s:4:"589d";s:39:"tinymce/plugins/emoticons/plugin.min.js";s:4:"3a62";s:45:"tinymce/plugins/emoticons/img/smiley-cool.gif";s:4:"e26e";s:44:"tinymce/plugins/emoticons/img/smiley-cry.gif";s:4:"e72b";s:51:"tinymce/plugins/emoticons/img/smiley-embarassed.gif";s:4:"d591";s:54:"tinymce/plugins/emoticons/img/smiley-foot-in-mouth.gif";s:4:"c12d";s:46:"tinymce/plugins/emoticons/img/smiley-frown.gif";s:4:"5993";s:49:"tinymce/plugins/emoticons/img/smiley-innocent.gif";s:4:"ec04";s:45:"tinymce/plugins/emoticons/img/smiley-kiss.gif";s:4:"4ae8";s:49:"tinymce/plugins/emoticons/img/smiley-laughing.gif";s:4:"c37f";s:52:"tinymce/plugins/emoticons/img/smiley-money-mouth.gif";s:4:"11c1";s:47:"tinymce/plugins/emoticons/img/smiley-sealed.gif";s:4:"bb82";s:46:"tinymce/plugins/emoticons/img/smiley-smile.gif";s:4:"2968";s:50:"tinymce/plugins/emoticons/img/smiley-surprised.gif";s:4:"2e13";s:51:"tinymce/plugins/emoticons/img/smiley-tongue-out.gif";s:4:"5ec3";s:50:"tinymce/plugins/emoticons/img/smiley-undecided.gif";s:4:"3c0c";s:45:"tinymce/plugins/emoticons/img/smiley-wink.gif";s:4:"8972";s:45:"tinymce/plugins/emoticons/img/smiley-yell.gif";s:4:"19bb";s:37:"tinymce/plugins/example/plugin.min.js";s:4:"c3c3";s:48:"tinymce/plugins/example_dependency/plugin.min.js";s:4:"8751";s:38:"tinymce/plugins/fullpage/plugin.min.js";s:4:"3fc4";s:40:"tinymce/plugins/fullscreen/plugin.min.js";s:4:"fa63";s:32:"tinymce/plugins/hr/plugin.min.js";s:4:"e1f5";s:35:"tinymce/plugins/image/plugin.min.js";s:4:"67b5";s:44:"tinymce/plugins/insertdatetime/plugin.min.js";s:4:"b58c";s:35:"tinymce/plugins/layer/plugin.min.js";s:4:"3d20";s:42:"tinymce/plugins/legacyoutput/plugin.min.js";s:4:"a16d";s:34:"tinymce/plugins/link/plugin.min.js";s:4:"0042";s:35:"tinymce/plugins/lists/plugin.min.js";s:4:"5d74";s:37:"tinymce/plugins/media/moxieplayer.swf";s:4:"9217";s:35:"tinymce/plugins/media/plugin.min.js";s:4:"fcfb";s:41:"tinymce/plugins/nonbreaking/plugin.min.js";s:4:"8c71";s:41:"tinymce/plugins/noneditable/plugin.min.js";s:4:"a30c";s:39:"tinymce/plugins/pagebreak/plugin.min.js";s:4:"fe88";s:35:"tinymce/plugins/paste/plugin.min.js";s:4:"27c1";s:37:"tinymce/plugins/preview/plugin.min.js";s:4:"3980";s:35:"tinymce/plugins/print/plugin.min.js";s:4:"7216";s:34:"tinymce/plugins/save/plugin.min.js";s:4:"7e44";s:43:"tinymce/plugins/searchreplace/plugin.min.js";s:4:"2ed3";s:42:"tinymce/plugins/spellchecker/plugin.min.js";s:4:"f376";s:38:"tinymce/plugins/tabfocus/plugin.min.js";s:4:"cfc7";s:35:"tinymce/plugins/table/plugin.min.js";s:4:"f822";s:38:"tinymce/plugins/template/plugin.min.js";s:4:"a61b";s:39:"tinymce/plugins/textcolor/plugin.min.js";s:4:"eefa";s:42:"tinymce/plugins/visualblocks/plugin.min.js";s:4:"f0fa";s:49:"tinymce/plugins/visualblocks/css/visualblocks.css";s:4:"697c";s:41:"tinymce/plugins/visualchars/plugin.min.js";s:4:"4327";s:39:"tinymce/plugins/wordcount/plugin.min.js";s:4:"7507";s:46:"tinymce/skins/lightgray/content.inline.min.css";s:4:"4498";s:39:"tinymce/skins/lightgray/content.min.css";s:4:"ecd7";s:40:"tinymce/skins/lightgray/skin.ie7.min.css";s:4:"6d5c";s:36:"tinymce/skins/lightgray/skin.min.css";s:4:"6007";s:47:"tinymce/skins/lightgray/fonts/icomoon-small.eot";s:4:"fba6";s:47:"tinymce/skins/lightgray/fonts/icomoon-small.svg";s:4:"25df";s:47:"tinymce/skins/lightgray/fonts/icomoon-small.ttf";s:4:"bb2e";s:48:"tinymce/skins/lightgray/fonts/icomoon-small.woff";s:4:"b407";s:41:"tinymce/skins/lightgray/fonts/icomoon.eot";s:4:"29e3";s:41:"tinymce/skins/lightgray/fonts/icomoon.svg";s:4:"de6a";s:41:"tinymce/skins/lightgray/fonts/icomoon.ttf";s:4:"4c53";s:42:"tinymce/skins/lightgray/fonts/icomoon.woff";s:4:"8621";s:39:"tinymce/skins/lightgray/fonts/readme.md";s:4:"7a0f";s:38:"tinymce/skins/lightgray/img/anchor.gif";s:4:"abd3";s:38:"tinymce/skins/lightgray/img/loader.gif";s:4:"394b";s:38:"tinymce/skins/lightgray/img/object.gif";s:4:"f372";s:37:"tinymce/skins/lightgray/img/trans.gif";s:4:"12bf";s:37:"tinymce/skins/lightgray/img/wline.gif";s:4:"c136";s:34:"tinymce/themes/modern/theme.min.js";s:4:"356d";}',
);

?>