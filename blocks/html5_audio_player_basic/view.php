<?php	
defined('C5_EXECUTE') or die("Access Denied.");

$bID = $controller->bID;

$c = Page::getCurrentPage();

if ($c->isEditMode()) { ?>
	<div class="ccm-edit-mode-disabled-item" style="width:670px;height:80px;">
		<div style="padding:20px"><?php echo t('Player disabled in edit mode.')?></div>
	</div>
<?php } else { 	
	echo $controller->getPlayerJavascript('JQUERYUI');
?>
	<div id="jquery_jplayer_<?php echo $bID; ?>" class="jp-jplayer"></div>
	
	<div class="jp-jquery-ui-darkness jquery-ui-player-full">
		<div id="jp_container_<?php echo $bID; ?>">
			<div class="jp-type-single">
				<div class="jp-gui ui-widget ui-widget-content ui-corner-all">
					<ul>
						<li class="jp-play ui-state-default ui-corner-all"><a href="javascript:;" class="jp-play ui-icon ui-icon-play" tabindex="1" title="play"><?php echo t('play'); ?></a></li>
						<li class="jp-pause ui-state-default ui-corner-all"><a href="javascript:;" class="jp-pause ui-icon ui-icon-pause" tabindex="1" title="pause"><?php echo t('pause'); ?></a></li>
						<li class="jp-stop ui-state-default ui-corner-all"><a href="javascript:;" class="jp-stop ui-icon ui-icon-stop" tabindex="1" title="stop"><?php echo t('stop'); ?></a></li>
						<li class="jp-repeat ui-state-default ui-corner-all"><a href="javascript:;" class="jp-repeat ui-icon ui-icon-refresh" tabindex="1" title="repeat"><?php echo t('repeat'); ?></a></li>
						<li class="jp-repeat-off ui-state-default ui-state-active ui-corner-all"><a href="javascript:;" class="jp-repeat-off ui-icon ui-icon-refresh" tabindex="1" title="repeat off"><?php echo t('repeat off'); ?></a></li>
						<li class="jp-mute ui-state-default ui-corner-all"><a href="javascript:;" class="jp-mute ui-icon ui-icon-volume-off" tabindex="1" title="mute"><?php echo t('mute'); ?></a></li>
						<li class="jp-unmute ui-state-default ui-state-active ui-corner-all"><a href="javascript:;" class="jp-unmute ui-icon ui-icon-volume-off" tabindex="1" title="unmute"><?php echo t('unmute'); ?></a></li>
						<li class="jp-volume-max ui-state-default ui-corner-all"><a href="javascript:;" class="jp-volume-max ui-icon ui-icon-volume-on" tabindex="1" title="max volume"><?php echo t('max volume'); ?></a></li>
					</ul>
					<div class="jp-current-title"></div>
					<div class="jp-progress-slider"></div>
					<div class="jp-volume-slider"></div>
					<div class="jp-current-time"></div>
					<div class="jp-duration"></div>
					<div class="jp-clearboth"></div>
				</div>
				
				<div class="jp-no-solution">
					<span><?php echo t('Update Required'); ?></span>
					<?php echo t('To play the media you will need to either update your browser to a recent version or update your'); ?> <a href="http://get.adobe.com/flashplayer/" target="_blank"><?php echo t('Flash plugin'); ?></a>.
				</div>
			</div>
		</div>
	</div>
<?php } ?>
