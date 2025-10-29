<?php
/**
 * 简单的Markdown解析器
 * 专门用于评论内容
 */
class Nova_Markdown {
    
    /**
     * 解析Markdown内容
     */
    public static function parse($text) {
        if (empty($text)) {
            return $text;
        }
        
        // 转义HTML
        $text = esc_html($text);
        
        // 标题
        $text = preg_replace('/^### (.*)$/m', '<h3>$1</h3>', $text);
        $text = preg_replace('/^## (.*)$/m', '<h2>$1</h2>', $text);
        $text = preg_replace('/^# (.*)$/m', '<h1>$1</h1>', $text);
        
        // 粗体和斜体
        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
        $text = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $text);
        
        // 行内代码
        $text = preg_replace('/`([^`]+)`/', '<code>$1</code>', $text);
        
        // 链接
        $text = preg_replace('/\[([^\]]+)\]\(([^\)]+)\)/', '<a href="$2" rel="nofollow">$1</a>', $text);
        
        // 换行（双空格换行）
        $text = preg_replace('/  \n/', '<br>', $text);
        
        // 段落（双换行）
        $text = preg_replace('/\n\n/', '</p><p>', $text);
        $text = '<p>' . $text . '</p>';
        
        return $text;
    }
    
    /**
     * 解析代码块
     */
    public static function parse_code_blocks($text) {
        // 代码块
        $text = preg_replace_callback('/```(\w+)?\n(.*?)```/s', function($matches) {
            $language = !empty($matches[1]) ? $matches[1] : '';
            $code = esc_html($matches[2]);
            return '<pre><code class="language-' . esc_attr($language) . '">' . $code . '</code></pre>';
        }, $text);
        
        return $text;
    }
    
    /**
     * 解析列表
     */
    public static function parse_lists($text) {
        // 无序列表
        $text = preg_replace('/^- (.*)$/m', '<li>$1</li>', $text);
        $text = preg_replace('/(<li>.*<\/li>)/s', '<ul>$1</ul>', $text);
        
        // 有序列表
        $text = preg_replace('/^\d+\. (.*)$/m', '<li>$1</li>', $text);
        
        return $text;
    }
}

