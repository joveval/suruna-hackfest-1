<?php 
include '../../models/DBConnection.php';
include '../../models/Usuario.php';
include '../../models/logos.php';
include '../../models/banners.php';
include '../../models/Videos.php';
include '../../models/VideoWord.php';
include '../../models/WordPosition.php';
include '../../models/DatosVideo.php';
include '../../libs/funciones.php';
include '../../models/videos_comentarios.php';
include '../../models/usuario_comentarios.php';
include '../../IA/Kmeans/Kmeans.php';

$ruta = $_SERVER["PHP_SELF"];
$rtmpRepository = explode('/', $ruta);

// Obtenemos el usuario.
$user = new Usuario();
$user->alias = $rtmpRepository[2];
$rsAct = $user->cargarforAlias();

if (!$user->activo) {
  echo '<h1>Usuario Desactivado</h1>'; 
  exit();
}
$nid = $_GET['nid'];

if (empty($nid)){
$last_video = new Videos ();
$last_video->idusuario = $user->idusuario;
$last_video->cargarlastforID(); 
}
else{
$last_video = new Videos ();
$last_video->videoid = $nid;
$last_video->cargarforID();
}
// contamos los videos de un usuario.
$total_videos = $last_video->countforIdUsuario(); 
// Cargamos los datos del ultimo video.

$datos_last_video = new DatosVideo();
$datos_last_video->idvideo = $last_video->videoid;
$datos_last_video->cargarforIDVideo();
// Obetenemos el logo
$id = $user->idusuario;
$logo = new logos();
$logo->get_by_idusuario($id);
if ($logo->path == "") {
  $logo->path = "../../usuarios/logo_default.png";
}
// Obtenemos el banner
$banner = new banners();
$banner->get_by_idusuario($id);
if ($banner->path == "") {
  $banner->path = "../../usuarios/banner_default.jpg";
}
// Obtenemos el skin para el jwplayer
$currentSkin = $user->skin_actual;


?>

<!DOCTYPE html>
<html lang="es" prefix="og: http://ogp.me/ns#">
  <head>

	<meta property="og:description" content="Etiquetas Cognitivas"/>
    <meta charset="utf-8"/>
    <meta property="og:image" content="og-picture.jpg"/>
    <title>Suruna | CADE Ejecutivos</title> 
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    
    	<!-- Google Analytics Alterlatina.com -->
    
    <script>
	
	
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-673056-1', 'auto');
  ga('send', 'pageview');

</script>
 
    
	
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	
	
	
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	
    <script src="http://alplayer.s3.amazonaws.com/jwplayer.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="../../Views/StylesWebsite/css/canales/tango_skin.css" />
	<link rel="stylesheet" type="text/css" href="css/jquery-confirm.css" />
	
	<link rel="stylesheet" type="text/css" href="css/jPages.css" />
	<link rel="stylesheet" type="text/css" href="css/animate.css" />
	<link rel="stylesheet" type="text/css" href="font-awesome/css/font-awesome.css" />
    <script src="js/jquery-confirm.js" type="text/javascript"></script>
	<script src="js/jPages.js" type="text/javascript"></script>
	<script>
      /* $(window).scroll(function() {    
      
        if ($(window).scrollTop() == ( $(document).height() - $(window).height())) {
            var i = $(".tmp_help_scroll").attr("id");
            $(".tmp_help_scroll").remove();
            loadcontent(<?php echo "'".$user->idusuario."'"; ?>, i,<?php echo $total_videos; ?>);
            //$("#post_timeline").css("background-color","#000");
            
        } 
       });

      var loadcontent = function(id, p, num_total) {  
        num = ((p - 1) * 2) + 1;
        pag = p + 1;
        num_ini = num;        
        $.ajax({
          type: "POST",
          url : 'more_content_ajax.php?p='+p+'&idusr='+id,
          async: true,
          success : function (html){
            $("#post_timeline").append(html);
          }
        });
        return false; 
      };

      
     function set_comment(comment ) {
        return alert(comment);    
      }



      $(function() 
        {
          /*load();
          $(".view_all_comments").click(function() 
          {
            var ID = $(this).attr("id");
            $.ajax({
              type: "POST",
              url: "view_all_comments_ajax.php",
              data: "vid_id="+ ID, 
              cache: false,
              success: function(html){
                $("#view_all_comments_ajax").css("width","696px");
                $("#view_all_comments_ajax").css("height","152px");
                $("#view_all_comments_ajax").css("overflow-y","scroll");
                $("#view_all_comments_ajax").prepend(html);
                $(".view_all_comments").remove();
                $("#view_tmp_comments").remove();
                load();
              }
            });
            return false;
          });
      });

      function load() {
        
        var el = document.getElementById('input_text_comment');
        if (typeof el.addEventListener != "undefined") {
            el.addEventListener("keypress", function(evt) {
                if(evt.keyCode === 13) {
                set_comment(el.value);
                el.value = '';
                }
            }, false);
        } else if (typeof el.attachEvent != "undefined") { //incase you support IE8
            el.attachEvent("keypress", function(evt) {
              if(evt.keyCode === 13) {
                set_comment(el.value);
                el.value = '';
                }
            });
        }        
      }

	  
	  window.fbAsyncInit = function() {
		FB.init({
		  appId      : '{your-app-id}',
		  xfbml      : true,
		  status	: true,
		  version    : 'v2.0'
		});
	  };

	  (function(d, s, id){
		 var js, fjs = d.getElementsByTagName(s)[0];
		 if (d.getElementById(id)) {return;}
		 js = d.createElement(s); js.id = id;
		 js.src = "//connect.facebook.net/es_LA/sdk.js";
		 fjs.parentNode.insertBefore(js, fjs);
	   }(document, 'script', 'facebook-jssdk'));

	FB.ui(
	{
	  method: 'share',
	  href: 'https://developers.facebook.com/docs/'
	}, function(response){});
	
	(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.0";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
	

(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

//Twiter
!function(d,s,id)
{var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}
(document, 'script', 'twitter-wjs');

// New windwos
function abrir(pagina) {
	window.open(pagina,'window','params');
}
 */
</script>
	
    <style type="text/css">		
      html {
        height: 100%;
        background: #ddd;
		font-family:Abel;
      }   

      a {
        text-decoration: none;
        color: #0B61A5;
      }

      a:visited {
        color: #0B61A5;
      }

      div#full-window{
       /*  width: 976px; */
        /*height: 2000px;*/
       /*  position: relative;
        left: 50%;
        margin-left: -483px; */

       /* border: 1px solid blue;*/
        
      }   

      div#div_banner{
	/* width: 976px;        
        height: 100px;
        position: relative;
        left: 50%;
        margin-left: -488px; */
        background: #033156;
	color:#033156;       /* border: 1px solid green;*/
      }

      div#second_body {
        width: 976px;
      }

      div#div_video{
       /*  width: 718px;        
        height: 416px; */
        /* float: left;
        position: relative;*/
        margin-top: 5px; 
        /*border: 1px solid red;*/
      }

      div#div_video_description{
        /* width: 716px;        
        height: 450px;
	    float: left; */
	    height: 168px;
		padding-bottom: 20px;
        position: relative;
        background: white;
 /*       border: 1px solid black;*/
      }

      div#div_videos_recommended{
       
        /* width: 257px;        
        height: 831px;
        float: right;
        position: relative;
        margin-top: 45px; */
/*        border-right: 1px solid black;
        border-top: 1px solid black;
        border-bottom: 1px solid black;
		font-family:Roboto;
*/      }

      div#div_videos_recommended div#videos_recommended {
        /* overflow-y: scroll;
        height: 745px;
        overflow-x: hidden; */
      }
	  .video_recommended a{
		font-size: 18px;
		font-family: Abel;
		color:black;
		font-weight:bolder;
	  }
	  .video_recommended a:link{
		 color: black; 
		 text-decoration: none;
	  }
	  
	  .video_recommended a:hover{
		  color: #2768ce;
	  }
	  

      /*div#div_text_view{
        width: 200px;        
        height: 305px;
        float: left;
        position: relative;

        border: 1px solid #888;
      }*/

      div#div_timeline{
        width: 976px;        
        height: 700px;
        float: left;
        position: relative;

        /*border: 1px solid #888;*/
      }

      div.banner#client_avatar {
        /* width: 168px;
        height: 168px; */
        margin-top: 27px;
        margin-left: 3px;
        float: left;
        /*border: 1px solid #888;*/
        position: relative;
      }

      div.banner#client_info {
        float: left;
        position: relative;
        margin-left: 30px;
        /*margin-top: 110px;*/
      }

      div.banner span#channel_title {
        font-size: 31px;
        display: inherit;
		margin-top:15px;
		font-family: Abel;
		font-weight: bold;
		
      }

      div.banner span#amount_video {
        font-size: 20px;
      }

      div.current_video#video_container{
      	width: 718px;
      	left: 50%;
      	position: relative;
      	margin-left: -359px;
      }

      div.current_video span#video_info{
      	font-size: 24px;
		font-weight:bold;
      }

      div.current_video span#video_stars{
        font-size: 20px;    
        float: right;
        margin-right: 10px;
        margin-top: 8px;    
        color: yellow;
      }

      div#div_video_description div#current_video_description {
        margin: 10px;
        font-size: 16px;
        height: 108px;
        width: 696px;
      }

      div#div_video_description div#current_video_like_comments {
        margin-left: 10px;
        margin-right: 10px;
        border-top: 1.5px solid #489FE4;
      }

      div#div_videos_recommended span#title {
        font-size: 25px;
        font-weight: bold;
        width: 258px;
        text-align: center;
        display: block;
        margin-top: 10px;
        height: 28px;
      }

      div#videos_recommended {
        margin-top: 10px;
      }

      /* div#videos_recommended div.video_recommended {
        margin-right: 10px;
        margin-left: 5px;
        margin-bottom: 15px;
        width: 237px;
      }

      div#videos_recommended div.video_recommended span {
        color: #489FE4;
        font-size: 14px;
        font-weight: bold;
      } */
	  
	  .video_recommended{
		  height:220px;
	  }
	  
	  .video_recommended_content{
		-webkit-line-clamp: 2;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		max-height:2.6em;
		display: block;
		line-height: 1.3em;
		overflow: hidden;
		position: relative;
		text-overflow: ellipsis;
		white-space: normal;
		word-wrap: break-word;
	  }
	  
	  
      div#div_videos_recommended div#scroll_icon {
/*        border-top: 1px solid black;*/
        background: #888;
        text-align: center;
        max-height: 33px;
        min-height: 33px;
        font-size: 30px;
      }

      div#pre_timeline {
        width: 976px;
        float: left;
        margin-right: 10px;
      }

      div#pre_timeline div.pre_timeline_video {
        background: white;
        padding-left: 10px;
        padding-top: 30px;
        padding-right: 10px;
        margin-top: 10px;
      }

      div#pre_timeline div.pre_timeline_video div.thumbnail_info {
        height: 148px;
        border: 1px solid #d5d9e2;
      }

      div#pre_timeline div.pre_timeline_video div.thumbnail_info img {
        float: left;
      }

      div#pre_timeline div.pre_timeline_video div.thumbnail_info div.title_info {
        float: left;
        width: 438px;
        margin-left: 10px;
        margin-top: 5px;
      }

      div#pre_timeline div.pre_timeline_video div.thumbnail_info span.title {
        display: block;
        color: #5d77ac;
        font-weight: bold;
        font-size: 17px;
        padding-bottom: 10px;
      }

      div#pre_timeline div.pre_timeline_video div.thumbnail_info span.info {
        width: 428px;
        display: block;
        font-size: 14px;
      }

      div#pre_timeline div.pre_timeline_video div.actions_like_comments {
        height: 65px;
      }

      div#pre_timeline div.pre_timeline_video div.actions_like_comments div.actions {
        font-size: 20px;
        padding-top: 10px;
        width: 130px;
        float: left;
        padding-left: 10px;
      }

      div#pre_timeline div.pre_timeline_video div.actions_like_comments div.actions i {
        margin-right: 10px;
      }

      div#pre_timeline div.pre_timeline_video div.actions_like_comments div.like_comments {
        font-size: 14px;
        padding-top: 12px;
        float: right;
        padding-right: 10px;
      }

      div#pre_timeline div.pre_timeline_video div.actions_like_comments div.like_comments i{
        margin-left: 10px;
      }



      div#search_selector {
        width: 258px;
        height: 440px;        
        float: left;
        margin-top: 10px;
        
      }

      div#search_selector div#search {
        height: 36px;
        background: white;
        margin-top: 10px;
        border: 1px solid black;
      }

      div#search_selector div#search input#input_text_search {
        width: 216px;
        height: 30px;
        border: 0px;
        padding-left: 10px;
      }

      div#search_selector div#search i {
        padding-top: 10px;
      }

      div#search_selector div#selector {
        height: 392px;
        background: #ececf0;
        border: 1px solid black;
      }

      div#post_timeline {
        position: relative;
        width: 976px;
        height: 170px;
        float: left;
        margin-top: 10px;
      }

      div#post_timeline div#left_post_timeline {
        width: 458px;
        height: 170px;
        float: left;
      }

      div#post_timeline div#right_post_timeline {
        width: 458px;
        height: 170px;
        float: left;
        margin-left: 60px;
      }

      div#post_timeline div.post_timeline_video {
        background: white;
        padding-left: 10px;
        padding-top: 10px;
        padding-right: 10px;
        margin-top: 10px;
      }

      div#post_timeline div.post_timeline_video div.thumbnail_info {
        height: 118px;
        border: 1px solid #d5d9e2;
      }

      div#post_timeline div.post_timeline_video div.thumbnail_info img {
        float: left;
      }

      div#post_timeline div.post_timeline_video div.thumbnail_info div.title_info {
        float: left;
        width: 218px;
        margin-left: 10px;
        margin-top: 5px;
      }

      div#post_timeline div.post_timeline_video div.thumbnail_info span.title {
        display: block;
        color: #5d77ac;
        font-weight: bold;
        font-size: 13px;
        padding-bottom: 10px;
      }

      div#post_timeline div.post_timeline_video div.thumbnail_info span.info {
        width: 218px;
        display: block;
        font-size: 10px;
      }

      div#post_timeline div.post_timeline_video div.actions_like_comments {
        height: 50px;
      }

      div#post_timeline div.post_timeline_video div.actions_like_comments div.actions {
        font-size: 13px;
        padding-top: 5px;
        width: 130px;
        float: left;
        padding-left: 10px;
      }

      div#post_timeline div.post_timeline_video div.actions_like_comments div.actions i {
        margin-right: 10px;
      }

      div#post_timeline div.post_timeline_video div.actions_like_comments div.like_comments {
        font-size: 10px;
        padding-top: 5px;
        float: right;
        padding-right: 10px;
      }

      div#post_timeline div.post_timeline_video div.actions_like_comments div.like_comments i{
        margin-left: 10px;
      }

      div.like_comments {
        color: #0B61A5;
      }

      i.fa {
        cursor: pointer;
      }

      i.fa-facebook-square {
        color: #0B61A5;
      }

      i.fa-twitter-square {
        color: #65B7F8;
      }

      i.fa-tumblr-square {
        color: rgba(7, 190, 197, 0.83);
      }

      div#current_video_like_comments div#current_video_comments {
        height: 285px;
        width: 718px;
      }

      div#current_video_like_comments div#current_video_like {
        height: 36px;
      }
      }

      div#current_video_like_comments div#current_video_like div.actions {
        font-size: 20px;
        padding-top: 10px;
        width: 130px;
        float: left;
        padding-left: 10px;
      }

      div#current_video_like_comments div#current_video_like i {
        margin-right: 10px;
      }

      div#current_video_like_comments div#current_video_like div.like_comments {
        font-size: 14px;
        padding-top: 12px;
        float: right;
        padding-right: 10px;
      }

      div#current_video_like_comments div#current_video_like div.like_comments i{
        margin-left: 10px;
      }

      div#current_video_like_comments div#current_video_comments {
        font-size: 15px;
      }

      div#current_video_like_comments div#current_video_comments div.c_current_video_comments span.name {
        color: #0B61A5;
        font-weight: bold;
        margin-left: 10px;
      }

      div#current_video_like_comments div#current_video_comments div.c_current_video_comments {
        margin-top: 9px;
        display: inline-block;
      }  

      div#current_video_like_comments div#current_video_comments div.c_current_video_comments img {
        float: left;
      }

      div#current_video_like_comments div#current_video_comments div.actions_like_comments {
        height: 22px;
      }

      div#current_video_like_comments div#current_video_comments div.actions_like_comments div.actions {
        font-size: 20px;
        padding-top: 5px;
        width: 130px;
        float: left;
        padding-left: 10px;
      }

      div#current_video_like_comments div#current_video_comments div.actions_like_comments div.actions i {
        margin-right: 10px;
      }

      div#current_video_like_comments div#current_video_comments div.actions_like_comments div.like_comments {
        font-size: 13px;
        padding-top: 5px;
        float: right;
        padding-right: 35px;
      }

      div#current_video_like_comments div#current_video_comments div.actions_like_comments div.like_comments i{
        margin-left: 10px;
      }

      div.c_current_video_comments div.name_comment {
        width: 580px; 
        float: left; 
        margin-top: 8px;
      }
      

      input#input_text_comment {
        height: 23px;
        width: 560px;
        padding-left: 5px;
        margin-left: 10px;
      }

      textarea#input_text_comment {
        height: 23px;
        width: 560px;
        padding-left: 5px;
        margin-left: 10px;        
      }
	  
	  .container-fluid#tag-search{
		  background-color:#d9dcde;
	  }
	  #video-player{
		  background-color:white;
	  }
	  
	  
	  .tag-container ul li {
		 list-style-type: none;
	  }
	  .tag-container ul li{
		cursor: pointer;
		display: inline-block;
		margin: 0.7rem 0.7rem 0 0;
		border-radius: 20px;
		-moz-border-radius: 20px;
		padding: 4px 8px;
		border: 2px solid #2c3e50;
		color: #2c3e50;
		
	  }
	  .tag-container ul li:nth-child(3):after { 
		content:"\A"; white-space:pre; 
	  }
	  
	  .tag-container ul li:hover{
		  color:#009cdd;
		  border-color:#009cdd;
	  }
	  
	  /* .tag-span:hover{
		  color:#009cdd;
		  border-color:#009cdd;
	  } */
	  .tag-container ul a{
		  font-size:12x;
		  font-family:Lato;
		  color:black;
	  }
	  
	  
	  .tag-container ul a:hover,
	  .tag-container ul a:link,
	  .tag-container ul a:focus,
	  .tag-container ul a:active,
	  .tag-container ul a:visited
	  {
		  text-decoration: none;
		  color: inherit;
	  }
	  .tag-container ul a{
		  display: inline-block;
		  color: inherit;
	  }
	  
	  #recomended-container{
			background-color: #ecf0f1;
		}
		
	@font-face {
		font-family: 'rezland';
		src: url('font/REZ.ttf');
	}
	#suruna_info{
	font-size:73px;
	color:#C6E2FB;
	font-family:rezland;
	margin-bottom: -34px;
	}
	
	#suruna_message{
		font-size:12px;
		color:#C6E2FB;
		font-family:Roboto;
		font-style: italic;
	}
		
	#suruna_link{
	background: #FA4949;
	border-radius:5px;
	border: solid #FA4949;
	color:#ffffff;
	padding: 1.0em 1.273em;
	position: relative;
	right: -311px;
	top: -38px;
	font-style:normal;
	}
	#suruna_link:hover{
		background-color: #da2929;
		border-color: #da2929;
		color: #ffffff;
		cursor:pointer;
	}
	input[type=text]{
		border-radius:0px;
		width:500px;
		height:45px;
	}
	#search-btn{
		border-radius:0px;
		width:80px;
		height:45px;
	}
	
	.btn {
		padding: 14px 24px;
		border: 0 none;
		font-weight: 700;
		letter-spacing: 1px;
		text-transform: uppercase;
	}
	 
	.btn:focus, .btn:active:focus, .btn.active:focus {
		outline: 0 none;
	}
	 
	.btn-primary {
		background: #0099cc;
		color: #ffffff;
	}
	 
	.btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open > .dropdown-toggle.btn-primary {
		background: #33a6cc;
	}
	 
	.btn-primary:active, .btn-primary.active {
		background: #007299;
		box-shadow: none;
	}
		
	.slider-field{
		padding-top:20px;
		
	}
	
	.current-tag{
		cursor: pointer;
		font-family: Lato;
		border:solid 2px #2c3e50;
		color:#2c3e50;
		padding:4px 8px;
		border-radius:8px;
	}
	.current-tag:hover{
		cursor: pointer;
		font-family: Lato;
		border:solid 2px #009cdd;
		color:#009cdd;
		padding:4px 8px;
		border-radius:8px;
	}
	.del-cur-tag-span{
		cursor: pointer;
		font-family: Lato;
		color:#025f5b;
		padding:4px 8px;
		border-radius:8px;
	}
	
	.del-cur-tag-span:hover{
		cursor: pointer;
		font-family: Lato;
		color:rgba(5, 152, 146, 0.85);
		padding:4px 8px;
		border-radius:8px;
	}
	
	
	/*Paginación de Lista de videos */
	
	ul.all-videos-list {
	  margin: 0;
	  padding: 0;
	  white-space: nowrap;
	  overflow-x: auto;
	  background-color: inherit;
	}
	ul.all-videos-list li {
	  display: inline;
	}
	
	/*jPages used*/
	
	.holder{
		margin:15px 0;
		
	}
	.holder a{
		font-size:17px;
		font-family:Lato;
		cursor:pointer;
		margin:0 5px;
		color:#333;
	}
	.holder a:hover{
		background-color:#222;
		color:#fff;
	}	
	
	.holder a.jp-previous{
		margin-right:15px;
	}
	.holder a.jp-next{
		margin-left:15px;
	}
	.holder a.jp-current,a.jp-current:hover{
		color:#FF4242;
		font-weight:bold;
	}
	.holder a.jp-disabled, a.jp-disabled:hover{
		color:#bbb;
	}
	.holder a.jp-current,a.jp-current:hover,.holder a.jp-disabled, a.jp-disabled:hover{
		cursor:default;
		background:none;
	}
	.holder span{
		margin:0 5px;
	}
	
	
	#res-word-search li {
		list-style-type: none;
	}
	#res-word-search li{
		cursor: pointer;
		margin: 0.7rem 0.7rem 0 0;
		-moz-border-radius: 20px;
		padding: 8px 8px;
		color: #2c3e50;
		background: white;
		text-align: left;
		font-weight: 900;
		color: #bbb;
		
	 }
	 #res-word-search li:nth-child(3):after { 
		content:"\A"; white-space:pre; 
	 }
	  
	#res-word-search li:hover{
		color:#009cdd;
		background:#ecf0f1;
		border-color:#009cdd;
	}
	  
	  #res-word-search a{
		  font-size:12x;
		  font-family:Lato;
		  color:black;
	  }
	  
	  
	  #res-word-search a:hover,
	  #res-word-search a:link,
	  #res-word-search a:focus,
	  #res-word-search a:active,
	  #res-word-search a:visited
	  {
		  text-decoration: none;
		  color: inherit;
		  
	  }
	  #res-word-search a{
		  display: inline-block;
		  color: inherit;
	  }
	  #res-word-search-container{
		  height:100px;
		  overflow-y:auto;
	  }
	
	.footer {
		padding-top:30px;
		padding-bottom:30px;
		bottom: 0;
		width: 100%;
		background-color: #f5f5f5;
		font-size:15px;
	}
	
	.footer a{
		font-size:15px;
		font-family:Lato;
		color:black;
		font-weight:bold;
	}
	.footer a:hover,
	.footer  a:link,
	.footer  a:focus,
	.footer  a:active,
	.footer  a:visited{
		text-decoration: none;
		color: inherit;
	}
	
	.footer h2{
		font-family:Lato;
		font-size:15px;
		font-weight:bold;
	}
	.footer p{
		font-family:Lato;
		font-size:12px;
	}
	
	
	
	.social-list li {
		list-style-type: none;
	}
	.social-list li{
		cursor: pointer;
		display: inline-block;
		margin: 0.7rem -0.3rem 0 0;
		-moz-border-radius: 20px;
		padding: 8px 8px;
		color: #2c3e50;
		text-align: left;
		font-weight: 900;
		
	 }
	 .social-list li:nth-child(3):after { 
		content:"\A"; white-space:pre; 
	 }
	  
	.social-list li:hover{
		color:#009cdd;
		background:#ecf0f1;
		border-color:#009cdd;
	}
	  
	  .social-list a{
		  font-size:27px;
		  font-family:Lato;
		  color:black;
	  }
	  
	  
	  .social-list a:hover,
	  .social-list a:link,
	  .social-list a:focus,
	  .social-list a:active,
	  .social-list a:visited
	  {
		  text-decoration: none;
		  color: inherit;
		  
	  }
	  .social-list a{
		  display: inline;
		  color: inherit;
	  }


    </style>

  </head>

  <body>
    <div id="full-window">       
		<div id="div_banner" class="container-fluid">
			<div class="row">
				<div class="col-lg-7" style="border-right: solid 4px;">
					
					<div class="row">
						<div class="col-lg-2">
						</div>
						<div class="col-lg-8" style="margin-top: -20px;">
							<p id="suruna_info"> SURUNA </p>
							<p id="suruna_message"> Creando sitios inteligentes de video </p>
						</div>
						<div class="col-lg-2">
						</div>
						  
					</div>
				
				</div>
				<div class="col-lg-5" style="height:75px;">
					<div class="banner" id="client_info">
					  <span id="channel_title" style="color:#c6e2fb;">CADE - EJECUTIVOS</span>
					  <span id="suruna_link"> Pide <span style="font-family:rezland;font-size:24px;font-style:normal;">suruna</span> <i class="glyphicon glyphicon-new-window"></i></span>
					</div>
				</div>
			</div>
		</div>   
		<div class="container-fluid" id="tag-search">
	  <div class="row">
				<div class="col-lg-7" id="video-player">
					<div id="div_video">
						
						<div class="current_video" id="video_container">
						
							<script type="text/javascript">
							  jwplayer("video_container").setup({
								width: "768px",
								height: "432px", 
								autostart: "false",
								autoplay: false,
								image: "../../usuarios/" + '<?php echo $user->nombre_carpeta;?>' + "/imag/" + '<?php echo $last_video->previews; ?>' ,
								/* skin: 'http://alplayer.s3.amazonaws.com/skins/' + '<?php echo $currentSkin; ?>' + '/' + '<?php echo $currentSkin; ?>' + '.zip', */ 
								skin:"../lulu.zip",
								modes: [
								  { type: "flash",
											src: "http://alplayer.s3.amazonaws.com/player.swf",
									config: {
									  file: 'mp4:amazons3/' + '<?php echo $user->nombre_carpeta;?>' + '/' + '<?php echo $last_video->nombre_archivo; ?>',
									  streamer: "rtmp://107.21.113.161/vods3/",
									  provider: "rtmp",
									}                
								  },
								  {type: "html5",
									config: {
									  file: 'http://s3.amazonaws.com/' + '<?php echo $user->nombre_carpeta;?>' + '/' + '<?php echo $last_video->nombre_archivo; ?>'
									}
								  },
								  {type: "download"}
								]                   
							  });
							</script>
						
						</div>
						<div class="current_video" style="height: 35px;font-family:Abel;">
							<span id="video_info"><?php echo  $last_video->titulo; ?> - <span style="font-size:20px;"><?php echo round($datos_last_video->getDuration/60,2)." ";?>min</span></span>
						</div>
					</div>
					<div id="div_video_description">
						<div class="row slider-field" >
							<div class="col-lg-1">
							</div>
							<div class="col-lg-7" id="slider-1">
							</div>
							<div class="col-lg-4">
								<span>Tag:</span>
								<span id="current-tag-1"></span>
							</div>
						</div>
						<div class="row slider-field" >
							<div class="col-lg-1">
							</div>
							<div class="col-lg-7" id="slider-2">
							</div>
							<div class="col-lg-4">
								<span>Tag:</span>
								<span id="current-tag-2"></span>
							</div>
						</div>
					</div>
					<script>
					  $( function() {
						$( "#slider-1" ).slider({
							max:<?php echo round($datos_last_video->getDuration,0);?>,
							min:0
						});
						//$( "#slider-1" ).slider( "disable" );
						$( "#slider-1" ).css('background', 'rgba(40, 96, 144, 0.88)');
						$( "#slider-1" ).css('margin-top', '5px');
						$( "#slider-1 .ui-slider-range" ).css('background', 'rgba(2, 21, 56, 0.21)');
						$('#slider-1').unbind('mousedown');
						
						$( "#slider-2" ).slider({
							max:<?php echo round($datos_last_video->getDuration,0);?>,
							min:0
						});
						//$( "#slider-1" ).slider( "disable" );
						$( "#slider-2" ).css('background', 'rgb(92, 184, 151)');
						$( "#slider-2" ).css('margin-top', '5px');
						$( "#slider-2 .ui-slider-range" ).css('background', 'rgba(2, 21, 56, 0.21)');
						$('#slider-2').unbind('mousedown');
					  } );
					</script>
				</div>
				<div class="col-lg-5">
					<div class="col-lg-12 tag-container">
						<h2 style="font-family:Abel">Etiquetas Cognitivas</h2><span style="top:-35px;right:-246px;position:relative;"><a href="#" id="help-service">Ayuda</a><i class="glyphicon glyphicon-question-sign"></i></span>
					
						<p style="font-family:Abel;font-size:20px;margin-top: -18px;">Usa las etiquetas cognitivas generadas por Suruna para navegar por los conceptos más importantes del video.</p>
						
						<div class="row">
							
								<ul id="tag-list">
									
								</ul>
								<script type="text/javascript">
								
									/*Current GLOBAL Variables for Words and Positions*/
									
									var jsonArrVideoWord = [];
									var jsonArrWordPosition=[];
									var currentWordVideoId_tag1=null;
									var positionList_tag1=[];
									var currentWordVideoId_tag2=null;
									var positionList_tag2=[];
									var currentVideoDuration = <?php echo $datos_last_video->getDuration;?>;
									
									/*Current GLOBAL Variables for slider position*/
									var tag1_index = 0;
									var tag1_index = 0;
									var tag1_size=0;
									var tag2_size=0;
									
									console.log(currentVideoDuration);
									$('document').ready(function(){ 
										var currentVideoId = "<?php echo  $last_video->videoid; ?>";
										var currentAzureId = "<?php echo  $last_video->idazure; ?>";
										console.log(currentAzureId);
										$.get("../../services/VideoWordService.php?idvideo="+currentVideoId,function(data){
											//console.log(data);
											jsonArrVideoWord = jQuery.parseJSON(data);
											$.each(jsonArrVideoWord,function(key,obj){
												var videoWordId = obj['idvideo_word'];
												var wordvalue = obj['wordvalue'];
												var newLine="<li class=\"tag-li\">"+
																"<a>"+
																	"<span class=\"tag-span\" >"+wordvalue+"<i class=\"glyphicon glyphicon-play\"></i>"+ 
																		"<input name=\"video_word_id\" type=\"hidden\" value=\""+videoWordId+"\">"+
																		"<input name=\"word_value\" type=\"hidden\" value=\""+wordvalue+"\">"+
																	"</span>"+	
																"</a>"+
															"</li>";
												
												$("#tag-list").append(newLine);
												$.get("../../services/WordPositionService.php?idwvideo="+videoWordId,function(data){
													jsonArrTemp=jQuery.parseJSON(data);
													$.each(jsonArrTemp,function(key,obj){
														jsonArrWordPosition.push(obj);
													});
													
												});
											});

										}).fail(function(){
											console.log("Error al cargar datos de la VideoWordService.php");
										});
										
										$("#suruna_link").click(function(){
											//window.location.replace("http://suruna.com/trial-suruna/");
											window.location.href='http://suruna.com/trial-suruna/';
										});
										$('ul').on('click','li a span.tag-span',function(){
												if(currentWordVideoId_tag1==null){
													var videoWordId = $(this).find('input[name="video_word_id"]').val();
													var wordValue = $(this).find('input[name="word_value"]').val();
													currentWordVideoId_tag1=videoWordId;
													positionList_tag1=getPositionList(videoWordId);
													tag1_index=0;
													tag1_size=positionList_tag1.length;
													$("#current-tag-1").html("<span class=\"current-tag\">"+wordValue+"</span><span class=\"del-cur-tag-span\"><i class=\"glyphicon glyphicon-remove\"></i></span>");
												}else{
													if(currentWordVideoId_tag2==null){
														var videoWordId = $(this).find('input[name="video_word_id"]').val();
														var wordValue = $(this).find('input[name="word_value"]').val();
														currentWordVideoId_tag2=videoWordId;
														positionList_tag2=getPositionList(videoWordId);
														tag2_index=0;
														tag2_size=positionList_tag2.length;
														$("#current-tag-2").html("<span class=\"current-tag\">"+wordValue+"</span><span class=\"del-cur-tag-span\"><i class=\"glyphicon glyphicon-remove\"></i></span>");
													}
												}

										});
										
										$('#slider-1').on('click','span.ui-slider-handle',function(){
											console.log("Handle 2...");
											var val = $( "#slider-1" ).slider( "option", "value" );
											console.log(val);
											jwplayer().seek(val); 
										});
										
										$('#slider-2').on('click','span.ui-slider-handle',function(){
											console.log("Handle 2...");
											var val = $( "#slider-2" ).slider( "option", "value" );
											console.log(val);
											jwplayer().seek(val);
										});
										
										$('#current-tag-1').on('click','span.current-tag',function(){
											console.log("Click Current tag 1");
											$( "#slider-1" ).slider( "option", "value", positionList_tag1[tag1_index]);
											tag1_index++;
											if(tag1_index==tag1_size){
												tag1_index=0;
											}
											
										});
										$('#current-tag-2').on('click','span.current-tag',function(){
											console.log("Click Current tag 2");
											$( "#slider-2" ).slider( "option", "value", positionList_tag2[tag2_index]);
											tag2_index++;
											if(tag2_index==tag2_size){
												tag2_index=0;
											}
										});
										$('#current-tag-1').on('click','span.del-cur-tag-span',function(){
											console.log("Deleting Current tag 1");
											$('#current-tag-1').empty();
											$( "#slider-1" ).slider( "option", "value", 0);
											currentWordVideoId_tag1=null;
											positionList_tag1=[];
										});
										$('#current-tag-2').on('click','span.del-cur-tag-span',function(){
											console.log("Deleting Current tag 2");
											$('#current-tag-2').empty();
											$( "#slider-2" ).slider( "option", "value", 0);
											currentWordVideoId_tag2=null;
											positionList_tag2=[];
										});
										
										
										$('#res-word-search').on('click','li',function(){
											var position = $(this).find('input').val();
											//console.log("position:"+position);
											jwplayer().seek(position);
										});
										
										
										$('.holder').on('click','a',function(){
											console.log("Here")
											$(".li-all-vid").each(function(){
												console.log($(this).css("display"));
												if ($(this).css("display")==="none"){
													$(this).addClass("jp-hidden");
												}
											});
											
											$(".li-all-vid").css('display','inline');
										});
										
										
										$("#search-btn").click(function(){
											var baseURL = "https://busqueda.search.windows.net/indexes/surunadev/docs";
											
											var videoFilter = "assetid eq "+"'"+currentAzureId+"'";
											var word = $("#search-input-text").val();
											var wordCount;
											$.ajaxSetup({
												beforeSend: function(xhr) {
													xhr.setRequestHeader('CORS', 'Access-Control-Allow-Origin');
												}
											});
											
											
											$.ajax({
												url: baseURL,
												type:'GET',
												dataType: 'json',
												data:{"api-version":"2015-02-28","search":word,"$select":"text,begin,end","$filter":videoFilter,"searchMode":"all"},//},//},//,"$select":"text"},//,"$filter":"assetid eq "+videoAzureId},
												headers: {'api-key':'A2658924BD81AAE9157FE9B83B201628'},
												success: function(data){
													var arr  = data.value;
													var size = arr.length;
													$( "#num-results" ).hide();
													$( "#res-word-search-container" ).hide();
													$("#res-word-search").empty();
													if(size>0){
														$.each(arr,function(k,v){
															var seconds = getSecondsPosition(v.begin);
															var newListItem = '<li><a>'+(k+1)+' : '+v.text+'</a><input type="hidden" value="'+seconds+'"></li>';
															$("#res-word-search").append(newListItem);
															console.log(word+" : "+k+" : "+v.begin+" : "+v.text+" : "+seconds);
														});
														$( "#res-word-search-container" ).slideDown( "slow", function() {
															// Animation complete.
														});	
													}
													$("#num-results").html(size);
													$( "#num-results" ).slideDown( "slow", function() {
															// Animation complete.
														});
													console.log(size);
												
												}
											});	
										});
		
										function getSecondsPosition(stringPosition){
											var hours = parseInt(stringPosition.substr(0,2));
											var mins = parseInt(stringPosition.substr(3,2));
											var secs = parseInt(stringPosition.substr(6,2));
											
											return hours*3600+mins*60+secs;
										}
											
										function getPositionList(vw_id){
											var result = [];
											var i = 0;
											$.each(jsonArrWordPosition,function(k,data){
												
												if(data['idvideo_word']==vw_id){
													result[i]=data['seconds_ini'];
													i++;
												}
											});
											return result;
										}
										
										
									});
								</script>
								
							</div>
					</div>
					<hr>
					<div class="col-lg-12" style="height:300px;">
						<h2 style="font-family:Abel">Crea tu  Etiqueta</h2>
				
						<p style="font-family:Abel;font-size:20px">Escribe una palabra o concepto.</p>
						<div class="input-group">
						  <input id="search-input-text" type="text" class="form-control" placeholder="Buscar...">
						  <span class="input-group-btn">
							<button id="search-btn" class="btn btn-primary" type="button"><i class="glyphicon glyphicon-search"></i></button>
							
						  </span>
						</div>
					
				
						<p style="font-family:Abel;font-size:20px">Resultados:<span id="num-results"></span></p>
						<div id="res-word-search-container">
							<ul id="res-word-search">
								
							</ul>
						</div>
					</div>
						
						<script>
							
							
							$('#help-service').click(function(){
								$.confirm({
									title: 'Instrucciones de Uso',
									theme:'supervan',
									content: '<ol style="font-family:Abel;font-size:18px;text-align:left;">'+
												'<li>Selecciona la etiqueta que quieres buscar en el video.</li>'+
												'<li>Ve a la barra de ubicación debajo del video.</li>'+
												'<li>Aprieta la etiqueta al lado derecho de la barra.</li>'+
												'<li>Ahora puedes hacer click en el puntero de la barra para ir al contenido.</li>'+
											 '</ol>',
									buttons: {
										ok: {
												btnClass:'btn-blue',
												action:function () {
													//$.alert('Estaremos resolviendolo en breve, gracias!!!');
												}
										}
									}	
								});	
							});
							
						</script>
					
				</div>	
			</div>
		</div>
		<div class="container-fluid" id="recomended-container">
			<div class="row">
				<div id="div_videos_recommended" class="col-lg-12">
					<h2 style="font-family:Abel">Recomendados | <span style="font-family:Abel;font-size:20px">Suruna te recomienda los videos analizando palabras, textos, imágenes y sonidos dentro del video</span></h2>
					<div id="videos_recommended">
						<?php 
						$elements = 4;
						$sim = get_similars($last_video->videoid, $user->idusuario, 4, 30, $elements);
						for ($i = 0; $i < $elements; $i++){
							$similar_video =  new Videos();
							$similar_video->videoid = $sim[$i]->_label;
							$similar_video->cargarforID();
							echo "<div class=\"video_recommended col-lg-3\" id=\"video_recommended_1\">";
							echo "<a href=\"index.php?nid=$similar_video->videoid\" > <img src=\"http://ia.suruna.com//usuarios/$user->nombre_carpeta/previ/$similar_video->previews\" height=\"80%\" width=\"100%\"></a>";
							echo "<span> <a href=\"index.php?nid=$similar_video->videoid\" class=\"video_recommended_content\" style=\"display:-webkit-box;\"> $similar_video->titulo</a></span>";
							echo "</div>";
						}
						?>
					</div>
				</div>
			</div>
		</div>
		
		
		<div class="container-fluid" id="recomended-container">
			<div class="row" style="background:#fff;">
				<div id="div_videos_recommended" class="col-lg-12">
					<h2 style="font-family:Abel">Todos los videos | <span style="font-family:Abel;font-size:20px"> Todo el contenido del CADE 2016</span></h2>
					<div id="videos_recommended2">
						<?php 
						$elements = 4;
						
						$videos = $last_video->getVideosForIdUsuario();
						$coun = 0;
						echo "<ul id=\"all-video-container\" class=\"all-videos-list\">";
						foreach($videos as $video){
							echo "<li class=\"li-all-vid\">";
							echo "<div class=\"video_recommended col-lg-3\">";
							echo "<a href=\"index.php?nid=$video->videoid\"  > <img src=\"http://ia.suruna.com//usuarios/$user->nombre_carpeta/previ/$video->previews\" height=\"70%\" width=\"100%\"></a>";
							echo "<span> <a href=\"index.php?nid=$video->videoid\" class=\"video_recommended_content\" style=\"display:-webkit-box;\"> $video->titulo</a></span>";
							echo "</div>";
							echo "</li>";
						}
						echo "</ul>";
						
						?>
						
						
					</div>
					<div class="col-lg-3">
					</div>
					<div class="holder col-lg-6" style="text-align:center;">
					</div>
					<div class="col-lg-3">
					</div>
					<script>
						$(function(){
							$("div.holder").jPages({
								containerID:"all-video-container",
								scrollBrowse:false,
								perPage:4
							});
						});
					</script>
					
				</div>
			</div>
		</div>
		
		<footer class="footer">
			<div class="container">
				<div class="row">
					<div class="col-lg-3" style="margin-top:50px;">
						<a href="#">Términos de uso</a><br>
						<a href="#">Política de privacidad</a><br>
						<p><i class=""></i> © 2012 - 2016 Suruna, Inc</p>
					</div>
					<div class="col-lg-3">
						<h2>Acerca de Nosotros:</h2>
						<p>Somos un equipo de apasionados <br>por la Inteligencia Artificial <br>buscando nuevos retos y oportunidades<br> para mejorar nuestro mundo.</p>
						<button class="btn btn-information" onClick="javascript:window.location.href='http://suruna.com/trial-suruna/'">Contáctanos</button>
					</div>
					<div class="col-lg-3">
						<h2>Síguenos:</h2>
						<ul class="social-list" style="padding-left: 0px;">
							<li><a><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
							<li><a><i class="fa fa-linkedin-square" aria-hidden="true"></i></a></li>
							<li><a><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
								
						</ul>
					</div>
					<div class="col-lg-3">
						
					</div>
				</div>
			</div>
		</footer>
		
		<!--Modal de Instrucciones-->
		
		
	</div>
  </body>
</html>