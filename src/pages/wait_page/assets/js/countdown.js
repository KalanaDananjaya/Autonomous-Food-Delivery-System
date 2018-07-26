function test(){
  console.log("iot request");
//i should generate the iot request here
 }
 function mediaplay(){
    var file=document.getElementById("song");
    if(file.paused == true){
        file.play()
    }
    else{
        file.pause();
    }
}
document.getElementById("customer").innerHTML = customer;
/*
if (fooditem==1){
  var minutes = 4;
  var seconds = 59;
  document.getElementById("image").src="assets/img/iphone.svg";
}
else if(fooditem==2){
  var minutes=9;
  var seconds=59;
}

// Update the count down every 1 second
var x = setInterval(function() {

  seconds--;
  if (seconds<0){
    minutes--;
    seconds=59;
  }

  document.getElementById("countdown").innerHTML = minutes + "m " + seconds + "s ";

  if (minutes<0){
    document.getElementById("countdown").innerHTML = minutes + "0 " + seconds + "0 ";

  }
  
}, 1000);*/
