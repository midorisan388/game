<?php 
    session_start();

    ini_set('display_errors',"On");
    error_reporting(E_ALL);

    class ScinarioTextControlle{
        private $respons_select=[];//選択肢文を格納する
        private $test_scinario_path;//セリフテキストファイル
        private $select_id=0;//選択肢ID

        private $user_name="";//ユーザ名
        private $scinarioId=1;//表示行

        private $select_count=0;
        private $resdata_array;//発言テキスト表示
    
        public function __construct($csv_path, $usernm){
            $this->scinarioId = (isset($_SESSION["SCINARIO_No"]))? $_SESSION["SCINARIO_No"] : 1;
            $this->test_scinario_path=$csv_path;
            $this->user_name =$usernm;
            $this->respons_select=[];
        }
        private  function getScinarioText($csv_file_nm){//シナリオテキストリスト取得
            $scinario_dot = ".csv";//シナリオテキストファイル拡張子
            $scinario_text_dir="../datas/scinarios/";//シナリオテキストファイルディレクトリ
    
            $x=0;
            $scinario_file = file($scinario_text_dir.$csv_file_nm.$scinario_dot);//シナリオテキスト取得
            foreach($scinario_file as $txt_line){//一行ずつ格納
                if( $x > 0 ){
                    $txt_data = explode(',',$txt_line);
                    $scinarios_line[$x] = $txt_data;
                    $_SESSION["SCINARIOS"][$x] = $txt_data;
                }
                $x++;
            }
            return $scinarios_line;
        }
    
        private function changePlayerMane($check_txt) {  //切り出した文字列がプレイヤー名置き換え文字列と一致するか
    
            $scinario_change_cmd="PN";//文中に含まれていたらプレイヤー名に置き換える
        
            //プレイヤー名変換記号内の置き換え処理
            if($check_txt === $scinario_change_cmd){
                //<>内が$scinario_change_check_txt名に変換して追加
                return true;//ユーザ名として返す
            }else{
                return false;//通常の文字として返す
            }
        }
    
        private function setSelifTexts($text_record, $usernm_){//表示する一行
    
            $new_record="";//変換後の一行文を格納
    
            for( $i=0; $i < strlen($text_record); $i++){
    
                $cell = $text_record[$i];
    
                if($cell === '['){//<PLAYERNAME>の開始記号
                    $start_tag=$i;//記号開始位置
                    $n=0;
                    while($text_record[$start_tag + $n] !== ']'){//終了記号まで読み込む
                        $check_tag = substr($text_record,$start_tag+1,$n);//< ~ >までも文字を切り出す
                        $n++;
                    }
                    if($this->changePlayerMane($check_tag)){
                        //プレイヤー名で置き換え
                        $new_record .= $usernm_;
                    }else{
                        $new_record .= "[{$check_tag}]";//そのまま文字列追加
                    }
                    $i += strlen($check_tag)+1;//切り出した文字数分進める
    
                }else if($cell === '@' && $i === 0){
                    $start_tag=$i;//記号開始位置
                   if($text_record[$start_tag + 1] === '@'){
                        //連続して@が続く場合選択肢の行として処理
                        $new_record = "select";
                        return  $new_record;
                   }else{
                        $new_record .= $cell;//一文字ずつ追加
                   }
                }else{
                    $new_record .= $cell;//一文字ずつ追加
                }
            }
    
            return $new_record;
        }
    
        private function selifPrint($id,$unm_){//表示テキストデータ生成
            $scinario_text = (isset($_SESSION["SCINARIOS"]))? $_SESSION["SCINARIOS"] : $this->getScinarioText($test_scinario_path);//シナリオテキストリスト
           
            $selif_owner = $this->setSelifTexts($scinario_text[$id][0], $unm_);//発言者
            $selif_owner = (($selif_owner === "select")? "選択肢": $selif_owner);//選択肢行なら発言者名を[選択肢]に変換
            $selif_body = $this->setSelifTexts($scinario_text[$id][3], $unm_);//本文
    
            if($selif_owner === "選択肢"){
                $this->respons_select[$this->select_id] = $selif_body;
                $this->select_id++;
            }else{
                $this->respons_select=null;
            }
    
            $_SESSION["SCINARIO_No"] = (int)$scinario_text[$id][2];
    
            if($this->setSelifTexts( $scinario_text[$id+1][0], $unm_) === "select"){
                //選択肢行が続く場合再起的に表示
                $this->selifPrint($id+1,$unm_);
            }
    
            return $resdata=array(
                "text_owner"=>$selif_owner,
                "text_body"=>$selif_body,
                "select_text"=>$this->respons_select
            );
        }
    
        public function showSelifText(){//テキストを整頓して表示
            $this->resdata_array = $this->selifPrint($this->scinarioId,$this->user_name);
            $select_lists = ($this->resdata_array['select_text'] !== null)? count($this->resdata_array['select_text']):0;
            $text_mode="selif";
            if( $select_lists > 0){
                $text_mode="select";
            }
            //最後行まで到達したときの処理
            if($_SESSION["SCINARIO_No"] >= count($this->getScinarioText($this->test_scinario_path))){
                unset($_SESSION["SCINARIO_No"]);
                $text_mode="end";
            }

            return $selif_responsdata=array(
                "owner"=>$this->resdata_array['text_owner'],
                "body"=>$this->resdata_array['text_body'],
                "mode"=>$text_mode,
                "selects"=>$this->resdata_array['select_text'],
                "selects_count"=> $select_lists
            );
        }
    }
    
   //ユーザ名取得
    function getUserData(){
        if(!isset($_SESSION["USER_DATA"])){//ユーザデータが取得できていなければ再取得
            //SQL接続-----------------------------------------------------------------
           require_once("../datas/sql.php");
           $sql_list=new PDO("mysql:host=$SERV;dbname=$DBNAME",$USER,$PASSWORD);
           $sql_list->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
           $sql_list-> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
           //----------------------------------------------------------------------
   
           $userId = 'A0004';//$_SESSION['userid'];
           $user_name = $sql_list->query("SELECT UserName FROM {$userdatas} WHERE UserID = '{$userId}'");
           $user_name = $user_name->fetch();
           $user_name = $user_name["UserName"];
   
           $restxt = setSelifTexts("セリフ:[PN] がしゃべっています ", $user_name['UserName']);
           echo "<br>".$restxt;
       }else{
           $user_name =  $_SESSION["USER_DATA"]["username"];//テキスト中の置き換えるプレイヤー名
       }
       return $user_name;
    }

    $scinario_ctl = new ScinarioTextControlle("event-selif-test", getUserData());

    $resdata = array();
    $resdata = $scinario_ctl->showSelifText();
    $resjson =json_encode( $resdata,JSON_PRETTY_PRINT );

    echo  $resjson;
?>