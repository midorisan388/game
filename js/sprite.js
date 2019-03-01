//スプライトシート 576*384 px

function Sprite(img, x, y, width, height,scal) {
    this.img = img;
    this.x = x ;
    this.y = y ;
    this.width = width;
    this.height = height;
    this.dw = this.width/scal;
    this.dh = this.height/scal;
};

function AnimSprite(img, x, y, width, height, sWidth,sHeight,stepsW,stepsH,scal) {
    this.img = img;
    this.x = x ;
    this.y = y ;
    this.width = width;//全体の幅
    this.height = height;//全体の高さ
    //-------------アニメーションステータス---------------------//
    this.spriteWidth = sWidth;//1駒の幅
    this.spriteHeight = sHeight;//1コマの高さ
    this.stepX=0;
    this.stepY=0;
    this.maxStepX=stepsW;//コマ数 x
    this.maxStepY=stepsH;//コマ数 y
    this.scal=scal;
};

Sprite.prototype.draw = function (ctx_, x, y) {
    ctx_.drawImage(this.img, this.x, this.y, this.width, this.height,
        x, y, this.dw, this.dh);
};


AnimSprite.prototype.animdraw = function (ctx_, x, y,updatecounter) {
    if(this.stepX >= this.maxStepX)this.stepX=0;//コマ始点X0

    ctx_.drawImage(this.img,//Image()
      this.x+this.stepX*(this.spriteWidth),//コマの始点座標x
      this.y+this.stepY,//コマの始点座標y
       this.spriteWidth,//切り取り幅
       this.spriteHeight,//切り取り高さ
        x, y,
        this.spriteWidth*this.scal,
         this.spriteHeight*this.scal);

    if(updatecounter % STEPTIME === 0){//コマ送り
      this.stepX++;
      if(this.stepX >= this.maxStepX){//モーション終了             
          return false;
        }
    }

    return true;
};
