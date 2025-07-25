(function( $ ) {
	'use strict';
	
	$(document).ready(function() {
		$('#sticker-list').sortable();
		
		let tableElement = document.getElementById('sticker-list');
		let observer = new MutationObserver(updateListData);

		console.log(tableElement);
		observer.observe(tableElement, {
			childList: true,
			subtree: true,
		});
		

	});
	

})( jQuery );



function updateListData(){
	let tableElement = document.getElementById('sticker-list');
	let childNodes = tableElement.children;
	let data = [];
	for (let i = 0; i < childNodes.length; i++) {
		node = childNodes[i];

		if(node.className.includes('ui-sortable-placeholder')) {
			continue;	
		}

		let id = node.id;
		console.log(node);
		let checkbox = node.getElementsByClassName('sticker-checkbox')[0];

		data.push({
			id: id,
			checked: checkbox.checked
		});
			
		
	}

	console.log(data);
	let input = document.getElementById('bulk-data');
	if (input) {
		input.value = JSON.stringify(data);
	}


	window.addEventListener("beforeunload", (event) => {
		event.preventDefault();
		event.returnValue = "";
	});

	
}
