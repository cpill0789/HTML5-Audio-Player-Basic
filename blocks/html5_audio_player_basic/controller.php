<?php defined('C5_EXECUTE') or die(_("Access Denied."));

class Html5AudioPlayerBasicBlockController extends BlockController {

	protected $btTable = "btHtml5AudioPlayerBasic";
	protected $btInterfaceWidth = "500";
	protected $btInterfaceHeight = "400";

	protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = false;
    protected $btCacheBlockOutputLifetime = CACHE_LIFETIME;

	public function getBlockTypeName() {
		return t('HTML5 Audio Player Basic');
	}

	public function getBlockTypeDescription() {
		return t('Basic HTML5/flash audio player built using the jPlayer jQuery plugin');
	}

	public function getJavaScriptStrings() {
		return array(
			'choose-file' => t('Choose an Audio File')
		);
	}

	function getBlockPath() {
		$uh = Loader::helper('concrete/urls');
		return $uh->getBlockTypeAssetsURL($this->getBlockObject()->getBlockTypeObject());
	}

	function on_page_view() {
		$html = Loader::helper('html');
		$uh = Loader::helper('concrete/urls');

		$playerTheme = $this->getBlockObject()->getBlockFilename();

		$this->addHeaderItem($html->javascript('jquery.js'));

		if ($playerTheme != 'basic_purple' && $playerTheme != 'circle_player' &&
			$playerTheme != 'blue_monday' && $playerTheme != 'pink_flag') {
			$this->addHeaderItem($html->css('jquery.ui.css'));
			$this->addFooterItem($html->javascript('jquery.ui.js'));
		}
		$this->addFooterItem($html->javascript('jplayer.min.js','html5_audio_player_basic'));
	}

	function add() {
		$this->set('pauseOthers', 1);
		$this->set('metaCategory', '');
		$this->set('title', '');
		$this->set('fID', 0);
		$this->set('secondaryfID', 0);
		$this->set('loopAudio', 0);
		$this->set('initialVolume', 80);
		$this->set('autoPlay', 0);
	}

	public function save($data) {
		$data['loopAudio'] = intval($data['loopAudio']);
		$data['autoPlay'] = intval($data['autoPlay']);
		$data['pauseOthers'] = intval($data['pauseOthers']);
		$data['useMetaTitle'] = intval($data['useMetaTitle']);
		parent::save($data);
	}

	function getPlayerJavascript($playerType) {

		// calculate values for player initialization
		$isLooped = ($this->loopAudio == 1) ? "true" : "false";
		$isAuto = ($this->autoPlay == 1) ? "true" : "false";
		$volume = $this->initialVolume/100;

		$basePath = $this->getBlockPath();

		$blockID = $this->bID;
		$defaultAncestor = 'jp_container_'.$blockID;

		$f = File::getByID($this->fID);
		$fileType = strtolower($f->getExtension());
		if ($fileType == 'ogg') {
			$fileType = 'oga';
		}

		$relPath = $f->getRelativePath();
		$fileTypes = $fileType;

		$f2 = 0;
		$relPath2 = '';
		$fileType2 = '';

		$title = $this->title;

		if ($this->secondaryfID > 0) {
			$f2 = File::getByID($this->secondaryfID);
			$relPath2 = $f2->getRelativePath();
			$fileType2 = strtolower($f2->getExtension());
			if ($fileType2 == 'ogg') {
				$fileType2 = 'oga';
			}
			$fileTypes .= ', '.$fileType2;

		}

		if ($this->metaCategory == 'TITLE') {
			$title = $f->getTitle();
		}
		if ($this->metaCategory == 'DESCRIPTION') {
			$title = $f->getDescription();
		}


		$fileInfo =	'{'.$fileType.':"'.$relPath.'",'.'title:"'.$title.'"';
		if ($this->secondaryfID > 0) {
			$fileInfo .= ','.$fileType2.':"'.$relPath2.'"';
		}
		$fileInfo .= '}';

		// Player event callback default values
		$defaultReady = '$(this).jPlayer("setMedia",'.$fileInfo.');'
					   . '$("#'.$defaultAncestor.' .jp-current-title").html($(this).data("jPlayer").status.media.title);';
		if ($this->autoPlay) {
			$defaultReady .= '$(this).jPlayer("play");';
		}

		if ($this->pauseOthers) {
			$defaultPlay = '$(this).jPlayer("pauseOthers");';
		} else {
			$defaultPlay = '';
		}

		$defaultStandard =	'swfPath: "'.REL_DIR_PACKAGES.'/html5_audio_player_basic/flash/",'
						 . 	'supplied: "'.$fileTypes.'",'
						 . 	'wmode: "window",'
						 . 	'volume: '.$volume.','
						 . 	'loop: '.$isLooped.',';

		// Assemble Player Javascript
		$playerScript = '<script type="text/javascript">$(document).ready(function(){';

		if ($playerType == 'STANDARD') {

			// Javascript for standard jPlayer skins (Blue Monday and Pink Flag)
			$playerScript .= '$("#jquery_jplayer_'.$blockID.'").jPlayer({'
						   .	'ready: function (event) { '
						   .		$defaultReady;
			if ($title == '') {
				$playerScript .= '$("#'.$defaultAncestor.' .jp-title").hide();';
			}
			$playerScript .= 	'},'
						   . 	'play: function(event) {'
						   .		$defaultPlay
						   . 	'},'
						   .	$defaultStandard
						   . 	'cssSelectorAncestor: "#'.$defaultAncestor.'"'
						   . '});'
						. '});</script>';

		} else if ($playerType == 'BASIC') {

			// Javascript for Basic player
			$playerScript .= '$("#jquery_jplayer_'.$blockID.'").jPlayer({'
						   .	'ready: function (event) { '
						   .		$defaultReady;
			if ($title == '') {
				$playerScript .= '$("#'.$defaultAncestor.' .jp-title").hide();';
			}
			$playerScript .= 	'},'
						   . 	'play: function(event) {'
						   .		$defaultPlay
						   .		'$("#'.$defaultAncestor.' .jp-controls .jp-play").hide();'
						   .		'$("#'.$defaultAncestor.' .jp-controls .jp-stop").show();'
						   . 	'},'
						   .	'pause: function (event) {'
						   .		'$("#'.$defaultAncestor.' .jp-controls .jp-play").show();'
						   .		'$("#'.$defaultAncestor.' .jp-controls .jp-stop").hide();'
						   .	'},'
						   .	'ended: function () {'
						   .		'$("#'.$defaultAncestor.' .jp-controls .jp-play").show();'
						   .		'$("#'.$defaultAncestor.' .jp-controls .jp-stop").hide();'
						   .	'},'
						   .	$defaultStandard
						   . 	'cssSelectorAncestor: "#'.$defaultAncestor.'"'
						   . '});'
						. '});</script>';

		} else if ($playerType == 'CIRCLE') {

			// Javascript for Circle player
			$playerScript .= 'var myCirclePlayer = new CirclePlayer("#jquery_jplayer_'.$blockID.'",'
						   .	$fileInfo.','
						   . '{'
						   .	$defaultStandard
						   . 	'cssSelectorAncestor: "#cp_container_'.$blockID.'"'
						   . '});'
						. '});</script>';

		} else if ($playerType == 'JQUERYUI') {

			// Javascript for jQuery UI player

			/*
			 * jQuery UI ThemeRoller
			 *
			 * Includes code to hide GUI volume controls on mobile devices.
			 * ie., Where volume controls have no effect. See noVolume option for more info.
			 *
			 * Includes fix for Flash solution with MP4 files.
			 * ie., The timeupdates are ignored for 1000ms after changing the play-head.
			 * Alternative solution would be to use the slider option: {animate:false}
			 */

			$playerScript .= 'var myPlayer = $("#jquery_jplayer_'.$blockID.'");'
						   . 'var myPlayerData, fixFlash_mp4, fixFlash_mp4_id, ignore_timeupdate;'
						   . 'var options = {'
						   .	'ready: function (event) {'
									// Hide the volume slider on mobile browsers. ie., They have no effect.
						   . 		'if (event.jPlayer.status.noVolume) {'
									// Add a class and then CSS rules deal with it.
						   .			'$("#jquery_jplayer_'.$blockID.' .jp-gui").addClass("jp-no-volume");'
						   .		'}'
									// Determine if Flash is being used and the mp4 media type is supplied. BTW, Supplying both mp3 and mp4 is pointless.
						   .		'fixFlash_mp4 = event.jPlayer.flash.used && /m4a|m4v/.test(event.jPlayer.options.supplied);'
									// Setup the player with media.
						   .		$defaultReady
						   . 	'},'
						   . 	'timeupdate: function(event) {'
						   . 		'if (!ignore_timeupdate) {'
						   .			'myControl.progress.slider("value", event.jPlayer.status.currentPercentAbsolute);'
						   .		'}'
						   . 	'},'
						   . 	'volumechange: function(event) {'
						   .		'if (event.jPlayer.options.muted) {'
						   .			'myControl.volume.slider("value", 0);'
						   . 		'} else {'
						   .			'myControl.volume.slider("value", event.jPlayer.options.volume);'
						   . 		'}'
						   . 	'},'
						   . 	'play: function() {'
						   .		$defaultPlay
						   . 	'},'
						   . 	$defaultStandard
						   . 	'cssSelectorAncestor: "#'.$defaultAncestor.'"'
						   . '};'
						   . 'var myControl = {'
						   . 	'progress: $("#'.$defaultAncestor.' .jp-progress-slider"),'
						   .	'volume: $("#'.$defaultAncestor.' .jp-volume-slider")'
						   . '};'
							// Instance jPlayer
						   . 'myPlayer.jPlayer(options);'
							// A pointer to the jPlayer data object
						   . 'myPlayerData = myPlayer.data("jPlayer");'
							// Define hover states of the buttons
						   . '$("#jquery_jplayer_'.$blockID.' .jp-gui ul li").hover('
						   .	'function() { '
						   .		'$(this).addClass("ui-state-hover");'
						   . 	'},'
						   .	'function() { '
						   .		'$(this).removeClass("ui-state-hover");'
						   .	'}'
						   . ');'
							// Create the progress slider control
						   .'myControl.progress.slider({'
						   .	'animate: "fast",'
						   .	'max: 100,'
						   .	'range: "min",'
						   .	'step: 0.1,'
						   .	'value: 0,'
						   .	'slide: function(event, ui) {'
						   .		'var sp = myPlayerData.status.seekPercent;'
						   .		'if (sp > 0) {'
						 				// Apply a fix to mp4 formats when the Flash is used.
						   .			'if (fixFlash_mp4) {'
						   .				'ignore_timeupdate = true;'
						   .				'clearTimeout(fixFlash_mp4_id);'
						   .				'fixFlash_mp4_id = setTimeout(function() {'
						   .					'ignore_timeupdate = false;'
						   .				'},1000);'
						   .			'}'
										// Move the play-head to the value and factor in the seek percent.
						   .			'myPlayer.jPlayer("playHead", ui.value * (100 / sp));'
						   .		'} else {'
										// Create a timeout to reset this slider to zero.
						   .			'setTimeout(function() {'
						   .				'myControl.progress.slider("value", 0);'
						   .			'}, 0);'
						   .		'}'
						   .	'}'
						   . '});'
							// Create the volume slider control
						   . 'myControl.volume.slider({'
						   .	'animate: "fast",'
						   .	'max: 1,'
						   .	'range: "min",'
						   .	'step: 0.01,'
						   .	'value: '.$volume.','
						   .	'slide: function(event, ui) {'
						   .		'myPlayer.jPlayer("option", "muted", false);'
						   .		'myPlayer.jPlayer("option", "volume", ui.value);'
						   .	'}'
						   . '});'
						. '});</script>';

		} else {
			$playerScript = '<div>Error: jPlayer player type not supported</div>';
		}

		return $playerScript;
	}

}
