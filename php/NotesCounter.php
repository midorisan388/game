<?php
ini_set('display_errors',"On");
error_reporting(E_ALL);

try{
    function NotesCol($gametime,$notesdata,$id){
        $timingZone=array(
            "BAD"=>array(
                "judget"=>0.42,
                "upScore"=>0
            ),
            "GOOD"=>array(
                "judget"=>0.38,
                "upScore"=>100
            ),
            "GREAT"=>array(
                "judget"=>0.25,
                "upScore"=>300
            ),
            "PARF"=>array(
                "judget"=>0.06,
                "upScore"=>700
            )
        );

        //初期化
        $hanteicount = array("MISS"=>0,"BAD"=>0,"GOOD"=>0,"GREAT"=>0,"PARF"=>0,"COMB"=>0,"SCORE"=>0);
        $hanteicount["SCORE"]=(!isset($_SESSION["Score"]))?0:0;//$_SESSION["Score"];
        $hanteicount["COMB"]=(!isset($_SESSION["COMB"]))?0:0;//$_SESSION["COMB"];

        $battleNotesdatas = $notesdata;
        if(isset($battleNotesdatas)){
         $noteslength = count($battleNotesdatas);//要素数取得
        }else{
            echo "count=0";
            $noteslength=0;
        }
        $count = 0;

        //タイミングの計算
        foreach($battleNotesdatas as $battleNotes){
            if($battleNotes["judge"]==="ALWAY"){
                //未処理のノーツから検査
                if((int)$battleNotes["lernID"] === $id){

                    $timejudge = (float)$gametime-(float)$battleNotes["timing"];

                    if(abs($timejudge) <= $timingZone["PARF"]["judget"]){
                        $battleNotesdatas[$count]["judge"] = "PARF";
                      
                        $hanteicount["SCORE"] +=$timingZone["PARF"]["upScore"];
                        break;
                    }else if(abs((float)$timejudge) <= $timingZone["GREAT"]["judget"]){
                        $battleNotesdatas[$count]["judge"] = "GREAT";
                      
                        $hanteicount["SCORE"] +=$timingZone["GREAT"]["upScore"];
                        break;
                    }else if(abs((float)$timejudge) <= $timingZone["GOOD"]["judget"]){
                        $battleNotesdatas[$count]["judge"] = "GOOD";
                       
                        $hanteicount["SCORE"] +=$timingZone["GOOD"]["upScore"];
                        break;
                    }else if(abs((float)$timejudge) <= $timingZone["BAD"]["judget"]){
                        $battleNotesdatas[$count]["judge"] = "BAD";
                        $hanteicount["SCORE"] +=$timingZone["BAD"]["upScore"];
                       
                        break;
                    }else if((float)$timejudge > $timingZone["BAD"]["judget"]){//許容時間超過
                        $battleNotesdatas[$count]["judge"] = "MISS";
                        
                        break;
                    }else {//判定範囲外
                        $battleNotesdatas[$count]["judge"] = "ALWAY";
                        continue;
                    }
                }
            }
            $count++;
        }
//ノーツ全部流した後の戦闘続行用データ
        if($count >= $noteslength){
            $notesresponsdata=array(
                "lernID"=>0,
                "timing"=>0,
                "type"=>"AKT",
                "judge"=>"GREAT"
            );
        }else{
            $notesresponsdata=$battleNotesdatas[$count];
        }
        //判定配列の更新
        foreach($battleNotesdatas as $battleNotes){
            if($battleNotes["judge"] === "MISS"){
                $hanteicount["MISS"]++;
                $hanteicount["COMB"]=0;
            }
            else if($battleNotes["judge"] === "BAD"){
                $hanteicount["BAD"]++;
                $hanteicount["COMB"]++;
            }
            else if($battleNotes["judge"] === "GOOD"){
                $hanteicount["GOOD"]++;
                $hanteicount["COMB"]++;
            }
            else if($battleNotes["judge"] === "GREAT"){
                $hanteicount["GREAT"]++;
                $hanteicount["COMB"]++;
            }
           else if($battleNotes["judge"] === "PARF"){
                $hanteicount["PARF"]++;
                $hanteicount["COMB"]++;
            }
        }

       $_SESSION["COMB"] =  $hanteicount["COMB"];
       $_SESSION["Score"] =  $hanteicount["SCORE"];
       $_SESSION["notesdata"]=$battleNotesdatas;//ノーツデータ更新

        $resdata =array(
            "notesdatas"=> $notesresponsdata,//判定したノーツのデータ
            "hanteilist"=>$hanteicount//成績データ
        );

        $resdata = mb_convert_encoding($resdata, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');

        return $resdata;
    }

}catch(PDOExeption $erro){
    echo "次のエラーが発生しました<br>";
    echo $erro->getmessage();
}
?>