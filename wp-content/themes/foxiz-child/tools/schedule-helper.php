<?php
// Файл: schedule-helper.php

function get_display_schedule($selected_days, $start_time, $end_time) {
    // Массивы дней недели
    $days_mapping = [
        'Понедельник' => 'ПН',
        'Вторник' => 'ВТ',
        'Среда' => 'СР',
        'Четверг' => 'ЧТ',
        'Пятница' => 'ПТ',
        'Суббота' => 'СБ',
        'Воскресенье' => 'ВС'
    ];

    $weekdays = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница'];
    $weekends = ['Суббота', 'Воскресенье'];
    $all_days = array_keys($days_mapping); // Все дни недели
    $tue_to_sun = ['Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье']; // ВТ-ВС

    // Проверка на специальные случаи
    if (in_array('Ежедневно', $selected_days)) {
        return ['Ежедневно', 'с ' . esc_html($start_time) . ' до ' . esc_html($end_time)];
    }

   if (in_array('По записи', $selected_days)) {
        return ['По записи', '']; // Убираем время и двоеточие
    }

    // Проверка на все дни недели
    if (array_intersect($all_days, $selected_days) === $all_days) {
        return ['Ежедневно', 'с ' . esc_html($start_time) . ' до ' . esc_html($end_time)];
    }

    // Проверка на ВТ-ВС
    if (array_intersect($tue_to_sun, $selected_days) === $tue_to_sun) {
        return ['ВТ-ВС', 'с ' . esc_html($start_time) . ' до ' . esc_html($end_time)];
    }

    // Проверка на будние дни (ПН-ПТ)
    if (array_intersect($weekdays, $selected_days) === $weekdays) {
        return ['ПН-ПТ', 'с ' . esc_html($start_time) . ' до ' . esc_html($end_time)];
    }

    // Проверка на выходные (СБ-ВС)
    if (array_intersect($weekends, $selected_days) === $weekends) {
        return ['СБ-ВС', 'с ' . esc_html($start_time) . ' до ' . esc_html($end_time)];
    }

    // Конвертация выбранных дней в короткие обозначения
    $short_days = array_map(function($day) use ($days_mapping) {
        return $days_mapping[$day];
    }, $selected_days);
    $display_day = implode(', ', $short_days);
    $display_time = $start_time && $end_time 
        ? 'с ' . esc_html($start_time) . ' до ' . esc_html($end_time) 
        : 'Выходной';

    return [$display_day, $display_time];
}
