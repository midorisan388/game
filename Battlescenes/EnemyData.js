var
Enemys=[
    {
      stetas:new Enemy(
        "ターゲットに通常攻撃",
        new CharacterData(
          "img/enemy/enemystand_1.png",//イメージuri
          0,//ID
          "ゴブリン",//表示名--パーティーデータ取得時に主キーにしてユーザー毎のキャラステータスを取得?
          100,//最大HP
          23,//攻撃力
          3,//防御力
          "atk",//タイプ
          "エネミー",//種族
          "N"//レアリティ
        )
      ),
      atkskill:function(obj){
        console.table(obj);
        console.log('エネミー'+ standenemy_data[obj.id].stetas.Date.name+'の攻撃');
        const damage_ = obj.Pow +100;
        return damage_;
      }
  },
  {
    stetas:new Enemy(
      "ターゲットに通常攻撃",
      new CharacterData(
            "img/enemy/enemystand_2.png",//イメージuri
            2,//ID
            "デビルウルフ",//表示名--パーティーデータ取得時に主キーにしてユーザー毎のキャラステータスを取得?
            300,//最大HP
            50,//攻撃力
            10,//防御力
            "atk",//タイプ
            "エネミー",//種族
            "N"//レアリティ
          )
    ),
    atkskill:function(obj){
      console.table(obj);
      console.log('エネミー'+ standenemy_data[obj.id].stetas.Date.name +'の攻撃');
      const damage_ = obj.Pow +100;
      return damage_;
    }
  },
  {
    stetas:new Enemy(
      "ターゲットに通常攻撃",
      new CharacterData(
        "img/enemy/enemystand_3.png",//イメージuri
        2,//ID
        "ハーピィ",//表示名--パーティーデータ取得時に主キーにしてユーザー毎のキャラステータスを取得?
        500,//最大HP
        65,//攻撃力
        25,//防御力
        "atk",//タイプ
        "エネミー",//種族
        "N"//レアリティ
      )
    ),
    atkskill:function(obj){
      console.table(obj);
      console.log('エネミー'+ standenemy_data[obj.id].stetas.Date.name+'の攻撃');
      const damage_ = obj.Pow +100;
      return damage_;
    }
  },
  {
    stetas:new Enemy(
      "ターゲットに通常攻撃",
      new CharacterData(
        "img/enemy/enemystand_4.png",//イメージuri
        2,//ID
        "ヒュドラ",//表示名--パーティーデータ取得時に主キーにしてユーザー毎のキャラステータスを取得?
        1700,//最大HP
        150,//攻撃力
        60,//防御力
        "atk",//タイプ
        "エネミー",//種族
        "R"//レアリティ
      )
    ),
    atkskill:function(obj){
      console.table(standenemy_data[obj.id]);
      console.log('エネミー'+ standenemy_data[obj.id].stetas.Date.name +'の攻撃');
      const damage_ = obj.Pow +100;
      return damage_;
    }
  }
];

function Enemy(actionSkill_discriptoin,date){
  this.partyid=0;
  this.ActionSkill_discriptoin=actionSkill_discriptoin;
  this.Date=date;
}
