   let
      fps_=60,

      canvas_w,canvas_h,
      charasd_margine,charasd_offset_x,
      charaicon_margine,icon_offset_x,
      charaSD_X,

      memberView = [],


      Startdelay=0,Gametimer=0,offsetTime=0,
      updatecounter=0;

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

    function init(){
      Gametimer=Startdelay-offsetTime;

      canvas_w=parentdom.clientWidth;
      canvas_h=parentdom.clientHeight;

      charasd_offset_x=canvas_w*0.8;
      charasd_margine=canvas_h*0.1;
      icon_offset_x=canvas_w*0.05;
      charaicon_margine=canvas_w/4;
    }

    function update(){
      Timecount();
      Maincanvas_update();
    }

    function render(){
      ctx.clearRect(0, 0, maincanvas_.width, maincanvas_.height);

      Maincanvas_render();
    }

    function run() {
        var loop = function () {
            update();
            render();

            window.requestAnimationFrame(loop, maincanvas_);
        }
        window.requestAnimationFrame(loop, maincanvas_);
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
        StetasUpdate(0,data);
      },
      error:(
        function(XMLHttpRequest, textStatus, errorThrown) {
        alert('error!!!');
    　　console.log("XMLHttpRequest : " + XMLHttpRequest.status);
    　　console.log("textStatus     : " + textStatus);
    　　console.log("errorThrown    : " + errorThrown.message); 
        console.log(data);
      },function(data){        
      }) 
    });
      SearchNotes(0);
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
        StetasUpdate(1,data);
      },
      error:function(data){
        $('#enemyid1').html(data+"失敗");
      }
    });
        SearchNotes(1);
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
        StetasUpdate(2,data);
      },
      error:function(data){
        $('#enemyid2').html(data+"失敗");
      }
    });
        SearchNotes(2);
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
        StetasUpdate(3,data);
      },
      error:function(data){
        $('#enemyid3').html(data+"失敗");
      }
    });
        SearchNotes(3);
  });

function StetasUpdate(id,datas){
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
        playerrenderStetas[i].currentHp=playerSt["member"+i]["HP"];

        let memberhpasp = parseFloat(parseInt(playerrenderStetas[i].currentHp)/parseInt(playerrenderStetas[i].MaxHp));
        let enemyhpasp =parseFloat(parseInt(enemtSt[i]["HP"]-enemtSt[i]["damage"])/parseInt(enemtSt[i]["HP"]));
        
        $("#HPbox_member"+(i+1)).css("width",(memberhpasp)*100 +"%");
        $("#member"+(i+1)+"_stetas").html(playerrenderStetas[i].currentHp);
      }      
      $('#enemyid'+id).html( playerrenderStetas[id].name+"の攻撃！ =>"+resdata["damage"]+"のダメージ<br>"+enemtSt[id]["name"]+"HP:"+ (parseInt(enemtSt[id]["HP"])-parseInt(enemtSt[id]["damage"])));
    }
}