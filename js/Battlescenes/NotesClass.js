const 
notesImagsprite = new Image(),

notesimg_size=32,
notesSpeed=markerdistance/fps_,//ノーツの移動速度

juge_time={
  BAD:0.42,
  GOOD:0.38,
  GREAT:0.25,
  PARF:0.06,
},

notesimg_sprite={
  "ATK":new Sprite(notesImagsprite,0,0,notesimg_size,notesimg_size,1),
  "DEF":new Sprite(notesImagsprite,notesimg_size,0,notesimg_size,notesimg_size,1),
  "ENH":new Sprite(notesImagsprite,0,notesimg_size,notesimg_size,notesimg_size,1),
  "TEC":new Sprite(notesImagsprite,notesimg_size,notesimg_size,notesimg_size,notesimg_size,1),
};
notesImagsprite.src = "img/battlescene/notesiconsprite.png";

var
//ノーツカウンター　全ノーツ数
  notescounter=0,notesLen=0,

 // notescount=0,

  notes=[];//ノーツオブジェクト配列


//------JSONファイルからノーツ情報取得&格納-------------------------------------//
function Notesinit(data){
  
  const notesdata_json = JSON.parse(data);//JSON形式へエンコード
  
  var i=0;
    notesdata_json.forEach((notesdata) => {
    notes_ = notesdata;
    notes[i] = {
      timing:notes_['timing'],//タイミング時間
      lernID:notes_['lernID'],//レーンID
      type:notes_['type'],//ノーツタイプ
      judge:"ALWAY"//状態初期化
    };
    i++;
});
  notesLen=notes.length;//全ノーツ数格納
}
//----------------------------------------------------------------------------//


function NotesDraw(){
  
  const ctx = document.getElementById("maincanvas").getContext('2d');

  for(i=0;i<notes.length;i++){
    //center 156px
    //60fps = 110px  -notesimg_size/2
    if(notes[i]["judge"] === "ALWAY"){
      const  
            x_ = 156+((notes[i].timing-Gametimer)*markerdistance),//横移動座標
            y_=25+notes[i].lernID*20,//レーンIDに対応した座標

      //判定基準に従ってノーツエフェクト(y座標かえるだけ)
      timingjudge = Math.abs(Gametimer-notes[i].timing);//判定時差計算

      if(timingjudge <= juge_time.PARF){//PERFECT範囲内
       notesimg_sprite[notes[i].type].draw(ctx, x_-notesimg_size/2, 30+notes[i].lernID*20);
      }else  if(timingjudge<=juge_time.GREAT){//GREAT範囲内
       notesimg_sprite[notes[i].type].draw(ctx, x_-notesimg_size/2, 35+notes[i].lernID*20);
      }else if(timingjudge <= juge_time.GOOD){//GOOD範囲内
        notesimg_sprite[notes[i].type].draw(ctx, x_-notesimg_size/2, 40+notes[i].lernID*20);
      }else if(Gametimer - notes[i].timing >  juge_time.BAD){
        notes[i].judge ="MISS";
        //ajax通信でノーツデータ更新
         notesMissfunc(notes[i].lernID);
        break;
      }else {//判定外時間
        notesimg_sprite[notes[i].type].draw(ctx, x_-notesimg_size/2, 45+notes[i].lernID*20);
       }
    }
  }
}

const notesMissfunc = function(id_){//打ち損じ
  $.ajax({
    url: 'php/battlePHP/ActionSkillList.php',
    dataType:"json",
    type:"post",
    data:{
      notesdata:notes,
      time:Gametimer,
      lernid:id_,
      updatenotes:"miss"
    },
    success:function(data){
      PlayerActionUpdate(id_,data);
    },
    error:(
      function(XMLHttpRequest, textStatus, errorThrown) {
      alert('n:error!!!');
  　　console.log("XMLHttpRequest : " + XMLHttpRequest.status);
  　　console.log("textStatus     : " + textStatus);
  　　console.log("errorThrown    : " + errorThrown.message); 
      
      },function(data) {
      })
  });
};