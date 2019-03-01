const
member_size_X = member_size_Y=64,//SD画像一枚分のサイズ
partyspritesize_W=576,partyspritesize_H=384;//スプライトシート一枚分のサイズ

//キャラクターデータクラス
function CharacterData(img){
  this.img=new Image(),
  this.img.src=img;
  
  this.waitSprite = new AnimSprite(this.img,0,0,partyspritesize_W,partyspritesize_H,member_size_X,member_size_Y,3,1,0.5);//待機
  this.attackSprite= new AnimSprite(this.img,member_size_X*3,0,partyspritesize_W,partyspritesize_H,member_size_X,member_size_Y,3,1,0.5);//攻撃
  this.skillSprite= new AnimSprite(this.img,member_size_X*3,member_size_Y,partyspritesize_W,partyspritesize_H,member_size_X,member_size_Y,3,1,0.5);//スキル使用
  this.damageSprite= new AnimSprite(this.img,0,member_size_Y*4,partyspritesize_W,partyspritesize_H,member_size_X,member_size_Y,3,1,0.5);//被ダメージ
  this.deadSprite= new AnimSprite(this.img,382,320,partyspritesize_W,partyspritesize_H,member_size_X,member_size_Y,3,1,0.5);//戦闘不能

  this.motionsprite = this.waitSprite;

}
//キャラ描画
//待機モーション
Characterdtaw = function(charax_,charay_,sprite_){
 if(sprite_.animdraw(ctx,charax_,charay_,updatecounter) === false)
  return false;//モーション終了フラグ
};
