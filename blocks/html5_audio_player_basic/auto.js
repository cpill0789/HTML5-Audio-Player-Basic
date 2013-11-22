var JPAudioPlayerBlock = {
	init:function(){},
	
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

/* Initialize Tabs */
$(document).ready(function(){
   $('#tabset a').click(function(ev){
	var tab_to_show = $(this).attr('href');
	$('#tabset li').
	  removeClass('active').
	  find('a').
	  each(function(ix, elem){
		var tab_to_hide = $(elem).attr('href');
		$(tab_to_hide).hide();
	  });
	$(tab_to_show).show();
	$(this).parent('li').addClass('active');
	return false;
  }).first().click();
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