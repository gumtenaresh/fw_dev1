
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-29289267-1']);
    _gaq.push(['_setDomainName', 'fanwire.com']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();


    var _ues = { host:'fanwire.userecho.com', forum:'15037', lang:'en', tab_corner_radius:10, tab_font_size:20, tab_image_hash:'R2l2ZSBVcyBGZWVkYmFjaw%3D%3D', tab_alignment:'right', tab_text_color:'#FBFAFF', tab_bg_color:'#1695B5', tab_hover_color:'#F04F2D' };

    (function() {

        var _ue = document.createElement('script'); _ue.type = 'text/javascript'; _ue.async = true;
        _ue.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.userecho.com/js/widget-1.4.gz.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(_ue, s);

    })();

    
    function ShowDialog(p,id){
      
        $('#comment_popup_id'+id).toggle();
    }
    function dislikefanwire(id){
       
        $('#like_popup_id'+id).toggle();
    }
    function likefanwire(id){
   
        $('#like_popup_id'+id).toggle();
    }
    
    //    jQuery(function(){
    //        jQuery("#email").validate({
    //            expression: "if (VAL.match(/^[^\\W][a-zA-Z0-9\\_\\-\\.]+([a-zA-Z0-9\\_\\-\\.]+)*\\@[a-zA-Z0-9_]+(\\.[a-zA-Z0-9_]+)*\\.[a-zA-Z]{2,4}$/)) return true; else return false;",
    //            message: "Please enter a valid email address"
    //        });
    //        jQuery('#emailForm').validated(function(){
    //            $('#regblock').show();
    //            $('#media_access_guest').hide();
    //        }); 
    //    });
    
    jQuery(function(){
        jQuery("#fan_email").validate({
            expression: "if (VAL.match(/^[^\\W][a-zA-Z0-9\\_\\-\\.]+([a-zA-Z0-9\\_\\-\\.]+)*\\@[a-zA-Z0-9_]+(\\.[a-zA-Z0-9_]+)*\\.[a-zA-Z]{2,4}$/)) return true; else return false;",
            message: "Please enter a valid email address"
        });
        jQuery("#fan_password").validate({
            expression: "if (VAL) return true; else return false;",
            message: "Please enter the Required field"
        });
                
        jQuery('#fan_login').validated(function(){
            // document.fan_login.submit();
            var username=document.getElementById('fan_email').value;
                     
            var password=document.getElementById('fan_password').value;
                    
                    
            var dataStringd="fan_email="+username+"&fan_password="+password;
                
            $.ajax({
                type: "POST",
                url:"{/literal}{$smarty.const.SITE_URL}{literal}loginAjax",
                data: dataStringd,
                beforeSend: function(){
                    $('#loaderlogin').show();
                },
                complete: function(){
                    $('#loaderlogin').hide();
                },
                success: function(resa) {
                    
                    if(resa==0){
                        window.location.href="{/literal}{$smarty.const.SITE_URL}{literal}StepFurther?Axt=Itt";
                    }else if(resa==1){
                        window.location.href="{/literal}{$smarty.const.SITE_URL}{literal}myFanwire";  
                    }else{
                        //document.getElementById('loginajaxTest').innerHTML(resa);
                        $('#loginajaxTest').html(resa);
                    }
                },
                error:function(data){
                    console.log(data);
                }
            });
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
            message: "Please enter a valid email address"
        });
        jQuery("#password").validate({
            expression: "if (VAL.length > 5 && VAL) return true; else return false;",
            message: "Please enter a valid Password"
             
        });
       
        jQuery("#reenterpassword").validate({
            expression: "if ((VAL == jQuery('#password').val()) && VAL) return true; else return false;",
            message: "Confirm password doesn't match the password"
        });
        jQuery("#reenterpassword").blur(function(){
            if(jQuery('#password').val()==jQuery('#reenterpassword').val())
                $('#cnf').show();else  $('#cnf').hide(); 
        });
         
          
        jQuery("#sex").validate({
            expression: "if (VAL != '0') return true; else return false;",
            message: "Please select gender"
        });
        jQuery('#fan_register').validated(function(){
            var months=document.getElementById('months').value;
            var years=document.getElementById('years').value;
            var days=document.getElementById('days').value;
            
            if(months==0 || days==0 || years==0){
                $('#datebirth').html('Please enter date of birth');
                return false;
            }else{
                var res=CalAge(months,days,years);
                if(res==1){
                    $('#datebirth').html('You are too young to use fanwire.');
                    return false;
                }else if(res==2){
                    $('#datebirth').html('You are too old to use fanwire.');
                    return false;
                
                }else{
                    //  $('#datebirth').html('');
                    //ajax registration form submission
                    var fname=document.getElementById('firstname').value;
                    var lname=document.getElementById('lastname').value;
                    var email=document.getElementById('email').value;
                    var password=document.getElementById('password').value;
                    var sex=document.getElementById('sex').value;
                
                    $('#loader').show();
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
                          
                            if(resa == 1){
                                $("#emailExist").show();return false;
                            }else if(resa == 3){
                            }
                            else if(resa == 2){
                                // $('#thanksMessage').show();//send confirmation email to the registered user
                                //  window.location.href='{/literal}{$smarty.const.SITE_URL}{literal}StepFurther?Axt=Itt';
                                window.location.href='{/literal}{$smarty.const.SITE_URL}{literal}addFanwires';
                            }else{
                                alert('there might be problem with registration');
                            }
                                
                     
                        },
                        error:function(data){
                            console.log(data);
                        }
                    });
                
                }
                
                 
            }
        }); 
    });    
    
    
			
            
    
    function CalAge(mm,dd,yy) {
        var now = new Date();
        bDay = dd + "/" + mm + "/" + yy;
        bD = bDay.split('/');
        if (bD.length == 3) {
            born = new Date(bD[2], bD[1] * 1 - 1, bD[0]);
            years = Math.floor((now.getTime() - born.getTime()) / (365.25 * 24 * 60 * 60 * 1000));
            if (years < 13 ) {
                return 1;

            }else if(years > 100 || years == 100){
                return 2;
            }
            return 0;
        }
    }
    
    
    jQuery(function(){
        jQuery("#fan_email_forgot").validate({
            expression: "if (VAL) return true; else return false;",
            message: "Please enter the Required field"
        });
 
        
        jQuery('#fanlogin').validated(function(){
            document.fanlogin.submit();
           
        }); 
    });   
  
    function stat(){
        FB.getLoginStatus(function(response) {
            if (response.status === 'connected') {
                login();
                // the user is logged in and has authenticated your
                // app, and response.authResponse supplies
                // the user's ID, a valid access token, a signed
                // request, and the time the access token 
                // and signed request each expire
                // var uid = response.authResponse.userID;
                //  var accessToken = response.authResponse.accessToken;
               
            } else if (response.status === 'not_authorized') {
                // FB.login(null,{scope:'email,user_birthday,status_update,publish_stream,user_about_me'});return false;
                //   alert('not authorized');
                // the user is logged in to Facebook, 
                // but has not authenticated your app
            } else {
                FB.login(null,{scope:'email,user_birthday,status_update,publish_stream,user_about_me'});return false;
                // alert('not asf');
                // the user isn't logged in to Facebook.
            }
        });}
    
    window.fbAsyncInit = function() {
        FB.init({appId: '{/literal}{$smarty.const.FB_APP_ID}{literal}', status: true, cookie: true, xfbml: true});

        /* All the events registered */
        FB.Event.subscribe('auth.login', function(response) {
            // do something with response
            // z console.log('user logged in in facebook');
            // alert('maresh');
                  
            login();
        });
        FB.Event.subscribe('auth.logout', function(response) {
            //  alert('naresh')
            window.location.reload();
            // do something with response
            //  console.log('user loggedout  in facebook');
            logout();
        });

        FB.getLoginStatus(function(response) {
            if (response.session) {
                //   alert('suresh');
                //  console.log('asdfasf');
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
        // alert('hehhehehhehhhe');
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
                    if(resa == true){
                        window.location.href='{/literal}{$smarty.const.SITE_URL}{literal}myFanwire';
                              
                    }else{
                        window.location.href='{/literal}{$smarty.const.SITE_URL}{literal}addFanwires';
                               
                    }
                },
                error:function(data){
                    console.log(data);
                }
            });
        });
                 
    }
    function logout(){
        var dataStringd="destroy=yes";
        $.ajax({
            type: "POST",
            url:"{/literal}{$smarty.const.SITE_URL}{literal}manageSession",
            data: dataStringd,
                
            success: function(resa) {
                           
                    
            },
            error:function(data){
                console.log(data);
            }
        });
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
          
    function showConnect_popup_id(id){
            
        $('#'+id).toggle();
    }
            
    function hideConnect_popup_id(id){
        $('#'+id).hide();
    }
       
    function toggThisPop(id){
        $('#'+id).toggle();
              
    }
    if('{/literal}{$smarty.request.dAx}{literal}'){
        $('#fanwirepopupbox7').fadeOut(10000,function(){
            call();
        });
        
    }
