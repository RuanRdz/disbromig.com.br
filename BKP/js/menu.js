function putover(sid){

var cid = document.getElementById(sid);
cid.innerHTML = "<img src='images/layout/sepover.gif'>";
//alert(cid.id);

}

function putout(sid){

var cid = document.getElementById(sid);

if(cid.id != seps)
{
cid.innerHTML = "<img src='images/layout/sep.gif'>";
}

}



function putdown(sid){

	var cid = document.getElementById(sid);
    cid.innerHTML = "<img src='images/layout/sepover.gif'>";
   
}