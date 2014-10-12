<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<div class="simple_square">
	<div id="jquery_jplayer_<?php echo $bID; ?>" class="jp-jplayer"></div>
	<div id="jp_container_<?php echo $bID; ?>" class="jp-audio">
		<div class="jp-type-single">
			<div class="jp-gui jp-interface">
				<div class="jp-controls">
					<div class="jp-play"><i class="fa fa-play"></i></div>
					<div class="jp-stop"><i class="fa fa-stop"></i></div>
				</div>
			</div>
			<div class="jp-details">
				<ul><li><span class="jp-title"></span></li></ul>
			</div>
			<div class="jp-no-solution">
				<span><?php echo t('Update Required'); ?></span>
				<?php echo t('To play the media you will need to either update your browser to a recent version or update your'); ?> <a href="http://get.adobe.com/flashplayer/" target="_blank"><?php echo t('Flash plugin'); ?></a>.
			</div>
		</div>
	</div>
<?php
	$c = \Page::getCurrentPage();
	if (!$c->isEditMode()) {
		echo $script;
	}
?>
</div>

