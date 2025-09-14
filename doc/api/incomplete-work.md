# 未完成工作列表 API

## 概述
此 API 端点用于获取项目中未完成工作的详细列表，包括任务摘要、分类详情和最近更新。

## 端点信息
- **URL**: `/adminapi/workbench/incomplete-work`
- **方法**: `GET`
- **认证**: 需要管理员登录
- **权限**: 管理员权限

## 响应格式

### 成功响应 (200 OK)
```json
{
  "code": 1,
  "msg": "success",
  "data": {
    "summary": {
      "total_tasks": 10,
      "completed_tasks": 5,
      "completion_rate": 50,
      "priority_tasks": 3
    },
    "categories": [
      {
        "name": "功能开发",
        "tasks": [
          {
            "id": 1,
            "title": "渠道设置 - 开放平台配置",
            "description": "完成微信开放平台的配置功能",
            "priority": "high",
            "status": "todo",
            "progress": 30,
            "estimated_hours": 16,
            "dependencies": ["微信小程序配置", "公众号菜单管理"]
          }
        ]
      }
    ],
    "recent_updates": [
      {
        "date": "2025-09-14",
        "message": "完成代码生成器前端UI列表筛选项样式优化"
      }
    ]
  }
}
```

## 数据字段说明

### summary (摘要)
- `total_tasks`: 总任务数
- `completed_tasks`: 已完成任务数
- `completion_rate`: 完成率 (百分比)
- `priority_tasks`: 高优先级任务数

### categories (分类)
每个分类包含：
- `name`: 分类名称
- `tasks`: 任务列表

### tasks (任务)
每个任务包含：
- `id`: 任务唯一标识
- `title`: 任务标题
- `description`: 任务描述
- `priority`: 优先级 (`high`, `medium`, `low`)
- `status`: 状态 (`todo`, `in_progress`, `testing`)
- `progress`: 进度 (0-100)
- `estimated_hours`: 预估工时
- `dependencies`: 依赖任务列表

### recent_updates (最近更新)
- `date`: 更新日期 (YYYY-MM-DD)
- `message`: 更新信息

## 使用示例

### cURL 请求
```bash
curl -X GET "http://your-domain/adminapi/workbench/incomplete-work" \
  -H "Authorization: Bearer your-admin-token"
```

### JavaScript (Axios)
```javascript
axios.get('/adminapi/workbench/incomplete-work')
  .then(response => {
    const incompleteWork = response.data.data;
    console.log('总任务数:', incompleteWork.summary.total_tasks);
    console.log('完成率:', incompleteWork.summary.completion_rate + '%');
  })
  .catch(error => {
    console.error('获取未完成工作失败:', error);
  });
```

## 错误响应

### 未授权 (401)
```json
{
  "code": 0,
  "msg": "请先登录",
  "data": {}
}
```

### 权限不足 (403)
```json
{
  "code": 0,
  "msg": "无访问权限",
  "data": {}
}
```

## 注意事项
1. 此 API 需要管理员登录权限
2. 返回的任务数据基于项目 README 中的优先级排期
3. 进度和状态可能需要根据实际开发进度手动更新
4. 依赖关系反映了任务之间的逻辑依赖