const Hammer = require('hammerjs')

var allCards = document.querySelectorAll('.card');
var swiperContainer = document.querySelector('.swiper-container');
var cardEvent = "";


function initCards() {
    var newCards = document.querySelectorAll('.card:not(.card-removed)');

    newCards.forEach(function (card, index) {
        card.style.zIndex = allCards.length - index;
        card.style.transform = 'scale(' + (20 - index) / 20 + ') translate(' + 20 * index + 'px,' + 20 * index + 'px )';
        card.style.opacity = (10 - index) / 10;
    });

}

initCards();

allCards.forEach(function (el) {
    var hammertime = new Hammer(el);

    hammertime.on('pan', function (event) {
        el.classList.add('moving');
    });

    hammertime.on('pan', function (event) {
        if (event.target.classList.contains('card')) {
            cardEvent = event;
            if (event.deltaX === 0) return;
            if (event.center.x === 0 && event.center.y === 0) return;


            var xMulti = event.deltaX * 0.03;
            var yMulti = event.deltaY / 80;
            var rotate = xMulti * yMulti;
            var posX = event.deltaX <= 0 ? event.deltaX : 0;

            event.target.style.transform = 'translateX(' + posX + 'px) rotate(' + rotate + 'deg)';
        }
    });

    hammertime.on('panend', function (event) {
        el.classList.remove('moving');
        swiperContainer.classList.remove('card_love');
        swiperContainer.classList.remove('card_nope');

        var moveOutWidth = document.body.clientWidth;
        var keep = Math.abs(event.deltaX) < 80;

        if (keep) {
            event.target.style.transform = '';
        } else {
            var endX = Math.max(Math.abs(event.velocityX) * moveOutWidth, moveOutWidth);
            var toX = event.deltaX > 0 ? 0 : -endX;

            if (event.target.classList.contains('card')) {
                event.target.style.transform = 'translate(' + toX + 'px, ' + (0) + 'px) rotate(' + 0 + 'deg)';
            }
            if (toX < 0) {
                event.target.classList.toggle('card-removed');
                initCards();
            }
            getCards();
        }
    });
});

var parentSwiper = new Hammer(swiperContainer);
var parentSwipDirection = false;

parentSwiper.on('panright', function (event) {
    parentSwipDirection = true;
    var allRemovedCards = document.querySelectorAll('.card-removed');

    for (var i = 0; i < allRemovedCards.length; i++) {
        var xMulti = event.deltaX * 0.03;
        var yMulti = event.deltaY / 80;
        var rotate = xMulti * yMulti;
        var posX = event.deltaX <= 0 ? event.deltaX * 1.5  : 0;

        allRemovedCards[allRemovedCards.length - 1].style.transform = 'translateX(' + posX + 'px) rotate(' + rotate + 'deg)';
        allRemovedCards[allRemovedCards.length - 1].style.zIndex = allCards.length + 1;
    }
});
parentSwiper.on('panend', function (event) {
    var allRemovedCards = document.querySelectorAll('.card-removed');
    var moveOutWidth = document.body.clientWidth;
    var keep = Math.abs(event.deltaX) < 80;

    if (!keep) {
        var endX = Math.max(Math.abs(event.velocityX) * moveOutWidth, moveOutWidth);
        var toX = event.deltaX > 0 ? 0 : -endX;

        for (var i = 0; i < allRemovedCards.length; i++) {
            if (allRemovedCards && parentSwipDirection) {
                parentSwipDirection = false;
                allRemovedCards[allRemovedCards.length - 1].classList.remove('card-removed');
                initCards();
            }
        }
    }


});

function getCards() {

}