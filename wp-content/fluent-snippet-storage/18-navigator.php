<?php
// <Internal Doc Start>
/*
*
* @description: 
* @tags: 
* @group: 
* @name: Навигатор
* @type: js
* @status: published
* @created_by: 
* @created_at: 
* @updated_at: 2024-10-21 04:21:52
* @is_valid: 
* @updated_by: 
* @priority: 10
* @run_at: wp_footer
* @load_as_file: 
* @condition: {"status":"no","run_if":"assertive","items":[[]]}
*/
?>
<?php if (!defined("ABSPATH")) { return;} // <Internal Doc End> ?>
document.addEventListener('DOMContentLoaded', function() {
    var addressLinks = document.querySelectorAll('.address-link');
    addressLinks.forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            var address = this.getAttribute('data-address');

            // Проверка на десктоп или мобильное устройство
            if (window.innerWidth > 1024) {
                // Для десктопа: открываем Яндекс.Карты в новом окне
                openYandexMapsDesktop(address);
            } else {
                // Для мобильных устройств: открываем popup
                Swal.fire({
                    showCancelButton: true,
                    showDenyButton: true,
                    confirmButtonText: 'Яндекс.Навигатор',
                    denyButtonText: 'Google Карты',
                    cancelButtonText: 'Apple Карты',
                    customClass: {
                        popup: 'swal2-popup',
                        title: 'swal2-title',
                        actions: 'swal2-actions'
                    },
                    buttonsStyling: false,
                    icon: undefined
                }).then((result) => {
                    if (result.isConfirmed) {
                        openYandexMaps(address);
                    } else if (result.isDenied) {
                        openGoogleMaps(address);
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        openAppleMaps(address);
                    }
                });
            }
        });
    });

    // Функция для открытия Яндекс.Карт на десктопе в новом окне
    function openYandexMapsDesktop(address) {
        var webYandexMapsUrl = 'https://yandex.ru/maps/?text=' + encodeURIComponent(address);
        window.open(webYandexMapsUrl, '_blank', 'noopener');
    }

    // Функция для открытия Яндекс.Навигатора на мобильных
    function openYandexMaps(address) {
        var url = 'yandexmaps://maps.yandex.ru/?text=' + encodeURIComponent(address);
        openInApp(url, 'https://yandex.ru/maps/?text=' + encodeURIComponent(address), true);
    }

    // Функция для открытия Google Maps
    function openGoogleMaps(address) {
        var isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);
        var url = isIOS
            ? 'comgooglemaps://?q=' + encodeURIComponent(address)
            : 'geo:0,0?q=' + encodeURIComponent(address);

        openInApp(url, 'https://maps.google.com/?q=' + encodeURIComponent(address), true);
    }

    // Функция для открытия Apple Maps
    function openAppleMaps(address) {
        var url = 'maps://maps.apple.com/?q=' + encodeURIComponent(address);
        openInApp(url, 'https://maps.apple.com/?q=' + encodeURIComponent(address));
    }

    // Универсальная функция открытия приложения с контролем fallback
    function openInApp(appUrl, webUrl, newWindow = false) {
        var opened = false;
        window.location = appUrl;

        // Таймер для fallback на веб-версию
        var timeout = setTimeout(function() {
            if (!opened) {
                if (newWindow) {
                    window.open(webUrl, '_blank', 'noopener');
                } else {
                    window.location.href = webUrl;
                }
            }
        }, 1500);

        // Отслеживаем видимость страницы
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'hidden') {
                opened = true;
                clearTimeout(timeout);
            }
        });
    }
});
