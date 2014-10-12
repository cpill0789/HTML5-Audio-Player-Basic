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
.row > div {
	padding-bottom:10px;
}
#metaCategory {
	width:175px;
	display:inline-block;
	margin:0 5px;
}
#title {
	width:300px;
	display:inline-block;
}
</style>

<fieldset id="audioBlock-file" class="">
	<legend><?php echo t('Select File') ?></legend>
	<div id="audioBlock-singleAudio" class="row">
		<div class="col-sm-12">
			<?php
			echo $form->label('metaCategory', t('Title Source:'));
			echo $form->select('metaCategory', array(
									'TITLE'=> t('File Title tag'),
									'DESCRIPTION' => t('File Description tag'),
									'NONE'=> t('Custom')
									), $metaCategory);

			echo $form->text('title', $title, array('placeholder'=>t('Custom Title'))); ?>
		</div>
		<div class="col-sm-6">
			<h4><?php echo t('Primary File'); ?></h4>
			<div class="input" style="margin:4px;">
				<?php echo $al->audio('ccm-b-audio', 'fID', t('Choose Audio'), $file);?>
			</div>
		</div>
		<div class="col-sm-6">
			<h4><a href="#" class="launch-tooltip"
				   title="<?php echo t('Ogg format recommended to ensure HTML5 compatibility in older versions of Firefox. Flash-fallback will be used if secondary format not provided.'); ?>"
					><img src="<?php echo ASSETS_URL_IMAGES?>/icons/tooltip.png" /></a>
				<em><?php echo t('Optional Secondary File'); ?></em>
			</h4>
			<div class="input" style="margin:4px;">
				<?php echo $al->file('ccm-b-audio2', 'secondaryfID', t('Choose Secondary Audio Format'), $secondFile);?>
			</div>
		</div>
	</div>
</fieldset>
<fieldset id="audioBlock-playback">
	<legend><?php echo t('Playback Options') ?></legend>
	<div class="row">
        <div class="col-sm-6">
    	<?php echo $form->checkbox('loopAudio', 1, $loopAudio).' '.$form->label('loopAudio', t('Loop')); ?><br>
    	<?php echo $form->checkbox('autoPlay', 1, $autoPlay).' '.$form->label('autoPlay', t('Play Automatically')); ?><br>
    	<?php echo $form->checkbox('pauseOthers', 1, $pauseOthers).' '.$form->label('pauseOthers', t('Pause other players on playback')); ?>
        </div>
    	<div id="audioBlock-volume" class="col-sm-6">
    		<label for="initialVolume"><?php echo t('Initial Volume:')?></label>
    		<span id="volumeLevel"></span>
    		<div id="audioBlock-volumeSlider" style=""></div>
    		<input type="hidden" name="initialVolume" value="<?php echo $initialVolume; ?>"/>
    	</div>
	</div>
</fieldset>
