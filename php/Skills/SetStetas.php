<?php

ini_set('display_errors',"On");
error_reporting(E_ALL);
require_once("../Skills/SkillClass.php");

//バフデバフ付与スキル
class SetStetas extends SkillBase{
    private $setStetasObj;//追加ステータスクラスオブジェクト
    private $stetasObjList=array();//オブジェクトリスト
    private $stetas_;//ステータスIDリスト

    //スキル名称,固有引数データ
    public function __construct($name,$stetasArray){
        $this->skillname=$name;
        $this->stetas_=$stetasArray;//ステータス異常IDリスト

        $stetasFile=file("../../datas/csv/StetasDataList.csv");//ステータスリストファイル読み込み
         //ステータス配列に格納されているIDと一致するかを検索
         foreach($this->stetas_ as $stetasId){

            foreach($stetasFile as $stetas){
             $data = explode(',', $stetas);
             $dataIndex =(int)$data[0];//ステータスレコードのIDをキャスト
             $stetasIndex =(int)$this->stetas_[$i];//ステータスリストのIDをキャスト

             if($dataIndex === $stetasIndex){//ステータスIDがargument[]のIDと一致するか
                 require_once("../StetasClass/".$data[2].".php");
                                         //クラス名　　表示名    対象   効果量   持続ターン　バフデバフ　計算タイミング
                 $this->setStetasObj = new $data[2]($data[1],$data[3],$data[4],$data[5],$data[6],$data[7]);//ステータスクラス生成
                array_push($this->stetasObjList, $this->setStetasObj);//ステータスリストに追加
             }
         }
        }
       // echo("状態異常スキル:".$this->skillname."を装備<br>");
    }

    public function skillaction($actionplayer, $targrtSt){ 
       
            foreach($this->stetasObjList as $stetas){
                //対象判定
                if($stetas->target === "party"){
                    //味方にステータス付与     
                    array_push($actionplayer["stetas"], $stetas);
                    //echo("メンバーに".$stetas->statename."を付与<br>");
                    break;
                }else if($stetas->target === "enemy"){
                    //敵にステータス付与
                    array_push($targrtSt["stetas"], $stetas);
                    //echo("敵に".$stetas->statename."を付与<br>");
                    break;
                }else{
                    array_push($actionplayer["stetas"], $stetas);
                    //echo("その他に".$stetas->statename."を付与<br>");
                }
               
            }
            //echo $this->skillname." 発動！<br>";
           
            $resdata = array("acter"=>$actionplayer,"target"=>$targrtSt);
       // return $resdata; 
        //echo("ステータス<br>");
       //var_dump($resdata["acter"]["stetas"]);
    }
}


?>