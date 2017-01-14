<script>
	(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.0";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

	FB.XFBML.parse(); 
</script>
<?php
include '../../models/DBConnection.php';
include '../../models/Videos.php';
include '../../models/Usuario.php';
include '../../models/videos_comentarios.php';

$page = $_GET['p'];
$iduser = $_GET['idusr'];
//$start = ($page-1)*2;
$start = ($page+2);

$user =  new Usuario();
$user->idusuario = $iduser;
$user->cargarforID();

$last_video = new Videos ();
$last_video->idusuario = $iduser;
$videos_lr = array();
$videos_lr = $last_video->getTimeline($start,2);
$dir = 0;
echo "<div class=\"tmp_help_scroll\" id='$start'>";
echo "</div>";

foreach ($videos_lr as $videos){
	if ($dir == 0){
	echo "<div id=\"left_post_timeline\">";
	$dir = 1;
	}
	elseif ($dir == 1){
		echo "<div id=\"right_post_timeline\">";
		$dir = 0;
	}
	$_vcmmts = new videos_comentarios();
	$_comentariosxv = array();
	$_comentariosxv = $_vcmmts->get_set_by_id_video($videos->videoid);	
	echo "<div class=\"post_timeline_video\">";
	echo " 	<div class=\"thumbnail_info\">";
	echo "   <a href=\"index.php?nid=$videos->videoid\" ><img src=\"http://suruna.com/app/usuarios/$user->nombre_carpeta/previ/$videos->previews\" height=\"118\" width=\"178\"></a>";
	echo "   	<div class=\"title_info\">";
	echo "     	<a href=\"index.php?nid=$videos->videoid\" ><span class=\"title\">$videos->titulo</span></a>";
	if (empty($videos->descripcion_larga))
		echo "      <span class=\"info\">No disponible </span>";
	else	
		echo "      <span class=\"info\">$videos->descripcion_larga </span>";
	echo "    	</div>";
	echo "  </div>"; 
	echo "  <div class=\"actions_like_comments\">";
	echo "    <div class=\"actions\">";

	$f_status = 'http://www.suruna.com/app/canales/test_canal/index.php?nid=' . $videos->nombre_archivo;
	echo "<div class=\"fb-like\" data-href=\"$f_status\" 
	data-layout=\"button_count\" data-action=\"like\" data-show-faces=\"true\" data-share=\"true\"></div>";

	
//	echo "      <i class=\"fa-facebook-square fa\"></i>";
	echo "      <i class=\"fa-twitter-square fa\"></i>";
	echo "    </div>";
	echo "    <div class=\"like_comments\">";
//	echo "      <i class=\"fa-thumbs-o-up fa\">no disponible</i>";
//	echo "      <i class=\"fa-comment-o fa\">". count($_comentariosxv) ."</i>";
//	echo "      <i class=\"fa-share-square-o fa\">20</i>";
	echo "    </div>";
	echo "  </div>";
	echo "</div>";
	echo "</div>";
}

	
?>