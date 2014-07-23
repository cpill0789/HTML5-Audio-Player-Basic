<?php
namespace Concrete\Package\Html5AudioPlayerBasic\Block\Html5AudioPlayerBasic;

use \Concrete\Core\Block\BlockController;
use \Concrete\Core\Http\ResponseAssetGroup;
use UserInfo;
use Loader;
use File;
use Config;
use Page;
use View;
use Concrete\Core\Asset\AssetList as AssetList;
use Concrete\Core\Asset\Asset as Asset;

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

    function getBlockPath()
    {
        $uh = Loader::helper('concrete/urls');
        return $uh->getBlockTypeAssetsURL($this->getBlockObject()
            ->getBlockTypeObject());
    }

    function view()
    {
        
        // $al = AssetList::getInstance();
        // $al->register('javascript', 'jplayer', 'js/jquery.jplayer.min.js', array('minify' => false, 'combine' => false), 'html5_audio_player_basic');
        // $al->registerGroup('jplayer', array(
        // array('javascript', 'jplayer')
        // ));
        $html = Loader::helper('html');
        $uh = Loader::helper('concrete/urls');
        
        $this->addHeaderItem($html->javascript('jquery.js'));
        // $this->addHeaderItem($html->css('jquery.ui.css'));
        // $this->addHeaderItem($html->javascript('jquery.ui.js'));
        $this->addFooterItem($html->javascript('jplayer.min.js', 'html5_audio_player_basic'));
        
        // $r = ResponseAssetGroup::get();
        // $r->requireAsset('jplayer');
    }

    public function save($data)
    {
        $data['loopAudio'] = intval($data['loopAudio']);
        $data['autoPlay'] = intval($data['autoPlay']);
        $data['pauseOthers'] = intval($data['pauseOthers']);
        $data['useMetaTitle'] = intval($data['useMetaTitle']);
        parent::save($data);
    }

    function getPlayerJavascript($playerType)
    {
        
        // calculate values for player initialization
        $isLooped = ($this->loopAudio == 1) ? "true" : "false";
        $isAuto = ($this->autoPlay == 1) ? "true" : "false";
        $volume = $this->initialVolume / 100;
        
        $basePath = $this->getBlockPath();
        
        $blockID = $this->bID;
        $defaultAncestor = 'jp_container_' . $blockID;
        
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
            $fileTypes .= ', ' . $fileType2;
        }
        
        if ($this->metaCategory == 'TITLE') {
            $title = $f->getTitle();
        }
        if ($this->metaCategory == 'DESCRIPTION') {
            $title = $f->getDescription();
        }
        
        $fileInfo = '{' . $fileType . ':"' . $relPath . '",' . 'title:"' . $title . '"';
        if ($this->secondaryfID > 0) {
            $fileInfo .= ',' . $fileType2 . ':"' . $relPath2 . '"';
        }
        $fileInfo .= '}';
        
        // Player event callback default values
        $defaultReady = '$(this).jPlayer("setMedia",' . $fileInfo . ');' . '$("#' . $defaultAncestor . ' .jp-current-title").html($(this).data("jPlayer").status.media.title);';
        if ($this->autoPlay) {
            $defaultReady .= '$(this).jPlayer("play");';
        }
        
        if ($this->pauseOthers) {
            $defaultPlay = '$(this).jPlayer("pauseOthers");';
        } else {
            $defaultPlay = '';
        }
        
        $defaultStandard = 'swfPath: "' . REL_DIR_PACKAGES . '/html5_audio_player_basic/flash/",' . 'supplied: "' . $fileTypes . '",' . 'wmode: "window",' . 'volume: ' . $volume . ',' . 'loop: ' . $isLooped . ',';
        
        // Assemble Player Javascript
        $playerScript = '<script type="text/javascript">$(document).ready(function(){';
        
        if ($playerType == 'STANDARD' || $playerType == 'BOOTSTRAP') {
            
            if ($playerType == 'BOOTSTRAP') {
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
        } elseif ($playerType == 'BASIC') {
            
            // Javascript for Basic player
            $playerScript .= '$("#jquery_jplayer_' . $blockID . '").jPlayer({' . 'ready: function (event) { ' . $defaultReady;
            if ($title == '') {
                $playerScript .= '$("#' . $defaultAncestor . ' .jp-title").hide();';
            }
            $playerScript .= '},' . 'play: function(event) {' . $defaultPlay . '$("#' . $defaultAncestor . ' .jp-controls .jp-play").hide();' . '$("#' . $defaultAncestor . ' .jp-controls .jp-stop").show();' . '},' . 'pause: function (event) {' . '$("#' . $defaultAncestor . ' .jp-controls .jp-play").show();' . '$("#' . $defaultAncestor . ' .jp-controls .jp-stop").hide();' . '},' . 'ended: function () {' . '$("#' . $defaultAncestor . ' .jp-controls .jp-play").show();' . '$("#' . $defaultAncestor . ' .jp-controls .jp-stop").hide();' . '},' . $defaultStandard . 'cssSelectorAncestor: "#' . $defaultAncestor . '"' . '});' . '});</script>';
        } elseif ($playerType == 'CIRCLE') {
            
            // Javascript for Circle player
            $playerScript .= 'var myCirclePlayer = new CirclePlayer("#jquery_jplayer_' . $blockID . '",' . $fileInfo . ',' . '{' . $defaultStandard . 'cssSelectorAncestor: "#cp_container_' . $blockID . '"' . '});' . '});</script>';
        } else {
            $playerScript = '<div>Error: jPlayer player type not supported</div>';
        }
        
        return $playerScript;
    }
}
