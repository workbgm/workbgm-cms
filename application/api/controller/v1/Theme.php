<?php

namespace app\api\controller\v1;

use app\api\validate\IDCollection;
use app\api\model\Theme as ThemeModel;
use app\api\validate\IDMustBePostiveInt;
use app\common\exception\ThemeMissException;

class Theme
{
    /**
     * @url /theme?ids=id1,id2……
     * @return 一组theme模型
     */
    public function getSimpleList($ids=''){
        (new IDCollection())->goCheck();
        $ids=explode(',',$ids);
        $result = ThemeModel::getThemeList($ids);
        return $result;
    }

    public function getComplexOne($id){
        (new IDMustBePostiveInt())->goCheck();
        $result=ThemeModel::getThemeWidthProducts($id);
        return $result;
    }
}
