<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/22 15:18
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\common\model;


class WxTheme extends BaseModel
{
    /**
     * 那个表中有外键，则要在那个模型中定义一对一的模型
     * @return \think\model\relation\BelongsTo
     */
    public function topicImg()
    {
        return $this->belongsTo('WxImage', 'topic_img_id', 'id');
    }

    public function headImg()
    {
        return $this->belongsTo('WxImage', 'head_img_id', 'id');
    }

    /**
     * 多对多 belongsToMany (多对多关联无论以哪个模型为参照关联不变)
     * 用法：belongsToMany('关联模型','中间表','外键','关联键');
     * @return \think\model\relation\BelongsToMany
     */
    public function products()
    {
        // 以下需要注意的: "wx_theme_product"不是表的全名，而是不带前缀的表明，完整表名为：resty_wx_theme_product
        return $this->belongsToMany('WxProduct', 'wx_theme_product', 'theme_id', 'product_id');
    }

    public static function getThemeWithProducts($id)
    {
        return self::with('topicImg,headImg,products')->find($id);
    }
}