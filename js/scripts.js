function hamburgerMenu() {
  var x = document.getElementById("myLinks");
  if (x.style.display === "block") {
    x.style.display = "none";
  } else {
    x.style.display = "block";
  }
}

window.stemKnop = function(toggle) {
    if(toggle == "aan"){
        document.getElementById('stemTekst').innerHTML = "<span>Stemmen</span> is nu mogelijk.";
        document.getElementById('stemKnop').disabled = false;
    }else if(toggle == "uit") {
        document.getElementById('stemTekst').innerHTML = "<span>Stemmen</span> is nu niet mogelijk. <br/> Je hebt al gestemd voor de meest recente aflevering.";
        document.getElementById('stemKnop').disabled = true;
    }
}

function openReg() {
    var x = document.getElementById("reg");
    var y = document.getElementById("log");
    if (x.style.display === "block") {
        x.style.display = "none";
        y.style.display = "block";
    } else {
        x.style.display = "block";
        y.style.display = "none";
    }
}
