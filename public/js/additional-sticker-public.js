'use strict';

// change textarea element here
let aStickerCommentbox = document.getElementById("comment");

let aStickerRadioEle = document.getElementsByClassName("sticker-rad");
function clickSticker(event) {
	let t = event.target
	let idf = "{" + t.parentElement.dataset.id + "#" + t.dataset.name + "}";
	aStickerCommentbox.value = aStickerCommentbox.value + idf;
}

function selectStickers() {
	for (let i = 0; i < aStickerRadioEle.length; i++) {
		let ele = document.getElementById("sticker-" + aStickerRadioEle[i].id);
		ele.hidden = !aStickerRadioEle[i].checked;
	}
}

