<?php
// <Internal Doc Start>
/*
*
* @description: 
* @tags: 
* @group: 
* @name: validate_phone_number
* @type: PHP
* @status: published
* @created_by: 
* @created_at: 
* @updated_at: 2024-10-27 17:08:01
* @is_valid: 
* @updated_by: 
* @priority: 10
* @run_at: backend
* @load_as_file: 
* @condition: {"status":"no","run_if":"assertive","items":[[]]}
*/
?>
<?php if (!defined("ABSPATH")) { return;} // <Internal Doc End> ?>
<?php
add_filter('acf/validate_value/name=phone', 'validate_phone_number_custom', 10, 4);

function validate_phone_number_custom($valid, $value, $field, $input) {
    // Пропускаем валидацию, если поле пустое
    if (empty($value)) {
        return $valid;
    }

    // Регулярное выражение для двух форматов номера телефона
    $pattern = '/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$|^\d{3}-\d{2}-\d{2}$/';

    // Проверка значения по регулярному выражению
    if (!preg_match($pattern, $value)) {
        // Возвращаем сообщение об ошибке, если формат неверный
        $valid = 'Введите номер в формате +7(XXX)XXX-XX-XX или XXX-XX-XX.';
    }

    return $valid;
}
