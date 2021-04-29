// boostrap breakpoint indicator
window.addEventListener('resize', getWindowSize);
function getWindowSize() {

    var breakPoints = [];
    breakPoints.push(['xs', '0']);
    breakPoints.push(['sm', '576']);
    breakPoints.push(['md', '768']);
    breakPoints.push(['lg', '992']);
    breakPoints.push(['xl', '1200']);
    breakPoints.push(['xxl', '1400']);

    var result = '';
    for(var i = 0; i < breakPoints.length; i++) {
        if(window.innerWidth < breakPoints[i][1] && result == '') {
            result = 'innerWidth : '+ window.innerWidth + 'px - (breakpoint : ' +breakPoints[i-1][0] + ' : >' + breakPoints[i -1][1] + 'px <' + breakPoints[i][1] + 'px )';						
        }
    }

    console.log(result)
}
