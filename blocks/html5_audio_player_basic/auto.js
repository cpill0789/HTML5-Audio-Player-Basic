var JPAudioPlayerBlock = {
	validate:function(){
		var failed=0;
		if ($("#audioBlock-singleAudio input[name=fID]").val() <= 0) {
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

$(function(){
	/* Initialize Title Source Picker */
	var $select = $("#audioBlock-singleAudio select[name=metaCategory]");

	var checkTitle = function ($select) {
		if ($select.val() == 'NONE') {
			$('#audioBlock-singleAudio #title').show();
		} else {
			$('#audioBlock-singleAudio #title').hide();
		}
	};

	checkTitle($select);

	$select.change(function(){
		checkTitle($select);
	});

	/* form volume slide */
	$( "#audioBlock-volume #audioBlock-volumeSlider").slider({
	  min: 0,
	  max: 100,
	  value: $( "#audioBlock-volume input" ).val(),
	  slide: function( event, ui ) {
		$( "#audioBlock-volume input" ).val(ui.value);
		$( "#audioBlock-volume #volumeLevel" ).html(ui.value);
	  }
	});
	$( "#audioBlock-volume input" ).val( $( "#audioBlock-volume #audioBlock-volumeSlider" ).slider( "value" ) );
	$( "#audioBlock-volume #volumeLevel" ).html( $( "#audioBlock-volume #audioBlock-volumeSlider" ).slider( "value" ) );

	/* tooltip */
	$( ".launch-tooltip" ).tooltip({placement: 'right'});
});
