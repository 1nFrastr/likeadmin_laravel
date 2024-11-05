<?php

namespace App\Common\Model\Article;

use App\Common\Enum\YesNoEnum;
use App\Common\Model\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 资讯管理模型
 */
class Article extends BaseModel
{
    protected $table = 'article';

    use SoftDeletes;

    protected function getDeletedAtColumn()
    {
        return 'delete_time';
    }

    protected $appends = [
        'cate_name',
        'click'
    ];

    /**
     * 获取分类名称
     * @return string
     */
    public function getCateNameAttribute($value)
    {
        return ArticleCate::where('id', $this->attributes['cid'])->value('name');
    }

    /**
     * 浏览量
     * @return mixed
     */
    public function getClickAttribute($value)
    {
        return $this->attributes['click_actual'] + $this->attributes['click_virtual'];
    }

    /**
     * 设置图片域名
     * @param $value
     * @return array|string|string[]|null
     */
    public function getContentAttribute($value)
    {
        return get_file_domain($value);
    }

    /**
     * 清除图片域名
     * @param $value
     */
    public function setContentAttribute($value)
    {
        $this->attributes['content'] = clear_file_domain($value);
    }

    /**
     * 获取文章详情
     * @param int $id
     * @return array
     */
    public static function getArticleDetailArr(int $id)
    {
        $article = self::query()->where(['id' => $id, 'is_show' => YesNoEnum::YES])->first();

        if (!$article) {
            return [];
        }

        // 增加点击量 TODO TEST
        $article->increment('click_actual');

        return $article
            ->makeHidden(['click_virtual', 'click_actual']) // TODO TEST
            ->toArray();
    }
}