<?php
function get_post_id_by_name( $post_name, $post_type = 'post' )
{
    $post_ids = get_posts(array
    (
        'post_name'   => $post_name,
        'post_type'   => $post_type,
        'numberposts' => 1,
        'fields' => 'ids'
    ));

    return array_shift( $post_ids );
}
?>