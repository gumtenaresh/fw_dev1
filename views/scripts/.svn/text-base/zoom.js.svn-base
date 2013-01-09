jQuery(function(){ // on page DOM load
    $('.staticUL').alternateScroll();
})
jQuery(function(){ // on page DOM load
    $('#demo1').alternateScroll();
})
jQuery(function(){ // on page DOM load
    $('#footerSlideContent').alternateScroll();
})
    
jQuery(function(){ // on page DOM load
    $('.share_fanwire_checkbox').alternateScroll();
})
   
jQuery(function(){ // on page DOM load
    $('#comment_container').alternateScroll();
})      
$(document).ready(function(){    
    //filters
    $(".fW_btns ul li a").click(function(){
     
        if($("#F"+this.id).val()=='1'){
            
            $("#F"+this.id).val('0');
        }else{
            $("#F"+this.id).val('1');
        }
        //   alert($("#F"+this.id).val());
        $('#filtersForm').submit();
    });
    //show less/more.
    $(".view_all_fan").click(function (){
        $(".fanslist_more").toggle();
        var str= $(this).text();
        $(this).text(str.indexOf("all") >0 ? str.replace("all", "less") : str.replace("less", "all"));
    });
});
function zoomTheContent(percentage,url,block1,block2,block3){
    
    var newWidth = percentage*150/40;
   
    var dataString='';
    newWidth = Math.floor(newWidth);
    
    $("#mainBlock").children("div").width(newWidth);
    var max_size = newWidth;
    
    var c=0;
    $(block1).each(function(i) {
        //c=c+1;
        //         var height = $(block2).val();
        //         var width = $(block3).val();
        //         var w = newWidth;
        //         var h =  Math.floor((height/width)*newWidth);
//        if ($(this).height() > $(this).width()) {
        //            var h = max_size;
        //            var w = Math.ceil($(this).width() / $(this).height() * max_size);
        //        } else {
        //            var w = max_size;
        //            var h = Math.ceil($(this).height() / $(this).width() * max_size);
        //        }
        var w = max_size;
        var h = Math.ceil($(this).height() / $(this).width() * max_size);
        dataString ='value='+percentage+'&width='+w+'&height='+h;
        $(this).css({
            height: h, 
            width: w
        });  

    });
    //write the code to save the zoom content
    $.ajax({
        type: "POST",
        url:url,
        data: dataString,
        success: function(response) { 
            //alert(response);
            $(window).trigger("load");
             
        //  location.reload();
            
             
        }
    });
}

$(document).keyup(function(e) {

    if (e.keyCode == 27) { 
        $('.web_dialog').hide();
        $('.sub3').hide();	
    }   // esc
});
(function($){
    $(window).load(function(){
        $(".comments").mCustomScrollbar();
    });
})(jQuery);     