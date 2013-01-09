/*********************added for searching ********************/
function checklength(){
    return true;
	
    var e=document.forms["searchform"]["searchbox"].value;
    if(e.length<4){
        alert('Keyword must contain minumum 4 characters');
        return false;
    }else{
        return true;
    }
}

$(function() {

    $('#searchbox').click(function(e) {
        if($(this).val()=='Search something..'){
            $(this).val('');
        }
    });
	
    $('#searchbox').keyup(function(e) {	
        lookup($(this).val());
    });
	
    $('#searchbox').blur(function() {
        $('#suggestions').fadeOut();
        if($(this).val()==''){
            $(this).val('Search something..');
        }
    });	
    function lookup(inputString) {

        if(inputString.length == 0) {
            $('#suggestions').fadeOut();
        } else {

            $.post(siteurl+"suggestion.php", {
                search: ""+inputString+""
            }, function(data) {
                $('#suggestions').fadeIn();
                $('#suggestions').html(data);
            });
		
        }
    }	
	
});
$(function() {
    $('#all_c').click(function(e) {
        $('#searchcriteria').val('all');
        $('#searchform').submit();
    });
    $('#profiles_c').click(function(e) {
        $('#searchcriteria').val('profiles');
        $('#searchform').submit();
    });	
    $('#media_c').click(function(e) {
        $('#searchcriteria').val('media');
        $('#searchform').submit();
    });	
    $('#social_c').click(function(e) {
        $('#searchcriteria').val('social');
        $('#searchform').submit();
    });	
    $('#mycollection_c').click(function(e) {
        $('#searchcriteria').val('mycollection');
        $('#searchform').submit();
    });					
});
/*********************added for searching  end ********************/

function cleanFeilds(){
    $('#current_password').val('');
    $('#new_password').val('');
    $('#verify_password').val('');
    Popup.hide('changepassword');
}
function textlimits(re,te){
    var characterLimit = 140;
    var remainingchars=re;
    var textarea =te;

    textlimit(characterLimit,remainingchars,textarea);
} 
function textlimit(characterLimit,remainingchars,textarea)
{
    $(remainingchars).html(characterLimit+" characters remaining.");
    $(textarea).bind('keyup', function(){
        var charactersUsed = $(this).val().length;

        if(charactersUsed > characterLimit){
                
            charactersUsed = characterLimit;
            $(this).val($(this).val().substr(0, characterLimit));
            $(this).scrollTop($(this)[0].scrollHeight);
        }
        var charactersRemaining = characterLimit - charactersUsed;
        $(remainingchars).html(charactersRemaining+" characters remaining.");
    });
}
    
function setVisibility(id, visibility) {
    if(document.getElementById(id).style.display=="inline"){
        document.getElementById(id).style.display ='none';
    //document.getElementById(id).style.display =visibility;
    }else{
        document.getElementById(id).style.display ='inline';
    }
}
    
function collect(fanid,type,url,siteurl){
    var dataString ='fwrid='+fanid+'&type='+type; 
    $.ajax({
        type: "POST",
        url:url,
        data: dataString,
        success: function(response) {
            if(response ==1 || response ==2){
                $('#collect'+fanid+type).html('<a class="collect_img_btn" href="javascript:void(0);" onclick="collect(\''+fanid+'\',\''+type+'\',\''+url+'\',\''+siteurl+'\');"><span class="icon" style="background: none repeat scroll 0 0 green;"><img src="'+siteurl+'views/images/collected_icon.png"/></span><span style="background: none repeat scroll 0 0 green;">collected</span></a>');	
            }       
            else{
                $('#collect'+fanid+type).html('<a class="collect_img_btn" href="javascript:void(0);" onclick="collect(\''+fanid+'\',\''+type+'\',\''+url+'\',\''+siteurl+'\');"><span class="icon"><img src="'+siteurl+'views/images/collect_icon.png"/></span> <span>collect</span></a>');
            }
        }
    });
    return false;
}
    

//this function is related to collecting and discollecting the blocks in p20,p21,p22
function collect_p(fanid,type){
    var dataString ='fwrid='+fanid+'&type='+type; 
    $.ajax({
        type: "POST",
        url:"{/literal}{$smarty.const.SITE_URL}{literal}collect",
        data: dataString,
        success: function(response) {
                       
            if(response ==1 || response ==2){
                $("#collect"+fanid+type).removeClass('collectred');
                $("#collect"+fanid+type).addClass('collectgreen');
                $("#collect"+fanid+type).removeAttr( 'onClick');
                $("#collect"+fanid+type).attr( 'onClick', 'collect('+fanid+','+type+')'); 
            }       
            else{
                $("#collect"+fanid+type).removeClass('collectgreen');
                $("#collect"+fanid+type).addClass('collectred');
                $("#collect"+fanid+type).removeAttr( 'onClick');
                $("#collect"+fanid+type).attr( 'onClick', 'collect('+fanid+','+type+')'); 
            }
        }
    });
    return false;
}
            
function getFanwireFans(fanid,url){
    var dataString ='fwrid='+fanid;
    $.ajax({
        type: "POST",
        url:url,
        data: dataString,
        success: function(response) {
            $('#fanwire_fans'+fanid).html(response);

        }
    });
    return false;
}

//to share the content to FB or twitter
function Share(fanid,fanlink){
   
          
    if(document.getElementById('facebook'+fanid).checked ==true){
           
        window.open('http://www.facebook.com/sharer.php?u='+fanlink,'','width=400,height=300');
    }
    if(document.getElementById('twitter'+fanid).checked==true){
            
        window.open('https://twitter.com/intent/tweet?original_referer='+fanlink+'&url='+fanlink,'','width=400,height=300');
    }
} 
  
//this function for select all /disselect all
function checkAll(){
    $(function(){
        // add multiple select / deselect functionality
        $(".allbox").click(function () {
            $('.case').attr('checked', this.checked);
        });
        // if all checkbox are selected, check the selectall checkbox
        // and viceversa
        $(".case").click(function(){
            if($(".case").length == $(".case").length) {
                $(".allbox").attr("checked", "checked");
            } else {
                $(".allbox").removeAttr("checked");
            }

        });
    });
}
function shareTheContentToRespectNetworks(fanid,url){
    var arr = $("input[name='contacts[]']:checked").map(function() { 
        return this.value; 
    }).get();
    var email_share=document.getElementById('email_share'+fanid).checked;
    var email_addresses='';
    var emailMessage=document.getElementById('personalmessage'+fanid).value;
         
    if(email_share){
        email_addresses=document.getElementById('email_addresses'+fanid).value;
    }else{
        email_addresses='nareshgumte412@gmail.com';
    }
    var dataStringd="det="+arr+"&fwrid="+fanid+"&email_addresses="+email_addresses+"&emailMessage="+emailMessage+"&email_share="+email_share;
        
    $.ajax({
        type: "POST",
        url:url,
        data: dataStringd,
        beforeSend: function(){
            $('#shareLoader'+fanid).show();
        },
        complete: function(){
            $('#shareLoader'+fanid).hide();
        // $('#light1'+fanid).hide(); 
        },
        success: function(resa) {
                
            $('#errorShare'+fanid).html(resa);
            if(resa==101){
                      
                //     $('#errorShare'+fanid).html(resa);
                
                $('#light1'+fanid).hide();
                $('#sharePopBlock').hide();
            } 
               
        },
        error:function(data){
            console.log(data);
        }
    });
       
 
}
     
  
function ShowDialog(modal,id,type)
{
    document.getElementById("type").value = type;
    $("#dialog"+id+type).toggle();
    $('#commentsToShow'+id+type).hide();
}
    
function HideDialog(id,type)    
{
    $('#commentsToShow'+id+type).hide();
    $("#dialog"+id+type).hide();
}
function showSend(id){
    var type = document.getElementById("type").value;
    $("#comm"+id+type).show();
    $('#commentsToShow'+id+type).show();
// document.getElementById('commentsToShow').style.display='block' ;
}
function expandtext(textArea){
    while (textArea.rows > 1 && textArea.scrollHeight < textArea.offsetHeight){
        textArea.rows--;
    }
    while (textArea.scrollHeight > textArea.offsetHeight)
    {
        textArea.rows++;
    }
    textArea.rows++
}
//comments are inserted from here
function submitForm(fanwireid,type,url,cnturl)
{        
        
    var msg = document.getElementById("msg"+fanwireid+type).value;
    if($.trim(msg)!=""){
        var dataString = 'id='+fanwireid+'&comment='+msg+'&type='+type;
        $.ajax({
            type: "POST",
            url: url,
            data: dataString,
            success: function(result) {
                //alert($.trim(result))
                if($.trim(result)=='1'){
                    alert("You can post only two comments at a time.")
                    document.getElementById("msg"+fanwireid+type).value="";
                    document.getElementById("msg"+fanwireid+type).rows=1;
                    return false;
                }
                else{ 
                    var url1 = cnturl;
                    var params = 'id='+fanwireid+'&type='+type;
                    $.ajax({
                        type: "POST",
                        url: url1,
                        data: params,
                        success: function(result) {                        
                            var rs=result.split('@@');
                            $("div #showcomment"+fanwireid+type).html('('+$.trim(rs[0])+')');
                            $("#comm"+fanwireid+type).append(rs[1]);    
                        }
                    });
                    document.getElementById("msg"+fanwireid+type).value="";
                    document.getElementById("msg"+fanwireid+type).rows=1;
                // HideDialog(fanwireid);
                }
            }
        });
    }
    else{
        alert("Please enter comment.")
        document.getElementById("msg"+fanwireid+type).value="";
        document.getElementById("msg"+fanwireid+type).rows=1;            
        return false;
    }
}
//view all comments are shown here.SAI SUDHEER
function viewAllComments(fanwireid,type,url)
{        
    var dataString = 'id='+fanwireid+'&type='+type;
    $.ajax({
        type: "POST",
        url: url,
        data: dataString,
        success: function(result) {
            // var rs=result.split('@@');
            $("#all_comments"+fanwireid+type).html(result);                 
            $("#view_all_link"+fanwireid+type).html("&nbsp;");//document.getElementById("#comm"+fanwireid).innerHTML=result;
            $("#comm"+fanwireid+type).hide();
                
                
        }
    });
}  
    
function onloadcall(fanwireid,url) {
    //alert('Exec on Onload');
    // alert(fanwireid+'==='+url);
    var params='fanwireid='+fanwireid;
    var url = url;
    $.ajax({
        type: "POST",  
        url: url,  
        data: params,  
        success: function(result) {
                
            if(result == false)
            {
                $("div #showlike"+fanwireid).animate({
                    opacity:0.4
                }, "slow");
                $("div #showdislike"+fanwireid).animate({
                    opacity:0.4
                }, "slow");
                    
            }
        }
    });
}
    
function likefanwire(fanwireid,type,url){
    var params='like=1&dislike=0&fanwireid='+fanwireid+'&type='+type;        
    $.ajax({
        type: "POST",  
        url: url,  
        data: params,  
        success: function(result) {
            if(result == false)
            {
            // alert('comment given');
            }
            else
            {
                var res = result.split("::");
                $("div #showlike"+fanwireid+type).html('('+$.trim(res[0])+')');
                $("div #showlike"+fanwireid+type).animate({
                    opacity:0.4
                }, "slow");
                $("div #showdislike"+fanwireid+type).animate({
                    opacity:0.4
                }, "slow");
                    
            }
        }
    });
}
        
function dislikefanwire(fanwireid,type,url){
    var params='like=0&dislike=1&fanwireid='+fanwireid+'&type='+type;        
    $.ajax({
        type: "POST",  
        url: url,  
        data: params,  
        success: function(result) {
            if(result == false)
            {
            // alert('comment given');
            }
            else
            {
                var res = result.split("::");
                $("div #showdislike"+fanwireid+type).html('('+$.trim(res[1])+')');
                  
                $("div #showlike"+fanwireid+type).animate({
                    opacity:0.4
                }, "slow");
                $("div #showdislike"+fanwireid+type).animate({
                    opacity:0.4
                }, "slow");
            }
        }
    });
}
function tog(id){
    	 
    $('#'+id).toggle();
}
function shareTogg(id){
        
    $('#sharePopBlock').toggle();
    $('#'+id).toggle();
       
//document.getElementById('light1{$fan.id}').style.display='block';
}
//for background popup conetent
function displayBlock(id){
 
    document.getElementById(id).style.display='block';
}
function addPerMes(id,key){//adding personal message
    if(key=='mail'){
        $('#addPmesg'+id).toggle();
    }
    else{
        $('#addPmesgFace'+id).toggle();
    }
}
function log(siteurl){
    window.location.href=siteurl+"logout";
}
    
function deactiveAccount(url,logurl){
    var pass=document.getElementById('deactivate_password').value;
    var dataStringd='submit=submit&password='+pass;
    $.ajax({
        type: "POST",
        url:url,
        data: dataStringd,
        beforeSend: function(){
            $('#loaderacc').show();
        },
        complete: function(){
            $('#loaderacc').hide();
        },
        success: function(resa) {
              
            if(resa==1){
                window.location.href=logurl;
            }else{
                Popup.showModal('errorpassword');
            }
               
        },
        error:function(data){
            console.log(data);
        }
    });
}
  
$(document).ready(function() { 
    $('#termsofservice').alternateScroll();
    $('#privacypolicy').alternateScroll();
    $("#loginfrm").validate({
        rules: {
            email:{ 
                required: true,
                email:true
            },
            password1:{ 
                required: true
            },
            password2: {
                required: true ,
                equalTo: '#password1'
            }
        } ,
        messages:{
            email_box:{
                email:'Provide valid email.'
            },
            password2:{
                equalTo:'Password and Confirm Password must be same.'
            }
        }
    })
});
    
function show_termspopup(id)
{
    $(id).show();
}
    
function close_termspopup(id)
{
    $(id).hide();
}
 
    

function showConnect_popup_id(id){
    $('#'+id).toggle();
} 
$(document).ready(function(){
    $("#showmore2").click(function(){
        $("#comment_container").toggle();
    });
});
function connect(userId,friendId,url1) {
		 
    var dataString = 'userId=' +userId+'&friendId='+friendId;

    $.ajax({
        type : "POST",
        url : url1,
        data : dataString,
        success : function(response) {
            if (response) {
                $('#con').html('<input name="" type="button" class="connect_btn" value="connect request sent"  />');
            } else {
                alert(response);
            }
        }
    });
    return false;
}
function removeCoverImage(id,urlr,siteurl) {
 
    if(confirm("Are you sure to remove the cover image?")){
        var dataString = 'userid=' +id+'&act=remove';

        $.ajax({
            type : "POST",
            url :urlr,
            data : dataString,
            success : function(response) {
                document.getElementById('bann').style.background='#636363';
                $('#pancontainer').html('<img src="'+siteurl+'views/images/Img Defalt_image.png" height="257" width="274" alt=""/><p>Don\'t forget to upload your cover image</p>');
            }
        });
        return false;
    }	 
        
}
function useFBCover(id,sessionname,cookie,cookieSer,urlfb,siteurl) {
    var sessionname=sessionname;
    var cookie=cookie;
    var cookieSer=cookieSer;
    if(sessionname!=''&& (cookie!=''||cookieSer!='')){
        var dataString = 'userid=' +id+'&act=fbcover';
        $.ajax({
            type : "POST",
            url : urlfb,
            data : dataString,
            beforeSend: function(){
                $('#fbloader').show();
            },
            complete: function(){
                $('#fbloader').hide();
                
            },
            success : function(response) {
                document.getElementById('bann').style.background='#000';
                $('#pancontainer').html('<img src="'+siteurl+'photos/'+response+'"/>');
                location.reload();
            }
        });
        return false;        
    }else{
        Popup.showModal('modalFacebook');
        return false;
    }
        
}
$(document).ready(function ()
{
    $("#btnShowSimple").click(function (e)
    {
        ShowDialogm(false);
        e.preventDefault();
    });

    $("#message_dialogue").click(function (e)
    {
        $("#output").html('');
        $("#msg").val('');
        ShowDialogm(true);
        e.preventDefault();
    });

    $("#btnClose").click(function (e)
    {
        HideDialogm();
        e.preventDefault();
    });
            
            
    $('#compose_message').ajaxForm({
        beforeSubmit: function() {
            $('#compose_message').validate({
                rules: {
                    msg:{
                        required: true
                    }
                }
            });
            return $('#compose_message').valid();
        },
        success: function(resp) {
            displayAlertMessage('message sent');
            HideDialogm();
            e.preventDefault();
        }
    });
 
            
});

function ShowDialogm(modal)
{
    $("#overlay").show();
    $("#dialog").fadeIn(300);

    if (modal)
    {
        $("#overlay").unbind("click");
    }
    else
    {
        $("#overlay").click(function (e)
        {
            HideDialogm();
        });
    }
}

function HideDialogm()
{
    $("#overlay").hide();
    $("#dialog").fadeOut(300);
}
function displayAlertMessage(message)
{
    var timeOut = 5
    jQuery('#output').text(message).fadeIn()
    jQuery('#output').css("display", "block")
    setTimeout(function() {
        jQuery('#output').fadeOut()
        jQuery('#output').css("display", "none")
    }, timeOut * 1000);
}
 
//for P20--22
function showSendp(id){
    $('#commentsToShow'+id).show();
}
function fun1(likeid, foo){
    //alert("div#showlike"+foo);
    if(likeid){
        $("div#showlike"+foo).html('(1)');
    } else {
        $("div#showdislike"+foo).html('(1)');
    }
	
    $("div#showlike"+foo).animate({
        opacity:0.4
    }, "slow");
    $("div#showdislike"+foo).animate({
        opacity:0.4
    }, "slow");
}
var foo=0;
function submitForm_p(fanwireid,url, aid, uname,type,url2,proimage)
{
    foo++; 
    var msg = document.getElementById("msg"+fanwireid).value;
    var dataString = 'id='+aid+'&comment='+msg+'&type='+type;
    $.ajax({
        type: "POST",
        url: url,
        data: dataString,
        success: function() {
            var url1 = url2;
            var params = 'id='+fanwireid+'&type='+type;
            $.ajax({
                type: "POST",
                url: url1,
                data: params,
                success: function(result) { 
                    var rs=result.split('@@');
                    $("div #showcomment"+fanwireid).html('('+rs[0]+')');
                    $("#comm"+fanwireid).prepend(rs[1]);
                }
            });
         
            document.getElementById("msg"+fanwireid).value="";
            $("#tracy").prepend('<div class="tracy"><div class="comment"><h5>few seconds ago</h5><img height="29" width="29" src="'+proimage+'" /> <div class="comment_text"><h4>'+uname+'</h4><p>'+msg+'</p></div></div></div>');
        }
    });  

}
function viewAllCommentsp(artid,url,type)
{
    var dataString = 'id='+artid+'&type='+type;
    //alert(dataString);
    $.ajax({
        type: "POST",
        url: url,
        data: dataString,
        success: function(result) {     
            $("#articlesScroll").html(result);   
            $('#viewAllLink').hide();
        }

    });
}
function addFan(id,urls){
    //	alert(id);
    var dataString ='id='+id;

    $.ajax({
        type: "POST",
        url:urls,
        data: dataString,
        success: function(response) {
            if(response==1){
                $('#n'+id).fadeOut('slow')
            }else{
                $('#message').html(response)
                .hide()
                .fadeIn(1500)
                .fadeOut(1500)
            }
        }
    });
    return false;
}
function addRelatedFan(id,urls){
    //	alert(id);
    var dataString ='id='+id;

    $.ajax({
        type: "POST",
        url:urls,
        data: dataString,
        success: function(response) {
            if(response==1){
                $('#related'+id).fadeOut('slow')
            }else{
                $('#message').html(response)
                .hide()
                .fadeIn(1500)
                .fadeOut(1500)
            }
        }
    });
    return false;
}
function nextPrev(param,fanwireid){
    $.ajax({
        type: "GET",
        url:siteurl+"photos/ajaxImages?page="+param+"&fanwireid="+fanwireid,
        success: function(response) {
            var res = response.split(":::");
            //$('#show').html(res[0]);
            $('.plr_photos').html(res[0]);
            $('#photoslinks').html(res[1]);
        //$('#navigation').html(res[1]);
        }
    });
    return false;
}
function nextArticle(param,fanwireid){
    $.ajax({
        type: "GET",
        url:siteurl+"articles/ajaxArticle?page="+param+"&fanwireid="+fanwireid,
        success: function(response) {
            // alert(response)
            var res = response.split(":::");
            $('#artDes').html(res[0]);
            var links=res[5].split("**");
            $('.content_header_right_left_arrow').html('<a href="javascript:void(0);" onclick="nextArticle(\''+links[0]+'\',\''+fanwireid+'\');"><img src="'+siteurl+'views/images/left-arrow.png" height="7" width="10" alt="" >Previous</a>');
            $('.content_header_right_right_arrow').html('<a href="javascript:void(0);" onclick="nextArticle(\''+links[1]+'\',\''+fanwireid+'\');"><img src="'+siteurl+'views/images/right_arrow.png" height="7" width="10" alt="" >Next</a>');
            $('#artBy').html(res[2]);
            $('#artFrom').html(res[1]);
            $('#artDate').html(res[3]);
            $("#articleTitle").html(res[4]);
        }
    });
    return false;
}
function nextBiography(param){
    $.ajax({
        type: "GET",
        url:siteurl+"fanwires/ajaxBiography?page="+param,
        success: function(response) {
            var res = response.split(":::");
            // alert(res[2])
            $('#plr_links_bio').html(res[1])
            $('#plr_about').html(res[0]);   
            $('#fanwireBioName').html(res[2])
        }
        
    });
    return false;
}

 
//signup page validations
         
$(document).ready(function(){
           
    $("#registerHere").validate({
        rules:{
            lastname:"required",
            firstname:"required",
                        
            email:{
                required:true,
                email: true
            },
            password:{
                required:true,
                minlength: 6
            },
            reenterpassword:{
                required:true,
                equalTo: "#password"
            },
            sex:{
                required:true
            } ,
            months:{
                required:true
            },
            days:{
                required:true
            },
            years:{
                required:true
            }
        },
         
        //errorClass: "help-inline",
        errorClass: "signup_error_red",
        errorElement: "div",
        validClass:"signup_input",
        
        highlight:function(element, errorClass, validClass) {
            $(element).parents('.signup_error').addClass('error');
            $(".signup_error_red").append("<div class=\"signup_error_red_arrow\"></div>");
        } ,
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.signup_error').removeClass('error');
            $(element).parents('.signup_error').addClass('success');
        }

        
    });
 
    //end signup page validations
    //validation for contact page
 
    $("#contactfrm").validate({
        rules:{
            name:{
                required:true
            },
            email:{
                required:true,
                email:true
            },
            subject:{
                required:true
            },
            message:{
                required:true
            }   
        },
        //errorClass: "error_contact",
        errorClass: "error_contact",
        errorElement: "div",
      
          
        highlight:function(element, errorClass, validClass) {
            $(element).parents('.errorContact').addClass('error');
            $(".error_contact").append("<div class=\"signup_error_red_arrow\"></div>");
        } ,
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.errorContact').removeClass('error');
            $(element).parents('.errorContact').addClass('success');
        }
    });
       
 
    //end contact page validations

    //login page validation
 
   
    $("#fan_login").validate({
        rules:{
            fan_password:{
                required:true
            },
            fan_email:{
                required:true,
                email:true
            }  
        },
        errorClass: "error_loginpopup",
        errorElement: "div",
        highlight:function(element, errorClass, validClass) {
            $(".signup_error_red").append("<div class=\"signup_error_red_arrow\"></div>");
        } 
    });
    $("#twitter_landing").validate({
        rules:{
            password1:{
                required:true
            },
            password2:{
                required:true,
                equalTo: "#password1"
            },
            email:{
                required:true,
                email:true
            }  
        },
        errorClass: "error_twitter_landing",
        errorElement: "div",
        highlight:function(element, errorClass, validClass) {
            $(element).parents('.signup_error').addClass('error');
            $(".error_twitter_landing").append("<div class=\"signup_error_red_arrow\"></div>");
        } ,
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.signup_error').removeClass('error');
            $(element).parents('.signup_error').addClass('success');
        }
    });
    
});
       
 
//login page validation end
function CalAge(mm,dd,yy) {
    if(mm!="" && dd!="" && yy!=""){
        var now = new Date();
        bDay = dd + "/" + mm + "/" + yy;
        //alert(bDay);
        bD = bDay.split('/');
        if (bD.length == 3) {
            born = new Date(bD[2], bD[1] * 1 - 1, bD[0]);
            years = Math.floor((now.getTime() - born.getTime()) / (365.25 * 24 * 60 * 60 * 1000));
            if (years < 13 ) {
                $("#showError").html("<div class=\"birthday_val\"><div class=\"signup_error_red1\"> <p style=\"padding: 0!important;\">You are too young to use fanwire.</p><div class=\"signup_error_red_arrow\" style=\"top: 0!important;\"></div></div></div>");
                return false;
            }else if(years > 100 || years == 100){
                $("#showError").html("<div class=\"birthday_val\"><div class=\"signup_error_red1\"> <p style=\"padding: 0!important;\">You are too old to use fanwire.</p><div class=\"signup_error_red_arrow\" style=\"top: 0!important;\"></div></div></div>");
                return false;
            }else{
                $("#showError").html(" ");
            }
            return true;
        }
    }
}
function validateDate(){
    var selectedDay = $("#days").val();
    var numDays;
    if($("#months").val() == 2){
        if($("#years").val() == ""){
            setDaysString(29);
            // if day is less than 29 pre slecting it
            if((selectedDay !="") && selectedDay<=29){
                preSelectingValue(selectedDay);
            }
        }else{
            numDays = daysInMonth($("#months").val(), $("#years").val());
            setDaysString(numDays);
            // if day is less than numDays pre slecting it
            if((selectedDay !="") && selectedDay<=numDays){
                preSelectingValue(selectedDay);
            }
        }

    }else if(($("#months").val() == 4) ||($("#months").val() == 6)||($("#months").val() == 9)||($("#months").val() == 11)){
        setDaysString(30)
        // if day is less than 30 pre slecting it
        if((selectedDay !="") && selectedDay<=30){
            preSelectingValue(selectedDay);
        }
    }else{
        setDaysString(31);
        // if day is less than 31 pre slecting it
        if((selectedDay !="") && selectedDay<=31){
            preSelectingValue(selectedDay);
        }
    }
    // setting dob value to hidden field//
    $("#dob").val($("#years").val()+"-"+$("#months").val()+"-"+$("#days").val());
//document.getElementById("dob").value=5;
}
function daysInMonth(month, year){
    var dd = new Date(year, month, 0);
    return dd.getDate();
}
function setDaysString(days){
    var i=1;
    var s="01";
    var string = "<option value=\"\">Day</option>";

    for(i;i<=days;i++){
        if(i<10)
            s='0'+i;
        else
            s=i;
        string +="<option value=\""+s+"\">"+s+"</option>";
    }
    //alert(string);
    $("#days").html(string);
}
function preSelectingValue(day){
    var day =day;
    $("#days option").each(function(){
        if($(this).val() == day){
            $("#days option[value="+day+"]").attr('selected', 'selected');
        }
    });
}
        
//addfanwire page functions
$(document).ready(function(){
    $('.comment_follow a').live("click",function(){
        followUnfllowFanwire(this.title,this.rel);
    });
 
    $('.comment_follow a').live("mouseout",function(){
        var className_per="comment_follow_img_active";
        if(this.className=="comment_follow_img_active_tmp"){
            $('#folunfol'+this.title).addClass(className_per);
        }
    });
});
function followUnfllowFanwire(id,status){
    var oldClassName=$('#folunfol'+id).attr('class');
    var dataString ='id='+id;
    if(status==1){
        var statusVal=0;
        var className="comment_follow_img";
        var urlString=siteurl+"unFanwire";
    }else{
        var statusVal=1;
        var className="comment_follow_img_active_tmp";
        var urlString=siteurl+"addFanwire";
    }
    //alert(urlString);
    $.ajax({
        type: "POST",
        url:urlString,
        data: dataString,
        success: function(response) {
            
            if(response==1){
                $('#folunfol'+id).removeClass(oldClassName);
                $('#folunfol'+id).addClass(className);
                $('#folunfol'+id).attr("rel", statusVal);
            }else{
                $('#folunfol'+id).removeClass(oldClassName);
                $('#folunfol'+id).addClass(className);
                $('#folunfol'+id).addAttr("rel",statusVal);
            }
        }
    });
    return false;
}
     
function checkMinThreeFan(sessionid){
    var dataString ='session_id='+sessionid;
    $.ajax({
        type: "POST",
        url:siteurl+"checkMinThreeFan",
        data: dataString,
        success: function(response) {
            if(response>=3){
                //location.reload();
                $('#cont1').show();
                $('#cont2').show();
            // window.location.href="{/literal}{$smarty.const.SITE_URL}{literal}uploadProfilePic";
            }else{
                //$('#message').html( "<h2>Must be fan of three fanwires</h2>")
                $('#message').html( " ")
                .hide()
                .fadeIn(3000)
                .fadeOut(3000)
                $('#cont1').hide();
                $('#cont2').hide();
            }
        }
    });
    return false;
}
function load(){
    $(window).trigger("load");
}
function showmoreless(){
    var showChar = 200;
    var ellipsestext = "...";
    var moretext = "more";
    var lesstext = "less";
    $('.more').each(function() {
        var content = $(this).text();//html/
                
        if(content.length > showChar) {
 
            var c = content.substr(0, showChar);
            var h = content.substr(showChar-1, content.length - showChar);
 
            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
 
            $(this).html(html);
        }
 
    });
    load();    
    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        load();
        return false;
    });
}
function nextPage(id){
    var dataStringd='val='+id;
    $.ajax({
        type: "POST",
        url:siteurl+"ajaxFashionCategoryFanwires",
        data: dataStringd,
        beforeSend: function(){
        },
        complete: function(){
        },
        success: function(response) {
            $('#categorycontents').html(response);
            showmoreless();
        }
    });
}
// end of addfanwire page functions
//to change the profile pic functions are used start

//to change the profile pic functions are used end


$(document).ready(function() {
    showmoreless();
});



function unFollowIt(){
    var id=$('#idU').val();
    var dataString ='id='+id;
    $.ajax({
        type: "POST",
        url:siteurl+"unFanwire",
        data: dataString,
        success: function(response) {
            if(response==1){
                $('#blocks'+id).remove();
                load();
                Popup.hide('modalFacebook1');
                return false;
                
            }else{
                load();
            }
        }
    });
    return false;    
}
$(document).ready(function(){
    $('.comment_follow_unfan a').live("click",function(){
        unFollow(this.title);
    });
});

function unFollow(id){
    var a=id;
    $('#idU').val(id);
    Popup.showModal('modalFacebook1','','fixed','','');
    return false;
}


$('login_menu').ready(function() {
    $('.dropdown').hover(function() {
        $(this).find('.sub_navigation').slideToggle(); 
    });
});
//$(document).ready(function(){
//    jQuery('iframe').remove();
//});
function loadMore(){
    
    var path=$('#path').val();
    var limit=$('#limit').val();
    var facebook=1;//$("#Ffacebook").val();
    var twitter=1;//$("#Ftwitter").val();
    var article=1;//$("#Farticle").val();
    var photo=1;//$("#Fphoto").val();
    var video=1;//$("#Fvideo").val();
    var instagram=1;//$("#Finstagram").val();
    var dataString="strlimit="+limit+"&path="+path+"&facebook="+facebook+"&twitter="+twitter+"&article="+article+"&video="+video+"&photo="+photo+"&instagram="+instagram;
                    
    $.ajax({
        type: "POST",
        url:siteurl+"fanwires/ajaxMore",
        data: dataString,
        beforeSend: function(){
            $('#loadingImage').show();
            $('#load-more').hide();
        },
        complete:function(){
            $('#loadingImage').hide();
            $('#load-more').show();
        },
        success: function(response) {
            var rees=response.split("##");
            $('#mainBlock').append(rees[0]);
            $('#limit').val(rees[1]);
            $(window).trigger("load");
             showmoreless();
            jQuery('iframe').remove();
        }
    });
    return false;
}
function nextAlbum(param,fanwireid){
    $.ajax({
        type: "GET",
        url:siteurl+"photos/ajaxNextAlbum?page="+param+"&fanwireid="+fanwireid,
        success: function(response) {
            var res = response.split(":::");
            $('.photopage_img').html(res[0]);
            var links=res[5].split("**");
            $('.content_header_right_left_arrow').html('<a href="javascript:void(0);" onclick="nextAlbum(\''+links[0]+'\',\''+fanwireid+'\');"><img src="'+siteurl+'views/images/left-arrow.png" height="7" width="10" alt="" >Previous</a>');
            $('.content_header_right_right_arrow').html('<a href="javascript:void(0);" onclick="nextAlbum(\''+links[1]+'\',\''+fanwireid+'\');"><img src="'+siteurl+'views/images/right_arrow.png" height="7" width="10" alt="" >Next</a>');
            $('#artBy').html(res[2]);
            $('#viaLink').html(res[1]);
            $('.aldate').html(res[3]);
            $(".altitle").html(res[4]);
        }
    });
    return false;
}

function fnSelect(objId) {
    fnDeSelect();
    $(".embed_input").toggle();
    if (document.selection) {
        var range = document.body.createTextRange();
        range.moveToElementText(document.getElementById(objId));
        range.select();
    }
    else if (window.getSelection) {
        var range = document.createRange();
        range.selectNode(document.getElementById(objId));
        window.getSelection().addRange(range);
    }
}
        
function fnDeSelect() {
    if (document.selection) document.selection.empty(); 
    else if (window.getSelection)
        window.getSelection().removeAllRanges();
}
$(document).ready(function(){
    jQuery('#mainBlock iframe').remove();
});
function nextVideo(param,fanwireid){
    $.ajax({
        type: "GET",
        url:siteurl+"videos/ajaxVideos?page="+param+"&fanwireid="+fanwireid,
        success: function(response) {
            //alert(response)
            var res = response.split(":::");
            //$('#show').html(res[0]);
            $('.plr_photos').html(res[0]);
            $('#photoslinks').html(res[1]);
        //$('#navigation').html(res[1]);
        }
    });
    return false;
}