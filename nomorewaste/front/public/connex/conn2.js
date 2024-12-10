document.addEventListener('DOMContentLoaded', function () {
    var switchCtn = document.getElementById('switch-cnt');
    var switchC1 = document.getElementById('switch-c1');
    var switchC2 = document.getElementById('switch-c2');
    var switchCircle = document.querySelector('.switch__circle');
    var switchCircleT = document.querySelector('.switch__circle--t');
    var aContainer = document.getElementById('a-container');
    var bContainer = document.getElementById('b-container');

    function switchForm(e) {
        switchCtn.classList.add('is-gx');
        setTimeout(function () {
            switchCtn.classList.remove('is-gx');
        }, 1500);

        switchCtn.classList.toggle('is-txr');
        switchCircle.classList.toggle('is-txr');
        switchCircleT.classList.toggle('is-txr');

        switchC1.classList.toggle('is-hidden');
        switchC2.classList.toggle('is-hidden');
        aContainer.classList.toggle('is-txl');
        bContainer.classList.toggle('is-txl');
        bContainer.classList.toggle('is-z200');
    }

    document.querySelectorAll('.switch-btn').forEach(function (btn) {
        btn.addEventListener('click', switchForm);
    });
});
