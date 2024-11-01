public function generate_uid( $text ) {

    $output = trim( strip_tags( $text ) );
    $output = preg_replace( "/\p{P}/u", "", $output );
    $output = str_replace( "&nbsp;", " ", $output );
    $output = remove_accents( $output );

    // Используем вашу функцию cyrillic_to_latin
    $output = cyrillic_to_latin( $output );

    $output = sanitize_title_with_dashes( $output );

    return $output;
}