# Format package for Concrete5 marketplace submission

rm -r build
mkdir -p build/html5_audio_player_basic/
cp -r blocks ./build/html5_audio_player_basic/
cp -r flash ./build/html5_audio_player_basic/
cp -r js ./build/html5_audio_player_basic/
cp controller.php ./build/html5_audio_player_basic/
cp icon.png ./build/html5_audio_player_basic/
cp LICENSE.TXT ./build/html5_audio_player_basic/
cp CHANGELOG ./build/html5_audio_player_basic/

find ./build/html5_audio_player_basic/ -name '*.DS_Store' -type f -delete
find ./build/html5_audio_player_basic/ -name '*.gitignore' -type f -delete
find ./build/html5_audio_player_basic/ -name '*.less' -type f -delete

find ./build/html5_audio_player_basic/ -name '*.php' -type f -exec chmod 644 {} \;
find ./build/html5_audio_player_basic/ -name '*.css' -type f -exec chmod 644 {} \;
find ./build/html5_audio_player_basic/ -name '*.js' -type f -exec chmod 644 {} \;
find ./build/html5_audio_player_basic/ -name '*.png' -type f -exec chmod 644 {} \;
find ./build/html5_audio_player_basic/ -name '*.jpg' -type f -exec chmod 644 {} \;
find ./build/html5_audio_player_basic/ -name '*.swf' -type f -exec chmod 644 {} \;

rm -rf `find ./build/html5_audio_player_basic/ -type d -name .git`
rm ./build/basic$1.zip
cd build
zip -r ./basic$1.zip ./html5_audio_player_basic/
rm -r ./html5_audio_player_basic
