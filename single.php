<?php
/**
 * 单篇文章模板
 *
 * @package Nova
 */

get_header();
?>

<main id="main" class="site-content">
    <div class="container">
        <div class="content-area">
                <?php while (have_posts()) : the_post(); ?>
                    <?php nova_breadcrumbs(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> data-post-id="<?php the_ID(); ?>">
                        <header class="entry-header">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-thumbnail">
                                    <?php the_post_thumbnail('large', array('alt' => get_the_title())); ?>
                                </div>
                            <?php endif; ?>
                            
                            <h1 class="entry-title"><?php the_title(); ?></h1>
                            
                            <div class="entry-meta">
                                <span class="posted-on">
                                    <span class="icon" aria-hidden="true">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                    </span>
                                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                        <?php echo esc_html(get_the_date()); ?>
                                    </time>
                                </span>
                                <span class="byline">
                                    <span class="icon" aria-hidden="true">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                    </span>
                                    <span class="author vcard">
                                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                            <?php echo esc_html(get_the_author()); ?>
                                        </a>
                                    </span>
                                </span>
                                <span class="reading-time">
                                    <span class="icon" aria-hidden="true">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                    </span>
                                    <?php echo esc_html(nova_get_reading_time()); ?> 分钟阅读
                                </span>
                                <span class="views-count">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <?php echo nova_get_post_views(); ?> <?php esc_html_e('阅读', 'nova'); ?>
                                </span>
                                <span class="comments-count">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                    <?php comments_number('0', '1', '%'); ?> <?php esc_html_e('评论', 'nova'); ?>
                                </span>
                            </div>
                        </header>

                        <div class="entry-content">
                            <?php
                            the_content();
                            
                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . esc_html__('页:', 'nova'),
                                'after'  => '</div>',
                            ));
                            ?>
                        </div>

                        <footer class="entry-footer">
                            <?php
                            $categories = get_the_category();
                            if ($categories) {
                                echo '<div class="tag-list">';
                                echo '<span class="category-label">' . esc_html__('分类:', 'nova') . '</span>';
                                foreach ($categories as $category) {
                                    echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
                                }
                                echo '</div>';
                            }
                            
                            $tags = get_the_tags();
                            if ($tags) {
                                echo '<div class="tag-list">';
                                echo '<span class="tag-label">' . esc_html__('标签:', 'nova') . '</span>';
                                the_tags('', '', '');
                                echo '</div>';
                            }
                            ?>
                            
                            <!-- 点赞按钮 -->
                            <div class="entry-like-wrapper">
                                <button class="entry-like-button" data-post-id="<?php the_ID(); ?>" aria-label="<?php esc_attr_e('点赞', 'nova'); ?>">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                    </svg>
                                    <span class="like-text"><?php esc_html_e('点赞', 'nova'); ?></span>
                                    <span class="like-count"><?php echo esc_html(nova_get_post_likes()); ?></span>
                                </button>
                            </div>
                        </footer>
                        
                        <?php nova_author_box(); ?>
                        
                        <?php
                        // 上一篇/下一篇文章导航
                        the_post_navigation(array(
                            'prev_text' => '<span class="nav-subtitle">' . esc_html__('上一篇:', 'nova') . '</span> <span class="nav-title">%title</span>',
                            'next_text' => '<span class="nav-subtitle">' . esc_html__('下一篇:', 'nova') . '</span> <span class="nav-title">%title</span>',
                        ));
                        ?>
                        
                        <?php
                        // 相关文章推荐
                        nova_related_posts();
                        ?>
                    </article>
                    
                    <?php
                    ?>
                    
                    <?php
                    // 如果评论已开启或当前评论数大于0，则加载评论模板
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>
                    
                <?php endwhile; ?>
        </div>
    </div>
</main>

<?php
get_footer();
