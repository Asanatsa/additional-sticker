'use strict';

let radEle = document.getElementsByClassName("sticker-rad");
let textboxEle = document.getElementById("comment");


function clickSticker(event) {
	let t = event.target
	let idf = "{" + t.parentElement.dataset.id + "#" + t.dataset.name + "}";
	textboxEle.value = textboxEle.value + idf;
}

function selectStickers() {
	let status = [];

	for (let i = 0; i < radEle.length; i++) {
		let ele = document.getElementById("sticker-" + radEle[i].id);
		ele.hidden = !radEle[i].checked;


		//debug
		let a = {};
		a.name = radEle[i].id;
		a.status = radEle[i].checked;
		status[i] = a;

	}

	console.log(status);
}


let _e = document.createElement("scrpit") ;
_e.innerText = "selectStickers();";
document.appendChild(_e);
_e = undefined;

