

var
x,y,
  SD_W=120,SD_H=120,
  ICON_W=120,ICON_H=120,
  i,
  backgroundImg="img/battlescene/mapimgtest.jpg",
  Nortsimg = new Image(),
  Norts_,
  lernImg = new Image(),

  leaderIcon=new Image(),

  memberIconimg=[new Image(),new Image(),new Image(),new Image()],
  memberSDImg=[new Image(),new Image(),new Image(),new Image()],
  battler=[],

  lernElement_top=[],
  lernElement_h=[],
  lernElement_w=[];


function battlemember(sdimg,iconimg,id,name,hp,pow){//Supportスキルやらも渡す
    this.sdimg=sdimg;
    this.icon=iconimg;
    this.memberIcon=new Sprite(this.icon,0,0,ICON_W,ICON_H);
    this.memberSD=new Sprite(this.sdimg,0,0,SD_W,SD_H);
    this.name=name;
    this.hp=hp;
    this.pow=pow;
  }

  function initSprite(){
    Nortsimg.src="img/battlescene/norts.jpg";
    Norts_=new Sprite(Nortsimg,0,0,120,150);
    for(i=0;i<4;i++){
      memberSDImg[i].src="img/characters/stand"+(i+1)+".jpg";
      memberIconimg[i].src="img/characters/face"+(i+1)+".jpg";
      battler[i]=new battlemember(memberSDImg[i],memberIconimg[i],i,"メンバー"+i,100*i,50*i);
    }
  }


  function memberRender(ctx_){
    for(i=1;i<=4;i++){
      lernElement_w[i]=$('#lern'+i).width();
      lernElement_h[i]=$('#lern'+i).height();
      lernElement_top[i]=$('#lern'+i).offset().top;
      x=lernElement_w[i]*0.75;
      y=canvas_h*0.5 + (canvas_h*0.5/4)*(i-1);
      //battler[i-1].memberSD.draw(ctx_,lernElement_w[i]*0.75,lernElement_top[i]-lernElement_h[i]);
      battler[i-1].memberSD.draw(ctx_,x,y-SD_H/2);
      Norts_.draw(ctx_,canvas_w/2-(40*i),y-150/2);
    }
  }
