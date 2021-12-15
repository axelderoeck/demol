function hamburgerMenu() {
	var x = document.getElementById("myLinks");
	if (x.style.display === "block") {
		x.style.display = "none";
	} else {
		x.style.display = "block";
	}
}

// Cookie Consent alert
(function() {
	if (!localStorage.getItem("cookieconsent")) {
	  document.body.innerHTML +=
		'\
	  <div class="cookieconsent info">\
		<p class="title">De mol eet ook cookies</p>\
		<p>Door het verdere gebruik van deze site ga je akkoord met het gebruik van cookies.</p>\
		<br>\
		<a href="#">\
			Akkoord\
		</a>\
		<img src="img/assets/demol_logo_geen_tekst_cookie.png" />\
	  </div>\
	  ';
	  document.querySelector(".cookieconsent a").onclick = function(e) {
		e.preventDefault();
		document.querySelector(".cookieconsent").style.display = "none";
		localStorage.setItem("cookieconsent", true);
	  };
	}
})();

window.stemKnop = function(toggle) {
	if (toggle == "aan") {
		document.getElementById('stemKnop').disabled = false;
	} else if (toggle == "uit") {
		document.getElementById('stemKnop').disabled = true;
	}
}

window.submitKnop = function(toggle) {
	if (toggle == "aan") {
		document.getElementById('formSubmitVote').disabled = false;
	} else if (toggle == "uit") {
		document.getElementById('formSubmitVote').disabled = true;
	}
}

window.infoTekst = function(tekst) {
	document.getElementById('infoTekst').innerHTML = tekst;
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

document.getElementById('menu-btn').onclick = function() {
	// access properties using this keyword
	if (this.checked) {
		document.getElementById("sideNav").style.width = "250px";
	} else {
		document.getElementById("sideNav").style.width = "0";
	}
};

function openNav() {
	document.getElementById("mySidenav").style.width = "250px";
	// document.getElementById("main").style.marginLeft = "250px";
}

/* Set the width of the side navigation to 0 and the left margin of the page content to 0 */
function closeNav() {
	document.getElementById("mySidenav").style.width = "0";
	// document.getElementById("main").style.marginLeft = "0";
}

function collapse(contentId, buttonId) {
	var button = document.getElementById(buttonId);
	var content = document.getElementById(contentId);
	if (content.style.display === "block") {
		button.style.transform = "rotate(0deg)";
		content.style.display = "none";
	} else {
		button.style.transform = "rotate(180deg)";
		content.style.display = "block";
	}
}

function showNotification(message = "", type = "warning") {
	var notification = document.getElementById('informationPopup');
	if(message != "" && type != null){
		notification.classList.add(type);
		notification.innerHTML = "<p>" + message + "</p>";
		notification.style.top = "20px";
		setTimeout(function() {
			notification.style.top = "-50px";
		}, 4000); //wait 4 seconds before closing again
	}
}

function showPopup(id, showhide) {
	if (showhide == "show") {
		document.getElementById(id).style.display = "block";
	} else if (showhide == "hide") {
		document.getElementById(id).style.display = "none";
	}
}

$(document).ready(function() {
	$('ul.tabs').tabs({
		swipeable: true,
		responsiveThreshold: 1920
	});
});

/* Indicator swipe function */
function setIndicator(direction) {
	var percent;
	if (direction == 'right') {
		percent = 100;
	} else if (direction == 'left') {
		percent = 0;
	}
	document.getElementById('thecooler_indicator').style.transform = "translateX(" + percent + "%)";
	document.getElementById('thecooler_indicator').style["-webkit-transform"] = "translateX(" + percent + "%)";
	document.getElementById('thecooler_indicator').style["-ms-transform"] = "translateX(" + percent + "%)";
}

function editMode(id, visible){
	if(visible == true){
		document.getElementById(id).style.transform = "translateX(0%)";
		document.getElementById(id).style["-webkit-transform"] = "translateX(0%)";
		document.getElementById(id).style["-ms-transform"] = "translateX(0%)";
	}else if(visible == false){
		document.getElementById(id).style.transform = "translateX(100%)";
		document.getElementById(id).style["-webkit-transform"] = "translateX(100%)";
		document.getElementById(id).style["-ms-transform"] = "translateX(100%)";
	}
}

function screenAnimation(textid, name, color) {
	// Get the element to start the typewriter animation on
	var textfield = document.querySelector("#"+textid);
	// Initiate typewriter object
	var typewriter = new Typewriter(textfield, {
		loop: false,
		delay: 300,
	});
	// Start typewriter animation
	typewriter.typeString(name)
	.pauseFor(1000)
	// Typing completed ->
	.callFunction(() => {
		// Hide all elements from page
		$("#screenPage").css({
			"opacity": "0"
		})
		$("#screenFingerprint").css({
			"opacity": "1"
		})
		// Set screen to correct color
		$("#screen_"+color).css({
			"animation": "showScreen 0.5s backwards ease",
			"opacity": "1"
		});
	})
	.start();
}
