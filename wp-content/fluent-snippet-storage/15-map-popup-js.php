<?php
// <Internal Doc Start>
/*
*
* @description: 
* @tags: 
* @group: 
* @name: map-popup.js
* @type: js
* @status: draft
* @created_by: 
* @created_at: 
* @updated_at: 2024-10-20 18:33:17
* @is_valid: 
* @updated_by: 
* @priority: 10
* @run_at: wp_footer
* @load_as_file: 
* @condition: {"status":"no","run_if":"assertive","items":[[]]}
*/
?>
<?php if (!defined("ABSPATH")) { return;} // <Internal Doc End> ?>
function openDefaultMapsApp(address) {
    var yandexMapsUrl = 'yandexmaps://maps.yandex.ru/?text=' + encodeURIComponent(address);
    var appleMapsUrl = 'maps://maps.apple.com/?q=' + encodeURIComponent(address);
    var geoUrl = 'geo:0,0?q=' + encodeURIComponent(address);
    var webYandexMapsUrl = 'https://yandex.ru/maps/?text=' + encodeURIComponent(address);

    var isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);
    var isAndroid = /Android/i.test(navigator.userAgent);

    // Проверка, является ли устройство мобильным
    var isMobile = isIOS || isAndroid;

    if (isMobile) {
        // Открытие popup на мобильных устройствах
        openPopup(address);
    } else {
        // Открытие веб-версии Яндекс.Карт на десктопах
        window.open(webYandexMapsUrl, '_blank');
    }
}

// Функция для открытия popup
function openPopup(address) {
    document.getElementById('map-popup').style.display = 'flex';
    document.getElementById('map-popup').setAttribute('data-address', address);
}

// Функция для закрытия popup
function closePopup() {
    document.getElementById('map-popup').style.display = 'none';
}

// Логика для открытия навигаторов
function openYandex() {
    var address = document.getElementById('map-popup').getAttribute('data-address');
    var yandexNavigatorUrl = 'yandexnavi://build_route_on_map?lat_to=0&lon_to=0&what=' + encodeURIComponent(address);

    // Попытка открыть Яндекс.Навигатор через iframe
    var iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    document.body.appendChild(iframe);

    // Пытаемся открыть Яндекс.Навигатор через iframe
    iframe.src = yandexNavigatorUrl;

    // Таймер на fallback (Apple Maps)
    setTimeout(function() {
        document.body.removeChild(iframe);
        var appleMapsUrl = 'maps://maps.apple.com/?q=' + encodeURIComponent(address);
        window.location = appleMapsUrl; // Fallback на Apple Maps
    }, 1500);

    closePopup();
}

function openGoogle() {
    var address = document.getElementById('map-popup').getAttribute('data-address');
    var googleMapsUrl = 'google.navigation:q=' + encodeURIComponent(address);

    // Попытка открыть Google Maps через iframe
    var iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    document.body.appendChild(iframe);

    // Пытаемся открыть Google Maps через iframe
    iframe.src = googleMapsUrl;

    // Таймер на fallback (Apple Maps)
    setTimeout(function() {
        document.body.removeChild(iframe);
        var appleMapsUrl = 'maps://maps.apple.com/?q=' + encodeURIComponent(address);
        window.location = appleMapsUrl; // Fallback на Apple Maps
    }, 1500);

    closePopup();
}

function openApple() {
    var address = document.getElementById('map-popup').getAttribute('data-address');
    var appleMapsUrl = 'https://maps.apple.com/?daddr=' + encodeURIComponent(address) + '&dirflg=d';
    window.location = appleMapsUrl;
    closePopup();
}
