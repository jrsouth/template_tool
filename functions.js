function limitText(limitField, limitNum, countdownID) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} 
}

function showFieldLocator(xloc, yloc, page, scale) {
  previewPage = document.getElementById('previewPage'+page);
  fieldLocator = document.getElementById('fieldLocator'+page);
    
  fieldLocatorStyle = window.getComputedStyle(fieldLocator);
  fieldLocatorWidth = fieldLocatorStyle.width.substring(0,fieldLocatorStyle.width.length-2)/2;
  fieldLocatorHeight = fieldLocatorStyle.height.substring(0,fieldLocatorStyle.height.length-2)/2;
  
  fieldLocator.style.left = (previewPage.x - fieldLocatorWidth + scale*xloc) + 'px';
  fieldLocator.style.top = (previewPage.y - fieldLocatorHeight + scale*yloc) + 'px';
  fieldLocator.style.display = "block";
}

function hideFieldLocator(page) {
  fieldLocator = document.getElementById('fieldLocator'+page);
  fieldLocator.style.display = "none";
}