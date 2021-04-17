function copyStringToClipboard (str) {
	// Create new element
	var el = document.createElement('textarea');
	// Set value (string to be copied)
	el.value = str;
	// Set non-editable to avoid focus and move outside of view
	el.setAttribute('readonly', '');
	el.style = {position: 'absolute', left: '-9999px'};

	//Check if there is active modal
	activeModal = document.querySelector(".modal.fade.in .modal-dialog .modal-content");
	if( activeModal != null )
		activeModal.appendChild(el);
	else
		document.body.appendChild(el);
	// Select text inside element
	el.select();
	// Copy text to clipboard
	document.execCommand('copy');

	// Remove temporary element
	if( activeModal != null )
		activeModal.removeChild(el);
	else
		document.body.removeChild(el);
}

function isUTCEquals(date1, date2) {
    return (
        date1.getUTCFullYear() === date2.getUTCFullYear() &&
        date1.getUTCMonth() === date2.getUTCMonth() &&
        date1.getUTCDate() === date2.getUTCDate()
    );
}
function UTCDate(){
    return new Date(Date.UTC.apply(Date, arguments));
}
function UTCToday(){
    var today = new Date();
    return UTCDate(today.getFullYear(), today.getMonth(), today.getDate());
}
function prettyPrice(price) {
	return price.toFixed(2);
}