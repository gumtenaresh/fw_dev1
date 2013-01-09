var colCount = 0;
var colWidth = 300;
var margin = 20;
var spaceLeft = 0;
var windowWidth = 0;
var blocks = [];

$(function(){
	$(window).resize(setupBlocks);
});

function setupBlocks(colWidth) {//alert(colWidth);
	windowWidth = $(window).width()-250;
	blocks = [];

	// Calculate the margin so the blocks are evenly spaced within the window
	colCount = Math.floor(windowWidth/(colWidth+margin*2));//alert(colCount);
	spaceLeft = (windowWidth - ((colWidth*colCount)+margin*2)) / 2;
	spaceLeft -= margin;
	
	for(var i=0;i<colCount;i++){
		blocks.push(margin);
	}
	positionBlocks();
}

function positionBlocks() {
	$('.block').each(function(i){
		var min = Array.min(blocks);
		var index = $.inArray(min, blocks);
		var leftPos = margin+(index*(colWidth+margin));
		$(this).css({
			'left':parseInt(parseInt(leftPos)+parseInt(spaceLeft))+'px',
			'top':parseInt(parseInt(min)+parseInt(100))+'px'
		});//alert(leftPos+spaceLeft+" -- "+parseInt(parseInt(min)+parseInt(100)));
		blocks[index] = min+$(this).outerHeight()+margin;
	});	
}

// Function to get the Min value in Array
Array.min = function(array) {
    return Math.min.apply(Math, array);
};
