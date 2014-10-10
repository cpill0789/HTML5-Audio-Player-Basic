<?php
namespace Concrete\Package\Html5AudioPlayerBasic\Block\Html5AudioPlayerBasic;

use Concrete\Core\Block\BlockController;
use Loader;
use File;
// use Concrete\Core\Http\Service\Json;
class Controller extends BlockController
{

    protected $btTable = "btHtml5AudioPlayerBasic";

    protected $btInterfaceWidth = "500";

    protected $btInterfaceHeight = "400";

    protected $btCacheBlockRecord = true;

    protected $btCacheBlockOutput = true;

    protected $btCacheBlockOutputOnPost = true;

    protected $btCacheBlockOutputForRegisteredUsers = false;

    protected $btCacheBlockOutputLifetime = CACHE_LIFETIME;

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

    function view()
    {
        $this->requireAsset('javascript', 'jquery');
        $this->requireAsset('javascript', 'jplayer');

        $theme = '';
        $block = $this->getBlockObject();
        if (is_object($block)) {
            $theme = $block->getBlockFilename();
        }
        if ($theme == '') {
            $theme = 'bootstrap';
        }

        $this->set('script', $this->getPlayerJavascript($theme));
    }

    public function save($data)
    {
        $data['loopAudio'] = intval($data['loopAudio']);
        $data['autoPlay'] = intval($data['autoPlay']);
        $data['pauseOthers'] = intval($data['pauseOthers']);
        $data['useMetaTitle'] = intval($data['useMetaTitle']);
        parent::save($data);
    }

    private function getFileInfo($f)
    {
        $fileType = strtolower($f->getExtension());

        if ($fileType == 'ogg') {
            $fileType = 'oga';
        }

        $relPath = $f->getRelativePath();

        return array(
            $fileType => $relPath
        );
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
        $defaultPlay = '';

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
        } else
            if ($this->metaCategory == 'DESCRIPTION') {
                $info['title'] = $f->getDescription();
            }

        $fileInfo = $jh->encode($info);

        // Player event callback default values
        $defaultReady = '$(this).jPlayer("setMedia",' . $fileInfo . ');' . '$("#' . $defaultAncestor . ' .jp-current-title").html($(this).data("jPlayer").status.media.title);';

        if ($this->autoPlay) {
            $defaultReady .= '$(this).jPlayer("play");';
        }

        if ($this->pauseOthers) {
            $defaultPlay = '$(this).jPlayer("pauseOthers");';
        }
        $defaultStandard = 'swfPath: "' . REL_DIR_PACKAGES . '/html5_audio_player_basic/flash/",' . 'supplied: "' . $fileTypes . '",' . 'wmode: "window",' . 'volume: ' . $volume . ',' . 'loop: ' . $isLooped . ',';

        // Assemble Player Javascript
        $playerScript = '<script type="text/javascript">$(document).ready(function(){';

        if ($playerType == 'basic_purple') {

            // Javascript for Basic player
            $playerScript .= '$("#jquery_jplayer_' . $blockID . '").jPlayer({' . 'ready: function (event) { ' . $defaultReady;
            if ($title == '') {
                $playerScript .= '$("#' . $defaultAncestor . ' .jp-title").hide();';
            }
            $playerScript .= '},' . 'play: function(event) {' . $defaultPlay . '$("#' . $defaultAncestor . ' .jp-controls .jp-play").hide();' . '$("#' . $defaultAncestor . ' .jp-controls .jp-stop").show();' . '},' . 'pause: function (event) {' . '$("#' . $defaultAncestor . ' .jp-controls .jp-play").show();' . '$("#' . $defaultAncestor . ' .jp-controls .jp-stop").hide();' . '},' . 'ended: function () {' . '$("#' . $defaultAncestor . ' .jp-controls .jp-play").show();' . '$("#' . $defaultAncestor . ' .jp-controls .jp-stop").hide();' . '},' . $defaultStandard . 'cssSelectorAncestor: "#' . $defaultAncestor . '"' . '});' . '});</script>';
        } elseif ($playerType == 'circle_player') {

            // Javascript for Circle player
            $playerScript .= 'var myCirclePlayer = new CirclePlayer("#jquery_jplayer_' . $blockID . '",' . $fileInfo . ',' . '{' . $defaultStandard . 'cssSelectorAncestor: "#cp_container_' . $blockID . '"' . '});' . '});</script>';
        } else {
            if ($playerType == 'bootstrap') {
                $defaultStandard .= "timeupdate: function(event) {
										$('#jp_container_$blockID .jp-time-wrapper').css('left', event.jPlayer.status.currentPercentAbsolute+'%');
									}, pause: function(event) {
										$('#jp_container_$blockID .jp-current-time').fadeOut();
									}, ended: function(event) {
										$('#jp_container_$blockID .jp-current-time').hide();
									}, ";
                $defaultPlay .= "$('#jp_container_$blockID .jp-current-time').fadeIn();";
            }

            // Javascript for standard jPlayer skins (Blue Monday and Pink Flag)
            $playerScript .= '$("#jquery_jplayer_' . $blockID . '").jPlayer({' . 'ready: function (event) { ' . $defaultReady;
            if ($title == '') {
                $playerScript .= '$("#' . $defaultAncestor . ' .jp-title").hide();';
            }
            $playerScript .= '},' . 'play: function(event) {' . $defaultPlay . '},' . $defaultStandard . 'cssSelectorAncestor: "#' . $defaultAncestor . '"' . '});' . '});</script>';
        }

        return $playerScript;
    }
}
