  function loginPop(){
                $('#loginFaceBookPopup').toggle();
            }
              
          
            function call(){
                $('#fanwirepopupbox').slideToggle("slow")
            }
            
            function callSign(){
                document.getElementById('fanwirepopupbox').style.display='none';
                $('#fanwirepopupbox1').slideToggle("slow")
            }
       
            function uploadFile() {
                var fd = new FormData();
                fd.append("photoimg", document.getElementById('photoimg').files[0]);
                var xhr = new XMLHttpRequest();
                xhr.upload.addEventListener("progress", uploadProgress, false);
                xhr.addEventListener("load", uploadComplete, false);
                xhr.open("POST", "");
                xhr.send(fd);    
            }

            function uploadProgress(evt) {         
                var percentComplete = Math.round(evt.loaded * 100 / evt.total); 
                document.getElementById('progressNumber').innerHTML = "<img src='images/progress.gif' />"; //percentComplete.toString() + '%';        
            }
            function uploadComplete(evt) {
                document.getElementById("progressNumber").innerHTML = "";  
            }
            /*  jQuery(function($) {
                $('img#photo').Jcrop({
                    onSelect: showCoords,
                    onChange: showCoords,
                    bgColor:     'black',
                    bgOpacity:   .4,            
                    aspectRatio: 9 / 9
                });
            });*/
    
            function showCoords(obj)
            {
                // variables can be accessed here as
                // c.x, c.y, c.x2, c.y2, c.w, c.h
       
        
                var x_axis = obj.x;
                var x2_axis = obj.x2;
                var y_axis = obj.y;
                var y2_axis = obj.y2;
                var thumb_width = obj.w;
                var thumb_height = obj.h;
                document.getElementById("x1").value =obj.x;
                document.getElementById("x2").value =obj.x2;
                document.getElementById("y1").value =obj.y;
                document.getElementById("y2").value =obj.y2;
                document.getElementById("w").value =obj.w;
                document.getElementById("h").value =obj.h;
            };
   
            $(document).ready(function() { 	
                $('#photoimg').live('change', function(){     
                    document.imageform.submit();         
                    uploadFile();
                });        
            }); 
      
          
    
  
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
            function goToEventDetailView(primaryKey,sampleon_events_view_scope_id){
                // alert(siteUrl+"events/eventDetailView?campaignkey="+primaryKey+"&evskey="+sampleon_events_view_scope_id+"&fbshare=fb&promotions=promotions"+primaryKey);
                $("#primaryKey").val(primaryKey);
                $("#sampleon_events_view_scope_id").val(sampleon_events_view_scope_id);
                $("#tteventDetailView").attr("action",httpSiteUrl+"events/eventDetailView?campaignkey="+primaryKey+"&evskey="+sampleon_events_view_scope_id+"&fbshare=fb&promotions=promotions"+primaryKey+"&");
                $("#tteventDetailView").submit();
            }

     
  
            function addFan(id){
                //	alert(id);
                var dataStringa ='id='+id;
    
                $.ajax({
                    type: "POST",
                    url:"{/literal}{$smarty.const.SITE_URL}{literal}addFanwire",
                    data: dataStringa,
                    success: function(response) {
                        if(response==1){
                            $("#n"+id).removeClass('fan');
                            $("#n"+id).addClass('unfan');
                            $("#n"+id).val('- unfan');
                            $("#n"+id).removeAttr( 'onClick');
                            $("#n"+id).attr( 'onClick', 'unFan("'+id+'")');
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
            function unFan(id){
	
                var dataStringb ='id='+id;
    
                $.ajax({
                    type: "POST",
                    url:"{/literal}{$smarty.const.SITE_URL}{literal}unFanwire",
                    data: dataStringb,
                    success: function(response) {
                        if(response==1){
                            $("#n"+id).removeClass('unfan');
                            $("#n"+id).addClass('fan');
                            $("#n"+id).val('+ fan');
                            //$("#n"+id).hide();
                            //$("#n"+id).hasClass();
                            $("#n"+id).removeAttr( 'onClick');
                            $("#n"+id).attr( 'onClick', 'addFan("'+id+'")');
				
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
            function checkMinThreeFan(){
        	
                var dataStringc ='session_id='+'{/literal}{$smarty.session.id}{literal}';
    
                $.ajax({
                    type: "POST",
                    url:"{/literal}{$smarty.const.SITE_URL}{literal}checkMinThreeFan",
                    data: dataStringc,
                    success: function(response) {
                	 
                        if(response>=3){
                            //location.reload();
                        
                            window.location.href="{/literal}{$smarty.const.SITE_URL}{literal}socialMedia";
                        }else{
                            $('#message').html( "<h2>Must be fan of three fanwires</h2>")
                            .hide()
                            .fadeIn(3000)
                            .fadeOut(3000)
                        }
                    }
                });
                return false;
            }
            
            
            //validations for feilds 
 
          /*  jQuery(function(){
                jQuery("#fan_email").validate({
                    expression: "if (VAL.match(/^[^\\W][a-zA-Z0-9\\_\\-\\.]+([a-zA-Z0-9\\_\\-\\.]+)*\\@[a-zA-Z0-9_]+(\\.[a-zA-Z0-9_]+)*\\.[a-zA-Z]{2,4}$/)) return true; else return false;",
                    message: "Please enter a valid Email ID"
                });
                jQuery("#fan_password").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter the Required field"
                });
                
                jQuery('#fan_login').validated(function(){
                    //  document.fan_login.submit();
                    //                    var username=document.getElementById('fan_email').value;
                    //                     
                    //                    var password=document.getElementById('fan_password').value;
                    //                    
                    //                    
                    //                    var dataStringd="fan_email="+username+"&fan_password="+password;
                    //                   
                    //                    $.ajax({
                    //                        type: "POST",
                    //                        url:"{/literal}{$smarty.const.SITE_URL}{literal}loginAjax",
                    //                        data: dataStringd,
                    //                        beforeSend: function(){
                    //                            $('#loaderlogin').show();
                    //                        },
                    //                        complete: function(){
                    //                            $('#loaderlogin').hide();
                    //                        },
                    //                        success: function(resa) {
                    //                            alert(resa);
                    //                            console.log(resa);
                    //                        },
                    //                        error:function(data){
                    //                            console.log(data);
                    //                        }
                    //                    });
                   
                }); 
            });    
   
            jQuery(function(){
                jQuery("#firstname").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter the Required field"
                });
                jQuery("#lastname").validate({
                    expression: "if (VAL) return true; else return false;",
                    message: "Please enter the Required field"
                });
                jQuery("#email").validate({
                    expression: "if (VAL.match(/^[^\\W][a-zA-Z0-9\\_\\-\\.]+([a-zA-Z0-9\\_\\-\\.]+)*\\@[a-zA-Z0-9_]+(\\.[a-zA-Z0-9_]+)*\\.[a-zA-Z]{2,4}$/)) return true; else return false;",
                    message: "Please enter a valid Email ID"
                });
                jQuery("#password").validate({
                    expression: "if (VAL.length > 5 && VAL) return true; else return false;",
                    message: "Please enter a valid Password"
                });
                jQuery("#reenterpassword").validate({
                    expression: "if ((VAL == jQuery('#password').val()) && VAL) return true; else return false;",
                    message: "Confirm password field doesn't match the password field"
                });
                jQuery("#sex").validate({
                    expression: "if (VAL != '0') return true; else return false;",
                    message: "Please select gender"
                });
             
                jQuery("#months").validate({
                    expression:"if (VAL != '0') return true; else return false;",
                    message: "Please Select Month."
                });
                jQuery("#days").validate({
                    expression:"if (VAL != '0') return true; else return false;",
                    message: "Please Select Date."
                });
                jQuery("#years").validate({
                    expression:"if (VAL != '0') return true; else return false;",
                    message: "Please Select Year."
                });
            
                jQuery('#fan_register').validated(function(){
                    //ajax registration form submission
                    var fname=document.getElementById('firstname').value;
                    var lname=document.getElementById('lastname').value;
                    var email=document.getElementById('email').value;
                    var password=document.getElementById('password').value;
                    var sex=document.getElementById('sex').value;
                    var months=document.getElementById('months').value;
                    var years=document.getElementById('years').value;
                    var days=document.getElementById('days').value;
                    
                    var dataStringd="firstname="+fname+"&lastname="+lname+"&email="+email+"&password="+password+"&sex="+sex+"&years="+years+"&months="+months+"&days="+days;
                    $.ajax({
                        type: "POST",
                        url:"{/literal}{$smarty.const.SITE_URL}{literal}registerAjax",
                        data: dataStringd,
                        beforeSend: function(){
                            $('#loader').show();
                        },
                        complete: function(){
                            $('#loader').hide();
                        },
                        success: function(resa) {
                      
                            $('#mss').html('<span    style="color: MediumSpringGreen;float: left;font: 15px OpenSansRegular;margin: 20px 0 0;"> '+resa+'</span><div class="conect_to_fb1"><a href="#"><img src="{/literal}{$smarty.const.SITE_URL}{literal}views/images/conect-facebook.png" height="" width="233" alt="" /></a><div class="clear"></div></div>');
                        }
                    });
                    
                }); 
            });    */
      
            function showConnect_popup_id(id){
                $('#'+id).toggle();
            }
            
            function hideConnect_popup_id(id){
                $('#'+id).hide();
            }
       
          function toggThisPop(id){
              $('#'+id).toggle();
              
          }
           window.fbAsyncInit = function() {
                FB.init({appId: '{/literal}{$smarty.const.FB_APP_ID}{literal}', status: true, cookie: true, xfbml: true});

                /* All the events registered */
                FB.Event.subscribe('auth.login', function(response) {
                    // do something with response
                  
                    login();
                });
                FB.Event.subscribe('auth.logout', function(response) {
                    // do something with response
                  
                    logout();
                });

                FB.getLoginStatus(function(response) {
                    if (response.session) {
                        // logged in and connected user, someone you know
                        login();
                    }
                });
            };
            (function() {
                var e = document.createElement('script');
                e.type = 'text/javascript';
                e.src = document.location.protocol +
                    '//connect.facebook.net/en_US/all.js';
                e.async = true;
                document.getElementById('fb-root').appendChild(e);
            }());

            function login(){
               
                FB.api('/me', function(response) {
                    //ajax registration form submission
                    var fname=response.first_name;
                    var lname=response.last_name;
                    var name=response.name;
                    var email=response.email;
                    var link=response.link
                    var fbid=response.id;
                    var birthday=response.birthday;
                    var sex=response.gender;
                    
 
                    var dataStringd="firstname="+fname+"&lastname="+lname+"&username="+name+"&email="+email+"&fb_url_link="+link+"&dob="+birthday+"&sex="+sex+"&fbid="+fbid;
                    
                    $.ajax({
                        type: "POST",
                        url:"{/literal}{$smarty.const.SITE_URL}{literal}fbregisterAjax",
                        data: dataStringd,
                        beforeSend: function(){
                            $('#step6').show();
                            $('#fade6').show();
                        },
                        complete: function(){
                            $('#step6').hide();
                            $('#fade6').hide();
                        },
                        success: function(resa) {
                            
                            if(resa){
                                
                                window.location.href='{/literal}{$smarty.const.SITE_URL}{literal}StepFurther?Axt=Itt';
                            }else{
                                
                                window.location.href='{/literal}{$smarty.const.SITE_URL}{literal}myFanwire';
                            }
                        },
                        error:function(data){
                            console.log(data);
                        }
                    });
                });
                /* var params = {};
                params['message'] = 'Message';
                params['name'] = 'Name';
                params['description'] = 'Description';
                params['link'] = 'http://apps.facebook.com/summer-mourning/';
                params['picture'] = 'http://summer-mourning.zoocha.com/uploads/thumb.png';
                params['caption'] = 'Caption';
  
                FB.api('/me/feed', 'post', params, function(response) {
                    
                    console.log(response);
                    console.log(response.message);
                     console.log(response.name);
                     console.log(response.description);
                     
                    if (!response || response.error) {
                        alert('Error occured');
                    } else {
                        alert('Published to stream - you might want to delete it now!');
                    }
                });*/
            }
            function logout(){
                document.getElementById('login').style.display = "none";
            }

 
            function share(){
                var share = {
                    method: 'stream.share',
                    u: 'http://202.136.65.24/fanwire'
                };

                FB.ui(share, function(response) { console.log(response); });
            }

            function graphStreamPublish(){
                var body = 'Reading New Graph api & Javascript Base FBConnect Tutorial from Thinkdiff.net';
                FB.api('/me/feed', 'post', { message: body }, function(response) {
                    if (!response || response.error) {
                        alert('Error occured');
                    } else {
                        alert('Post ID: ' + response.id);
                    }
                });
            }

            function fqlQuery(){
                FB.api('/me', function(response) {
                    var query = FB.Data.query('select name, hometown_location, sex, pic_square from user where uid={0}', response.id);
                    query.wait(function(rows) {
                       
                        document.getElementById('name').innerHTML =
                            'Your name: ' + rows[0].name + "<br />" +
                            '<img src="' + rows[0].pic_square + '" alt="" />' + "<br />";
                    });
                });
            }

            function setStatus(){
                status1 = document.getElementById('status').value;
                FB.api(
                {
                    method: 'status.set',
                    status: status1
                },
                function(response) {
                    if (response == 0){
                        alert('Your facebook status not updated. Give Status Update Permission.');
                    }
                    else{
                        alert('Your facebook status updated');
                    }
                }
            );
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