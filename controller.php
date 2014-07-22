<?php
	defined('C5_EXECUTE') or die(_("Access Denied."));

	class Html5AudioPlayerBasicPackage extends Package {

		protected $pkgHandle = 'html5_audio_player_basic';
		protected $appVersionRequired = '5.7';
		protected $pkgVersion = '1.2.0';

		public function getPackageDescription() {
			return t("Installs the HTML5 Audio Player Basic block.");
		}

		public function getPackageName() {
			return t("HTML5 Audio Player Basic");
		}

		public function install() {
			$pkg = parent::install();

			// install block
			BlockType::installBlockTypeFromPackage('html5_audio_player_basic', $pkg);
		}
	}
