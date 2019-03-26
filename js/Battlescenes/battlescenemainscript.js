   let
      fps_=60,

      canvas_w,canvas_h,
      charasd_margine,charasd_offset_x,
      charaicon_margine,icon_offset_x,
      charaSD_X,

      memberView = [],


      Startdelay=0,Gametimer=0,offsetTime=0,
      updatecounter=0,
      audioObj;//BGM再生用オブジェクト
      audioDurarion=0;//再生時間

    const
      actionfile ='php/battlePHP/ActionSkillList.php',

      Waveinfo = document.getElementById("enemycount"),
      Scoreinfo = document.getElementById("scoreview"),
      
      parentdom = document.getElementById("mainview"),//アニメーション用div
      maincanvas_=document.getElementById("maincanvas"),//描画用
      ctx = maincanvas_.getContext('2d');


    function Timecount(){
        updatecounter++;
        Gametimer=updatecounter/fps_;
    }

    function Init(data){
      Gametimer=Startdelay-offsetTime;

      canvas_w=parentdom.clientWidth;
      canvas_h=parentdom.clientHeight;

      charasd_offset_x=canvas_w*0.8;
      charasd_margine=canvas_h*0.1;
      icon_offset_x=canvas_w*0.05;
      charaicon_margine=canvas_w/4;

      //クエストデータ、ステータスデータ格納
      const audiodata = data["audiohtml"],
      titledata =data["title"],
      notesfile=data["notesdata"],
      partystetas =data["partySt"],
      enemystetas = data["enemySt"];

      $("#battlebgm").attr("src",audiodata);
      $('#addstetas').html(titledata);//曲タイトル表示
      ScoreInit();
      Partyinit(partystetas);
      Enemyinit(enemystetas);
      Notesinit(notesfile);
      
       audioObj = new Audio(audiodata);//音楽データセット
    }

    function touchStartPlay(audioObj){
     /* audioObj.play();//音楽再生開始
      AudioTest();*/
      AudioSetup(audioObj);
      audioDurarion=audioObj.duration;//終了時間取得
    }

    function update(){
      if(audioDurarion <= Gametimer){
        GameStage="Clear";
      }else{
       Timecount();
      }
    }

    function render(){
      ctx.clearRect(0, 0, maincanvas_.width, maincanvas_.height);

      Maincanvas_render();
    }

    function run() {
      
      if(GameStage === "GameOver"){
        location.href="./MyPage.html";
      }
      if(GameStage === "Clear"){
       
        location.href="./MyPage.html";
        $.ajax({
          url:"php/battlePHP/battleEnd.php",
          type:"get",
          data:{
            clearmode:"clear"
          },
          success:function(data){

          },
          error:(
            function(XMLHttpRequest, textStatus, errorThrown) {
            alert('error!!!');
        　　console.log("XMLHttpRequest : " + XMLHttpRequest.status);
        　　console.log("textStatus     : " + textStatus);
        　　console.log("errorThrown    : " + errorThrown.message); 
           
          })
        });
      }
      else if(GameStage === "Play"){
        if(!checkMedia()){
          GameStage="Pause";
          audioObj.pause();
        }
        else {
          update();
          render();
        }
      }
    }

//トリガーイベント
$(function(){

  $('#member1-icon').on(EVENTNAME_TOUCHSTART,function(){
    Action(0);
  });
  $('#member2-icon').on(EVENTNAME_TOUCHSTART,function(){
    Action(1);
  });
  $('#member3-icon').on(EVENTNAME_TOUCHSTART,function(){
    Action(2);
  });
  $('#member4-icon').on(EVENTNAME_TOUCHSTART,function(){
    Action(3);
  });
});

function Action(memberid_){
  AudioTest(battle_se_volume);

  $.ajax({
    url: actionfile,
    dataType:"json",
    type:"post",
    data:{
       notesdata:notes,
       time:Gametimer,
       lernid:memberid_,
       updatenotes:"alway"
    },
  }).done(function(data){
    PlayerActionUpdate(memberid_,data);
  }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
    alert('error!!!');
　　console.log("XMLHttpRequest : " + XMLHttpRequest.status);
　　console.log("textStatus     : " + textStatus);
　　console.log("errorThrown    : " + errorThrown.message); 
   
  });
}

function PlayerActionUpdate(id_, datas_){
  StetasUpdate(id_, datas_);
}

function StetasUpdate(id, datas){//ステータス表示更新  
  const resdata = datas,
        noteshantei = resdata["noteshantei"],
        gameover = resdata["gameOverFlag"],
        playerSt = resdata["memberdata"],
        state = resdata["motionState"],
        message = resdata["message"];
      
        notes=resdata["notesdata"];//ノーツデータ更新
        ScoreUpdate(noteshantei);

      
    if(gameover === "Gameover"){
      gameEnd();
    }else{ 
      playerrenderStetas.map(function( memberSt, index, array){        
        memberSt.currentHp=playerSt[index]["HP"]-playerSt[index]["currentDamage"];//HP更新
        const 
          memberhpasp = parseFloat(parseInt(memberSt.currentHp)/parseInt(memberSt.MaxHp));

        $("#HPbox_member"+(index+1)).css("width",(memberhpasp)*100 +"%");//HPバー更新
        $("#member"+(index+1)+"_stetas").html(memberSt.currentHp);//パーティHP表示
      });
      
      if(playerSt[id].currentHp <= 0){
        CharacterStateChange(id, "dead");//戦闘不能モーション変更
      }
      else CharacterStateChange(id, state);//モーション変更
      $('#battle_anounce_party').html(message);//行動メッセージ表示
    }
}

function gameEnd(){//曲終わり、ゲームオーバー時に呼び出す
  audioObj.pause();
  GameStage="GameOver";
}

//ダブルタップの拡大防止策
document.addEventListener(EVENTNAME_TOUCHSTART, event=>{
  if(event.targetTouches > 1){
    event.preventDefault();
  }
}, {
  passive:false
});

let lastTouch=0;
document.addEventListener(EVENTNAME_TOUCHEND, event=>{
  const now = window.performance.now();
  if(now - lastTouch <= 500){
    event.preventDefault();
  }
  lastTouch=now;
},{
  passive:false
});