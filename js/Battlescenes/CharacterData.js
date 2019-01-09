/*var

characters =[
  {
    stetas:new Character(
      "ターゲットに通常攻撃",
      "味方全体のHPを毎秒回復",
      new CharacterData(
        "img/characters/sprite_char1.jpg",//イメージuri
        0,//ID
        "主人公",//表示名--パーティーデータ取得時に主キーにしてユーザー毎のキャラステータスを取得?
        1100,//最大HP
        1500,//攻撃力
        800,//防御力
        "atk",//タイプ
        "ヒューマン",//種族
        "SR"//レアリティ
      )
    ),
    atkskill:function (obj){
      const damage =partystetas[obj.id].Pow;
      standEnemy_stetas[obj.id].Hp -= damage;
      console.log("主人公の攻撃");
    },
    sapportskill:function(obj){
      if(updatecounter % fps_*3 === 0){
        for(var i=0;i<4;i++){
          if(partystetas[i].Hp < partystetas[i].maxHP)
              partystetas[i].Hp += 50;
          }
      }
    },
    damageskill:function(obj,target,damage){
      Damage(target,damage);
    }
  },
    {
      stetas:new Character(
        "ターゲットに弱攻撃",
        "SCOREを徐々に増加",
        new CharacterData(
          "img/characters/sprite_char2.jpg",
            1,
            "朱雀",
            1010,
            1020,
            200,
            "atk",
            "ヒューマン",
          "SSR"//レアリティ
        )
      ),
      atkskill:function (obj){
        const damage = partystetas[obj.id].Pow;
        standEnemy_stetas[obj.id].Hp -= damage;
      },
      sapportskill:function(obj){
        if(updatecounter % fps_*3 === 0){
          score_ += 10;
        }
      },
      damageskill:function(obj,target,damage){
        Damage(target,damage);
        obj.Hp -= 100;//カウンター
      }
    },
    {
      stetas:new Character(
        "敵全体に通常攻撃",
        "自身の奥義ゲージを徐々に増加",
        new CharacterData(
          "img/characters/sprite_char3.jpg",//イメージuri
          0,//ID
          "白虎",//表示名--パーティーデータ取得時に主キーにしてユーザー毎のキャラステータスを取得?
          1080,//最大HP
          1300,//攻撃力
          500,//防御力
          "tec",//タイプ
          "ビースト",//種族
          "SSR"//レアリティ
        )
      ),
      atkskill:function (obj){
        const damage = partystetas[obj.id].Pow;
        standEnemy_stetas[obj.id].Hp -= damage;
      },
      sapportskill:function(obj){
        if(updatecounter % fps_*3 === 0){
          member_combCount[obj.id]++;
        }
      },
      damageskill:function(obj,target,damage){
        Damage(target,damage);
      }
    },
    {
      stetas:new Character(
        "敵全体に強攻撃",
        "味方全体のの奥義ゲージを徐々に増加",
        new CharacterData(
          "img/characters/sprite_char4.jpg",
          1,
          "玄武",
          1300,
          1520,
          300,
          "def",
          "オーガ",
          "SSR"
        ),
      ),
      atkskill:function (obj){
        const damage = partystetas[obj.id].Pow;
        standEnemy_stetas[obj.id].Hp -= damage;
      },
      sapportskill:function(obj){
        if(updatecounter % fps_*3 === 0){
          for(var i=0;i<4;i++)
            member_combCount[i]++;
        }
      },
      damageskill:function(obj,target,damage){
        Damage(target,damage);
        obj.Hp -= 100;//カウンター
      }
    }
];

//characpoint =[];

/*function Character(actionSkill_discriptoin,sapportSkill_discription,date){
  this.self = null;
  this.ActionSkill_discriptoin=actionSkill_discriptoin;
  this.SapportSkill_discription=sapportSkill_discription;
  this.Date=date;
}


function charadatainit(){
  testchara.src="img/characters/stand4.jpg";
  testsprite = new Sprite(testchara,0,0,225,225,4);

  characters.map(function(value,index,array){
     //characpoint[index] = array[index];

    array[index].stetas.self = array[index];

    array[index].AttackSkill=new AttackDate(array[index].stetas.self.ActionSkill_discriptoin,array[index].stetas.self.ActionSkill_count,array[index].stetas.self );
    array[index].SapportSkill=new SapportDate(array[index].stetas.self.SapportSkill_discription,array[index].stetas.self );
  });

}
*/
//敵を交代させるとき呼び出す
function EnemySetting(num){
  /*if(standEnemy_stetas[num].Hp <= 0){
    if(enemystetas.length > 0){
      standEnemy_stetas[num]=(enemystetas.shift());
      standEnemy_stetas[num].id = num;
      standenemy_data[num]=enemydates.shift();
    }
  }*/
}
