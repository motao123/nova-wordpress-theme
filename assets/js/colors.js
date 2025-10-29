/**
 * Nova 颜色管理系统
 * 支持实时调整、预设、预览、导出/导入功能
 */

(function() {
    'use strict';

    const NovaColors = {
        // 默认颜色预设
        presets: {
            default: {
                primary: '#5bc0eb',
                secondary: '#333333',
                text: '#6f6f6f',
                background: '#ffffff'
            },
            dark: {
                primary: '#5bc0eb',
                secondary: '#222222',
                text: '#cccccc',
                background: '#1a1a1a'
            },
            blue: {
                primary: '#4a90e2',
                secondary: '#2c3e50',
                text: '#34495e',
                background: '#ecf0f1'
            },
            green: {
                primary: '#27ae60',
                secondary: '#2c3e50',
                text: '#34495e',
                background: '#ffffff'
            },
            orange: {
                primary: '#e67e22',
                secondary: '#2c3e50',
                text: '#34495e',
                background: '#ffffff'
            }
        },

        // 当前颜色方案
        currentColors: {},

        /**
         * 初始化颜色系统
         */
        init: function() {
            this.loadColors();
            this.bindEvents();
            this.updatePreview();
        },

        /**
         * 加载保存的颜色
         */
        loadColors: function() {
            const saved = localStorage.getItem('nova_colors');
            if (saved) {
                try {
                    this.currentColors = JSON.parse(saved);
                } catch (e) {
                    this.currentColors = { ...this.presets.default };
                }
            } else {
                this.currentColors = { ...this.presets.default };
            }
            
            this.applyColors();
        },

        /**
         * 应用颜色到页面
         */
        applyColors: function() {
            const root = document.documentElement;
            
            Object.keys(this.currentColors).forEach(key => {
                const value = this.currentColors[key];
                root.style.setProperty(`--nova-${key}-color`, value);
            });
        },

        /**
         * 保存颜色到本地存储
         */
        saveColors: function() {
            localStorage.setItem('nova_colors', JSON.stringify(this.currentColors));
        },

        /**
         * 更新预览
         */
        updatePreview: function() {
            // 这里可以添加实时预览功能
            const preview = document.getElementById('nova-color-preview');
            if (preview) {
                preview.style.setProperty('--preview-primary', this.currentColors.primary);
                preview.style.setProperty('--preview-secondary', this.currentColors.secondary);
            }
        },

        /**
         * 绑定事件
         */
        bindEvents: function() {
            // 颜色选择器变化
            document.addEventListener('change', (e) => {
                if (e.target.classList.contains('nova-color-picker')) {
                    const colorKey = e.target.dataset.color;
                    const colorValue = e.target.value;
                    
                    this.currentColors[colorKey] = colorValue;
                    this.applyColors();
                    this.saveColors();
                    this.updatePreview();
                }
            });

            // 预设选择
            document.addEventListener('click', (e) => {
                if (e.target.classList.contains('nova-preset')) {
                    const presetName = e.target.dataset.preset;
                    this.loadPreset(presetName);
                }
            });

            // 导出颜色
            document.addEventListener('click', (e) => {
                if (e.target.classList.contains('nova-export')) {
                    this.exportColors();
                }
            });

            // 导入颜色
            document.addEventListener('change', (e) => {
                if (e.target.classList.contains('nova-import')) {
                    this.importColors(e.target.files[0]);
                }
            });
        },

        /**
         * 加载预设
         */
        loadPreset: function(presetName) {
            if (this.presets[presetName]) {
                this.currentColors = { ...this.presets[presetName] };
                this.applyColors();
                this.saveColors();
                this.updatePreview();
                
                // 更新颜色选择器
                Object.keys(this.currentColors).forEach(key => {
                    const picker = document.querySelector(`[data-color="${key}"]`);
                    if (picker) {
                        picker.value = this.currentColors[key];
                    }
                });
            }
        },

        /**
         * 导出颜色配置
         */
        exportColors: function() {
            const dataStr = JSON.stringify(this.currentColors, null, 2);
            const dataBlob = new Blob([dataStr], { type: 'application/json' });
            const url = URL.createObjectURL(dataBlob);
            
            const link = document.createElement('a');
            link.href = url;
            link.download = 'nova-colors.json';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            URL.revokeObjectURL(url);
        },

        /**
         * 导入颜色配置
         */
        importColors: function(file) {
            if (!file) return;
            
            const reader = new FileReader();
            reader.onload = (e) => {
                try {
                    const imported = JSON.parse(e.target.result);
                    this.currentColors = { ...imported };
                    this.applyColors();
                    this.saveColors();
                    this.updatePreview();
                    
                    // 更新颜色选择器
                    Object.keys(this.currentColors).forEach(key => {
                        const picker = document.querySelector(`[data-color="${key}"]`);
                        if (picker) {
                            picker.value = this.currentColors[key];
                        }
                    });
                    
                    alert('颜色配置导入成功！');
                } catch (err) {
                    alert('导入失败：无效的文件格式');
                }
            };
            reader.readAsText(file);
        },

        /**
         * 重置为默认颜色
         */
        reset: function() {
            this.currentColors = { ...this.presets.default };
            this.applyColors();
            this.saveColors();
            this.updatePreview();
        }
    };

    // 页面加载完成后初始化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => NovaColors.init());
    } else {
        NovaColors.init();
    }

    // 暴露到全局
    window.NovaColors = NovaColors;

})();

