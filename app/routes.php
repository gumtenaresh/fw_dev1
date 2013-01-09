
<?php

//Non-default overrided controller names
$controllers = array(
        /* 'abcd' => 'dingdongbell' */
);

//Non-default overrided action names for a particular controller
$actions = array(
    'users' => array(
        'users-list' => 'usersList'
    )
);

//Short clean single word route, will map to controller/action
$routes = array(
    'login' => 'index/login',
    'signup' => 'index/signup',
    'findFans' => "index/findFans",
    'uploadProfilePic' => 'index/uploadProfilePic',
    'addFanwires' => 'index/addFanwires',
    'ConnectToMedia' => 'index/ConnectToMedia', //this is removed becoz this is implemented as popup
    'socialMedia' => 'social/socialMedia',
    //'mediaCenter' => 'fanwires/mediaCenter',
    'myFanwire' => 'fanwires/myFanwire',
    'facebookLogin' => 'index/facebookLogin',
    'twitterLogin' => 'index/twitterLogin',
    'invite' => 'index/invite',
    'testContact2' => 'index/testContact2',
    'settings' => 'fanwires/settings',
    'changeName' => 'fanwires/changeName',
    'changeUrl' => 'fanwires/changeUrl',
    'changePassword' => 'fanwires/changePassword',
    'changeEmail' => 'fanwires/changeEmail',
    'changeProfilePic' => 'fanwires/changeProfilePic',
    'ajaxMore' => 'fanwires/ajaxMore',
    'forgotpassword' => 'index/forgotpassword',
    'logout' => 'index/logout',
    'multiMailContacts' => 'index/multiMailContacts',
    'getEmailPassword' => 'index/getEmailPassword',
    'fashion' => 'index/fashion',
    'sports' => 'index/sports',
    'music' => 'index/music',
    'news' => 'index/news',
    'add_fanwires' => 'index/addFanwires',
    'changePrivacy' => 'fanwires/changePrivacy',
    'addItem' => 'item/index',
    'my_fanwire_new_article' => 'articles/addArticle',
    'addPhotos' => 'photos/addPhotos',
    'my_fanwire_add_new_wire' => 'articles/stepThree',
    'my_fanwire_organise' => 'articles/organize',
    'my_fanwire_organise_publish' => 'articles/publish',
    // 'message_center' => 'messages/messageCenter',
    'my_fanwire_add_new_videos' => 'videos/addVedio',
    'addFanwire' => 'index/addFanwire',
    'unFanwire' => 'index/unFanwire',
    'checkMinThreeFan' => 'index/checkMinThreeFan',
    'myfanwires' => 'fanwires/havingFan',
    'browseFans' => 'fanwires/browseFans',
    'profileInfo' => 'fanwires/fanwirePersonalProfile',
    'emailConfirming' => 'fanwires/emailConfirming',
    'fanwiresFan' => 'fanwires/fanwiresFan',
    'collect' => 'fanwires/collect',
    'ajaxMoreCollections' => 'mycollection/ajaxMoreCollections',
    'mycollection' => 'mycollection/index',
    'mediaCenter' => 'mediamanager/myPhotos',
    'myVedios' => 'mediamanager/myVedios',
    'myArticles' => 'mediamanager/myArticles',
    'myLinks' => 'mediamanager/myLinks',
    'remove' => 'fanwires/removeFanwirePermanantly',
    'getFanwireFans' => 'fanwires/getFanwireFans',
    'shareContent' => 'fanwires/shareContent',
    'profile' => 'fan/fanPersonalProfile',
    'social' => 'fanwires/social',
    'about' => 'fanwires/about',
    'zoomPositions' => 'fanwires/zoomPositions',
    'fans' => 'fan/myFans',
    'requests' => 'fan/requests',
    'notifications' => 'fan/notifications',
    'respondRequest' => "fan/respondRequest",
    'messageCenter' => 'fan/messageCenter',
    'listFanwires' => 'fan/listFanwires',
    'chooseFans' => 'fan/findFansOther',
    'uFans' => 'fan/theirFans',
    'fCollections' => 'fan/fanCollections',
    'fanInfo' => 'fan/aboutThisFan',
    'fanwiresInfo' => 'fanwires/aboutThisFanwire',
    'phots' => 'photos/photos',
    'vidos' => 'videos/videos',
    'articles' => 'articles/articles',
    'viewArticle' => 'articles/viewArticle',
    'viewAlbum' => 'photos/viewAlbum',
    'viewVideos' => 'videos/viewVideos',
    'test' => 'mediamanager/commentCount', //rak any method you add in controller should be registered here
    'werecommend' => 'werecommend/recommend',
    'wefashion' => 'werecommend/fashion',
    'wesports' => 'werecommend/sports',
    'wenews' => 'werecommend/news',
    'wemusic' => 'werecommend/music',
    'fannetwork' => 'fannetwork/fannetwork',
    'footer' => 'footer/aboutus',
    'registerAjax' => 'index/registerAjax',
    'StepFurther' => 'index/StepFurther',
    'ajaxImageCrop' => 'index/ajaxImageCrop',
    'ajaxFashionCategoryFanwires' => 'index/ajaxFashionCategoryFanwires',
    'fbregisterAjax' => 'facebookConnect',
    'twitterRegister' => 'facebookConnect/twitterRegister',
    'loginAjax' => 'index/loginAjax',
    'fanlikesLoad' => 'fan/fanwirelikes',
    'editAlbum' => 'photos/editAlbum',
    'editVideo' => 'videos/editVideo',
    'editArticle' => 'articles/editArticle',
    'changeCoverUsingDesktop' => 'fan/changeCoverUsingDesktop',
    'manageSession' => 'index/manageSession',
    'fbCoverImport' => 'facebookConnect/fbCoverImport',
    'facebookCrawl' => 'index/facebookCrawl',
    'twitterCrawl' => 'index/twitterCrawl',
    'websiteCrawl' => 'index/websiteCrawl',
    'Crawl' => 'index/Crawl',
    'WebCrawl' => 'index/CrawlWeb',
    'crawl' => 'crawl/index',
    "contactUs"=>"footer/contact"
);

$conn =  Dao::getInstance();
$sql = "SELECT id,name,url FROM tbl_fanwires";
$rs = $conn->selectSql($sql, array());
foreach ($rs as $key => $value) {
    $id = $value['id'];
    $name = strtolower($value['url']);
    $routes[$name] = "photos/photos?ac=1&fwrid=" . $id;
    $routes[$name . "/socialMedia"] = "social/socialMedia?ac=2&fwrid=" . $id;
}

$sql = "SELECT id,username FROM tbl_users";
$rs = $conn->selectSql($sql, array());
foreach ($rs as $key => $value) {
    $id = $value['id'];
    $name = strtolower($value['url']);
    $routes[$name] = "fan/fanPersonalProfile";
}
$sql = "SELECT a.id,a.title,f.url FROM tbl_articles a,tbl_fanwires f where a.belongsTo=2 and a.user_id=f.id";
$rs = $conn->selectSql($sql, array());
foreach ($rs as $key => $value) {
    $title = str_replace("'", "", $value['title']);
    $title = str_replace(",", "-", $title);
    $title = str_replace(".", "-", $title);
    $title = str_replace("(", "-", $title);
    $title = str_replace(")", "-", $title);
    $title = str_replace('"', '-', $title);
    $title = str_replace('#', '', $title);
    $title = str_replace('&', '', $title);

    $routes[$value['url'] . "/Articles/" . str_replace(" ", "-", $title)] = "articles/viewArticle?aid=" . $value['id'];
}

$sql = "SELECT a.id,a.title,f.url FROM tbl_articles a,tbl_fanwires f where a.belongsTo=2 and a.user_id=f.id";
$rs = $conn->selectSql($sql, array());
foreach ($rs as $key => $value) {
    $id = $value['id'];
    $routes[$value['url'] . "/FB/" . $id] = "articles/viewArticle?aid=" . $value['id'];
}

$sql = "SELECT a.id,a.name,f.url FROM tbl_albums a,tbl_fanwires f where a.belongsTo=2 and a.pid=f.id";
$rs = $conn->selectSql($sql, array());
foreach ($rs as $key => $value) {
    $title = str_replace("'", "", $value['name']);
    $title = str_replace(",", "-", $title);
    $title = str_replace(".", "-", $title);
    $title = str_replace("(", "-", $title);
    $title = str_replace(")", "-", $title);
    $title = str_replace('"', '-', $title);
    $title = str_replace('#', '', $title);
    $title = str_replace('&', '', $title);
    $routes[$value['url'] . "/Gallery/" . str_replace(" ", "-", $title)] = "photos/viewAlbum?aid=" . $value['id'];
}
$sql = "SELECT a.id,a.name,f.url FROM tbl_albums a,tbl_fanwires f where a.belongsTo=2 and a.pid=f.id";
$rs = $conn->selectSql($sql, array());
foreach ($rs as $key => $value) {
    $title = $value['id'];
    $routes[$value['url'] . "/Instagram/" . $title] = "photos/viewAlbum?aid=" . $value['id'];
}

$sql = "SELECT v.id,v.title,f.url FROM tbl_videos v,tbl_fanwires f where v.belongsTo=2 and v.whomItBelongsTo=f.id";
$rs = $conn->selectSql($sql, array());
foreach ($rs as $key => $value) {
    $title = str_replace("'", "", $value['title']);
    $title = str_replace(",", "-", $title);
    $title = str_replace(".", "-", $title);
    $title = str_replace("(", "-", $title);
    $title = str_replace(")", "-", $title);
    $title = str_replace('"', '-', $title);
    $title = str_replace('#', '', $title);
    $title = str_replace('&', '', $title);
    $routes[$value['url'] . "/Videos/" . str_replace(" ", "-", $title)] = "videos/viewVideos?vid=" . $value['id'];
}


$routes = array_change_key_case($routes, CASE_LOWER);
//echo "<pre>";print_r($routes);echo "</pre>";