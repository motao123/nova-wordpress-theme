<?php
/**
 * 作者页面模板
 *
 * @package Nova
 */

get_header();
?>

<main id="main" class="site-content">
    <div class="container">
        <div class="content-area">
                <?php nova_breadcrumbs(); ?>
                
                <header class="author-header">
                    <?php echo get_avatar(get_the_author_meta('user_email'), 96); ?>
                    <h1 class="author-name"><?php echo esc_html(get_the_author()); ?></h1>
                    <?php if (get_the_author_meta('description')) : ?>
                        <p class="author-bio"><?php echo esc_html(get_the_author_meta('description')); ?></p>
                    <?php endif; ?>
                    
                    <div class="author-stats">
                        <span class="stat-item">
                            <strong><?php echo count_user_posts(get_the_author_meta('ID')); ?></strong>
                            <span><?php esc_html_e('篇文章', 'nova'); ?></span>
                        </span>
                        <span class="stat-item">
                            <strong><?php echo get_comments_number(); ?></strong>
                            <span><?php esc_html_e('条评论', 'nova'); ?></span>
                        </span>
                    </div>
                </header>

                <?php if (have_posts()) : ?>
                    <div class="author-posts">
                        <h2 class="section-title"><?php esc_html_e('作者文章', 'nova'); ?></h2>
                        
                        <?php while (have_posts()) : the_post(); ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                                <div class="post-card-inner flex">
                                    <!-- 文章内容 -->
                                    <div class="article-content">
                                        <h2 class="entry-title">
                                            <a href="<?php the_permalink(); ?>" rel="bookmark" class="hoverColor"><?php the_title(); ?></a>
                                        </h2>
                                        
                                        <div class="entry-content">
                                            <?php the_excerpt(); ?>
                                        </div>
                                        
                                        <div class="entry-info">
                                            <span class="entry-category">
                                                <?php the_category(' '); ?>
                                            </span>
                                            <span class="entry-date">
                                                <?php echo esc_html(get_the_date('Y-m-d')); ?>
                                            </span>
                                            <span class="entry-views">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                <?php echo nova_get_post_views(); ?>
                                            </span>
                                            <span class="entry-comments">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                                </svg>
                                                <?php comments_number('0', '1', '%'); ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- 缩略图 -->
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="entry-thumb">
                                            <a href="<?php the_permalink(); ?>" class="thumb-link">
                                                <?php the_post_thumbnail('medium', array('alt' => get_the_title())); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </article>
                        <?php endwhile; ?>
                        
                        <?php nova_pagination(); ?>
                    </div>
                <?php else : ?>
                    <div class="no-results">
                        <h2><?php esc_html_e('暂无文章', 'nova'); ?></h2>
                        <p><?php esc_html_e('该作者还没有发布任何文章。', 'nova'); ?></p>
                    </div>
                <?php endif; ?>
        </div>
    </div>
</main>

<?php
get_footer();

