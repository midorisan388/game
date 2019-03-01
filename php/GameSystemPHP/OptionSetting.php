<?php
    //オプション設定保存
    session_start();

    ini_set('display_errors',"On");
    error_reporting(E_ALL);

    try{
        $option_mode=$_POST["option"];

        switch($option_mode){
            case 'audio':
                //オーディオ設定
                $audio_vol=$_POST["audio_volume"];
                $_SESSION["AUDIO_VOLUME"]=$audio_vol;//音量保持
                
                $res=(int)$audio_vol;
            break;
            case 'se':
                //オーディオ設定
                $se_vol=$_POST["se_volume"];
                $_SESSION["SE_VOLUME"]=$se_vol;//音量保持

                $res=(int)$se_vol;
            break;
            default:
                //全体適用
                $audio_vol = (isset($_SESSION["AUDIO_VOLUME"]))?$_SESSION["AUDIO_VOLUME"]:0.1;
                $se_vol = (isset($_SESSION["SE_VOLUME"]))?$_SESSION["SE_VOLUME"]:0.5;

                $res=array(
                    "audio"=>(int)$audio_vol,
                    "se"=>(int)$se_vol
                );
               
            break;
        };

        header('Content-Type: application/json; charset=utf-8');
        $resjson =json_encode( $res,JSON_PRETTY_PRINT );
        echo $resjson;
        
    }catch(PDOExeption $erro){
        echo "次のエラーが発生しました<br>";
        echo $erro->getmessage();
    }
?>