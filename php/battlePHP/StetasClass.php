<?php
session_start();

ini_set('display_errors',"On");
error_reporting(E_ALL);

    class Stetas{//ステータスクラスの親
        public $stetasname="";
        public $upstetas=0;
        public $Buffturn=0;
        public $stetasType="Buff";
        public $Timing="";

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

        public function __construct($name, $up, $turn, $type, $timing_){
            $this->stetasname =$name;//表示名
            $this->upstetas = $up;//効果量
            $this->Buffturn = $turn;//効果時間
            $this->stetasType = $type;//バフかデバフか
            $this->Timing=$timing_;//処理タイミング
        }

        public function stetasfunc(){}//処理内容(override)
    };

    //攻撃力上げるステータス
    class PowerUp extends Stetas{
        public function stetasfunc(){
            echo "攻撃力上昇!:".($this->upstetas*100)."%UP<br>";
        }
    };
    
    //防御力上げるステータス
    class DefenseUp extends Stetas{
        public function stetasfunc(){
            echo  "防御力上昇!:".$this->upstetas;
            /*totaldef += $upDef
                stetas["Def"] *= totaldef
            */
        }
    };
    
?>