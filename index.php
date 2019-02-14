<?php

    require_once( 'authenticity.php' );
    $authenticity = new App\Authenticity();

    echo 'Есть признак недостоверности:<pre>';
    print_r( $authenticity->get('7704313718') );
    echo '</pre>';

    echo 'Нет признака о недостоверности:<pre>';
    print_r( $authenticity->get('7804480543') );
    echo '</pre>';

?>