(function($) {
    $(document).ready(function() {
        // Отслеживаем фокус на полях репитера ACF с классом acf-address-input
        $(document).on('input', '.acf-address-input input[type="text"]', function() {
            var inputField = this;
            var query = $(inputField).val();

            // Минимальная длина запроса для начала поиска
            if (query.length < 3) return;

            // Выполнение AJAX-запроса к Geocoder API
            $.ajax({
                url: 'https://geocode-maps.yandex.ru/1.x/',
                dataType: 'json',
                data: {
                    apikey: 'ВАШ_API_КЛЮЧ',
                    format: 'json',
                    geocode: query,
                    results: 5
                },
                success: function(data) {
                    // Очистка предыдущих подсказок
                    $(inputField).next('.suggestions').remove();

                    // Создание контейнера для подсказок
                    var $suggestions = $('<div class="suggestions"></div>');
                    data.response.GeoObjectCollection.featureMember.forEach(function(item) {
                        var address = item.GeoObject.metaDataProperty.GeocoderMetaData.text;
                        var $suggestionItem = $('<div class="suggestion-item"></div>').text(address);

                        // Обработка клика по подсказке
                        $suggestionItem.on('click', function() {
                            $(inputField).val(address);
                            $suggestions.remove();
                        });

                        $suggestions.append($suggestionItem);
                    });

                    // Отображение подсказок под полем ввода
                    $(inputField).after($suggestions);
                }
            });
        });

        // Закрытие подсказок при потере фокуса
        $(document).on('blur', '.acf-address-input input[type="text"]', function() {
            var $suggestions = $(this).next('.suggestions');
            setTimeout(function() {
                $suggestions.remove();
            }, 200); // Задержка для обработки клика по подсказке
        });
    });
})(jQuery);
