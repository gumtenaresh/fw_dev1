<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Fanwire</title>
        <link href="css/fanwire-css.css" rel="stylesheet" type="text/css">
        <link href="SpryAssets/SpryTabbedPanels.css" rel="stylesheet" type="text/css">
        <script src="SpryAssets/SpryTabbedPanels.js" type="text/javascript"></script>
        <script type="text/javascript" src="../Fixed - V1 extra pagees/jquery/jquery00.js"></script>
        <script type="text/javascript">
	       
            $('login_menu').ready(function() {
                $('.dropdown').hover(function() {
                    $(this).find('.sub_navigation').slideToggle(); 
                });
            });
		
	
        </script>

        <script src="jquery/place_holder/jquery.js"></script>
        <script src="jquery/place_holder/modernizr.js"></script>

        <script type="text/javascript">
		
            $(document).ready(function(){

                if(!Modernizr.input.placeholder){

                    $('[placeholder]').focus(function() {
                        var input = $(this);
                        if (input.val() == input.attr('placeholder')) {
                            input.val('');
                            input.removeClass('placeholder');
                        }
                    }).blur(function() {
                        var input = $(this);
                        if (input.val() == '' || input.val() == input.attr('placeholder')) {
                            input.addClass('placeholder');
                            input.val(input.attr('placeholder'));
                        }
                    }).blur();
                    $('[placeholder]').parents('form').submit(function() {
                        $(this).find('[placeholder]').each(function() {
                            var input = $(this);
                            if (input.val() == input.attr('placeholder')) {
                                input.val('');
                            }
                        })
                    });

                }

            });
		
		
        </script>


    </head>

    <body>
        <div  class="wrapper">
            <div class="left_section">
                <div class="logo"><a href="#"><img src="images/logo.png" height="120" width="109" alt=""></a></div>
                <div class="guest_user"><a href="login.html">Guest User</a></div>

                <div class="search">
                    <div class="search_input">
                        <input type="text" class="search_text" placeholder="Search">
                        <a href="#" class="search_icon"></a>
                    </div>

                </div>

                <div class="left_menusec">
                    <ul>
                        <li><a href="my_fanwire.html" class="active">Top Fanwires</a></li>
                        <li><a href="werecommended.html">Daily Trends</a></li>

                    </ul>
                </div>
                <div class="clear"></div>
                <div class="popular">
                    <div class="popular_conent">
                        <h1>Who’s Popular</h1>

                        <div class="spear">
                            <div class="spear_left">
                                <span class="spear_left1">1</span>
                                <img src="images/spear.gif" height="30" width="30" alt="">
                            </div>
                            <div class="spear_right">
                                <p>Briney Spears</p>
                                <span>Briney Spears</span>
                            </div>
                            <div class="clear"></div>

                        </div>

                        <div class="spear">
                            <div class="spear_left">
                                <span class="spear_left1">2</span><img src="images/spear.gif" height="30" width="30" alt=""></div>
                            <div class="spear_right"> <p>Briney Spears</p><span>Briney Spears</span> </div>
                            <div class="clear"></div>
                        </div>

                        <div class="spear">
                            <div class="spear_left">
                                <span class="spear_left1">3</span>
                                <img src="images/spear.gif" height="30" width="30" alt="">
                            </div>
                            <div class="spear_right">
                                <p>Briney Spears</p>
                                <span>Briney Spears</span>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="spear">
                            <div class="spear_left">
                                <span class="spear_left1">4</span>
                                <img src="images/spear.gif" height="30" width="30" alt="">
                            </div>
                            <div class="spear_right">
                                <p>Briney Spears</p>
                                <span>Briney Spears</span>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="spear">
                            <div class="spear_left">
                                <span class="spear_left1">5</span>
                                <img src="images/spear.gif" height="30" width="30" alt="">
                            </div>
                            <div class="spear_right">
                                <p>Briney Spears</p>
                                <span>Briney Spears</span>
                            </div>
                            <div class="clear"></div>

                        </div>

                        <div class="spear">
                            <div class="spear_left">
                                <span class="spear_left1">6</span>
                                <img src="images/spear.gif" height="30" width="30" alt="">
                            </div>
                            <div class="spear_right">
                                <p>Briney Spears</p>
                                <span>Briney Spears</span>
                            </div>
                            <div class="clear"></div>

                        </div>

                        <div class="spear">
                            <div class="spear_left">
                                <span class="spear_left1">7</span>
                                <img src="images/spear.gif" height="30" width="30" alt="">
                            </div>
                            <div class="spear_right">
                                <p>Briney Spears</p>
                                <span>Briney Spears</span>
                            </div>
                            <div class="clear"></div>

                        </div>

                        <div class="spear">
                            <div class="spear_left">
                                <span class="spear_left1">8</span>
                                <img src="images/spear.gif" height="30" width="30" alt="">
                            </div>
                            <div class="spear_right">
                                <p>Briney Spears</p>
                                <span>Briney Spears</span>
                            </div>
                            <div class="clear"></div>





                        </div>
                        <div class="see">
                            <a href="#">See all</a>
                        </div>

                    </div>




                    <div class="popular_conent1">
                        <h1>What’s Hot</h1>

                        <div class="spear1">
                            <div class="spear1_left">
                                <img src="images/spear1.gif" height="40" width="50" alt="" >
                            </div>
                            <div class="spear1_right">
                                <span>Early voting in key </span>
                                <span>states</span>
                            </div>
                            <div class="clear"></div>

                        </div>

                        <div class="spear1">
                            <div class="spear1_left">
                                <img src="images/spear1.gif" height="40" width="50" alt="" >
                            </div>
                            <div class="spear1_right">
                                <span>Early voting in key </span>
                                <span>states</span>
                            </div>
                            <div class="clear"></div>

                        </div>

                        <div class="spear1">
                            <div class="spear1_left">
                                <img src="images/spear1.gif" height="40" width="50" alt="" >
                            </div>
                            <div class="spear1_right">
                                <span>Early voting in key </span>
                                <span>states</span>
                            </div>
                            <div class="clear"></div>

                        </div>
                        <div class="spear1">
                            <div class="spear1_left">
                                <img src="images/spear1.gif" height="40" width="50" alt="" >
                            </div>
                            <div class="spear1_right">
                                <span>Early voting in key </span>
                                <span>states</span>
                            </div>
                            <div class="clear"></div>

                        </div>
                        <div class="spear1">
                            <div class="spear1_left">
                                <img src="images/spear1.gif" height="40" width="50" alt="" >
                            </div>
                            <div class="spear1_right">
                                <span>Early voting in key </span>
                                <span>states</span>
                            </div>
                            <div class="clear"></div>

                        </div>



                        <div class="see">
                            <a href="#">See all</a>
                        </div>
                    </div>

                </div>


            </div>

            <div class="right_menusec">

                <div class="login_section"><div class="login"><a href="#" class="login_check">Login</a></div></div>
                <div class="login_menu">
                    <ul id="navigation">
                        <li class="dropdown"style="><a href="#" >About <span></span></a>
                            <ul class="sub_navigation">
                                <li><a href="#">Help</a></li>
                                <li><a href="#">Contact</a></li>
                                <li><a href="#">Terms and Conditions</a></li>
                                <li><a href="#">Privacy Policy</a></li>
                            </ul>
                        </li>
                        <li class="dropdown"><a href="#">Customize <span></span></a>
                            <ul class="sub_navigation" style="right:20px; ">
                                <li><a href="#">Add / Remove Profiles</a></li>
                                <li style="border-top:#bfbcbd solid 1px;"><a href="#">Account Settings</a></li>
                                <li><a href="#">Log Out</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="clear"></div>
                <div class="plr_pts">

                    <div class="plr_pts1">
                        <P>Mariah Carey</P>
                        <span style="color:#333333; font-size:14px; font-weight:bold;">Popular Photos</span>

                        <div class="plr_links">
                            <a href="#"><img src="images/arrow_left.gif" height="13" width="5" alt=""></a>
                            <a href="#"><img src="images/arrow_right.gif" height="13" width="5" alt=""></a>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="plr_photos">
                        <div class="clear"></div>
                        <div class="smallvideo"><img src="images/plr_img.gif" height="79" width="120" alt="">
                            <div class="smallvideo_icon"></div>
                        </div>
                        <div class="smallvideo"><img src="images/plr_img1.gif" height="79" width="120" alt="">
                            <div class="smallvideo_icon"></div>
                        </div>
                        <div class="smallvideo"> <img src="images/plr_img.gif" height="79" width="120" alt="">
                            <div class="smallvideo_icon"></div>
                        </div>
                        <div class="smallvideo"><img src="images/plr_img.gif" height="79" width="120" alt="">
                            <div class="smallvideo_icon"></div>
                        </div>
                        <div class="smallvideo">   <img src="images/plr_img1.gif" height="79" width="120" alt="">
                            <div class="smallvideo_icon"></div>
                        </div>
                        <div class="smallvideo"><img src="images/plr_img.gif" height="79" width="120" alt="">
                            <div class="smallvideo_icon"></div>
                        </div>
                        <div class="smallvideo">          <img src="images/plr_img.gif" height="79" width="120" alt="">
                            <div class="smallvideo_icon"></div>
                        </div><div class="smallvideo"> <img src="images/plr_img1.gif" height="79" width="120" alt="">
                            <div class="smallvideo_icon"></div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="plr_twitter">
                        <div class="plr_twitter1">
                            <img src="images/twitetr-icon.png" height="50" width="50" alt="" >
                            <div class="twitter_name"><p>Mariah Carey</p><span style="color:#333333; font-size:14px; font-weight:bold;">Twitter</span></div>
                            <a href="#" class="twitter_icon"></a>
                        </div>
                        <div class="clear"></div>

                        <div class="twitter_comments_links">
                            <ul>
                                <li><a href="#">Hot!</a></li>
                                <li><a href="#">Celeb</a></li>
                                <li><a href="#">Music</a></li>
                                <li><a href="#">Sports</a></li>
                                <li><a href="#">Custom</a></li>
                            </ul>
                        </div>
                        <div class="clear"></div>

                        <div class="twitter_comments">
                            <div class="spear_comment">
                                <div class="spr_cmt_img"><img src="images/spear.gif" height="30" width="30" alt="" ></div>
                                <div class="spr_cmt_right">
                                    <div class="spr_cmt_righttitle"><p>@Briney Spears</p> <span>41m</span></div>
                                    <div class="clear"></div>
                                    <span>Lorem ipsum dolor sit amet, consectetur adipisicing elit</span>
                                    <a href="#">Retweet   </a><a href="#">Reply</a><a href="#">   Shore</a>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="spear_comment">
                                <div class="spr_cmt_img"><img src="images/spear.gif" height="30" width="30" alt="" ></div>
                                <div class="spr_cmt_right">
                                    <div class="spr_cmt_righttitle"><p>@Briney Spears</p> <span>41m</span></div>
                                    <div class="clear"></div>
                                    <span>Lorem ipsum dolor sit amet, consectetur adipisicing elit</span>
                                    <a href="#">Retweet   </a><a href="#">Reply</a><a href="#">   Shore</a>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="spear_comment" style="border:none;">
                                <div class="spr_cmt_img"><img src="images/spear.gif" height="30" width="30" alt="" ></div>
                                <div class="spr_cmt_right">
                                    <div class="spr_cmt_righttitle"><p>@Briney Spears</p> <span>41m</span></div>
                                    <div class="clear"></div>
                                    <span>Lorem ipsum dolor sit amet, consectetur adipisicing elit</span>
                                    <a href="#">Retweet   </a><a href="#">Reply</a><a href="#">   Shore</a>
                                </div>
                                <div class="clear"></div>
                            </div>



                            <div class="twitter_bottom">
                                <p style="float:left"><a href="#">Share</a></p>
                                <p style="float:right"><a href="#">View More</a></p>
                            </div>



                        </div>



                    </div>
                    <div class="clear"></div>

                    <div class="plr_twitter">
                        <div class="plr_twitter1">
                            <img src="images/facebook-icon.png" height="50" width="50" alt="" >
                            <div class="twitter_name"><p>FACEBOOK TRENDS
                                </p><span style="color:#333333; font-size:14px; font-weight:bold;">Today</span></div>
                            <a href="#" class="facebook_icon"></a>
                        </div>
                        <div class="clear"></div>

                        <div class="twitter_comments_links">
                            <ul>
                                <li><a href="#">Hot!</a></li>
                                <li><a href="#">Celeb</a></li>
                                <li><a href="#">Music</a></li>
                                <li><a href="#">Sports</a></li>
                                <li><a href="#">Custom</a></li>
                            </ul>
                        </div>
                        <div class="clear"></div>

                        <div class="twitter_comments">






                            <div class="spear_comment">
                                <div class="spr_cmt_img"><img src="images/spear.gif" height="30" width="30" alt="" ></div>
                                <div class="spr_cmt_right">
                                    <div class="spr_cmt_righttitle"><p>@Briney Spears</p> <span>41m</span></div>
                                    <div class="clear"></div>
                                    <span>Lorem ipsum dolor sit amet, consectetur adipisicing elit</span>
                                    <a href="#">Retweet   </a><a href="#">Reply</a><a href="#">   Shore</a>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="spear_comment">
                                <div class="spr_cmt_img"><img src="images/spear.gif" height="30" width="30" alt="" ></div>
                                <div class="spr_cmt_right">
                                    <div class="spr_cmt_righttitle"><p>@Briney Spears</p> <span>41m</span></div>
                                    <div class="clear"></div>
                                    <span>Lorem ipsum dolor sit amet, consectetur adipisicing elit</span>
                                    <a href="#">Retweet   </a><a href="#">Reply</a><a href="#">   Shore</a>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="spear_comment" style="border:none;">
                                <div class="spr_cmt_img"><img src="images/spear.gif" height="30" width="30" alt="" ></div>
                                <div class="spr_cmt_right">
                                    <div class="spr_cmt_righttitle"><p>@Briney Spears</p> <span>41m</span></div>
                                    <div class="clear"></div>
                                    <span>Lorem ipsum dolor sit amet, consectetur adipisicing elit</span>
                                    <a href="#">Retweet   </a><a href="#">Reply</a><a href="#">   Shore</a>
                                </div>
                                <div class="clear"></div>
                            </div>



                            <div class="twitter_bottom">
                                <p style="float:left"><a href="#">Share</a></p>
                                <p style="float:right"><a href="#">View More</a></p>
                            </div>



                        </div>



                    </div>
                    <div class="plr_twitter">
                        <div class="plr_twitter1">
                            <img src="images/spear.png" height="50" width="50" alt="" >
                            <div class="twitter_name"><p>MUSIC TRENDS</p><span>Lil Wayne</span>
                            </div>
                            <a href="#" class="music_icon"></a>

                            <div class="buy_text">
                                <p>A Moment Isn’t ...</p> <a href="#" class="buy_text_buy">Buy</a>
                            </div>

                        </div>
                        <div class="clear"></div>

                        <div class="twitter_comments_links">
                            <ul>
                                <li><a href="#">Hot!</a></li>
                                <li><a href="#">Celeb</a></li>
                                <li><a href="#">Music</a></li>
                                <li><a href="#">Sports</a></li>
                                <li><a href="#">Custom</a></li>
                            </ul>
                        </div>
                        <div class="clear"></div>

                        <div class="video_playlist">

                            <div class="playlist">
                                <a href="#" class="play_list_active">A Moment Isn’t Very Long</a>
                                <span>2:43</span></div>
                            <div class="playlist">
                                <a href="#" class="play_list">A Moment Isn’t Very Long</a>
                                <span>2:43</span></div>
                            <div class="playlist">
                                <a href="#" class="play_list">A Moment Isn’t Very Long</a>
                                <span>2:43</span></div>
                            <div class="playlist">
                                <a href="#" class="play_list">A Moment Isn’t Very Long</a>
                                <span>2:43</span></div>
                            <div class="playlist"  style="border:none">
                                <a href="#" class="play_list">A Moment Isn’t Very Long</a>
                                <span>2:43</span></div>





                            <div class="clear"></div>
                            <div class="twitter_bottom">
                                <p style="float:left"><a href="#">Share</a></p>
                                <p style="float:right"><a href="#">View More</a></p>
                            </div>



                        </div>



                    </div>

                    <div class="plr_twitter">
                        <div class="plr_twitter1">
                            <img src="images/spear.png" height="50" width="50" alt="" >
                            <div class="twitter_name"><p>MUSIC TRENDS</p><span>Lil Wayne</span>
                            </div>
                            <a href="#" class="music_icon"></a>

                            <div class="buy_text">
                                <p>My Homies Still</p> <a href="#" class="buy_text_buy">Buy</a>
                            </div>

                        </div>
                        <div class="clear"></div>

                        <div class="twitter_comments_links">
                            <ul>
                                <li><a href="#">Hot!</a></li>
                                <li><a href="#">Celeb</a></li>
                                <li><a href="#">Music</a></li>
                                <li><a href="#">Sports</a></li>
                                <li><a href="#">Custom</a></li>
                            </ul>
                        </div>
                        <div class="clear"></div>

                        <div class="video_playlist">
                            <div>
                                <img src="images/vedio_img.png" height="145" width="220" alt="" >
                            </div>
                            <div class="playlist">
                                <a href="#" class="play_list_active">A Moment Isn’t Very Long</a>
                                <span>2:43</span></div>
                            <div class="playlist">
                                <a href="#" class="play_list">A Moment Isn’t Very Long</a>
                                <span>2:43</span></div>
                            <div class="playlist">
                                <a href="#" class="play_list">A Moment Isn’t Very Long</a>
                                <span>2:43</span></div>
                            <div class="playlist">
                                <a href="#" class="play_list">A Moment Isn’t Very Long</a>
                                <span>2:43</span></div>
                            <div class="playlist"  style="border:none">
                                <a href="#" class="play_list">A Moment Isn’t Very Long</a>
                                <span>2:43</span></div>





                            <div class="clear"></div>
                            <div class="twitter_bottom">
                                <p style="float:left"><a href="#">Share</a></p>
                                <p style="float:right"><a href="#">View More</a></p>
                            </div>



                        </div>



                    </div>

                    <div class="plr_twitter">
                        <div class="plr_twitter1">
                            <img src="images/spear4.gif" height="65" width="50" alt="" >
                            <div class="twitter_name"><p>TOP MOVIE TRAILERS</p>
                            </div>
                            <a href="#" class="video1_icon"></a>
                                
                            <div class="buy_text">
                                <p>The Dark Knight Rises</p>
                                <span>Lorem ipsum dolor sit amet, con
                                    sectetur adipisicing elit, sed do 
                                    eiusmod tempor incididunt ut la
                                    bore et dolore magna aliqua.</span>
                                <a href="#" class="buy_text_more">more</a>
                            </div>
                                
                        </div>
                        <div class="clear"></div>
                        <div class="clear"></div>
                            
                            
                            
                            
                        <div class="video_playlist">
                            <div>
                                <img src="images/vedio1_img.png" height="145" width="220" alt="" >
                            </div>
                            <div class="clear"></div>
                                
                            <div class="playlist">
                                <a href="#" class="play_list_active">A Moment Isn’t Very Long</a>
                                <a href="#" class="buy_text_buy">buy </a></div>
                            <div class="playlist">
                                <a href="#" class="play_list">A Moment Isn’t Very Long</a>
                                <a href="#" class="buy_text_buy">buy </a></div>
                            <div class="playlist">
                                <a href="#" class="play_list">A Moment Isn’t Very Long</a>
                                <a href="#" class="buy_text_buy">buy </a></div>
                            <div class="playlist">
                                <a href="#" class="play_list">A Moment Isn’t Very Long</a>
                                <a href="#" class="buy_text_buy">buy </a></div>
                            <div class="playlist" style="border:none;">
                                <a href="#" class="play_list">A Moment Isn’t Very Long</a>
                                <a href="#" class="buy_text_buy">buy </a></div>
                                    
                            <div class="clear"></div>
                            <div class="twitter_bottom">
                                <p style="float:left"><a href="#">Share</a></p>
                                <p style="float:right"><a href="#">View More</a></p>
                            </div>
                                
                                
                                
                        </div>
                            
                            
                            
                    </div>



                </div>



            </div>


            <div class="main_section">
                <div class="main_content">


                    <div class="clear"></div>
                    <div class="whats_hot">
                                    <div class="test1"><img src="images/test-1.png" height="255" width="442px" alt="" >
                                        <div class="test1_top">
                                            <div class="test_hedding">
                                                <a href="#">24 min ago</a>
                                            </div>
                                            <div class="test_text">
                                                <h1 style="color:#FFF; size:8px;">Angry Birds Star Wars tests just how far</h1>
                                                <p>Angry Birds Star Wars tests just how far those funny birds can fly Angry Birds 
                                                    Star Wars tests just how far those funny birds can fly</p>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="profile_music1">

                                        <div class="whats_hot_3">
                                            <div class="test3"> <img src="images/wrapp.gif" height="123" width="224" alt="" >
                                                <div class="test3_top">
                                                    <div class="test_hedding3"><a href="#">5 min ago</a></div>
                                                    <div class="test_text3"><p>Duis aute irure dolor in reprehende
                                                            rit in voluptate velit esse cillum</p></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="whats_hot_3">
                                            <div class="test3"> <img src="images/wrapp_1.gif" height="123" width="224" alt="" >
                                                <div class="test3_top">
                                                    <div class="test_hedding3"><a href="#">5 min ago</a></div>
                                                    <div class="test_text3"><p>Duis aute irure dolor in reprehende
                                                            rit in voluptate velit esse cillum</p></div>
                                                </div>
                                            </div>
                                        </div>




                                    </div>


                                    <div class="clear"></div>


                                    <div class="clear"></div>



                                </div>
                    <div class="clear"></div>
                    <div class="news_elements">

                        <div class="twitter_comments_links1">
                            <ul>
                                <li><a href="#" class="active">All</a></li>
                                <li><a href="#">       News    </a></li>
                                <li><a href="#">    Videos     </a></li>
                                <li><a href="#">   Social Media</a></li>


                            </ul>
                        </div>

                        <div class="clear"></div>

                        <div class="news">
                            <div class="element">
                                <div class="element_content">
                                    <div class="element_content1">
                                        <p>Mariah Carey</p>
                                        <a href="#" class="small_icons_riview"></a>
                                        <div class="clear"></div> 
                                        <span>Springsteen Plays Obama 
                                            Song in Virginia</span>
                                    </div>
                                    <p>10/21/12</p> 
                                    <div class="image">
                                        <img src="images/iamge.png" height="75" width="75" alt="" >
                                        <p>YouTube</p>
                                    </div>
                                    <p>Lorem ipsum dolor sitamet, consectetur adipisicing elit, sed do eiusmotempor incididunt ut labore et dolore magna aliqua.Ut enim ad minim ve exercitation ullamco Lorem ipsum dolor sitamet, consecteturadipisicing elit, sed do eiusmotempor incididunt ut labore et dolore magna aliqua.Ut enim ad minim ve exercitation ullamco 

                                    </p>                </div>
                                <div class="clear"></div>
                                <div class="twitter_bottom1">
                                    <p style="float:left"><a href="#">Share</a></p>
                                    <p style="float:right"><a href="#">View More</a></p>
                                </div>
                            </div>
                            <div class="element">
                                <div class="element_content">
                                    <div class="element_content1">
                                        <p>Mariah Carey</p>
                                        <a href="#" class="small_icons_riview"></a>
                                        <div class="clear"></div> 
                                        <span>Springsteen Plays Obama 
                                            Song in Virginia</span>
                                    </div>
                                    <p>10/21/12</p> 
                                    <div class="image">
                                        <img src="images/iamge.png" height="75" width="75" alt="" >

                                        <p>YouTube</p>
                                    </div>
                                    <p>Lorem ipsum dolor sitamet, consectetur adipisicing elit, sed do eiusmotempor incididunt ut labore et dolore magna aliqua.Ut enim ad minim ve exercitation ullamco Lorem ipsum dolor sitamet,

                                    </p>                </div>
                                <div class="clear"></div>
                                <div class="twitter_bottom1">
                                    <p style="float:left"><a href="#">Share</a></p>
                                    <p style="float:right"><a href="#">View More</a></p>
                                </div>
                            </div>

                            <div class="element">
                                <div class="element_content">
                                    <div class="element_content1">
                                        <p>Mariah Carey</p>
                                        <a href="#" class="small_icons_riview"></a>
                                        <div class="clear"></div> 
                                        <span>Springsteen Plays Obama 
                                            Song in Virginia</span>
                                    </div>
                                    <p>10/21/12</p> 
                                    <div class="image">
                                        <img src="images/iamge.png" height="75" width="75" alt="" >

                                        <p>YouTube</p>
                                    </div>
                                    <p>Lorem ipsum dolor sitamet, consectetur adipisicing elit, sed do eiusmotempor incididunt ut labore et dolore magna aliqua.Ut enim ad minim ve exercitation ullamco Lorem ipsum dolor sitamet,

                                    </p>                </div>
                                <div class="clear"></div>
                                <div class="twitter_bottom1">
                                    <p style="float:left"><a href="#">Share</a></p>
                                    <p style="float:right"><a href="#">View More</a></p>
                                </div>
                            </div>


                        </div>
                        <div class="video_content">

                            <div class="element_greyline">
                                <div class="element_content">
                                    <div class="element_content1">
                                        <p>Mariah Carey</p>
                                        <a href="#" class="small_icons_mda"></a>
                                        <div class="clear"></div> 
                                        <span>Springsteen Plays Obama 
                                            Song in Virginia</span>
                                    </div>
                                    <p>10/21/12</p> 
                                    <div class="video">
                                        <div class="play_video"><div class="play_video_icon"></div><img src="images/video.png" height="113" width="202" alt="" ></div>
                                        <p>YouTube</p>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="twitter_bottom1">
                                    <p style="float:left"><a href="#">Share</a></p>
                                    <p style="float:right"><a href="#">View More</a></p>
                                </div>
                            </div>
                            <div class="element_greyline">
                                <div class="element_content">
                                    <div class="element_content1">
                                        <p>Mariah Carey</p>
                                        <a href="#" class="small_icons_mda"></a>
                                        <div class="clear"></div> 
                                        <span>Springsteen Plays Obama 
                                            Song in Virginia</span>
                                    </div>
                                    <p>10/21/12</p> 
                                    <div class="video">
                                        <div class="play_video"><div class="play_video_icon"></div><img src="images/video.png" height="113" width="202" alt="" ></div>
                                        <p>YouTube</p>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="twitter_bottom1">
                                    <p style="float:left"><a href="#">Share</a></p>
                                    <p style="float:right"><a href="#">View More</a></p>
                                </div>
                            </div>
                            <div class="element_greyline">
                                <div class="element_content">
                                    <div class="element_content1">
                                        <p>Mariah Carey</p>
                                        <a href="#" class="small_icons_mda"></a>
                                        <div class="clear"></div> 
                                        <span>Springsteen Plays Obama 
                                            Song in Virginia</span>
                                    </div>
                                    <p>10/21/12</p> 
                                    <div class="video">
                                        <div class="play_video"><div class="play_video_icon"></div><img src="images/video.png" height="113" width="202" alt="" ></div>
                                        <p>YouTube</p>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="twitter_bottom1">
                                    <p style="float:left"><a href="#">Share</a></p>
                                    <p style="float:right"><a href="#">View More</a></p>
                                </div>
                            </div>



                        </div>
                        <div class="socialmedia_content">
                            <div class="element">
                                <div class="element_content">
                                    <div class="element_content1">
                                        <p>Mariah Carey</p>
                                        <a href="#" class="small_icons_fb"></a>
                                        <div class="clear"></div> 
                                        <span>Springsteen Plays Obama 
                                            Song in Virginia</span>
                                    </div>
                                    <p>10/21/12</p> 
                                    <div class="video">
                                        <img src="images/social1.png" height="150" width="202" alt="" >
                                        <p>YouTube</p>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="twitter_bottom1">
                                    <p style="float:left"><a href="#">Share</a></p>
                                    <p style="float:right"><a href="#">View More</a></p>
                                </div>
                            </div>

                            <div class="element">
                                <div class="element_content">
                                    <div class="element_content1">
                                        <p>Mariah Carey</p>
                                        <a href="#" class="small_icons_cam"></a>
                                        <div class="clear"></div> 
                                        <span>Springsteen Plays Obama 
                                            Song in Virginia</span>
                                    </div>
                                    <p>10/21/12</p> 
                                    <div class="video">
                                        <img src="images/social_img.png" height="286" width="202" alt="">
                                        <p>YouTube</p>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="twitter_bottom1">
                                    <p style="float:left"><a href="#">Share</a></p>
                                    <p style="float:right"><a href="#">View More</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="clear"></div>
                <div class="loadmore">
                    <a href="#">Load More</a>
                </div>


                <div class="loading">
                    <a href="#"><img src="images/loading.gif" height="30" width="21" alt="" > Loading</a>
                </div>


            </div>

        </div>
        <script type="text/javascript">
            var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
        </script>
    </body>
</html>
