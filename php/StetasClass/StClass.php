<?php
//session_start();

ini_set('display_errors',"On");
error_reporting(E_ALL);

    class Stetas{//ステータスクラスの親
        public $stetasname="";//ステータス名
        public $argument_val=0;//効果量
        public $Buffturn=0;//効果ターン
        public $stetasType="Buff";//ステータスタイプ
        public $Timing="";//判定タイミング
        public $target ="";

        /*ステータス関与タイミング
            ステータス付与:"StetasSet"
            ダメージ計算時:"Damage"
            被ダメージ時:"toDamage"
            スキル発動時:"Skillstart"
            戦闘不能時(なった瞬間):"Deadstart"
            戦闘不能中:"Daing"
            復帰時:"Alive"
            奥義使用時:"OverDrive"
        */

        public function __construct($name, $target, $argument, $turn, $type, $timing_){
            $this->stetasname =$name;//表示名
            $this->argument_val = $argument;//効果量
            $this->Buffturn = $turn;//効果時間
            $this->stetasType = $type;//バフかデバフか
            $this->Timing=$timing_;//処理タイミング
            $this->target=$target;//対象
        }

        public function stetasfunc(){}//処理内容(override)
    }; 
?>