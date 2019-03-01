var i=0;
const 
  partylength=4,
  posutionY=45,
  positiondist=20;

  let
  STEPTIME = 10,
  step=0,
  playerrenderStetas=[
    {
      y:posutionY,
      name:"",
      currentHp:0,
      maxHp:1000,
      state:"idle",
      id_img:100000,//通し番号
      img: "",//imgurl　"img/characters/character1/"+id_img+"0301.png",
      sprite:null//キャラクタースプライトデータnew CharacterData("img/characters/105101/1051010301.png")
    },
    {
      y:posutionY+1*20,
        name:"",
        currentHp:0,
        maxHp:1000,
        state:"idle",
        id_img:100000,
        img:"",// "img/characters/103601/1036010301.png",
        sprite:null//new CharacterData("img/characters/103601/1036010301.png")
    },
    {
      y:posutionY+2*positiondist,
        name:"",
        currentHp:0,
        maxHp:1000,
        state:"idle",
        id_img:100000,
        img:"",// "img/characters/110401/0301.png",
        sprite:null//new CharacterData("img/characters/110401/1104010301.png")
    },
    {
      y:posutionY+3*positiondist,
      name:"",
      currentHp:0,
      maxHp:1000,
      state:"idle",
      id_img:100000,
      img:"",// "img/characters/146401/0301.png",
      sprite:null//new CharacterData("img/characters/146401/1464010301.png")
    }
  ];

//メンバーのステータス初期値格納
function Partyinit(data){
  const partySt = data;

  playerrenderStetas.map(function( partyrender, index, array){
    let img_id = 0;
    let chara_img_folder ="";//キャラ画像フォルダ名

    partyrender.id_img = partySt[index]["imgId"];//通し番号格納    
    img_id=partyrender.id_img;

    chara_img_folder="img/characters/"+img_id;

    partyrender.sprite=new CharacterData(chara_img_folder+"/"+img_id+"0301.png");//スプライトデータ生成
    $("#membericonimg"+(index+1)).attr("src", chara_img_folder+"/"+img_id+"0101.png");//顔グラフィックurl格納
    
    partyrender.currentHp=partyrender.MaxHp=partySt[i]["HP"];//HP格納
    partyrender.name=partySt[i]["name"];//キャラ名格納

    $("#HPbox_member"+(index+1)).css("width",100 +"%");
    $("#member"+(index+1)+"_stetas").html(partyrender.currentHp);
  });
}

function CharacterStateChange(id_,state_){
  playerrenderStetas[id_].state = state_;
  switch(state_){
    case "idle":
      playerrenderStetas[id_].sprite.motionsprite =  playerrenderStetas[id_].sprite.waitSprite;
    break;
    case "attack":
      playerrenderStetas[id_].sprite.motionsprite =  playerrenderStetas[id_].sprite.attackSprite;
    break;
    case "damage":
      playerrenderStetas[id_].sprite.motionsprite =  playerrenderStetas[id_].sprite.damageSprite;
    break;
    case "skill":
      playerrenderStetas[id_].sprite.motionsprite =  playerrenderStetas[id_].sprite.skillSprite;
    break;
    case "dead":
      playerrenderStetas[id_].sprite.motionsprite =  playerrenderStetas[id_].sprite.deadSprite;
    break;
    default:
      playerrenderStetas[id_].sprite.motionsprite =  playerrenderStetas[id_].sprite.waitSprite;
    break;
  }
  playerrenderStetas[id_].sprite.motionsprite.stepX=0;
}

function Party_Draw(){

  playerrenderStetas.map(function(memberSt, index, array){
    
    if(memberSt.state === "dead"){//戦闘不能
        Characterdtaw(140+markerdistance,memberSt.y,memberSt.sprite.motionsprite);
    }else if(memberSt.state === "damage"){//ダメージ
      if(Characterdtaw(140+markerdistance+20,memberSt.y,memberSt.sprite.motionsprite) === false){//演出用座標
        CharacterStateChange(index , "idle");//待機モーションに戻す
      }
    }else if(memberSt.state !== "idle"){
      if(Characterdtaw(140+markerdistance-20,memberSt.y,memberSt.sprite.motionsprite) === false){//演出用座標
        CharacterStateChange(index , "idle");//待機モーションに戻す
      } 
    }else  Characterdtaw(140+markerdistance,memberSt.y,memberSt.sprite.motionsprite)
   
  });
}
