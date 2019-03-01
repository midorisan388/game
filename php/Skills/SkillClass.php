<?php

ini_set('display_errors',"On");
error_reporting(E_ALL);

$i=0;
try{
    //スキルの基底クラス
    class SkillBase {
        public $skillname;//スキル名
        public $argument;//固有引数
        public $skillCharge;//スキルCT
        public $damage;
        protected $actionMes;//行動メッセージ
    }

   
    //攻撃上昇量計算
    function PowerUpCol($plySt){
        $total=0;
        $i=0;

        //クラスの選別
        foreach($plySt as  $st){
            //攻撃力に影響する　かつ　ダメージ計算時影響
            if(get_class($st) === "PowerUp" && $st->Timing === "Damage"){
                $total +=  $st->upstetas;
                $st->stetasfunc();

                $st->Buffturn -=1;
            }
            $i++;
        }

        return $total;
    }

}catch(PDOExeption $erro){
    echo "次のエラーが発生しました<br>";
    echo $erro->getmessage();
}

?>