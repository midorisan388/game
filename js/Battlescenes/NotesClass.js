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
  notescounter=0,notesLen=0,

  notescount=0,

  notes=[];//ノーツオブジェクト配列

//今のtころ保留
function SkillCall(num){
  AudioTest(num);
 $.ajax({
  url:"php/battlePHP/battleCharacterSt.php",
  method:"post",
  data:{
    mid:num,
    stetaspara:"Pow"
  },
  success:function(data){
    var damage = data;
    $('#enemyid'+num).html(damage);
  },
  error:function(data){
    console.log("error"+data);
  }
});
 
  //getPartyMemberActionSkill(num);//アクションスキル呼び出し
}


//------JSONファイルからノーツ情報取得&格納-------------------------------------//
function Notesinit(data){
  
  const notesdata_json = JSON.parse(data);
  
  var i=0;
    notesdata_json.forEach((notesdata) => {
    notes_ = notesdata;
    //NotesClass(notes_['lernID'],notes_['timing'],notes_['type']);
    notes[i] = {
      timing:notes_['timing'],
      lernID:notes_['lernID'],
      type:notes_['type'],
      judge:"ALWAY"
    };
    i++;
});
  notesLen=notes.length;
}
//----------------------------------------------------------------------------//
function SearchNotes(no){
  AudioTest(no);
}


function NotesDraw(){
  const ctx = document.getElementById("maincanvas").getContext('2d');
  for(i in notes){
    //center 156px
    //60fps = 110px  -notesimg_size/2
    if(notes[i]["judge"] === "ALWAY"){
      const  
            x_ = 156+((notes[i].timing-Gametimer)*markerdistance),
            y_=25+notes[i].lernID*20,

            timingjudge = Math.abs(Gametimer-notes[i].timing);

      if(timingjudge <= juge_time.PARF){
       notesimg_sprite[notes[i].type].draw(ctx, x_-notesimg_size/2, 30+notes[i].lernID*20);
      }else  if(timingjudge<=juge_time.GREAT){
       notesimg_sprite[notes[i].type].draw(ctx, x_-notesimg_size/2, 35+notes[i].lernID*20);
      }else  if(timingjudge <= juge_time.GOOD){
        notesimg_sprite[notes[i].type].draw(ctx, x_-notesimg_size/2, 40+notes[i].lernID*20);
      }else {
       notesimg_sprite[notes[i].type].draw(ctx, x_-notesimg_size/2, 45+notes[i].lernID*20);
      }

   if(Gametimer - notes[i].timing >  juge_time.BAD){
        notes[i].judge ="MISS";
       
        //ajax通信でノーツデータ更新
         notesMissfunc();
        break;
      }
    }
  }
}

const notesMissfunc = function(){
  $.ajax({
    url: 'php/battlePHP/ActionSkillList.php',
    dataType:"json",
    type:"post",
    data:{
      notesdata:notes,
      time:Gametimer,
      lernid:i,
    },
    success:function(data){
      
      StetasUpdate(notes[i].lernID,data);
      //SearchNotes(1);
    },
    error:(
      function(XMLHttpRequest, textStatus, errorThrown) {
      alert('error!!!');
  　　console.log("XMLHttpRequest : " + XMLHttpRequest.status);
  　　console.log("textStatus     : " + textStatus);
  　　console.log("errorThrown    : " + errorThrown.message); 
      
      },function(data) {
      })
  });
};