<?php
/**
 * 月度归档模板
 *
 * @package Nova
 */

get_header();
?>

<main id="main" class="site-content">
    <div class="container">
        <div class="content-area">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e('文章归档', 'nova'); ?></h1>
                <p class="page-description"><?php esc_html_e('按月份浏览所有文章', 'nova'); ?></p>
            </header>

            <?php
            // 获取所有文章，按月分组
            $archives = wp_get_archives(array(
                'type' => 'monthly',
                'format' => 'custom',
                'before' => '',
                'after' => '',
                'echo' => 0,
            ));
            
            if ($archives) {
                // 解析归档链接
                preg_match_all('/<a href="([^"]+)">([^<]+)<\/a>/', $archives, $matches);
                
                if (!empty($matches[1])) {
                    echo '<div class="archive-list">';
                    
                    foreach ($matches[1] as $index => $url) {
                        $month = $matches[2][$index];
                        
                        // 提取年月
                        preg_match('/(\d{4})\/(\d{2})/', $url, $date_match);
                        
                        if (!empty($date_match)) {
                            $year = $date_match[1];
                            $month_num = $date_match[2];
                            
                            // 获取该月的文章数量
                            $args = array(
                                'post_type' => 'post',
                                'post_status' => 'publish',
                                'date_query' => array(
                                    array(
                                        'year' => $year,
                                        'month' => $month_num,
                                    ),
                                ),
                                'posts_per_page' => -1,
                                'fields' => 'ids',
                            );
                            
                            $query = new WP_Query($args);
                            $count = $query->found_posts;
                            
                            echo '<div class="archive-item">';
                            echo '<a href="' . esc_url($url) . '" class="archive-link">';
                            echo '<span class="archive-month">' . esc_html($month) . '</span>';
                            echo '<span class="archive-count">' . esc_html($count) . ' ' . esc_html__('篇文章', 'nova') . '</span>';
                            echo '</a>';
                            echo '</div>';
                            
                            wp_reset_postdata();
                        }
                    }
                    
                    echo '</div>';
                }
            } else {
                echo '<p>' . esc_html__('暂无文章', 'nova') . '</p>';
            }
            ?>
        </div>
    </div>
</main>

<?php
get_footer();

