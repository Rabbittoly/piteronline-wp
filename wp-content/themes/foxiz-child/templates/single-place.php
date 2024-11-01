<?php
/** Don't load directly */
defined( 'ABSPATH' ) || exit;

// Подключаем основной заголовок темы Foxiz
get_header(); 

$classes          = [ 'single-standard-5', 'single-place' ]; // Добавлены классы Foxiz
$sidebar_name     = foxiz_get_single_setting( 'sidebar_name' );
$sidebar_position = foxiz_get_single_sidebar_position();
$crop_size        = foxiz_get_single_crop_size( '2048x2048' );

if ( 'none' === $sidebar_position ) {
    $sidebar_name = false;
}
if ( empty( $sidebar_name ) || ! is_active_sidebar( $sidebar_name ) ) {
    $classes[] = 'without-sidebar';
} else {
    $classes[] = 'is-sidebar-' . $sidebar_position;
    $classes[] = foxiz_get_single_sticky_sidebar();
}
?>

<div class="<?php echo join( ' ', $classes ); ?>">
    <?php foxiz_single_open_tag(); ?>

    <header class="single-header">
        <div class="single-header-inner">
            <div class="s-feat-holder full-dark-overlay">
                <?php 
                // Вывод главного изображения из ACF
                $place_image = get_field('place_image');
                if ($place_image) : ?>
                    <img src="<?php echo esc_url($place_image['url']); ?>" alt="<?php echo esc_attr($place_image['alt']); ?>" class="featured-img">
                <?php else : ?>
                    <?php the_post_thumbnail($crop_size, [ 'class' => 'featured-img' ]); ?>
                <?php endif; ?>
            </div>

            <div class="rb-s-container edge-padding">
                <div class="single-header-content overlay-text">
                    <?php
                    // Выводим заголовок и мета-данные Foxiz
                    foxiz_single_title( 'fw-headline' );
                    foxiz_single_entry_category();
                    foxiz_single_header_meta();
                    ?>
                </div>
            </div>
        </div>
    </header>

    <div class="rb-s-container edge-padding">
        <div class="grid-container">
            <div class="s-ct">
                <!-- Основной контент -->
                <div class="place-content">

                    <!-- Описание места -->
                    <?php 
                    $place_description = get_field('place_description');
                    if ($place_description) : ?>
                        <div class="place-description">
                            <?php echo wpautop($place_description); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Телефон -->
                    <?php 
                    $phone = get_field('phone');
                    if ($phone) : ?>
                        <div class="place-phone">
                            <strong>Телефон:</strong> 
                            <a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a>
                        </div>
                    <?php endif; ?>

                    <!-- Адреса -->
                    <?php 
                    $addresses = get_field('multiple_addresse');
                    if ($addresses) : ?>
                        <div class="place-addresses">
                            <strong>Адреса:</strong>
                            <ul>
                                <?php foreach ($addresses as $address) : ?>
                                    <li><?php echo esc_html($address['city'] . ', ' . $address['address']); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Режим работы -->
                    <?php 
                    $schedule = get_field('Schedule');
                    if ($schedule) : ?>
                        <div class="place-schedule">
                            <strong>Режим работы:</strong>
                            <ul>
                                <?php foreach ($schedule as $day) : 
                                    $day_label = $day['day'];
                                    $start_time = $day['start_time'];
                                    $end_time = $day['end_time'];
                                ?>
                                    <li><?php echo esc_html($day_label); ?>: <?php echo esc_html($start_time); ?> - <?php echo esc_html($end_time); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Ближайшие станции метро -->
                    <?php 
                    $nearest_metro_stations = get_field('nearest_metro_stations');
                    if ($nearest_metro_stations) : ?>
                        <div class="nearest-metro">
                            <strong>Ближайшие станции метро:</strong>
                            <ul>
                                <?php foreach ($nearest_metro_stations as $station) : ?>
                                    <li><?php echo esc_html($station->name); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Инфоблок (Flexible Content) -->
                    <?php if (have_rows('place_info_block')) : ?>
                        <div class="place-info-block">
                            <?php while (have_rows('place_info_block')) : the_row(); ?>

                                <?php if (get_row_layout() == 'ticket_prices_layout') : ?>
                                    <div class="ticket-prices">
                                        <strong>Стоимость билетов:</strong>
                                        <ul>
                                            <?php while (have_rows('ticket_prices')) : the_row(); ?>
                                                <li><?php echo esc_html(get_sub_field('ticket_type')); ?>: <?php echo esc_html(get_sub_field('ticket_price')); ?> ₽</li>
                                            <?php endwhile; ?>
                                        </ul>
                                    </div>

                                <?php elseif (get_row_layout() == 'average_check_layout') : ?>
                                    <div class="average-check">
                                        <strong>Средний чек:</strong> <?php echo esc_html(get_sub_field('average_check')); ?> ₽
                                    </div>

                                <?php elseif (get_row_layout() == 'participants_layout') : ?>
                                    <div class="participants">
                                        <strong>Количество участников:</strong> <?php echo esc_html(get_sub_field('max_participants')); ?>
                                    </div>

                                <?php elseif (get_row_layout() == 'age_restrictions_layout') : ?>
                                    <div class="age-restrictions">
                                        <strong>Возрастные ограничения:</strong> <?php echo esc_html(get_sub_field('min_age')); ?>+
                                    </div>

                                <?php endif; ?>

                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php
                // Вызовы стандартных функций Foxiz для контента и комментариев
                //foxiz_single_content();
                foxiz_single_author_box();
                foxiz_single_next_prev();
                foxiz_single_comment();
                ?>
            </div>

            <?php //foxiz_single_sidebar( $sidebar_name ); ?>
        </div>
    </div>

    <?php foxiz_single_close_tag(); ?>

    <div class="single-footer rb-s-container edge-padding">
        <?php foxiz_single_footer(); ?>
    </div>
</div>

<?php 
// Подключаем основной футер темы Foxiz
get_footer(); 
?>
