<?php

ini_set('display_errors',"On");
error_reporting(E_ALL);
require_once("../Skills/SkillClass.php");

function setStetasPartySide($partySt, $stetasObj){//全体にステータス付与
    $i=0;
    for( $i=0; $i<4; $i++){
        if($partySt[$i]->$haracterId === null )$partySt[$i]->characterId=0;
        if($partySt[$i]->characterId !==0 )
            array_push($partySt[$i]->stetasList, $stetasObj);//ステータス付与
    }

    return $partySt;//変更後のステータス返す
}

function setStetasPartySolo($charaSt, $stetasObj){//単体にステータス付与
    if($charaSt->characterId >= 0 )
        $charaSt->setStetas($stetasObj);

    return $charaSt;//変更後のステータス返す
}

//バフデバフ付与スキル
class SetStetas extends SkillBase{
    private $setStetasObj;//追加ステータスクラスオブジェクト
    private $stetasObjList=array();//オブジェクトリスト
    private $stetasIds=array();//ステータス異常IDリスト

    //スキル名称,固有引数データ
    public function __construct($name,$ct,$stetasArray){
        //スキルパラメータ格納
        $this->skillname=$name;
        $this->argument=$stetasArray;//異常ステータスIDリスト
        $this->skillCharge=(int)$ct;
    }

    public function skillaction($actionplayer, $targetSt, $id, $eId){ 
        $this->actionMes = $actionplayer[$id]->characterName."はスキル<strong class=damage >".$this->skillname."</strong>を発動<br>";
        $stetasIds = array();
        $stetasIds = $this->argument;

        foreach($stetasIds as $stetasId){//ステータス異常IDの数ループ
            $responsdata = GeinStetas($actionplayer[$id], $stetasId);//ステータス付与
            $actionplayer[$id] = $responsdata["updateSt"];
            $this->actionMes .="自身に{$responsdata['Stname']}を付与<br>";
        }
        $this->damage=0;
        $resdata = array("acter"=>$actionplayer,"target"=>$targetSt,"damage"=>$this->damage,"actMes"=>$this->actionMes);//ステータス情報を返す
        
        return $resdata; 
    }
}


?>