// Функция для открытия Яндекс.Навигатора
function openYandex() {
    var yandexNavigatorUrl = 'yandexnavi://build_route_on_map?lat_to=0&lon_to=0&what=destination';
    var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

    if (isMobile) {
        var iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = yandexNavigatorUrl;
        document.body.appendChild(iframe);

        // Удаление iframe через 1 секунду
        setTimeout(function() {
            document.body.removeChild(iframe);
            console.log('Попытка открытия Яндекс.Навигатора');
        }, 1000);
    } else {
        alert("Эта функция доступна только на мобильных устройствах.");
    }
}
