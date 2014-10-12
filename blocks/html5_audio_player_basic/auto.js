var JPAudioPlayerBlock = {
	validate:function(){
		var failed=0;
		if ($("#ccm-jPAudioPlayerBlock-singleAudio input[name=fID]").val() <= 0) {
			alert(ccm_t('choose-file'));
			failed = 1;
		}
		if(failed){
			ccm_isBlockError=1;
			return false;
		}
		return true;
	}
}

ccmValidateBlockForm = function() { return JPAudioPlayerBlock.validate(); }

/* Initialize Title Source Picker */
$(function(){
	var $select = $("#ccm-jPAudioPlayerBlock-singleAudio select[name=metaCategory]");
	var $title = $('#ccm-jPAudioPlayerBlock-singleAudio #title');

	if ($select.val() == 'NONE') {
		$title.show();
	} else {
		$title.hide();
	}
	$select.change(function(){
		if (this.value == 'NONE') {
			$title.show();
		} else {
			$title.hide();
		}
	});
});

/* Initialize Tabs */
$(function () {
	$('#audioBasicTabs a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
	$('#audioBasicTabs a:first').tab('show');
});

/* form volume slide */
$(function() {
	$( "#ccm-jPAudioPlayerBlock-volume #ccm-jPAudioPlayerBlock-volumeSlider").slider({
	  min: 0,
	  max: 100,
	  value: $( "#ccm-jPAudioPlayerBlock-volume input" ).val(),
	  slide: function( event, ui ) {
		$( "#ccm-jPAudioPlayerBlock-volume input" ).val(ui.value);
		$( "#ccm-jPAudioPlayerBlock-volume #volumeLevel" ).html(ui.value);
	  }
	});
	$( "#ccm-jPAudioPlayerBlock-volume input" ).val( $( "#ccm-jPAudioPlayerBlock-volume #ccm-jPAudioPlayerBlock-volumeSlider" ).slider( "value" ) );
	$( "#ccm-jPAudioPlayerBlock-volume #volumeLevel" ).html( $( "#ccm-jPAudioPlayerBlock-volume #ccm-jPAudioPlayerBlock-volumeSlider" ).slider( "value" ) );
});
$( ".launch-tooltip" ).tooltip({placement: 'right'});
