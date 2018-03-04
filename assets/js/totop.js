/*
    As described at (but with major modifications & improvements) - 

        https://www.w3schools.com/howto/howto_js_scroll_to_top.asp
*/
window.onscroll = function() {onWindowScroll()};

// a percentage of document size, if scrolled past this
// point the "to top" button will be displayed.
const scroll_travel = 0.05;

function onWindowScroll() {

    //dump();

    // don't care about safari!
    //if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    if(showToTop()) {
        document.getElementById("gototop").style.display = "block";
    } else {
        document.getElementById("gototop").style.display = "none";
    }
}

// When the user clicks on the button, scroll to the top of the document
function jumpToTop() {
    document.body.scrollTop = 0;            // For Safari (who cares? I sure don't! LOL get a REAL computer!)
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
} 

function showToTop() {
    // the point where the the button appears is based on percentage of
    // the height of the document and NOT the window.
    if(Math.round(($(document).height() * scroll_travel)) < document.documentElement.scrollTop) return true;
    else return false;
}

// for debugging
function dump() {
    console.log($(document).height());
    console.log(document.documentElement.scrollTop);
    console.log(Math.round($(document).height() * scroll_travel));
    console.log(Math.round(($(document).height() * scroll_travel)) < document.documentElement.scrollTop);
    console.log('showToTop() - ' + showToTop());
    console.log('-------');
    console.log(document.body.scrollTop);
    console.log('=======');
}
