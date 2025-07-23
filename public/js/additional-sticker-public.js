'use strict';

let radEle = document.getElementsByClassName("sticker-rad");
let textboxEle = document.getElementById("comment");


function clickSticker(event) {
	let t = event.target
	let idf = "{" + t.parentElement.dataset.id + "#" + t.dataset.name + "}";
	textboxEle.value = textboxEle.value + idf;
}

function selectStickers() {
	for (let i = 0; i < radEle.length; i++) {
		let ele = document.getElementById("sticker-" + radEle[i].id);
		let ele2 = document.getElementById("copyright-" + radEle[i].id);
		ele2.hidden = !radEle[i].checked;
		ele.hidden = !radEle[i].checked;
	}
}

