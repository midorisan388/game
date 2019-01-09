let
  member_combCount=[0,0,0,0],
  maxComb_=0,
  comb_=0;


  function ScoreInit(){
    document.getElementById("MISS").innerHTML= "MISS : "+0;
    document.getElementById("BAD").innerHTML= "BAD : "+0;
    document.getElementById("GOOD").innerHTML= "GOOD : "+0;
    document.getElementById("GREAT").innerHTML= "GREAT : "+0;
    document.getElementById("PARF").innerHTML= "PARF : "+0;

    document.getElementById("Score").innerHTML="SCORE : "+0;
    document.getElementById("COMB").innerHTML= "コンボ : "+0;


    $("#stetasview-hidden #scoreview").html("SCORE : "+0 +" コンボ :"+0);
    
    for(var i=0;i<4;i++){
      if(member_combCount[i] > 100)member_combCount[i]=100;
      $("#Comb_member"+(i+1)).html(member_combCount[i] +" %");
    }
  }
  function ScoreUpdate(judgedata){
    document.getElementById("MISS").innerHTML= "MISS : "+judgedata["MISS"];
    document.getElementById("BAD").innerHTML= "BAD : "+judgedata["BAD"];
    document.getElementById("GOOD").innerHTML= "GOOD : "+judgedata["GOOD"];
    document.getElementById("GREAT").innerHTML= "GREAT : "+judgedata["GREAT"];
    document.getElementById("PARF").innerHTML= "PARF : "+judgedata["PARF"];;

    document.getElementById("Score").innerHTML="SCORE : "+judgedata["SCORE"];
    document.getElementById("COMB").innerHTML= "コンボ : "+judgedata["COMB"];


    for(var i=0;i<4;i++){
      if(member_combCount[i] > 100)member_combCount[i]=100;
      $("#Comb_member"+(i+1)).html(member_combCount[i] +" %");
    }
  }
