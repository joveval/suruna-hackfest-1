<?php
include '../../models/DBConnection.php';
include '../../models/videos_comentarios.php';
include '../../models/usuario_comentarios.php';


if(isSet($_POST['vid_id']))
{
	
	$id = $_POST['vid_id'];
	$video_cmmt = new videos_comentarios();
	$comentarios = array();
	$comentarios = $video_cmmt->get_set_by_id_video($id);
	
	foreach ($comentarios as $cmt){
      $user_cmt = new usuario_comentarios(); 
      $user_cmt->get_by_id($cmt->id_usuario_cmmt);
      echo "<div class=\"c_current_video_comments\">";
      echo "<img src=\"http://suruna.com/app/canales/test_canal/profile.jpg\" height=\"32\" width=\"40\">";
      echo "<div class=\"name_comment\">";
      echo "<span class=\"name\">".$user_cmt->nombre." ". $user_cmt->apellidos."    </span>";
      echo "<span class=\"comment\">". $cmt->comentario ."</span>";
      echo "</div>";              
      echo "</div>";        
    }
    echo "<div class='c_current_video_comments'>
                <img src='http://suruna.com/app/canales/test_canal/profile.jpg' height='32' width='40' style='float: left;'>
                <form id='form_comments' name='commentform' action='' method='post' style='float: left;'>
                  <textarea id='input_text_comment' name='input_text_comment' type='text' placeholder='Escribe un comentario...'></textarea>
                </form>
              </div>";
}
?>
