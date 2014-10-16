<?php
namespace Concrete\Package\Html5AudioPlayerBasic\Block\Html5AudioPlayerBasic;

use Concrete\Core\Block\BlockController;
use File;
use Loader;

defined('C5_EXECUTE') or die("Access Denied.");

class Controller extends BlockController
{

    protected $btTable = "btHtml5AudioPlayerBasic";

    protected $btInterfaceWidth = "600";

    protected $btInterfaceHeight = "500";

    protected $btCacheBlockRecord = true;

    protected $btCacheBlockOutput = true;

    protected $btCacheBlockOutputOnPost = true;

    protected $btCacheBlockOutputForRegisteredUsers = false;

    protected $btCacheBlockOutputLifetime = CACHE_LIFETIME;

    protected $btDefaultSet = 'multimedia';

    public function getBlockTypeName()
    {
        return t('HTML5 Audio Player Basic');
    }

    public function getBlockTypeDescription()
    {
        return t('Basic HTML5/flash audio player built using the jPlayer jQuery plugin');
    }

    public function getJavaScriptStrings()
    {
        return array(
            'choose-file' => t('Choose an Audio File')
        );
    }

	private function getFileInfo($f, $fallback = null)
    {
        if (!is_object($f)) {
            return false;
        }

        $info = array();

        $ext = $this->processExtension($f->getExtension());
        $info[$ext] = $f->getRelativePath();
        $info['downloadURL'] = $f->getDownloadURL();
        $info['title'] = $f->getTitle();
        $info['description'] = $f->getDescription();
        $info['formats'] = array($ext);
        $info['free'] = (bool) $this->free;

        if (is_object($fallback)) {
            $ext2 = $this->processExtension($fallback->getExtension());
            $info[$ext2] = $fallback->getRelativePath();
            $info['formats'][] = $ext2;
        }

        return $info;
    }

    private function processExtension($ext) {
        $ext = strtolower($ext);
        if ($ext == 'ogg') {
            $ext  = 'oga';
        }
        return $ext;
    }

    public function view()
    {
        $this->requireAsset('javascript', 'jquery');
        $this->requireAsset('javascript', 'jplayer');
        $this->requireAsset('css', 'font-awesome');

		$f = File::getByID(intval($this->fID));
		$fallback = File::getByID(intval($this->secondaryfID));

		$fileInfo = $this->getFileInfo($f, $fallback);

		if ($this->titleSource == 'DESCRIPTION') {
			$fileInfo['title'] = $fileInfo['description'];
		} elseif ($this->titleSource == 'CUSTOM') {
			$fileInfo['title'] = $this->title;
		}

		$options = array (
			'volume' => $this->initialVolume / 100,
			'autoPlay' => (bool) $this->autoPlay,
			'loop' => (bool) $this->loopAudio,
			'pauseOthers' => (bool) $this->pauseOthers,
			'swfPath' => REL_DIR_PACKAGES . '/html5_audio_player_basic/flash/',
			'supplied' => implode(', ', $fileInfo['formats']),
            'wmode' => 'window',
            'cssSelectorAncestor' => '#jp_container_' . $this->bID,
            'files' => $fileInfo
		);

		$json = \Core::make('helper/json');
		$this->set('options', $json->encode($options));
    }

    public function add()
    {
        $this->set('initialVolume', 80);
    }

    public function save($data)
    {
        $data['loopAudio'] = intval($data['loopAudio']);
        $data['autoPlay'] = intval($data['autoPlay']);
        $data['pauseOthers'] = intval($data['pauseOthers']);
        parent::save($data);
    }

    public function getPlayerJavascript($playerType)
    {
        $jh = \Core::make('helper/json');

        // calculate values for player initialization
        $isLooped = ($this->loopAudio == 1) ? "true" : "false";
        $isAuto = ($this->autoPlay == 1) ? "true" : "false";
        $volume = $this->initialVolume / 100;

        $blockID = $this->bID;
        $defaultAncestor = 'jp_container_' . $this->bID;
        $playEvent = '';

        $fileTypes = $fileType;

        $f = File::getByID($this->fID);
        $info = $this->getFileInfo($f);

        if ($this->secondaryfID > 0) {
            $f2 = File::getByID($this->secondaryfID);
            $info2 = $this->getFileInfo($f2);
            array_merge($info, $info2);
        }

        $fileTypes = implode(', ', array_keys($info));

        $info['title'] = $this->title;

        if ($this->metaCategory == 'TITLE') {
            $info['title'] = $f->getTitle();
        } elseif ($this->metaCategory == 'DESCRIPTION') {
            $info['title'] = $f->getDescription();
        }

        $fileInfo = $jh->encode($info);

        // Player event callback default values
        $readyEvent = '$(this).jPlayer("setMedia",' . $fileInfo . ');';

        if ($this->autoPlay) {
            $readyEvent .= '$(this).jPlayer("play");';
        }

        if ($this->pauseOthers) {
            $playEvent = '$(this).jPlayer("pauseOthers");';
        }

        $defaultStandard = 'swfPath: "' . REL_DIR_PACKAGES . '/html5_audio_player_basic/flash/",'
                         . 'supplied: "' . $fileTypes . '",'
                         . 'wmode: "window",'
                         . 'volume: ' . $volume . ','
                         . 'loop: ' . $isLooped . ',';

        // Assemble Player Javascript
        $playerScript = '<script type="text/javascript">$(document).ready(function(){';

        if (substr($playerType, 0, 6) === 'simple') {

            // Javascript for simple player
            $playerScript .= '$("#jquery_jplayer_' . $blockID . '").jPlayer({'
                           . 'ready: function (event) { ' . $readyEvent . '},'
                           . 'play: function(event) {'
                               . $playEvent
                               . '$("#' . $defaultAncestor . ' .jp-controls .jp-play").hide();'
                               . '$("#' . $defaultAncestor . ' .jp-controls .jp-stop").show();'
                           . '},'
                           . 'pause: function (event) {' . '$("#' . $defaultAncestor . ' .jp-controls .jp-play").show();'
                               . '$("#' . $defaultAncestor . ' .jp-controls .jp-stop").hide();'
                           . '},'
                           . 'ended: function () {'
                               . '$("#' . $defaultAncestor . ' .jp-controls .jp-play").show();'
                               . '$("#' . $defaultAncestor . ' .jp-controls .jp-stop").hide();'
                           . '},'
                           . $defaultStandard
                           . 'cssSelectorAncestor: "#' . $defaultAncestor . '"'
                           . '});';

        } elseif ($playerType == 'circle_player') {

            // Javascript for Circle player
            $playerScript .= 'var myCirclePlayer = new CirclePlayer("#jquery_jplayer_' . $blockID . '",' . $fileInfo . ', {'
                                . $defaultStandard
                                . 'cssSelectorAncestor: "#cp_container_' . $blockID . '"';
            if ($this->autoPlay) {
                $playerScript .= ', autoPlay: true';
            }

            if ($this->pauseOthers) {
                $playerScript .= ', pauseOthers: true';
            }

            $playerScript .= '});';

        } else {

            if (substr($playerType, 0, 7) === 'default') {
                $defaultStandard .= 'timeupdate: function(event) {'
                                    . '$("#jp_container_' . $blockID . ' .jp-time-wrapper").css("left", event.jPlayer.status.currentPercentAbsolute+"%");'
                                  . '},'
                                  . 'pause: function(event) {' . '$("#jp_container_' . $blockID . ' .jp-current-time").fadeOut();' . '}, '
                                  . 'ended: function(event) {' . '$("#jp_container_' . $blockID . ' .jp-current-time").hide();' . '}, ';
                $playEvent .= "$('#jp_container_$blockID .jp-current-time').fadeIn();";
            }

            // Javascript for standard jPlayer skins (Blue Monday and Pink Flag)
            $playerScript .= '$("#jquery_jplayer_' . $blockID . '").jPlayer({'
                                . 'ready: function (event) { ' . $readyEvent . '},'
                                . 'play: function(event) {' . $playEvent . '},'
                                . $defaultStandard
                                . 'cssSelectorAncestor: "#' . $defaultAncestor . '"'
                           . '});';
        }
        if ($info['title'] == '') {
            $playerScript .= '$("#' . $defaultAncestor . ' .jp-details").hide();';
        }
        $playerScript .= '});</script>';
        return $playerScript;
    }
}
