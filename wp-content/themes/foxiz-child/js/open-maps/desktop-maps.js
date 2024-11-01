function openDesktopMaps(address) {
    // Создание URL для веб-версии Яндекс.Карт
    var webYandexMapsUrl = 'https://yandex.ru/maps/?text=' + encodeURIComponent(address);
    
    // Переход по ссылке
    window.location.href = webYandexMapsUrl;
}

// Пример использования на десктопах
document.addEventListener('DOMContentLoaded', function() {
    var mapLinks = document.querySelectorAll('.open-map-popup');
    
    mapLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault(); // Предотвращение стандартного перехода

            // Получаем значение data-address
            var address = this.getAttribute('data-address');
            if (address) {
                openDesktopMaps(address); // Вызов функции для открытия Яндекс.Карт
            }
        });
    });
});
