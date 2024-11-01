<?php

defined( 'ABSPATH' ) || exit;

// Подключение файлов
require_once get_stylesheet_directory() . '/tools/city-checker.php';
require_once get_stylesheet_directory() . '/tools/schedule-helper.php';

// Получение данных из полей ACF
$schedule = get_field('Schedule') ?: [];
$addresses = get_field('multiple_addresse') ?: [];
$default_city = 'Санкт-Петербург';
$phone = get_field('phone');
$prices = get_field('prices');

// Подготовка адресов из Repeater
$valid_addresses = [];
foreach ($addresses as $address_row) {
    $address = !empty($address_row['address']) ? $address_row['address'] : '';
    $city = !empty($address_row['city']) ? $address_row['city'] : $default_city;

    if (!empty($address)) {
        $full_address = add_city_to_address($city, $address);
        $valid_addresses[] = $full_address;
    }
}

$clean_phone = str_replace(' ', '', $phone);
?>

<div class="place-info-block">
    <!-- Средний чек -->
    <?php if (!empty($prices)) : ?>
        <p class="place-info-item">
            <i class="fas fa-wallet"></i>
            <span>
                <?php 
                    // Убираем пробелы из строки и проверяем, является ли результат числом
                    $clean_prices = str_replace(' ', '', $prices);

                    if (is_numeric($clean_prices)) : 
                ?>
                    от <?php echo esc_html($prices); ?> ₽
                <?php 
                    else : 
                ?>
                    <?php echo esc_html($prices); ?>
                <?php endif; ?>
            </span>
        </p>
    <?php endif; ?>

    <!-- График работы -->
    <?php if (!empty($schedule)) : ?>
        <ul class="work-schedule-list">
            <?php 
            foreach ($schedule as $row) {
                $selected_days = $row['day'];
                $start_time = $row['start_time'];
                $end_time = $row['end_time'];

                list($display_day, $display_time) = get_display_schedule($selected_days, $start_time, $end_time);
                $day_label = ($display_day === 'По записи') ? $display_day : $display_day . ':';
            ?>
                <li class="work-schedule-item">
                    <span class="fas fa-clock" aria-hidden="true"></span>
                    <strong> <?php echo esc_html($day_label); ?></strong>
                    <span>&nbsp;<?php echo esc_html($display_time); ?></span>
                </li>
            <?php } ?>
        </ul>
    <?php endif; ?>

    <!-- Телефон -->
    <?php if (!empty($phone)) : ?>
        <p class="place-info-item">
            <i class="fas fa-phone" aria-hidden="true"></i> 
            <a href="tel:<?php echo esc_attr($clean_phone); ?>" class="phone-link">
                <?php echo esc_html($phone); ?>
            </a>
        </p>
    <?php endif; ?>

    <!-- Адреса -->
    <?php if (!empty($valid_addresses)) : ?>
        <ul class="place-info-list">
            <?php foreach ($valid_addresses as $full_address) : 
                $display_address = str_ireplace($default_city . ', ', '', $full_address);
            ?>
                <li class="place-info-item">
                    <span class="fas fa-map-marker-alt" aria-hidden="true"></span>
                    <a href="#" class="address-link" data-address="<?php echo esc_js($full_address); ?>">
                        <?php echo esc_html($display_address); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
