<?php
// If comments are open or there are comments, load the comment template.
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php
    if (have_comments()) :
        ?>
        <h2 class="comments-title">
            <?php
            printf(
                _nx('One Comment', '%1$s Comments', get_comments_number(), 'comments title', 'your-text-domain'),
                number_format_i18n(get_comments_number())
            );
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments(array('style' => 'ol'));
            ?>
        </ol>

        <?php
        // Pagination for comments, if needed
        the_comments_navigation();
    endif;

    // Load the comment form
    comment_form();
    ?>

</div>