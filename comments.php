<?php
/**
 * 评论模板
 *
 * @package Nova
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php
    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $aria_req = ($req ? " aria-required='true'" : '');

    $fields = array();

    // 启用cookies复选框
    if (has_action('set_comment_cookies', 'wp_set_comment_cookies') && get_option('show_comments_cookies_opt_in')) {
        $consent = empty($commenter['comment_author_email']) ? '' : ' checked="checked"';
        $fields['cookies'] = '<div class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />' .
                             '<label for="wp-comment-cookies-consent">' . esc_html__('在浏览器中保存我的昵称和电子邮件，以便下次发表评论', 'nova') . '</label></div>';
        if (isset($args['fields']) && !isset($args['fields']['cookies'])) {
            $args['fields']['cookies'] = $fields['cookies'];
        }
    }

    // 添加表单字段（昵称、邮箱、网址）
    $fields['author'] = '<div class="comment-form-author"><input id="author" placeholder="' . esc_attr__('昵称', 'nova') . ($req ? '*' : '') . '" name="author" type="text" value="' . esc_attr($commenter['comment_author']) .
        '" size="30"' . $aria_req . ' /></div>';

    $fields['email'] = '<div class="comment-form-email"><input id="email" placeholder="' . esc_attr__('Email', 'nova') . ($req ? '*' : '') . '" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) .
        '" size="30"' . $aria_req . ' /></div>';

    $fields['url'] = '<div class="comment-form-url"><input id="url" placeholder="' . esc_attr__('网址（可选）', 'nova') . '" name="url" type="url" value="" size="30" /></div>';

    $user_avatar = get_avatar("", 45);

    if (is_user_logged_in()) :
        $current_user = wp_get_current_user();
        if (($current_user instanceof WP_User)) {
            $user_avatar = get_avatar($current_user->user_email, 45);
        }
    endif;

    $args = array(
        'id_form'           => 'commentform',
        'class_form'        => 'comment-form nova-style',
        'id_submit'         => 'submit',
        'class_submit'      => 'submit',
        'name_submit'       => 'submit',
        'cancel_reply_link' => esc_html__('取消回复', 'nova'),
        'label_submit'      => esc_html__('发表评论', 'nova'),
        'format'            => 'xhtml',

        'comment_field'     => '<div class="comment_textarea_wrapper">' . $user_avatar . '<textarea placeholder="' . esc_attr__('嗨，如果想说点什么？请写下你的评论...', 'nova') . '" id="comment" name="comment" cols="45" rows="8" aria-required="true">' .
            '</textarea></div>',

        'comment_notes_before' => '<div class="comment-notes">' . esc_html__('电子邮件地址不会被公开，必填项已用*标注', 'nova') . '</div>',

        'fields' => apply_filters('comment_form_default_fields', $fields),
    );

    comment_form($args);
    ?>

    <?php
    if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            if ($comment_count === 1) {
                echo esc_html__('1 条评论', 'nova');
            } else {
                printf(
                    esc_html(_nx('%s 条评论', '%s 条评论', $comment_count, '评论标题', 'nova')),
                    number_format_i18n($comment_count)
                );
            }
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'avatar_size' => 45,
                'style'       => 'ol',
                'short_ping'  => true,
                'reply_text'  => '<span class="comments_reply_icon"><svg width="15" height="15" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"><path d="M427.15 343.07V146.35L79.43 446.86C56 467.15 56 500.09 79.46 520.32l347.69 300.5V624.1c196.72 0 379.39 42.16 534 252.93-70.26-393.45-365.34-534-534-534" fill="#666666"></path></svg></span>' . esc_html__('回复', 'nova'),
            ));
            ?>
        </ol>

        <?php
        the_comments_pagination(array(
            'prev_text' => '<span class="screen-reader-text">' . esc_html__('上一页', 'nova') . '</span>',
            'next_text' => '<span class="screen-reader-text">' . esc_html__('下一页', 'nova') . '</span>',
        ));

    endif; // Check for have_comments().

    // 如果评论已关闭但有评论
    if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>

        <p class="no-comments"><?php esc_html_e('评论已关闭', 'nova'); ?></p>
    <?php
    endif;
    ?>

</div><!-- #comments -->
