<?php	
defined('C5_EXECUTE') or die("Access Denied.");
$al = Loader::helper('concrete/asset_library');
$ah = Loader::helper('concrete/interface');

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
#ccm-jPAudioPlayerBlock-singleAudio {margin-bottom:16px;clear:both;padding:7px;}
</style>
<div class="ccm-ui">
<ul id="tabset" class="tabs" id="ccm-formblock-tabs">
	<li><a href="#ccm-formBlockPane-file" id="ccm-formblock-tab-file"><?php	 echo t('File')?></a></li>
	<li><a href="#ccm-formBlockPane-playback" id="ccm-formblock-tab-playback"><?php	 echo t('Playback Options')?></a></li>
</ul>
<div class="spacer"></div>
<div id="ccm-formBlockPane-file" class="ccm-formBlockPane">
	<div class="clearfix"><h2><?php echo t('Select File:') ?></h2></div>
	<div id="ccm-jPAudioPlayerBlock-singleAudio">
		<div style="margin-bottom:10px;">
			<?php echo t('Display Title'); ?>:	
			<?php echo	$form->text('title', $title, array('style' => 'width:200px')); ?>
			<select name="metaCategory">
				<option value="NONE"<?php  if ($metaCategory == 'NONE') { ?> selected<?php	} ?>><?php echo t('use file metadata')?>: </option>
				<option value="TITLE"<?php	if ($metaCategory == 'TITLE') { ?> selected<?php  } ?>><?php echo t('Title tag')?></option>
				<option value="DESCRIPTION"<?php  if ($metaCategory == 'DESCRIPTION') { ?> selected<?php  } ?>><?php echo t('Description tag')?></option>
			</select>		 
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
<div id="ccm-formBlockPane-playback" class="ccm-formBlockPane">
	<h2><?php echo t('Playback Options:') ?></h2>
	<div>
		<input type="checkbox" name="loopAudio" value="1" <?php	 if ($loopAudio == 1) { ?> checked <?php  } ?> /> <?php echo t('Loop')?><br />
		<input type="checkbox" name="autoPlay" value="1" <?php	if ($autoPlay == 1) { ?> checked <?php	} ?> /> <?php echo t('Autoplay')?><br />
	</div>
	<div id="ccm-jPAudioPlayerBlock-volume" style="width:200px;margin:8px">
		<span><?php echo t('Initial Volume: ')?></span><span id="volumeLevel"></span>
		<div id="ccm-jPAudioPlayerBlock-volumeSlider" style=""></div>
		<input type="hidden" name="initialVolume" value="<?php echo ($initialVolume > 0) ? $initialVolume : '80'; ?>"/>
	</div>
</div>