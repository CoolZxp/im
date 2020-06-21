var mark = document.querySelector('#loading .mark'),
    loadingNum = 18,
    loadingstep = 360 / loadingNum,
    container1 = document.querySelector('#loading .container1'),
    container2 = document.querySelector('#loading .container2'),
    loadingCount = 0,
    mainTl = new TimelineMax({paused: true});
TweenMax.set('svg', {
    visibility: 'visible'
})
TweenMax.set([container2, container1], {
    transformOrigin: '50% 50%'
})
mainTl.timeScale(2)
function makeDial(container, radius, alpha) {
    var angle, clone, point, tl, cloneLabel;
    for (var i = 0; i < loadingNum; i++) {
        angle = loadingstep * i;
        clone = mark.cloneNode(true);
        container.appendChild(clone);
        point = {
            x: (Math.cos(angle * Math.PI / 180) * radius) + 400,
            y: (Math.sin(angle * Math.PI / 180) * radius) + 300
        }
        TweenMax.set(clone, {
            x: point.x,
            y: point.y,
            rotation: angle,
            alpha: alpha
        })
        tl = new TimelineMax({
            repeat: -1
        });
        tl.to(clone, 1, {
            attr: {
                x2: 60
            },
            ease: Power3.easeInOut
        }).to(clone, 2, {
            attr: {
                x1: 80,
                x2: 80
            },
            ease: Power1.easeInOut
        })
        mainTl.add(tl, loadingCount / 10)
        loadingCount++;
    }
}
makeDial(container1, 33, 0.5)
makeDial(container2, 33, 1)