
var length = 0;
var newLength = null;
var face = null;



//================================================= ON LOAD ============================================

$( document ).ready(function() {
  KeepLook();

});


//================================================== UPLOADS PHOTOS ======================================
$('#upload').on('click', function() {
  console.log("upload started");
  $('#loading').prepend('<img src="load.gif" /><br>');
    var file_data = $('#sortpicture').prop('files')[0];
    var form_data = new FormData();
    form_data.append('file', file_data);
    console.log(form_data);

    $.ajax({
                url: 'upload.php', // point to server-side PHP script
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',

                success: function(php_script_response){
                    console.log(php_script_response); // display response from the PHP script, if any
                    if (php_script_response == "failed") {
                      alert("Mr.Api Does Not Belive That This Was A Picture Of a Face remeber to face the camera");
                      console.log("Mr.Api Does Not Belive That This Was A Picture Of a Face remeber to face the camera");
                    }
                    //listPhotos();
                    $("#loading").empty();
                    $( "#dialog-1" ).dialog( "close" );//CLose Jquery ui dialog
                    console.log("upload ended");
                }
     });

});




//============================================= ADD PHOTOS TO DOM ======================================
function displayPhotos() {
  $(".row").empty();
  var ppl = [];
  for (var i = 0; i < face.faces.length; i++) {
    ppl.push({url: face.faces[i].url, rate: faceinfo(face.faces[i])});
    //console.log(face.faces[i].url);

  }
  ppl.sort(function(a, b){
	   return a.rate- b.rate;
  });
  console.log(ppl);


  //ppl = removeDuplicate(ppl);
  //console.log(ppl);
  for (var i = 0; i < ppl.length; i++) {
    //=========================================== Adding to dom
    $('.row').prepend('<div id="ppl" class="col l3 s6"><img class="col s12 " src="http://' + ppl[i].url + '"/><br><p class="center-align">' + ppl[i].rate +'/10<p/></div>');
  }
}


//========================================= GRAB FACE INFO ===========================================
function faceinfo(obj) {
  // setting two different points as lengths on face in order to calculate ratios
  var C = dist(obj.leftEyeCenterX,obj.leftEyeCenterY,obj.noseTipX,obj.noseTipY);//C
  var D = dist(obj.leftEyeCenterX,obj.leftEyeCenterY,obj.lipLineMiddleX,obj.lipLineMiddleY);//D
  var E = dist(obj.nostrilLeftSideX,obj.nostrilLeftSideY,obj.nostrilRightSideX,obj.nostrilRightSideY);//E
  var F = dist(obj.leftEyeCornerLeftX,obj.leftEyeCornerLeftY,obj.rightEyeCornerRightX,obj.rightEyeCornerRightY);//F
  var G = dist(obj.leftEarTragusX,obj.leftEarTragusY,obj.rightEarTragusX,obj.rightEarTragusY);//G
  var I = dist(obj.noseTipX,obj.noseTipY,obj.chinTipX,obj.chinTipY);//I
  var H = dist(obj.lipLineMiddleX,obj.height,obj.leftEyeCenterX,obj.leftEyeCenterY);//h
  var J = dist(obj.lipLineMiddleX,obj.lipLineMiddleY,obj.chinTipX,obj.chinTipY);//J
  var L = dist(obj.noseTipX,obj.noseTipY,obj.lipLineMiddleX,obj.lipLineMiddleY);//L
  // calculating ratios
  var rate = ((I/J) + (I/C) + (E/L) + (F/H)) /4;
  var golden = 1.61803398875;
  if (rate < golden) {

    return((rate / golden * 10).toFixed(3));

  }
  else {

      return((golden / rate * 10).toFixed(3));

  }

}


//====================================== Place Dots ===================================================
// function not used anyore just made it to wrap my head around this
function placeDot(x,y) {
  $("body").append(
                $('<div class="marker"></div>').css({
                    position: 'absolute',
                    top:  x+'px',
                    left: y+'px',
                    width: '5px',
                    height: '5px',
                    background: '#000000'
                })
            );
}


//================================================= distance ======================================

function dist(Xa,Ya,Xb,Yb) {
  var out = Math.sqrt(Math.pow((Xa-Xb),2) + Math.pow((Ya-Yb),2));
  return out;
}


//================================================== Look over local json for changes =========================================
function KeepLook() {
    $.get( "info.php", function( data ) {
    $( ".result" ).html( data );
    face = JSON.parse(data);
  //console.log(removeDuplicate(face));
    //console.log(face);
    var newLength = face.faces.length;
    //console.log(newLength);
    if (newLength > length) {
      displayPhotos();
      length = newLength;
    }
  });
  setTimeout(KeepLook, 5000);
}


//============================================== remove dublicites from aray ========================================
function removeDuplicate(obj) {
  out = [];
  for (var i = 0; i < obj.length; i++) {
    out.push(JSON.stringify());
  }
  out = $.unique(out);
  console.log(out);
  return out;
}
//============================================== Jquery UI Dialog upload box ========================================================
$(function() {
  $( "#dialog-1" ).dialog({
    autoOpen: false,
  });
  $( "#opener" ).click(function() {
    $( "#dialog-1" ).dialog( "open" );
  });
});
