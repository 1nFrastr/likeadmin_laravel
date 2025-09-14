<?php

namespace App\Adminapi\Logic;

use App\Common\Logic\BaseLogic;
use App\Common\Service\ConfigService;
use App\Common\Service\FileService;

/**
 * 工作台
 */
class WorkbenchLogic extends BaseLogic
{
    /**
     * @notes 工作套
     * @param $adminInfo
     * @return array
     */
    public static function index()
    {
        return [
            // 版本信息
            'version' => self::versionInfo(),
            // 今日数据
            'today' => self::today(),
            // 常用功能
            'menu' => self::menu(),
            // 近15日访客数
            'visitor' => self::visitor(),
            // 服务支持
            'support' => self::support(),
            // 销售数据
            'sale' => self::sale()
        ];
    }


    /**
     * @notes 常用功能
     * @return array[]
     */
    public static function menu(): array
    {
        return [
            [
                'name' => '管理员',
                'image' => FileService::getFileUrl(config('project.default_image.menu_admin')),
                'url' => '/permission/admin'
            ],
            [
                'name' => '角色管理',
                'image' => FileService::getFileUrl(config('project.default_image.menu_role')),
                'url' => '/permission/role'
            ],
            [
                'name' => '部门管理',
                'image' => FileService::getFileUrl(config('project.default_image.menu_dept')),
                'url' => '/organization/department'
            ],
            [
                'name' => '字典管理',
                'image' => FileService::getFileUrl(config('project.default_image.menu_dict')),
                'url' => '/dev_tools/dict'
            ],
            [
                'name' => '代码生成器',
                'image' => FileService::getFileUrl(config('project.default_image.menu_generator')),
                'url' => '/dev_tools/code'
            ],
            [
                'name' => '素材中心',
                'image' => FileService::getFileUrl(config('project.default_image.menu_file')),
                'url' => '/material/index'
            ],
            [
                'name' => '菜单权限',
                'image' => FileService::getFileUrl(config('project.default_image.menu_auth')),
                'url' => '/permission/menu'
            ],
            [
                'name' => '网站信息',
                'image' => FileService::getFileUrl(config('project.default_image.menu_web')),
                'url' => '/setting/website/information'
            ],
        ];
    }


    /**
     * @notes 版本信息
     * @return array
     */
    public static function versionInfo(): array
    {
        return [
            'version' => config('project.version'),
            'website' => config('project.website.url'),
            'name' => ConfigService::get('website', 'name'),
            'based' => 'vue3.x、ElementUI、MySQL',
            'channel' => [
                'website' => 'https://www.likeadmin.cn',
                'gitee' => 'https://gitee.com/likeadmin/likeadmin_php',
                'blog' => 'https://www.sodair.top/',
                'github' => 'https://github.com/1nFrastr/likeadmin_laravel',
            ]
        ];
    }


    /**
     * @notes 今日数据
     * @return int[]
     * @author 段誉
     * @date 2021/12/29 16:15
     */
    public static function today(): array
    {
        return [
            'time' => date('Y-m-d H:i:s'),
            // 今日销售额
            'today_sales' => 100,
            // 总销售额
            'total_sales' => 1000,

            // 今日访问量
            'today_visitor' => 10,
            // 总访问量
            'total_visitor' => 100,

            // 今日新增用户量
            'today_new_user' => 30,
            // 总用户量
            'total_new_user' => 3000,

            // 订单量 (笔)
            'order_num' => 12,
            // 总订单量
            'order_sum' => 255
        ];
    }


    /**
     * @notes 访问数
     * @return array
     */
    public static function visitor(): array
    {
        $num = [];
        $date = [];
        for ($i = 0; $i < 15; $i++) {
            $where_start = strtotime("- " . $i . "day");
            $date[] = date('m/d', $where_start);
            $num[$i] = rand(0, 100);
        }

        return [
            'date' => $date,
            'list' => [
                ['name' => '访客数', 'data' => $num]
            ]
        ];
    }

    /**
     * @notes 访问数
     * @return array
     */
    public static function sale(): array
    {
        $num = [];
        $date = [];
        for ($i = 0; $i < 7; $i++) {
            $where_start = strtotime("- " . $i . "day");
            $date[] = date('m/d', $where_start);
            $num[$i] = rand(30, 200);
        }

        return [
            'date' => $date,
            'list' => [
                ['name' => '销售量', 'data' => $num]
            ]
        ];
    }


    /**
     * @notes 服务支持
     * @return array[]
     */
    public static function support()
    {
        return [
            [
                'image' => FileService::getFileUrl(config('project.default_image.qq_group')),
                'title' => '官方公众号',
                'desc' => '关注官方公众号',
            ],
            [
                'image' => FileService::getFileUrl(config('project.default_image.customer_service')),
                'title' => '添加企业客服微信',
                'desc' => '想了解更多请添加客服',
            ]
        ];
    }

    /**
     * @notes 未完成的工作列表
     * @return array
     */
    public static function incompleteWork(): array
    {
        return [
            'summary' => [
                'total_tasks' => 8,
                'completed_tasks' => 2,
                'completion_rate' => 25,
                'priority_tasks' => 4
            ],
            'categories' => [
                [
                    'name' => '优先级排期',
                    'tasks' => [
                        [
                            'id' => 1,
                            'title' => '渠道设置 - 开放平台',
                            'description' => '渠道设置：微信小程序配置✔、公众号菜单管理✔、公众号消息回复逻辑✔、h5设置✔、开放平台TODO',
                            'priority' => 'high',
                            'status' => 'todo',
                            'progress' => 80,
                            'estimated_hours' => 16,
                            'dependencies' => ['微信小程序配置', '公众号菜单管理', 'H5设置']
                        ],
                        [
                            'id' => 2,
                            'title' => '第三方登录 - PC端扫码登录',
                            'description' => '第三方登录：微信小程序授权登录✔、H5公众号授权登录✔、PC端扫码登录TODO（需配合开放平台）',
                            'priority' => 'medium',
                            'status' => 'todo',
                            'progress' => 60,
                            'estimated_hours' => 12,
                            'dependencies' => ['开放平台配置']
                        ],
                        [
                            'id' => 3,
                            'title' => '支付功能 - 支付宝支付',
                            'description' => '钱包充值✔、微信支付（小程序支付✔、公众号/H5付款暂未测试）、支付宝支付TODO',
                            'priority' => 'high',
                            'status' => 'todo',
                            'progress' => 70,
                            'estimated_hours' => 20,
                            'dependencies' => []
                        ],
                        [
                            'id' => 4,
                            'title' => '微信支付测试 - 公众号/H5付款',
                            'description' => '完成公众号和H5微信支付的测试验证',
                            'priority' => 'medium',
                            'status' => 'testing',
                            'progress' => 90,
                            'estimated_hours' => 4,
                            'dependencies' => ['小程序支付']
                        ],
                        [
                            'id' => 5,
                            'title' => '模型事件优化',
                            'description' => 'fix 修改全部edit方法，使用模型事件',
                            'priority' => 'low',
                            'status' => 'todo',
                            'progress' => 0,
                            'estimated_hours' => 8,
                            'dependencies' => []
                        ],
                        [
                            'id' => 6,
                            'title' => '代码生成器优化',
                            'description' => 'fix 修改代码生成器，edit方法',
                            'priority' => 'low',
                            'status' => 'todo',
                            'progress' => 0,
                            'estimated_hours' => 6,
                            'dependencies' => []
                        ]
                    ]
                ],
                [
                    'name' => 'API接口迁移',
                    'tasks' => [
                        [
                            'id' => 7,
                            'title' => 'PC端 - 扫码登录接口',
                            'description' => 'PC端扫码登录相关API接口开发',
                            'priority' => 'medium',
                            'status' => 'todo',
                            'progress' => 0,
                            'estimated_hours' => 8,
                            'dependencies' => ['PC端扫码登录功能']
                        ],
                        [
                            'id' => 8,
                            'title' => '管理后台 - 系统设置',
                            'description' => '管理后台系统设置API接口完善',
                            'priority' => 'medium',
                            'status' => 'in_progress',
                            'progress' => 60,
                            'estimated_hours' => 10,
                            'dependencies' => []
                        ]
                    ]
                ]
            ],
            'recent_updates' => [
                [
                    'date' => date('Y-m-d'),
                    'message' => '完成代码生成器前端UI列表筛选项样式优化'
                ],
                [
                    'date' => date('Y-m-d', strtotime('-1 day')),
                    'message' => '完成存储引擎配置：本地存储、阿里云、腾讯云、七牛云'
                ],
                [
                    'date' => date('Y-m-d', strtotime('-2 days')),
                    'message' => '完成安装引导UI和Release发行版'
                ],
                [
                    'date' => date('Y-m-d', strtotime('-3 days')),
                    'message' => '完成安装引导页面测试redis连接'
                ],
                [
                    'date' => date('Y-m-d', strtotime('-4 days')),
                    'message' => '完成代码生成器功能'
                ]
            ]
        ];
    }

}
