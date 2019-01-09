
let
  enemyHpvar=new Image(),
  enemyHpSprite,
  enemydates=[//ステータス初期値
    {
      x:0,y:45,
      name:"",
      currentHp:0,
      maxHp:1000,
      img:"img/characters/sprite_char4.jpg",
      sprite:new CharacterData("img/characters/sprite_char4.jpg")
    },
    {
      x:0,y:45+1*20,
      name:"",
      currentHp:0,
      maxHp:1000,
      img:"img/characters/sprite_char4.jpg",
      sprite:new CharacterData("img/characters/sprite_char4.jpg")
    },
    {
      x:0,y:45+2*20,
      name:"",
      currentHp:0,
      maxHp:1000,
      img:"img/characters/sprite_char4.jpg",
      sprite:new CharacterData("img/characters/sprite_char4.jpg")
    },
    {
      x:0,y:45+3*20,
      name:"",
      currentHp:0,
      maxHp:1000,
      img:"img/characters/sprite_char4.jpg",
      sprite:new CharacterData("img/characters/sprite_char4.jpg")
    }
  ];
 


function Enemyinit(data){
  enemyHpvar.src="img/battlescene/enemyHpvar.jpg";//エネミーHpフレーム
  enemyHpSprite=new Sprite(enemyHpvar,0,0,180,35,1);

  var i=0;

  while(data && i< 4){    
    enemydates[i].name=data[i]["name"];
    enemydates[i].currentHp=enemydates[i].maxHp=data[i]["HP"];
    enemydates[i].img = "img/characters/sprite_char4.jpg" ;
    enemydates[i].sprite = new CharacterData(enemydates[i].img);
    i++;
  }

}

function EnemyDraw(){
  for (var i=0;i<4;i++){
    //搭乗時の座標移動
    if(enemydates[i].currentHp > 0){
      if(enemydates[i].x < 156-markerdistance)enemydates[i].x+=3;
        Characterdtaw(enemydates[i].x - member_size_X/2 ,45+i*20,enemydates[i].sprite.motionsprite);//エネミー
      }
  }
 for (var i=0;i<4;i++){
    if(enemydates[i].currentHp > 0){
      ctx.fillStyle="green";
      ctx.fillRect( 140-markerdistance, 42+i*20, 45*(enemydates[i].currentHp/enemydates[i].maxHp), 6);
      enemyHpSprite.draw(ctx,140-markerdistance,40+i*20);
      ctx.strokeText(enemydates[i].name,142-markerdistance, 42+i*20);
    }
  }
}

function EnemyUpdate(){
 
}
