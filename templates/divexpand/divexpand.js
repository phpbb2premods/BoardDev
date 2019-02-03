/* 
Forum Images Expand & Hilight control for Code Divs 
Version 1.1 re-coded by SamG 05-04-03
*/ 

function selectAll(elementId) { 
  var element = document.getElementById(elementId); 
  if ( document.selection ) { 
    var range = document.body.createTextRange(); 
    range.moveToElementText(element); 
    range.select(); 
  } 
  if ( window.getSelection ) { 
    var range = document.createRange(); 
    range.selectNodeContents(element); 
    var blockSelection = window.getSelection(); 
    blockSelection.removeAllRanges(); 
    blockSelection.addRange(range); 
  } 
} 

function resizeLayer(layerId, newHeight) { 
  var myLayer = document.getElementById(layerId); 
  myLayer.style.height = newHeight + 'px'; 
} 

function codeDivStart() { 
  var randomId = Math.floor(Math.random() * 2000); 
  var imgSrc = 'templates/divexpand/images/'; 
  document.write('<div class="codetitle">Code:<img src="' + imgSrc + 'nav_expand.gif" width="14" height="10" title="View More of this Code" onclick="resizeLayer(' + randomId + ', 200)" onmouseover="this.style.cursor = \'pointer\'" /><img src="' + imgSrc + 'nav_expand_more.gif" width="14" height="10" title="View Even More of this Code" onclick="resizeLayer(' + randomId + ', 500)" onmouseover="this.style.cursor = \'pointer\'" /><img src="' + imgSrc + 'nav_contract.gif" width="14" height="10" title="View Less of this Code" onclick="resizeLayer(' + randomId + ', 50)" onmouseover="this.style.cursor = \'pointer\'" /><img src="' + imgSrc + 'nav_select_all.gif" width="14" height="10" title="Select All of this Code" onclick="selectAll(' + randomId + ')" onmouseover="this.style.cursor = \'pointer\'" /></div><div class="codediv" id="' + randomId + '">'); 
}