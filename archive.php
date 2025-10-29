<?php
/**
 * 归档页面模板
 *
 * @package Nova
 */

get_header();

// 获取首页样式设置（在所有列表页面都应用）
$home_style = get_theme_mod('nova_home_card_style', 'default');
?>

<main id="main" class="site-content <?php echo esc_attr($home_style); ?>-style" role="main">
    <div class="container">
        <div class="content-area">
                <?php nova_breadcrumbs(); ?>
                <header class="page-header">
                    <?php
                    the_archive_title('<h1 class="page-title">', '</h1>');
                    the_archive_description('<div class="archive-description">', '</div>');
                    ?>
                </header>

                <?php if (have_posts()) : ?>
                    <?php if ($home_style === 'nova') : ?>
                        <!-- 网格布局 -->
                        <?php
                        // 获取默认图片
                        $default_image = get_theme_mod('nova_home_default_image', '');
                        if (empty($default_image)) {
                            $default_image = get_template_directory_uri() . '/assets/images/default.jpg';
                        }
                        ?>
                        <div class="nova-card-list">
                            <?php while (have_posts()) : the_post(); ?>
                                <article id="post-<?php the_ID(); ?>" <?php post_class('nova-card-item'); ?> itemscope itemtype="http://schema.org/BlogPosting">
                                    <?php
                                    // 获取文章图片
                                    $post_image = '';
                                    if (has_post_thumbnail()) {
                                        $post_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
                                    } else {
                                        $post_image = $default_image;
                                    }
                                    ?>
                                    <div class="nova-card-image">
                                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" aria-label="<?php the_title(); ?>" style="background-image: url(<?php echo esc_url($post_image); ?>);">
                                        </a>
                                    </div>
                                    
                                    <div class="nova-card-content">
                                        <div class="nova-card-meta">
                                            <h2 class="nova-card-title" itemprop="headline">
                                                <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
                                            </h2>
                                        </div>
                                        
                                        <div class="nova-card-info">
                                            <span class="nova-card-date">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                                </svg>
                                                <?php echo esc_html(get_the_date('Y-m-d')); ?>
                                            </span>
                                            <span class="nova-card-views">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                <?php echo nova_get_post_views(); ?>
                                            </span>
                                            <span class="nova-card-comments">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                                </svg>
                                                <?php comments_number('0', '1', '%'); ?>
                                            </span>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    <?php else : ?>
                        <!-- 默认横向卡片布局 -->
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
                                            <span class="entry-author">
                                                <?php echo esc_html(get_the_author()); ?>
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
                    <?php endif; ?>
                    
                    <?php nova_pagination(); ?>
                    
                <?php else : ?>
                    <div class="no-results">
                        <h2><?php esc_html_e('未找到内容', 'nova'); ?></h2>
                        <p><?php esc_html_e('抱歉，此归档下暂无内容。', 'nova'); ?></p>
                    </div>
                <?php endif; ?>
        </div>
    </div>
</main>

<?php
get_footer();
