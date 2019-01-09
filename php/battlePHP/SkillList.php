<?php

ini_set('display_errors',"On");
error_reporting(E_ALL);

require_once("./StetasClass.php");//クラスリスト読み込み

try{
    class SkillBase {
        public $skillname;
    }

    class StandardDamageSkill extends SkillBase{//倍率ダメージのみ
        private $damageup = 1.1;//倍率

        public function __construct($name,$updamage){
            $this->damageup=$updamage;
           $this->skillname = $name;
        }

        public function skillaction($actionplayer, $targrtSt){
            //ダメージ計算
            $Basedamage = $actionplayer["Pow"] *(1+PowerUpCol($actionplayer["stetas"]));//基礎ダメージ
            $damage = ($Basedamage + random_int(10,100))*$this->damageup;
            
            echo $this->skillname." 発動！<br>";
            echo (int)$damage."ダメージを与えた<br>";

            $resdata = array("acter"=>$actionplayer,"target"=>$targrtSt);
            return $resdata; 
        }
    }

    class DoubleAttack extends SkillBase{//連続ダメージ
        private $count = 3;//攻撃回数

        public function __construct($name,$atkcount){
           $this->count=$atkcount;
           $this->skillname = $name;
        }

        public function skillaction($actionplayer, $targrtSt){
            //ダメージ計算
            $i=1;
            while($i <= $this->count){
                $Basedamage = $actionplayer["Pow"] *(1+PowerUpCol($actionplayer["stetas"]));//基礎ダメージ
                $damage = ($Basedamage + random_int(10,100));
                
                echo $this->skillname." 発動！<br>";
                echo (int)$damage."ダメージを与えた<br>";
                $i++;
            }
            $resdata = array("acter"=>$actionplayer,"target"=>$targrtSt);
            return $resdata; 
        }
    }

    class SimpleHeal extends SkillBase{//単体回復
        private $heal = 1;//回復倍率

        public function __construct($name,$heal_){
            $this->skillname=$name;
           $this->heal = $heal_;
        }

        public function skillaction($actionplayer, $targrtSt){
            //デバフ付与
               $actionplayer["damage"] -= $this->heal; 
                
                echo $this->skillname." 発動！<br>";
                echo $this->heal." 回復した<br>";

                $resdata = array("acter"=>$actionplayer,"target"=>$targrtSt);
                return $resdata; 
        }
    }

    class DeBuffDamage extends SkillBase{//バフデバフ付与
        private $stetas_ = array();//バフデバフ配列

        public function __construct($name,$set_stetas){
            $this->skillname=$name;
            foreach ($set_stetas as $st) {
                array_push($this->stetas_, $st);
            }
        }

        public function skillaction($actionplayer, $targrtSt){    

                echo $this->skillname." 発動！<br>";
                foreach ($this->stetas_ as $st) {
                    if($st->stetasType === "Buff"){
                        echo "付与したステータス:".$st->stetasname." <br>";
                        array_push($actionplayer["stetas"] , $st);
                    }else if($st->stetasType === "DeBuff"){
                        echo "付与したステータス:".$st->stetasname." <br>";
                        array_push($targrtSt["stetas"] , $st);
                    }
                }
                $resdata = array("acter"=>$actionplayer,"target"=>$targrtSt);
            return $resdata; 
        }
    }

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