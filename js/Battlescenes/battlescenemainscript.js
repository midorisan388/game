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

      const audiodata = data["audiohtml"],
      titledata =data["title"],
      notesfile=data["notesdata"],
      partystetas =data["partySt"],
      enemystetas = data["enemySt"];

      $("#battlebgm").attr("src",audiodata);
      $('#addstetas').html(titledata);//タイトル表示
      ScoreInit();
      Partyinit(partystetas);
      Enemyinit(enemystetas);
      Notesinit(notesfile);
      
       audioObj = new Audio(audiodata);//音楽データセット
    }

    function touchStartPlay(audioObj){
      audioObj.play();//音楽再生開始
    }

    function update(){
    
      Timecount();
    }

    function render(){
      ctx.clearRect(0, 0, maincanvas_.width, maincanvas_.height);

      Maincanvas_render();
    }

    function run() {

      if(GameStage === "Play"){
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
  $('#member1-icon').on(EVENTNAME_TOUCHSTART,function(){
    $.ajax({
      url: actionfile,
      dataType:"json",
      type:"post",
      data:{
         notesdata:notes,
         time:Gametimer,
         lernid:0,
      },
      success:function(data){
        AudioTest(0);
        StetasUpdate(0,data);
      },
      error:(
        function(XMLHttpRequest, textStatus, errorThrown) {
        alert('error!!!');
    　　console.log("XMLHttpRequest : " + XMLHttpRequest.status);
    　　console.log("textStatus     : " + textStatus);
    　　console.log("errorThrown    : " + errorThrown.message); 
        
      },function(data){  
       
      }) 
    });
     
  });
  $('#member2-icon').on(EVENTNAME_TOUCHSTART,function(){
    $.ajax({
      url: actionfile,
      dataType:"json",
      type:"post",
      data:{
         notesdata:notes,
         time:Gametimer,
         lernid:1,
      },
      success:function(data){
        AudioTest(1);
        StetasUpdate(1,data);
      },
      error:(
        function(XMLHttpRequest, textStatus, errorThrown) {
        alert('error!!!');
    　　console.log("XMLHttpRequest : " + XMLHttpRequest.status);
    　　console.log("textStatus     : " + textStatus);
    　　console.log("errorThrown    : " + errorThrown.message); 
       
      },function(data){    
       
      }) 
    });
       
  });
  $('#member3-icon').on(EVENTNAME_TOUCHSTART,function(){
    $.ajax({
      url: actionfile,
      dataType:"json",
      type:"post",
      data:{
         notesdata:notes,
         time:Gametimer,
         lernid:2,
      },
      success:function(data){
        AudioTest(2);
        StetasUpdate(2,data);
      },
       error:(
        function(XMLHttpRequest, textStatus, errorThrown) {
        alert('error!!!');
    　　console.log("XMLHttpRequest : " + XMLHttpRequest.status);
    　　console.log("textStatus     : " + textStatus);
    　　console.log("errorThrown    : " + errorThrown.message); 
       
      },function(data){    
       // console.log(data);    
      }) 
    });
       
  });
  $('#member4-icon').on(EVENTNAME_TOUCHSTART,function(){
    $.ajax({
      url: actionfile,
      dataType:"json",
      type:"post",
      data:{
         notesdata:notes,
         time:Gametimer,
         lernid:3,
      },
      success:function(data){
        AudioTest(3);
        StetasUpdate(3,data);
      },
      error:(
        function(XMLHttpRequest, textStatus, errorThrown) {
        alert('error!!!');
    　　console.log("XMLHttpRequest : " + XMLHttpRequest.status);
    　　console.log("textStatus     : " + textStatus);
    　　console.log("errorThrown    : " + errorThrown.message); 
       
      },function(data){    
       // console.log(data);    
      }) 
    });
       
  });

function StetasUpdate(id,datas){//ステータス表示更新
  const resdata = datas,
        noteshantei = resdata["noteshantei"],
        gameover = resdata["gameOverFlag"],
        playerSt = resdata["memberdata"],
        enemtSt = resdata["enemydata"];
        
        notes=resdata["notesdata"];//ノーツデータ更新
        ScoreUpdate(noteshantei);

      
    if(gameover === "Gameover"){
      //全滅処理
    }else{
   
      for(var i=0;i<4;i++){
        playerrenderStetas[i].currentHp=playerSt[i]["HP"];

        let memberhpasp = parseFloat(parseInt(playerrenderStetas[i].currentHp)/parseInt(playerrenderStetas[i].MaxHp));
        let enemyhpasp =parseFloat(parseInt(enemtSt[i]["HP"]-enemtSt[i]["damage"])/parseInt(enemtSt[i]["HP"]));
        
        $("#HPbox_member"+(i+1)).css("width",(memberhpasp)*100 +"%");
        $("#member"+(i+1)+"_stetas").html(playerrenderStetas[i].currentHp);
      }      
      $('#enemyid'+id).html( playerSt[id]["characterName"]+"の攻撃！ =>"+resdata["damage"]+"のダメージ<br>"+enemtSt[id]["characterName"]+"HP:"+ (parseInt(enemtSt[id]["HP"])-parseInt(enemtSt[id]["curretnDamage"])));
    }
}

function gameEnd(){//曲終わり、ゲームオーバー時に呼び出す
  
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