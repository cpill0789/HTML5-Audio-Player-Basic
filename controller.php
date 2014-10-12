<?php
namespace Concrete\Package\Html5AudioPlayerBasic;

use Asset;
use AssetList;
use BlockType;
use Package;

class Controller extends Package
{

    protected $pkgHandle = 'html5_audio_player_basic';

    protected $appVersionRequired = '5.7.0.4';

    protected $pkgVersion = '1.9.1';

    public function getPackageName()
    {
        return t("HTML5 Audio Player Basic");
    }

    public function getPackageDescription()
    {
        return t("Installs the HTML5 Audio Player Basic block.");
    }

    public function install()
    {
        $pkg = parent::install();

        // install block
        BlockType::installBlockTypeFromPackage('html5_audio_player_basic', $pkg);
    }

    public function on_start()
    {
        $al = AssetList::getInstance();

        $al->register('javascript', 'jplayer', 'js/jplayer.min.js', array(
            'version' => '2.7.0',
            'position' => Asset::ASSET_POSITION_FOOTER,
            'minify' => false,
            'combine' => false
        ), $this);
    }
}
