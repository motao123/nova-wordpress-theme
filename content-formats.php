<?php
/**
 * Template for displaying posts with different formats
 * 
 * @package Nova
 */

get_header(); ?>

<main id="main" class="site-content" role="main">
    <div class="container">
        <?php if (have_posts()) : ?>
            <div class="posts-container">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-card format-' . get_post_format()); ?> itemscope itemtype="http://schema.org/BlogPosting">
                        <div class="post-content">
                            <?php
                            // 根据文章格式显示不同内容
                            $format = get_post_format();
                            
                            switch ($format) {
                                case 'aside':
                                    // 旁白格式 - 简洁显示
                                    ?>
                                    <div class="post-meta">
                                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                                            <?php echo esc_html(get_the_date()); ?>
                                        </time>
                                    </div>
                                    <div class="post-excerpt">
                                        <?php the_content(); ?>
                                    </div>
                                    <?php
                                    break;
                                    
                                case 'image':
                                    // 图片格式 - 突出显示图片
                                    if (has_post_thumbnail()) {
                                        ?>
                                        <div class="post-thumbnail">
                                            <a href="<?php the_permalink(); ?>" aria-label="<?php the_title(); ?>">
                                                <?php the_post_thumbnail('large'); ?>
                                            </a>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <div class="post-header">
                                        <h2 class="post-title">
                                            <a href="<?php the_permalink(); ?>" aria-label="<?php the_title(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        <div class="post-meta">
                                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                                                <?php echo esc_html(get_the_date()); ?>
                                            </time>
                                            <span class="post-author">
                                                <?php esc_html_e('by', 'nova'); ?> 
                                                <span itemprop="author"><?php the_author(); ?></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="post-excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    <?php
                                    break;
                                    
                                case 'video':
                                    // 视频格式
                                    ?>
                                    <div class="post-header">
                                        <h2 class="post-title">
                                            <a href="<?php the_permalink(); ?>" aria-label="<?php the_title(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        <div class="post-meta">
                                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                                                <?php echo esc_html(get_the_date()); ?>
                                            </time>
                                            <span class="post-author">
                                                <?php esc_html_e('by', 'nova'); ?> 
                                                <span itemprop="author"><?php the_author(); ?></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="post-content">
                                        <?php the_content(); ?>
                                    </div>
                                    <?php
                                    break;
                                    
                                case 'quote':
                                    // 引用格式
                                    ?>
                                    <div class="post-quote">
                                        <blockquote>
                                            <?php the_content(); ?>
                                        </blockquote>
                                        <div class="quote-meta">
                                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                                                <?php echo esc_html(get_the_date()); ?>
                                            </time>
                                        </div>
                                    </div>
                                    <?php
                                    break;
                                    
                                case 'link':
                                    // 链接格式
                                    ?>
                                    <div class="post-link">
                                        <h2 class="post-title">
                                            <a href="<?php the_permalink(); ?>" aria-label="<?php the_title(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        <div class="post-meta">
                                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                                                <?php echo esc_html(get_the_date()); ?>
                                            </time>
                                        </div>
                                    </div>
                                    <?php
                                    break;
                                    
                                case 'gallery':
                                    // 画廊格式
                                    ?>
                                    <div class="post-header">
                                        <h2 class="post-title">
                                            <a href="<?php the_permalink(); ?>" aria-label="<?php the_title(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        <div class="post-meta">
                                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                                                <?php echo esc_html(get_the_date()); ?>
                                            </time>
                                            <span class="post-author">
                                                <?php esc_html_e('by', 'nova'); ?> 
                                                <span itemprop="author"><?php the_author(); ?></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="post-gallery">
                                        <?php the_content(); ?>
                                    </div>
                                    <?php
                                    break;
                                    
                                case 'audio':
                                    // 音频格式
                                    ?>
                                    <div class="post-header">
                                        <h2 class="post-title">
                                            <a href="<?php the_permalink(); ?>" aria-label="<?php the_title(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        <div class="post-meta">
                                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                                                <?php echo esc_html(get_the_date()); ?>
                                            </time>
                                            <span class="post-author">
                                                <?php esc_html_e('by', 'nova'); ?> 
                                                <span itemprop="author"><?php the_author(); ?></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="post-audio">
                                        <?php the_content(); ?>
                                    </div>
                                    <?php
                                    break;
                                    
                                default:
                                    // 标准格式
                                    ?>
                                    <div class="post-header">
                                        <h2 class="post-title">
                                            <a href="<?php the_permalink(); ?>" aria-label="<?php the_title(); ?>"><?php the_title(); ?></a>
                                        </h2>
                                        <div class="post-meta">
                                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                                                <?php echo esc_html(get_the_date()); ?>
                                            </time>
                                            <span class="post-author">
                                                <?php esc_html_e('by', 'nova'); ?> 
                                                <span itemprop="author"><?php the_author(); ?></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="post-excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    <?php
                                    break;
                            }
                            ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <?php
            // 分页导航
            nova_pagination();
            ?>
            
        <?php else : ?>
            <div class="no-results">
                <h2><?php esc_html_e('未找到内容', 'nova'); ?></h2>
                <p><?php esc_html_e('抱歉，没有找到符合条件的内容。', 'nova'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
