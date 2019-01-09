const battle_se_tap = "audio/se/sword.mp3";
let battle_se_volume = 0.5;

    function AudioTest(lernid_){
      var  audiotest1 = new Audio(battle_se_tap);
      audiotest1.volume = battle_se_volume;
      audiotest1.play();
    }
