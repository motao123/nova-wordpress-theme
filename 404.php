<?php
/**
 * 404错误页面模板
 *
 * @package Nova
 */

get_header();
?>

<main id="main" class="site-content">
    <div class="container">
        <div class="error-404 not-found">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e('页面未找到', 'nova'); ?></h1>
            </header>

            <div class="page-content">
                <p><?php esc_html_e('抱歉，您访问的页面不存在。可能是网址输入错误，或者页面已被删除。', 'nova'); ?></p>
                
                <?php get_search_form(); ?>
                
                <div class="error-helpful-links">
                    <h2><?php esc_html_e('可能对您有帮助', 'nova'); ?></h2>
                    <ul>
                        <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('返回首页', 'nova'); ?></a></li>
                        <?php 
                        $posts_page = get_option('page_for_posts');
                        if ($posts_page) {
                            echo '<li><a href="' . esc_url(get_permalink($posts_page)) . '">' . esc_html__('查看最新文章', 'nova') . '</a></li>';
                        }
                        ?>
                    </ul>
                </div>
                
                <?php
                // 显示最近文章
                $recent_posts = get_posts(array(
                    'numberposts' => 5,
                    'post_status' => 'publish'
                ));
                
                if ($recent_posts) : ?>
                    <div class="recent-posts-section">
                        <h2><?php esc_html_e('最近文章', 'nova'); ?></h2>
                        <ul class="recent-posts-list">
                            <?php foreach ($recent_posts as $post) : setup_postdata($post); ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    <span class="post-date"><?php echo esc_html(get_the_date('Y-m-d')); ?></span>
                                </li>
                            <?php endforeach; wp_reset_postdata(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
