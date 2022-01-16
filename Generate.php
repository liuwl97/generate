<?php

namespace app\iswweb\controller;
use generate\Generate as GenerateUtil;
use app\common\controller\AdminBase;
use think\Db;
use think\Controller;

/**
 * 自动生成
 * Class Generate
 * @package app\iswweb\controller
 */
class Generate extends AdminBase
{   
    
    protected function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 首页
     * @return mixed
     */
    public function add()
    {   
        
        if(request()->isPost()){
            $data = input('post.');
            $param = [
                'menu'  =>[
                    'module_icon'   =>  $data['module_icon'],
                    'table'   =>  $data['table'],
                    'name'   =>  $data['name'],
                    'ext'   =>  $data['ext'],
                    'parent_id'   =>  $data['parent_id'],
                    'menu'   =>  explode(',',$data['action_menu']),
                ],
            ];
            $GenerateUtil = new GenerateUtil($param);
            $GenerateUtil->run();
            $this->success('操作成功');
        }
        return $this->fetch('', get_defined_vars());
    }
   
}
