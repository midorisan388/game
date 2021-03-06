<?php

require 'php/password.php';
  session_start();

  ini_set('display_errors',"On");
  error_reporting(E_ALL);

  require_once("./php/getcharacterlist.php");//キャラクターレコードを取得する処理ファイル

  $id_cookie = $_SESSION['userid'];
  $characters_image_dir ="./img/characters/";
  $csvpath="./datas/csv/CharactersStetas.csv";
  $APhealtime = 10;//分

  $userdata=array(
    'userID'=>$id_cookie,
    'username'=>"",
    'userrank'=>0,
    'userunitID'=>0,
    'userTitleID'=>0,
    'userStage'=>0,
    'userAP'=>0,
    'userMaxAP'=>0,
    'userexp'=>0,
    'usernextexp'=>0,
    'userstartdate'=>0,
    'userlastdate'=>0,
    'userAPupdatedate'=>0,
  );

  try{

    //SQL接続-----------------------------------------------------------------
    require_once("./datas/sql.php");
    $sql_list=new PDO("mysql:host=$SERV;dbname=$DBNAME",$USER,$PASSWORD);
    $sql_list->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
    $sql_list-> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    //----------------------------------------------------------------------

     //ユーザー情報取得
     $userinfo=$sql_list->query("CALL getuseraccountdata('$id_cookie')");
     $kekka=$userinfo->fetch();

        $userdata['userID']=$kekka['userID'];
        $userdata['username']=$kekka['username'];
        $userdata['userexp']=$kekka['userhaveExp'];
        $userdata['userAP']=$kekka['playerAP'];
        $userdata['userMaxAP']=$kekka['playerMaxAP'];
        $userdata['usernextexp']=$kekka['usernextExp'];
        $userdata['userrank']=$kekka['userRank'];
             
        $_SESSION["USER_DATA"] = $userdata;//ユーザー情報を保持
        $_SESSION["PARTY_IDS"] = array(
        "1st"=> $kekka['1st'],
        "2nd"=> $kekka['2nd'],
        "3rd"=> $kekka['3rd'],
        "4th"=> $kekka['4th']
        );

   //PT情報取得  
   $partymember_id=(int)$_SESSION["PARTY_IDS"]['1st'];//一番目のキャラクターID取得
   
   $character_data = getRecord($partymember_id,$csvpath);//キャラクターデータ取得

   $character_image_dir = "./img/characters/{$character_data[1]}/{$character_data[1]}0201.png";//通し番号立ち絵


  }catch(PDOExeption $erro){
    echo "次のエラーが発生しました<br>";
    echo $erro->getmessage();
  }
?>
<!DOCTYPE html>
<html> 
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" media="screen" href="./css/accountpage.css" />
        <link rel="stylesheet" href="./css/MenuVar.css" />
        <script type="text/javascript" src="./js/prag/jquery-3.3.1.js"></script>
        <script> 
            $(window).on('load',function(){
                $('audio').prop('volume',0);
                $(".loading").addClass('loading_comp');
                $(".loading").removeClass('loading');
                $(".loading").fadeOut();
            });
          </script>
    </head>
    <body>
         <audio id="homebgm" type="audio/ogg" src="./audio/bgm/homebgm.ogg" preload="auto" autoplay loop>
             <p>音声を再生するには、audioタグをサポートしたブラウザが必要です。</p>
         </audio>
        <div class="main-contents">
        <div class="left-contents">
            <div id="user-main-character"><img class="user-main-character-img" src=<?php echo $character_image_dir; ?>></div>
        </div>
        <div class="right-contents">
            <div id="user-status-contents">
                <div class="user-status-frame">
                    <div id="user-status-main">
                         <div id="user-status-basedata">
                             <div id="user-name"><p id="user-name-main"><?php echo $userdata['username'];?></p></div>
                             <div id="user-rank">
                                 <p id="user-rank-main">Rank <?php echo $userdata['userrank'];?></p>
                                 <div class="user-status-exp">
                                     <div id="user-exp-bars">
                                         <div id="user-rank-back"></div>
                                        <div id="user-rank-bar"></div>
                                    </div>
                                     <div id="user-rank-exp">
                                         <span id="h"><?php echo $userdata['userexp'];?></span>/<span id="n"><?php echo $userdata['usernextexp'];?></span>
                                    </div>
                                </div>
                            </div>
                         </div>
                    
                        <div class="user-status-questcount">
                            <div id="questcount-title">クエストカウント</div>
                            <div id="questcount-clearcount"><span>クエストクリアカウント</span>回</div>
                            <div class="quest-grade-main">
                                 <div id="quest-grade-title">成績タイトル</div>
                                 <div id="quest-grade-bad"><span id="bad">バッド</span>%</div>
                                 <div id="quest-grade-nice"><span id="nice">ナイス</span>%</div>
                                 <div id="quest-grade-gread"><span id="gread">グレート</span>%</div>
                                 <div id="quest-grade-parfect"><span id="parfect">パーフェクト</span>%</div>
                            </div>
                            <div class="user-collection-main">
                                <div id="character-possession"><span id="user-character-possession">5</span>/<span id="character-max">12</span></div>
                                <div id="wepon-possession"><span id="user-wepon-possession">10</span>/<span id="wepon-max">30</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="loading"></div>
    <script type="text/javascript" src="./js/MenuVarInsert.js"></script>
    </body>
</html>