function limitText(limitField, limitNum, countdownID) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} 
}

function hideAllBut(container_id, exception_id) {
  
  var container = document.getElementById(container_id);
  var exception = document.getElementById(exception_id);
    
  // Hide all children of container
  for (child in container.childNodes) {
    if (typeof(container.childNodes[child].style) != 'undefined') {
      container.childNodes[child].style.display = 'none';
    }
  }
  
  // Show specific child
  exception.style.display = 'block'
  
  
}

function makeBlack (span_element) {
  
  var container = span_element.parentNode;
  var exception = span_element;
  
  // fontWeight = normal for all children of container
  for (child in container.childNodes) {
    if (typeof(container.childNodes[child].style) != 'undefined') {
      container.childNodes[child].style.color = 'inherit';
    }
  }
  
  // fontWeight = bold for specific child
  exception.style.color = '#000000'
  
}



function makeUnderlined (span_element) {
  
  var container = span_element.parentNode;
  var exception = span_element;
  
  // fontWeight = normal for all children of container
  for (child in container.childNodes) {
    if (typeof(container.childNodes[child].style) != 'undefined') {
      container.childNodes[child].style.textDecoration = 'none';
    }
  }
  
  // fontWeight = bold for specific child
  exception.style.textDecoration = 'underline'
  
}



function makeInverted (element) {
  
  var container = element.parentNode;
  var exception = element;
  
  // fontWeight = normal for all children of container
  for (child in container.childNodes) {
    if (typeof(container.childNodes[child].style) != 'undefined') {
      container.childNodes[child].style.color = 'inherit';
      container.childNodes[child].style.backgroundColor = 'inherit';
    }
  }
  
  // fontWeight = bold for specific child
      exception.style.color = '#FFFFFF';
      exception.style.backgroundColor = '#666666';
  
}

function makeBold (span_element) {
  
  var container = span_element.parentNode;
  var exception = span_element;
  
  // fontWeight = normal for all children of container
  for (child in container.childNodes) {
    if (typeof(container.childNodes[child].style) != 'undefined') {
      container.childNodes[child].style.fontWeight = 'normal';
    }
  }
  
  // fontWeight = bold for specific child
  exception.style.fontWeight = 'bold'
  
}