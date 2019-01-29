$.ajax({
    url:"http://localhost/serverside/MenuVar.html",
    dataType:"html",
    cache:false,
    async:false,
    success:function(data){
        $(".MenuVar-frame").html(data);
    },
    error:(function(XMLHttpRequest, textStatus, errorThrown) {
          alert('error!!!');
      　　console.log("XMLHttpRequest : " + XMLHttpRequest.status);
      　　console.log("textStatus     : " + textStatus);
      　　console.log("errorThrown    : " + errorThrown.message);              
    })
});