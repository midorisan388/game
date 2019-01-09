const faceimgurl = "img/Eventimg/";

function createXmlHttpRequest(){
    var xmlhttp=null;
    if(window.ActiveXObject)
    {
        try
        {
            xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch(e)
        {
            try
            {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (e2)
            {
            }
        }
    }
    else if(window.XMLHttpRequest)
    {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}

function sendRequest(s)
{
    var num=s;
    var xmlhttp=createXmlHttpRequest();
    if(xmlhttp!=null)
    {
        xmlhttp.open("POST", "php/getSeliftext.php", false);
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        var data="data="+num;
        xmlhttp.send(data);
        var res=xmlhttp.responseText;
        
       return res;
    }
}

function getSelectRequest(s){
    var num=s;//選択肢グループID
    var xmlhttp=createXmlHttpRequest();
    if(xmlhttp!=null)
    {
        xmlhttp.open("POST", "php/getSelecttext.php", false);
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        var data="data="+num;
        xmlhttp.send(data);
        var res=xmlhttp.responseText;
       return res;
    }
}

function SelifCtrl(t){
    var txtres;
    var txt=[];

    var selecthtml;
    if(t.owner === "@@"){
        var w=$('#selifcanvas').css('width'),
            x= $('#selifcanvas').css('left')+w,
            y=$('#selifcanvas').css('top');
          
            txtres=getSelectRequest(selectGroupId);
            txtres=JSON.parse(txtres);

            txt[0] = txtres[0];
            txt[1] = txtres[1];
            $('#selects').css('left',x+"px");
            $('#selects').css('top',y+"px");
           $('#select-0').attr('data-nextln',txt[0].nextline);
           $('#select-1').attr('data-nextln',txt[1].nextline);

            selecthtml = "<p class=selecter >"+txt[0].selecttxt+"</p>";
            $('#select-0').html(selecthtml);
            selecthtml = "<p class=selecter >"+txt[1].selecttxt+"</p>";
            $('#select-1').html(selecthtml);

            $('#selifcanvas').attr('data-textmode',"select");
    }else{
        var id=Number(t.id);
        
        $('.selectlis').html("");
        $('#selifcanvas').css('left',charactersprites[id].dx*c_waspect +"px");
        $('#selifcanvas').css('top',charactersprites[id].dy*c_waspect +"px");
        $('.faceglaph').attr('src',faceimgurl+t.faceurl);
        $('#remake').html(t.owner);
        $('#selif').html(t.selif);
        eventmodedoc.attr('data-lnid',t.nextline);
    }
}