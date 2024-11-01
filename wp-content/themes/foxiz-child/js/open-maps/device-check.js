document.addEventListener('DOMContentLoaded', function() {
    // Определение устройства
    var userAgent = navigator.userAgent || navigator.vendor || window.opera;
    var isMobile = /iPhone|iPad|iPod|Android|webOS|BlackBerry|Windows Phone/i.test(userAgent);

    // Функция для открытия popup
    function openPopup(address) {
        var popup = document.getElementById('map-popup');
        if (popup) {
            popup.style.display = 'flex';
            popup.setAttribute('data-address', address); // Сохраняем адрес в popup
            console.log('Popup открыт с адресом:', address);
        } else {
            console.error('Элемент popup не найден');
        }
    }

    // Функция для закрытия popup
    function closePopup() {
        var popup = document.getElementById('map-popup');
        if (popup) {
            popup.style.display = 'none';
            console.log('Popup закрыт');
        }
    }

    // Функция обработки кнопок в popup
    function handlePopupButtons() {
        var yandexButton = document.getElementById('yandex-button');
        var googleButton = document.getElementById('google-button');
        var appleButton = document.getElementById('apple-button');
        var popup = document.getElementById('map-popup');

        // Получаем адрес из атрибута popup
        var address = popup ? popup.getAttribute('data-address') : '';

        // Обработчик для Яндекс.Навигатора
        if (yandexButton) {
            yandexButton.addEventListener('click', function() {
                var yandexMapsUrl = 'yandexmaps://maps.yandex.ru/?text=' + encodeURIComponent(address);
                window.location = yandexMapsUrl;
                console.log('Переход на Яндекс.Навигатор:', yandexMapsUrl);
                closePopup();
            });
        }

        // Обработчик для Google Maps
        if (googleButton) {
            googleButton.addEventListener('click', function() {
                var googleMapsUrl = 'geo:0,0?q=' + encodeURIComponent(address);
                window.location = googleMapsUrl;
                console.log('Переход на Google Maps:', googleMapsUrl);
                closePopup();
            });
        }

        // Обработчик для Apple Maps
        if (appleButton) {
            appleButton.addEventListener('click', function() {
                var appleMapsUrl = 'maps://maps.apple.com/?q=' + encodeURIComponent(address);
                window.location = appleMapsUrl;
                console.log('Переход на Apple Maps:', appleMapsUrl);
                closePopup();
            });
        }

        // Обработчик для кнопки "Отмена"
        var closeButton = document.getElementById('close-popup');
        if (closeButton) {
            closeButton.addEventListener('click', function() {
                closePopup();
            });
        }
    }

    // Функция обработки кликов на ссылках с классом open-map-popup
    function handleMapLinks() {
        var mapLinks = document.querySelectorAll('.open-map-popup');

        mapLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                var address = this.getAttribute('data-address');
                if (address && address.trim() !== '') {
                    if (isMobile) {
                        // Открытие popup на мобильных устройствах
                        openPopup(address);
                    } else {
                        // Открытие веб-версии Яндекс.Карт на десктопе
                        var webYandexMapsUrl = 'https://yandex.ru/maps/?text=' + encodeURIComponent(address);
                        window.open(webYandexMapsUrl, '_blank');
                        console.log('Открывается Яндекс.Карты в новой вкладке:', webYandexMapsUrl);
                    }
                } else {
                    console.error('Адрес отсутствует или пустой');
                }
            });
        });
    }

    // Инициализация
    handleMapLinks();
    handlePopupButtons();
});
