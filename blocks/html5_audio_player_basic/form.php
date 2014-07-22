<?php
defined('C5_EXECUTE') or die("Access Denied.");
$al = Loader::helper('concrete/asset_library');

$file = null;
$secondaryFile = null;

if($fID > 0) {
	$file = File::getByID($fID);
}
if($secondaryfID > 0) {
	$secondFile = File::getByID($secondaryfID);
}

?>
<style type="text/css">
#ccm-jPAudioPlayerBlock-singleAudio { margin-bottom:16px;clear:both;padding:7px; }
#ccm-jPAudioPlayerBlock-singleAudio select { width:150px; }
</style>
<div class="ccm-ui">
<ul class="tabs" id="audioBlock-tabs">
	<li><a href="#ccm-audioBlockPane-file" id="ccm-audioBlock-tab-file"><?php	 echo t('File')?></a></li>
	<li><a href="#ccm-audioBlockPane-playback" id="ccm-audioBlock-tab-playback"><?php	 echo t('Playback Options')?></a></li>
</ul>
<div class="spacer"></div>
<div id="ccm-audioBlockPane-file" class="ccm-audioBlockPane">
	<div class="clearfix"><h2><?php echo t('Select File:') ?></h2></div>
	<div id="ccm-jPAudioPlayerBlock-singleAudio">
		<div style="margin-bottom:10px;">
			<?php echo t('Display Title'); ?>:
			<select name="metaCategory">
				<option value="NONE"<?php  if ($metaCategory == 'NONE') { ?> selected<?php	} ?>><?php echo t('Custom')?></option>
				<option value="TITLE"<?php	if ($metaCategory == 'TITLE') { ?> selected<?php  } ?>><?php echo t('File Title tag')?></option>
				<option value="DESCRIPTION"<?php  if ($metaCategory == 'DESCRIPTION') { ?> selected<?php  } ?>><?php echo t('File Description tag')?></option>
			</select>
			<?php echo	$form->text('title', $title, array('style' => 'width:150px')); ?>
		</div>
		<div style="width:48%;float:left">
			<div style="font-style:italic;"><?php echo t('Primary File'); ?>:</div>
			<div class="input" style="margin:4px;">
				<?php echo $al->audio('ccm-b-audio', 'fID', t('Choose Audio'), $file);?>
			</div>
		</div>
		<div style="width:48%;float:left">
			<div style="font-style:italic;"><a href="#" class="launch-tooltip" title="<?php echo t('Ogg format recommended to ensure HTML5 compatibility in Firefox. Flash-fallback will be used if secondary format not provided.'); ?>">
																		<img src="<?php	 echo ASSETS_URL_IMAGES?>/icons/tooltip.png" /></a> <?php echo t('Optional'); ?>:
			</div>
			<div class="input" style="margin:4px;">
						<?php echo $al->file('ccm-b-audio2', 'secondaryfID', t('Choose Secondary Audio Format'), $secondFile);?>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<div id="ccm-audioBlockPane-playback" class="ccm-audioBlockPane">
	<h2><?php echo t('Playback Options:') ?></h2>
	<div>
		<input type="checkbox" name="loopAudio" value="1" <?php	 if ($loopAudio == 1) { ?> checked <?php  } ?> /> <?php echo t('Loop')?><br />
		<input type="checkbox" name="autoPlay" value="1" <?php	if ($autoPlay == 1) { ?> checked <?php	} ?> /> <?php echo t('Autoplay')?><br />
		<input type="checkbox" name="pauseOthers" value="1" <?php	if ($pauseOthers == 1) { ?> checked <?php	} ?> /> <?php echo t('Pause other players on playback')?><br />
	</div>
	<div id="ccm-jPAudioPlayerBlock-volume" style="width:200px;margin:8px">
		<span><?php echo t('Initial Volume: ')?></span><span id="volumeLevel"></span>
		<div id="ccm-jPAudioPlayerBlock-volumeSlider" style=""></div>
		<input type="hidden" name="initialVolume" value="<?php echo ($initialVolume > 0) ? $initialVolume : '80'; ?>"/>
	</div>
</div>
