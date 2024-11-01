<?php
// Функция для добавления города к адресу, если он не указан
function add_city_to_address($city, $address) {
    // Проверка на пустые значения
    if (empty($city) || empty($address)) {
        return $address; // Возвращаем адрес, если одно из значений пусто
    }

    // Приводим строки к нижнему регистру для проверки
    $city_lower = mb_strtolower($city);
    $address_lower = mb_strtolower($address);

    // Проверка на наличие города в адресе
    if (strpos($address_lower, $city_lower) === false) {
        return $city . ', ' . $address; // Добавляем город в начало, если его нет
    }

    return $address; // Если город уже есть в адресе, возвращаем его без изменений
}
