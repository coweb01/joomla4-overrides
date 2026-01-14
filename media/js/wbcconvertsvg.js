"use strict";

/** * 
 * @subpackage  Templates.wbc_blanco_j4
 * @copyright   (C) das-webconcept.de 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Claudia Oerter
 * Wandelt svg Grafiken in inline svg um. Klasse .sv-inline
 * 
 * */

document.addEventListener('DOMContentLoaded', () => {

  var svgImages = document.querySelectorAll('img.inline-svg');
  svgImages.forEach(function(img) {
    var imgURL = img.getAttribute('src');
    var imgID = img.getAttribute('id');

    var xhr = new XMLHttpRequest();
    xhr.open('GET', imgURL, true);
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4 && xhr.status === 200) {
        var parser = new DOMParser();
        var svgContent = parser.parseFromString(xhr.responseText, 'image/svg+xml');
        var svgElement = svgContent.querySelector('svg');

        if (imgID) {
          svgElement.setAttribute('id', imgID);
        }

        svgElement.removeAttribute('xmlns:a');
        
        img.parentNode.replaceChild(svgElement, img);
      }
    };
    xhr.send();
  });
})