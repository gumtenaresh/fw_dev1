function textlimit(characterLimit,remainingchars,textarea)
{
        $(remainingchars).html(characterLimit);
	$(textarea).bind('keyup', function(){
		var charactersUsed = $(this).val().length;

		if(charactersUsed > characterLimit){
			charactersUsed = characterLimit;
			$(this).val($(this).val().substr(0, characterLimit));
			$(this).scrollTop($(this)[0].scrollHeight);
		}
		var charactersRemaining = characterLimit - charactersUsed;
		$(remainingchars).html(charactersRemaining);
	});
}