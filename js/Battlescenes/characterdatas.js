const
member_size_X = member_size_Y=64,//SD画像一枚分のサイズ
partyspritesize_W=576,partyspritesize_H=64;//スプライトシート一枚分のサイズ

//キャラクターデータクラス
function CharacterData(img){
  this.img=new Image(),
  this.img.src=img;
  
  this.waitSprite = new AnimSprite(this.img,0,0,partyspritesize_W,partyspritesize_H,member_size_X,member_size_Y,3,1,2);
  this.attackSprite= new AnimSprite(this.img,member_size_X*3,0,partyspritesize_W,partyspritesize_H,member_size_X,member_size_Y,3,1,2);
  this.skillSprite= new AnimSprite(this.img,0,member_size_Y*2,partyspritesize_W,partyspritesize_H,member_size_X,member_size_Y,3,1,2);
  this.damageSprite= new AnimSprite(this.img,0,member_size_Y*3,partyspritesize_W,partyspritesize_H,member_size_X,member_size_Y,3,1,2);
  this.deadSprite= new AnimSprite(this.img,382,320,partyspritesize_W,partyspritesize_H,member_size_X,member_size_Y,3,1,2);

  this.motionsprite=  this.waitSprite ;

}
//キャラ描画
Characterdtaw = function(charax_,charay_,sprite_){
  sprite_.animdraw(ctx,charax_,charay_,updatecounter);
};
