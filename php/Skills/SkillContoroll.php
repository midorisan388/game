<?php
//スキル内の共通する処理を置いておく

//対象にダメージを与える処理
function GeinDamage($targetSt, $damage){
    $damage=(int)$damage;

    $targetSt->currentDamage += $damage;
    return $targetSt;//変更後のパラメータを返す
}

//全体にステータス付与
function GeinStetasPT($targetSt, $newStId){
    $stId = (int)$newStId;
    $setStetasObj=array();

    $stetasFile=file("../../datas/csv/StetasDataList.csv");//ステータスリストファイル読み込み
    $n=0;
    foreach($stetasFile as $stetas){//ステータス配列に格納されているIDと一致するかを検索
        $data = explode(',', $stetas);
        if($n > 0){
            $dataIndex =(int)$data[0];//ステータスレコードのIDをキャスト

            if($dataIndex === $stId){//ステータスIDがstetasIdsのIDと一致するか
                require_once("../StetasClass/".$data[2].".php");
                                        //クラス名　　表示名  効果量  持続ターン　
                $setStetasObj = new $data[2]($data[1],$data[3],$data[4]);//ステータスクラス生成
                //PT全員に単体付与
                for($i=0;$i<4;$i++){
                    if($targetSt[$i]->characterId >= 0){
                        $targetSt[$i] = setStetasPartySolo($targetSt[$i], $setStetasObj);
                    }
                }
                break;
            }
        }
     $n++;
    }

    return array(
        "Stname"=>$data[1],//付与したステータス名
       "updateSt"=>$targetSt//更新するパラメータ
    );
}

//単体にステータス付与
function GeinStetas($targetSt, $newStId){
    $setStetasObj=array();
    $sId = (int)$newStId;
    $stetasFile=file("../../datas/csv/StetasDataList.csv");//ステータスリストファイル読み込み
    $n=0;

    foreach($stetasFile as $stetas){//ステータス配列に格納されているIDと一致するかを検索
        $data = explode(',', $stetas);
        if($n > 0){
            $dataIndex =(int)$data[0];//ステータスレコードのIDをキャスト

            if($dataIndex === $sId){//ステータスIDがstetasIdsのIDと一致するか
                require_once("../StetasClass/".$data[2].".php");
                                    //クラス名　　表示名  効果量  持続ターン　
                $setStetasObj = new $data[2]($data[1],$data[3],$data[4]);//ステータスクラス生成
                //対象に単体付与
                if($targetSt->characterId >= 0){
                    $targetSt = setStetasPartySolo($targetSt, $setStetasObj);
                    break;
                }
            }
        }
        $n++;
    }
    
    return array(
         "Stname"=>$data[1],
        "updateSt"=>$targetSt
     );
}


?>