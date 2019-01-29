<?php

ini_set('display_errors',"On");
error_reporting(E_ALL);

//バトルステータスクラス
class BattleActor{
    public $characterId=0;
    public $characterName="";
    public $battleType="";
    public $skillCurrentCount=0;
    public $curretnDamage=0;
    public $driveGage=0;
    public $HP=0;
    public $power=0;
    public $defense=0;
    public $magicPow=0;
    public $mental=0;

    public $skillParam;
    
    //csvから取得したキャラステータスを取得
    public function __construct($mStArray){
        $this->characterId=$mStArray["id"];//パーティ配置配置ID
        $this->characterName=$mStArray["name"];
        $this->battleType=$mStArray["type"];
        $this->skillCurrentCount=0;
        $this->curretnDamage=0;
        $this->HP=$mStArray["HP"];
        $this->power=$mStArray["pow"];
        $this->defense=$mStArray["def"];
        $this->magicPow=$mStArray["magicpow"];
        $this->mental=$mStArray["mental"];
    
        $this->skillParam = getSkillData($mStArray["skillid"]);
    }

    public function skillUse($playparty, $targetparty){
        $this->skillParam->skillaction($playparty, $tatrge, $this->characterId);
    }
};

//スキルデータ取得
function getSkillData($sid_){
    $skillArgument=array();
    $searchId =(int)$sid_;
    $skillFileLine = file("../../datas/csv/SkillList.csv");
    $skillLineLength = count($skillFileLine);
    $i=0;
    
    foreach( $skillFileLine as $skillLine){
        $sdata=explode(',', $skillLine);
        if($i > 0){//最初の行は飛ばす
         $sdataIndex = (int)$sdata[0];
            if($sdataIndex === $searchId){//スキルデータレコードにヒット
                 $skillData = $sdata;
 
                for($n=3; !empty($skillData[$n]); $n++){
                    array_push($skillArgument, $skillData[$n]);//ステータス引数配列作成
                }
                 break;
            }
        }
        $i++;
    }
 
    if($i < $skillLineLength){
     require_once("../Skills/".$skillData[2].".php");//該当クラスファイル読み込み
     //引数2には$skillData[3]以降のデータをまとめた配列を渡す
                         //スキルクラス名   スキル名　　　引数リスト
     $skillClassObj = new $skillData[2]($skillData[1],$skillArgument);//バトルメンバーのスキルに格納する
     //$playerSt["skillName"]=$skillClassObj;
     
     //$playerSt["skillName"]->skillaction($playerSt,$enemySt);

     return $skillClassObj;
    }else{
     echo "見つかりませんでした<br>";
     return 0;
    }
}
?>