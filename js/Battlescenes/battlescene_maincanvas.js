
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var
GameStage = "Ready",
markerdistance=110;//中央マーカーからの両サイドの距離px

////////////////////////////////////////////////////メイン処理//////////////////////////////////////////

function Maincanvas_render(){
  ctx.fillRect(156,0,1,300);
  NotesDraw();
  Party_Draw();
  EnemyDraw();
}

function Maincanvas_update(){
    EnemyUpdate();
}