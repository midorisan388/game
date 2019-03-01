const battle_se_tap = "audio/se/sword.mp3";
let battle_se_volume = 0.5;

function AudioSetup(audio_obj){
  $.ajax({
    url:"./php/gameSystemPHP/OptionSetting.php",
    type:'post',
    dataType:'json',
    data:{
        option:'all'
    }
  }).done(function(data){
    audio_obj.volume=data["audio"]/10;
    battle_se_volume = data["se"]/10;
    audio_obj.play();
    AudioTest(battle_se_volume);
  }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
    alert('error!!!');
  　　console.log("XMLHttpRequest : " + XMLHttpRequest.status);
  　　console.log("textStatus     : " + textStatus);
  　　console.log("errorThrown    : " + errorThrown.message); 
  });
}

function AudioTest(se_vol){
  var  audiotest1 = new Audio(battle_se_tap);
  audiotest1.volume = se_vol;
  audiotest1.play();
}
