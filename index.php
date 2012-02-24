<?php
 /**
  * This is the main page.
  */

// Load engine.php
require 'engine.php';

if(isset($_GET['err']) && $_GET['err']!='') {
	switch($_GET['err']) {
		case '404':
			header('Content-Type: image/jpeg', true, 404);
			echo file_get_contents('./site-img/404.jpg');
			break;
		case '403':
			header('Content-Type: image/jpeg', true, 403);
			echo file_get_contents('./site-img/403.jpg');
			break;
	}
}else {
	
	$uploaded=false;
	if(isset($_POST['submit'])){
		$files = save_upload_files();
		$uploaded=true;
	}
	
	if(isset($_POST['is_thumb']) && $_POST['is_thumb']!='') {
		if($_POST['is_thumb'] == 'yes')
			$isthumb = true;
		elseif($_POST['is_thumb'] == 'no')
			$isthumb = false;
	}else {
		$isthumb = IS_THUMB;
	}
	if(isset($_POST['thumb_size']) && $_POST['thumb_size']!='' && is_numeric($_POST['thumb_size'])) {
		$thumbinit = $_POST['thumb_size'];
	}else {
		$thumbinit = THUMB_DEFAULT;
	}
$isthumb
// Page header
?>
<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<meta name="Keywords" content="<?=SITE_KEYWORDS ?>">
<meta name="Description" content="<?=SITE_DESCRIPTION ?>">
<meta http-equiv-"X-UA-Compatible" content="IE=Edge" />
<title><?=SITE_TITLE . ' - ' . INFO_NAME ?></title>
<link type="text/css" rel="stylesheet" href="style.css">
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="ui.js"></script>
<script type="text/javascript" src="upload.js"></script>
</head>

<body>
<!-- Header -->
<div id="header">
<a rel="index" href="<?=the_url() ?>"><img src="site-img/logo.png" alt="<?=SITE_TITLE ?>" title="<?=SITE_TITLE ?>"></a><?=SITE_DESCRIPTION ?>
</div>

<!-- Main Section -->
<div id="main">
  <!-- Normal Upload Section -->
  <div id="normal" class="workplace <?=(!isset($_COOKIE['lastest_upload_method']) || $_COOKIE['lastest_upload_method']=='normal') ? 'expanded' : 'collapsed' ?>">
    <div class="method"><?=UI_METHOD_NORMAL ?></div>
    <div class="userhandle"<?=(!isset($_COOKIE['lastest_upload_method']) || $_COOKIE['lastest_upload_method']=='normal') ? '' : ' style="display:none;"' ?>>
      <p><?=UI_INFO_NORMAL ?></p>
      <form id="normalform" method="post" enctype="multipart/form-data">
      <p class="psubmit"><button id="normalsubmit" type="submit" name="submit" value="submit"><?=UI_SUBMIT ?></button>&nbsp;&nbsp;<button id="normaladd" type="button" data-nomore="<?=UI_NOMORE ?>" data-more="<?=UI_ADD ?>" data-remove="<?=UI_REMOVE ?>"><?=UI_ADD ?></button></p>
      <ul id="filelist"><li><button class="normalremove" type="button"><?=UI_REMOVE ?></button><input type="file" name="files[]" accept="image"></li></ul>
      <input type="hidden" id="normalisthumb" name="is_thumb" value="<?=$isthumb ? 'yes' : 'no' ?>">
      <input type="hidden" id="normalthumbsize" name="thumb_size" value="<?=$thumbinit ?>">
      <input id="filesizebytes" type="hidden" name="MAX_FILE_SIZE" value="<?=the_size_limit() ?>">
      </form>
    </div>
  </div>
  
  <!-- URL Upload Section -->
  <div id="url" class="workplace <?=(isset($_COOKIE['lastest_upload_method']) && $_COOKIE['lastest_upload_method']=='url') ? 'expanded' : 'collapsed' ?>">
    <div class="method"><?=UI_METHOD_URL ?></div>
    <div class="userhandle"<?=(isset($_COOKIE['lastest_upload_method']) && $_COOKIE['lastest_upload_method']=='url') ? '' : ' style="display:none;"' ?> >
      <p><?=UI_INFO_URL ?></p>
      <p class="psubmit"><button id="urlsubmit" type="button"><?=UI_SUBMIT ?></button>&nbsp;&nbsp;<button id="urlclear" type="button"><?=UI_CLEAR ?></button>&nbsp;&nbsp;<button id="urlgrab" type="button"><?=UI_GRAB ?></button></p>
      <textarea id="urllist" wrap="off"></textarea>
    </div>
  </div>
  
  <!-- Drag & Drop Section -->
  <div id="drop" class="workplace collapsed wpright">
    <div class="method"><?=UI_METHOD_DROP ?></div>
    <div class="userhandle" style="display:none;">
      <?=UI_INFO_DROP ?>
    </div>
  </div>
  
   <!-- Tips For Settings -->
  <div id="tips"><?=UI_TIPS_IS_THUMB ?>: <span class="is_thumb_checked" data-yes="<?=UI_TIPS_IS_THUMB_YES ?>" data-no="<?=UI_TIPS_IS_THUMB_NO ?>"><?=$isthumb ? UI_TIPS_IS_THUMB_YES : UI_TIPS_IS_THUMB_NO ?></span>;&nbsp;&nbsp;<?=UI_TIPS_THUMB_SIZE ?>: <span class="thumb_size_output"><?=$thumbinit ?></span>×<span class="thumb_size_output"><?=$thumbinit ?></span>;&nbsp;&nbsp;<?=UI_TIPS_FILESIZE ?>: <?=SIZE_LIMIT ?>MiB</div>
  
  <!-- Settings -->
  <div id="settings">
    <!-- Settings Setup -->
    <div id="param" style="display:none;">
      <p><?=UI_SET_TIPS ?></p>
      <p><?=UI_SET_IS_THUMB ?>: <input type="checkbox" id="is_thumb" data-init="<?=$isthumb ? 'yes' : 'no' ?>" <?=$isthumb ? 'checked' : '' ?> data-default="<?=IS_THUMB ? 'yes' : 'no' ?>"></p>
      <p><?=UI_SET_THUMB_SIZE ?>: <input type="number" id="thumb_size" min="<?=THUMB_MIN ?>" max="<?=THUMB_MAX ?>" value="<?=$thumbinit ?>" data-default="<?=THUMB_DEFAULT ?>" data-init="<?=$thumbinit ?>"></p>
      <!-- Thumbnail Size Slider -->
      <div id="tsslider"><div class="tsrange"><?=THUMB_MIN ?></div><div id="tssliderctn"><div id="tssliderbar" style="width:<?=500*($thumbinit-THUMB_MIN)/(THUMB_MAX-THUMB_MIN) ?>px;">&nbsp;</div></div><div class="tsrange"><?=THUMB_MAX ?></div></div>
      <p><button id="reset_settings" type="button"><?=UI_SET_RESET ?></button></p>
      <p><?=UI_SET_FILESIZE ?>: <?=SIZE_LIMIT ?><span class="help" title="<?=UI_SET_INFO_MIB ?>">MiB</span></p>
    </div>
    <!-- Settings Icon -->
    <div id="seticon" class="off" title="<?=UI_SET_SETICON ?>">⚙</div>
  </div>

  <!-- User Tips -->
  <?php if (defined('USER_TIPS') && USER_TIPS != '') { ?>
  <div id="usertipsicon" title="<?=UI_USER_TIPS ?>">⚠</div>
  <div id="usertips" style="display:none;"><?=USER_TIPS ?></div>
  <?php } ?>
</div>

<!-- Result Section -->
<div id="result" <?=$uploaded ? '' : 'style="display:none;"' ?>
data-err-title="<?=UI_ERROR_TITLE ?>"
data-err-name="<?=UI_ERROR_NAME ?>"
data-upload-title="<?=UI_UPLOADING_TITLE ?>"
data-upload-info="<?=UI_UPLOADING_INFO ?>"
data-err-chottomatte="<?=UI_MSG_WAIT ?>"
data-err-noturl="<?=ERR_NOT_URL ?>"
data-err-toomany="<?=ERR_UPLOAD_TOO_MANY ?>"
data-err-noresponse="<?=ERR_NO_RESPONSE ?>"
data-err-toobig="<?=ERR_UPLOAD_FORM_SIZE ?>"
>
  <div id="resulttitle"><?=UI_RESULT_TITLE ?></div>
  <ul id="resultlist">
<?php
if($uploaded) {
	result_format($files,'normalresult');
}
?>
  </ul>
</div>

<!-- Footer -->
<div id="footer">
  <p>&copy;<?=the_time('Y') ?> <?php
if (defined('SITE_HOMEPAGE') && SITE_HOMEPAGE != '') {
	echo '<a href="' . SITE_HOMEPAGE_URL . '" title="' . SITE_HOMEPAGE_DESC .'" target="_blank">' . SITE_HOMEPAGE . '</a>';
}else {
	echo '<a href="' . the_url() . '" title="' . SITE_DESCRIPTION .'" target="_blank">' . SITE_TITLE . '</a>';
}
?>.</p>
  <p>Powered by <a href="http://sourceforge.net/projects/qchan/" target="_blank" title="Qchan Image Hosting">Qchan <?=QCHAN_VER ?></a>. &copy;2011-2012 <a href="http://tsukkomi.org" title="有槽必吐 - 不吐槽，毋宁死" target="_blank">有槽必吐</a>.</p>
</div>

</body>

</html>
<?php } ?>