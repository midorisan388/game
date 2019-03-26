<?php

ini_set('display_errors',"On");
error_reporting(E_ALL);

require_once("../Skills/Require_skillList.php");//スキルファイルリスト

//バトルステータスクラス
class BattleActor{
    public $characterId=0;//キャラクターID
    public $characterName="";
    public $imgId="";
    protected $battleType="";
    public $skillCurrentCount=0;
    public $skillCharge=0;
    public $currentDamage=0;
    public $HP=0;
    public $power=0;
    public $defense=0;
    protected $magicPow=0;
    protected $mental=0;
    private $stetasList=array();

    public $skillParam = null;
    //csvから取得したキャラステータスを取得
    public function __construct($mStArray){
        $this->characterId=$mStArray["id"];//パーティ配置配置ID
        $this->characterName=$mStArray["name"];//表示名
        $this->imgId=$mStArray["imgid"];//画像通し番号
        $this->battleType=$mStArray["type"];//バトルタイプ
        $this->skillCurrentCount=0;//現在のスキルチャージカウント
        $this->currentDamage=0;//受けているダメージ
        $this->HP=$mStArray["HP"];//HP
        $this->power=$mStArray["pow"];//攻撃力
        $this->defense=$mStArray["def"];//防御力
        $this->magicPow=$mStArray["magicpow"];//魔力
        $this->mental=$mStArray["mental"];//精神力
    
        $this->skillParam = setSkillData($mStArray["skillid"]);//スキルデータ
        $this->skillCharge = $this->skillParam->skillCharge;//スキル使用間隔
    }
                            //使用者サイド　//敵対サイド　何番目のキャラクターか　 
    public function skillUse($playparty, $targetparty, $memberid, $enemyId){//スキルを使用する         
       $returnData = $this->skillParam->skillaction($playparty, $targetparty,  $memberid,  $enemyId);
       return $returnData;
    }

    public function getStetas(){
        return $this->stetasList;//ステータスリストを返す
    }

    public function setStetas($add_stetas){
        array_push($this->stetasList, $add_stetas);//ステータスを追加する
    }

    public function deleteStetas($id){
        //ステータスを削除する
        array_slice($this->stetasList, $id);
    } 

    public function stetasCheck(){
        $stId=0;
        //ステータスの効果時間が0以下なら削除
        foreach($this->stetasList as $st){
            if($st->getFuncTurn() <= 0){
                array_slice($this->$stetasList, $stId, 1);//配列から削除
                $stId--;//Idを一つ戻す
            }
            $stId++;
        }
    }

};

//スキルデータ取得
function setSkillData($sid_){
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
 
                for($n=5; !empty($skillData[$n]); $n++){
                    array_push($skillArgument, $skillData[$n]);//ステータス引数配列作成
                }
                 break;
            }
        }
        $i++;
    }
 
    if($i < $skillLineLength){
     require_once("../Skills/".$skillData[2].".php");//該当クラスファイル読み込み
     //引数2にはctカウンター以降のデータをまとめた配列を渡す
                         //スキルクラス名   スキル名　    スキルCT      　引数リスト
     $skillClassObj = new $skillData[2]($skillData[1],$skillData[4],$skillArgument);//バトルメンバーのスキルに格納する

     return $skillClassObj;
    }else{
     echo "見つかりませんでした<br>";
     return 0;
    }
}
?>