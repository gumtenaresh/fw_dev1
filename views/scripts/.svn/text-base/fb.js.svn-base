/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
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
            FB.login(null,{
                scope:'email,user_birthday,status_update,publish_stream,user_about_me'
            });
            return false;
        // alert('not asf');
        // the user isn't logged in to Facebook.
        }
    });
}
    
window.fbAsyncInit = function() {
    FB.init({
        appId: '{/literal}{$smarty.const.FB_APP_ID}{literal}', 
        status: true, 
        cookie: true, 
        xfbml: true
    });

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

    FB.ui(share, function(response) {
        console.log(response);
    });
}

function graphStreamPublish(){
    var body = 'Reading New Graph api & Javascript Base FBConnect Tutorial from Thinkdiff.net';
    FB.api('/me/feed', 'post', {
        message: body
    }, function(response) {
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


