<?php
// <Internal Doc Start>
/*
*
* @description: 
* @tags: 
* @group: 
* @name: map-popup
* @type: php_content
* @status: published
* @created_by: 
* @created_at: 
* @updated_at: 2024-10-21 01:46:44
* @is_valid: 
* @updated_by: 
* @priority: 10
* @run_at: wp_body_open
* @load_as_file: 
* @condition: {"status":"no","run_if":"assertive","items":[[]]}
*/
?>
<?php if (!defined("ABSPATH")) { return;} // <Internal Doc End> ?>
<div id="map-popup" style="display: none;" class="popup-overlay">
    <div class="popup-content">
        <h3>Выберите навигатор:</h3>
        <button id="yandex-button">Яндекс.Навигатор</button>
        <button id="google-button">Google Maps</button>
        <button id="apple-button">Apple Maps</button>
        <button id="close-popup">Отмена</button>
    </div>
</div>