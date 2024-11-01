function openDefaultMapsApp(address) {
    var yandexMapsUrl = 'yandexmaps://maps.yandex.ru/?text=' + encodeURIComponent(address);
    var appleMapsUrl = 'maps://maps.apple.com/?q=' + encodeURIComponent(address);
    var geoUrl = 'geo:0,0?q=' + encodeURIComponent(address);
    var webYandexMapsUrl = 'https://yandex.ru/maps/?text=' + encodeURIComponent(address);

    var isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);
    var isAndroid = /Android/i.test(navigator.userAgent);

    if (isIOS || isAndroid) {
        var iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        document.body.appendChild(iframe);

        // Пытаемся открыть Яндекс.Карты через iframe
        iframe.src = yandexMapsUrl;

        // Устанавливаем таймер на fallback
        setTimeout(function() {
            document.body.removeChild(iframe);

            if (isIOS) {
                window.location = appleMapsUrl; // Переход на Apple Maps
            } else if (isAndroid) {
                window.location = geoUrl; // Переход на Google Maps
            }
        }, 500); // Таймаут перед fallback
    } else {
        // Для десктопов открываем веб-версию Яндекс.Карт
        window.open(webYandexMapsUrl, '_blank');
    }
}
