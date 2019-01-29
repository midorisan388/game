var i=0;
const partylength=4;
let
STEPTIME = 10,
step=0,
playerrenderStetas=[
  {
    x:0,y:45,
    name:"",
    currentHp:0,
    maxHp:1000,
    img: "img/characters/character1/1051010301.png",
    sprite:new CharacterData("img/characters/character1/1051010301.png")
  },
  {
    x:0,y:45+1*20,
      name:"",
      currentHp:0,
      maxHp:1000,
      img: "img/characters/character2/1036010301.png",
      sprite:new CharacterData("img/characters/character2/1036010301.png")
  },
  {
    x:0,y:45+2*20,
      name:"",
      currentHp:0,
      maxHp:1000,
      img: "img/characters/character3/1104010301.png",
      sprite:new CharacterData("img/characters/character3/1104010301.png")
  },
  {
    x:0,y:45+3*20,
    name:"",
    currentHp:0,
    maxHp:1000,
    img: "img/characters/character4/1464010301.png",
      sprite:new CharacterData("img/characters/character4/1464010301.png")
  }
];

function Partyinit(data){
  const partySt = data;

  for(i=0;i<4;i++){
    const memberhpasp = parseFloat(parseInt(partySt[i]["HP"]-partySt[i]["currentDamage"])/parseInt(partySt[i]["HP"]));

    playerrenderStetas[i].currentHp=playerrenderStetas[i].MaxHp=partySt[i]["HP"];
    playerrenderStetas[i].name=partySt[i]["name"];

    $("#HPbox_member"+(i+1)).css("width",(memberhpasp)*100 +"%");
    $("#member"+(i+1)+"_stetas").html(playerrenderStetas[i].currentHp);
  }
}



function Party_Draw(){
  for(i=0;i<4;i++){//パーティメンバー描画
      Characterdtaw(140+markerdistance,45+i*20,playerrenderStetas[i].sprite.motionsprite);//パーティー
  }
}
