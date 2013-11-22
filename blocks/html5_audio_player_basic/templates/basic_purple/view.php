<?php 
defined('C5_EXECUTE') or die("Access Denied.");

$bID = $controller->bID;

$c = Page::getCurrentPage();

if ($c->isEditMode()) { ?>
	
	<div class="ccm-edit-mode-disabled-item" style="width:100px;height:70px;">
		<div style="padding:5px"><?php echo t('Content disabled in edit mode.')?></div>
	</div>
	
<?php } else { 
	
	echo $controller->getPlayerJavascript('BASIC');
?>
	<div id="jquery_jplayer_<?php echo $bID; ?>" class="jp-jplayer"></div>
	
	<div class="single_basic_purple">
		<div id="jp_container_<?php echo $bID; ?>" class="jp-audio">
			<div class="jp-type-single">
				<div class="jp-gui jp-interface">
					<ul class="jp-controls">
						<li><a href="javascript:;" class="jp-stop" tabindex="1"><?php echo t('stop'); ?></a></li>
						<li><a href="javascript:;" class="jp-play" tabindex="1"><?php echo t('play'); ?></a></li>
					</ul>
				</div>
				<div class="jp-no-solution">
					<span><?php echo t('Update Required'); ?></span>
					<?php echo t('To play the media you will need to either update your browser to a recent version or update your'); ?> <a href="http://get.adobe.com/flashplayer/" target="_blank"><?php echo t('Flash plugin'); ?></a>.
				</div>
			</div>
		</div>
	</div>
<?php } ?>