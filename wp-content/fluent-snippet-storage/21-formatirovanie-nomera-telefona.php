<?php
// <Internal Doc Start>
/*
*
* @description: 
* @tags: 
* @group: 
* @name: форматирование номера телефона
* @type: js
* @status: published
* @created_by: 
* @created_at: 
* @updated_at: 2024-10-27 17:08:24
* @is_valid: 
* @updated_by: 
* @priority: 10
* @run_at: admin_footer
* @load_as_file: 
* @condition: {"status":"no","run_if":"assertive","items":[[]]}
*/
?>
<?php if (!defined("ABSPATH")) { return;} // <Internal Doc End> ?>
jQuery(document).ready(function($) {
    // Ищем все поля, в имени которых есть "phone"
    $('input[name*="phone"]').on('input', function() {
        var input = $(this);
        var value = input.val();

        // Убираем все символы, кроме цифр
        var numbers = value.replace(/\D/g, '');

        // Форматируем номер
        var formattedNumber = '';

        if (numbers.length > 0) {
            if (numbers[0] === '7' || numbers[0] === '8') {
                formattedNumber += '+7';
                numbers = numbers.substring(1);
            }
        }

        if (numbers.length > 0) {
            formattedNumber += '(' + numbers.substring(0, 3) + ')';
        }

        if (numbers.length >= 4) {
            formattedNumber += numbers.substring(3, 6) + '-';
        }

        if (numbers.length >= 7) {
            formattedNumber += numbers.substring(6, 8) + '-';
        }

        if (numbers.length >= 9) {
            formattedNumber += numbers.substring(8, 10);
        }

        // Устанавливаем отформатированный номер обратно в поле
        input.val(formattedNumber);
    });

    // Валидация при потере фокуса
    $('input[name*="phone"]').on('blur', function() {
        var value = $(this).val();

        // Регулярное выражение для проверки формата
        var pattern = /^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$|^\d{3}-\d{2}-\d{2}$/;

        // Если формат неверный, показываем предупреждение
        if (!pattern.test(value) && value !== '') {
            alert('Введите номер в формате +7(XXX)XXX-XX-XX или XXX-XX-XX.');
            $(this).focus();
        }
    });
});
