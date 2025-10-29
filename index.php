<?php
/**
 * 主模板文件
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
            <?php if (have_posts()) : ?>
                <?php if ($home_style === 'nova') : ?>
                    <!-- Nova风格网格布局 -->
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
                                            <?php nova_sticky_badge(); ?>
                                            <a href="<?php the_permalink(); ?>" aria-label="<?php the_title(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                    </div>
                                    
                                    <div class="nova-card-info">
                                        <time itemprop="datePublished" datetime="<?php echo get_the_date('c'); ?>">
                                            <?php echo human_time_diff(get_the_time('U'), current_time('U')) . ' ' . esc_html__('前', 'nova'); ?>
                                        </time>
                                        <span class="middot-divider"></span>
                                        <?php the_category(''); ?>
                                        <span class="middot-divider"></span>
                                        <span class="views-count">
                                            <?php echo nova_get_post_views(); ?> <?php esc_html_e('阅读', 'nova'); ?>
                                        </span>
                                        <span class="middot-divider"></span>
                                        <span class="comments-count">
                                            <?php comments_number('0', '1', '%'); ?> <?php esc_html_e('评论', 'nova'); ?>
                                        </span>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                <?php else : ?>
                    <!-- 默认样式 -->
                    <?php while (have_posts()) : the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?> itemscope itemtype="http://schema.org/BlogPosting">
                            <div class="post-card-inner flex">
                                <!-- 文章内容 -->
                                <div class="article-content">
                                    <h2 class="entry-title">
                                        <?php nova_sticky_badge(); ?>
                                        <a href="<?php the_permalink(); ?>" rel="bookmark" itemprop="url" class="hoverColor"><?php the_title(); ?></a>
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
                                            <?php the_post_thumbnail('medium', array(
                                                'alt' => get_the_title(),
                                                'loading' => 'lazy',
                                                'itemprop' => 'image'
                                            )); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endwhile; ?>
                <?php endif; ?>
                
                <?php if (!is_singular()) : ?>
                    <?php nova_pagination(); ?>
                <?php endif; ?>
                
            <?php else : ?>
                <div class="no-results">
                    <h2><?php esc_html_e('未找到内容', 'nova'); ?></h2>
                    <p><?php esc_html_e('抱歉，没有找到您要查找的内容。请尝试使用搜索功能。', 'nova'); ?></p>
                    <?php get_search_form(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php
get_footer();