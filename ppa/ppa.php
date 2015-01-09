<?
error_reporting(E_ERROR);
define("DB_HOST", "localhost");
define("DB_NAME", "ppa");
define("DB_USER", "ppa");
define("DB_PASS", "kfc3*9mn");
define("DB_DEBG", false);
define("CHAPTER_IMAGES_PATH", "chapter_images");

require("header.php");
session_start();
setlocale (LC_ALL,"spanish");
if( isset( $_GET['clients'] ) || isset( $_GET['edit_client'] ) || isset( $_GET['add_client'] ) || isset( $_POST['send_client'] ) || isset( $_GET['delete_client'] ) || isset( $_GET['show_channels'] ) || isset( $_POST['asign_channel'] ) || isset( $_GET['delete_channel'] ) || isset( $_GET['edit_channel'] ) || isset( $_POST['channel_edit'] ) ){
        require("ppa/clients.php");
}else{
        if( isset( $_GET['channels'] ) || isset( $_GET['add'] ) || isset( $_POST['send_channel'] ) || isset( $_GET['edit'] ) || isset( $_GET['delete'] ) || isset( $_GET['show_program'] ) || isset( $_POST['show_program'] ) || isset( $_GET['borrar_slot'] ) || isset( $_GET['asign_program'] ) || isset( $_POST['asign_program'] ) || isset( $_GET['asign_programnew'] ) || isset( $_POST['asign_programnew'] )  ){
        require("ppa/channels.php");
        }else{
        if( isset( $_GET['movie'] ) || isset( $_POST['search_movieEs'] ) || isset( $_GET['search_movie'] ) || isset( $_POST['search_movie'] ) || isset( $_POST['search2_movie'] ) || isset( $_GET['edit_movie'] ) || isset( $_GET['delete_movie'] ) || isset( $_GET['add_movie'] ) || isset( $_POST['send_movie'] ) || isset( $_POST['dup_movie'] ) ){
                require("ppa/movies.php");
        }else{
                if( isset( $_GET['serie'] ) || isset( $_POST['search_seriesEs'] ) || isset( $_GET['search_series'] ) || isset( $_POST['search_series'] ) || isset( $_POST['search2_series'] ) || isset( $_GET['edit_series'] ) || isset( $_GET['delete_series'] ) || isset( $_GET['add_series'] ) || isset( $_POST['send_series'] ) || isset( $_POST['dup_series'] ) ){
                                require("ppa/series.php");
                }else{
                                if( isset( $_GET['special'] ) || isset( $_POST['search_specialEs'] ) || isset( $_GET['search_special'] ) || isset( $_POST['search_special'] ) || isset( $_POST['search2_special'] ) || isset( $_GET['edit_special'] ) || isset( $_GET['delete_special'] ) || isset( $_GET['add_special'] ) || isset( $_POST['send_special'] ) || isset( $_POST['dup_special'] ) ){
                                        require("ppa/specials.php");
                                }else{
                                        if( isset( $_GET['asign_chapters'] ) || isset( $_POST['asign_chapters'] )){
                                            require("ppa/asign_chapters.php");   
                                        }else{
                                                if( isset( $_GET['delete_programs'] ) ){
                                                    require("ppa/del_programs.php");     
                                                }else{
                                                        if( isset( $_GET['dup_programs'] ) ){
                                                            require("ppa/dup_programs.php");     
                                                        }else{
                                                                if( isset( $_GET['exit_asign_chapter'] ) ){
                                                                        $_SESSION['programs_found'] = array();
                                                                require("ppa/index.php");
                                                                }else{
                                                                require("ppa/index.php");
                                                                }
                                                        }
                                                }
                                  }
                                }
              }
      }
   }
}

require("footer.php");
?>