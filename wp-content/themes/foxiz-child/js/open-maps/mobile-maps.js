document.addEventListener('DOMContentLoaded', function() {
    // Функция для открытия и закрытия popup
    function openPopup() {
        var popup = document.getElementById('map-popup');
        if (popup) {
            popup.style.display = 'flex';
            console.log('Popup открыт');
        } else {
            console.error('Элемент popup не найден');
        }
    }

    function closePopup() {
        var popup = document.getElementById('map-popup');
        if (popup) {
            popup.style.display = 'none';
            console.log('Popup закрыт');
        }
    }

    // Функция загрузки скриптов и их вызова
    function loadScript(src, callback) {
        var script = document.createElement('script');
        script.src = src;
        script.type = 'text/javascript';
        
        script.onload = function() {
            console.log('Скрипт загружен:', src);
            if (typeof callback === 'function') callback();
        };

        script.onerror = function() {
            console.error('Ошибка загрузки скрипта:', src);
        };

        document.head.appendChild(script);
    }

    // Добавление обработчиков для кнопок
    function addButtonHandlers() {
        var yandexButton = document.getElementById('yandex-button');
        var googleButton = document.getElementById('google-button');
        var appleButton = document.getElementById('apple-button');

        if (yandexButton) {
            yandexButton.addEventListener('click', function() {
                console.log('Нажата кнопка "Яндекс.Навигатор"');
                loadScript('/wp-content/themes/foxiz-child/js/open-maps/open-yandex.js', openYandex);
            });
        }

        if (googleButton) {
            googleButton.addEventListener('click', function() {
                console.log('Нажата кнопка "Google Maps"');
                loadScript('/wp-content/themes/foxiz-child/js/open-maps/open-google.js', openGoogle);
            });
        }

        if (appleButton) {
            appleButton.addEventListener('click', function() {
                console.log('Нажата кнопка "Apple Maps"');
                loadScript('/wp-content/themes/foxiz-child/js/open-maps/open-apple.js', openApple);
            });
        }
    }

    // Обработка кликов на ссылки для открытия popup
    var mapLinks = document.querySelectorAll('.open-map-popup');
    if (mapLinks.length > 0) {
        mapLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                openPopup(); // Открытие popup
            });
        });
        console.log('Слушатели событий добавлены для ссылок');
    }

    // Добавление обработчиков событий для кнопок
    addButtonHandlers();
});
