<?php
/**
 *
 * @author: liuwl
 * @day: 2017/10/24
 */

namespace generate;
use app\common\model\SysAuthRule;
use think\Db;
class Generate
{   
    protected $model_name = 'iswweb';
    protected $config = [];
    //主数据
    protected $data = [];
    protected $error;
    public function __construct($data = [], $config = null)
    {

        $root_path = ROOT_PATH;
        $app_path = APP_PATH;
        // $config_tmp = [
        //     //模版目录
        //     'template' => [
        //         'path' => $root_path . 'extend/generate/stub/',
        //         'controller' => $root_path . 'extend/generate/stub/Controller.stub',
        //         'model' => $root_path . 'extend/generate/stub/Model.stub',
        //         'validate' => $root_path . 'extend/generate/stub/Validate.stub',
        //         'view' => [
        //             'index' => $root_path . 'extend/generate/stub/view/index.stub',
        //             'index_path' => $root_path . 'extend/generate/stub/view/index/',
        //             'index_del1' => $root_path . 'extend/generate/stub/view/index/del1.stub',
        //             'index_del2' => $root_path . 'extend/generate/stub/view/index/del2.stub',
        //             'index_filter' => $root_path . 'extend/generate/stub/view/index/filter.stub',
        //             'index_export' => $root_path . 'extend/generate/stub/view/index/export.stub',
        //             'index_select1' => $root_path . 'extend/generate/stub/view/index/select1.stub',
        //             'index_select2' => $root_path . 'extend/generate/stub/view/index/select2.stub',
        //             'add' => $root_path . 'extend/generate/stub/view/add.stub',
        //         ],
        //     ],
        //     //生成文件目录
        //     'file_dir' => [
        //         'controller' => $app_path . 'admin/controller/',
        //         'model' => $app_path . 'common/model/',
        //         'validate' => $app_path . 'common/validate/',
        //         'view' => $app_path . 'admin/view/',
        //     ],
        // ];

        $this->config = $config;

        $this->data = $data;
    }
    public function run(){
        $this->createMenu();
    }
    private function createMenu(){
        //菜单前缀
        $url_prefix = $this->model_name.'/' . $this->data['menu']['table'];
        //显示名称
        $name_show = $this->data['menu']['name'];
        Db::startTrans();
        try {

            if (SysAuthRule::where('name', $url_prefix . '/index')->find()) {
                exception('菜单已存在');
            }

            $parent = [
                'name'  => $url_prefix . '/index',
                'title' => $this->data['menu']['name'].$this->data['menu']['ext'],
                'group' => $this->data['menu']['table'],
                'status'=> 1,
                'pid'   => $this->data['menu']['parent_id'],
                'icon'  => $this->data['menu']['module_icon'],
                
            ];
            $result = SysAuthRule::create($parent);
            if (in_array(2, $this->data['menu']['menu'])) {
                SysAuthRule::create([
                    'title' => '添加' . $name_show,
                    'name' => $url_prefix . '/add',
                    'group' => $this->data['menu']['table'],
                    'status'=> 0,
                    'pid' => $result->id,
                ]);
            }

            if (in_array(3, $this->data['menu']['menu'])) {
                SysAuthRule::create([
                    'title' => '修改' . $name_show,
                    'name' => $url_prefix . '/edit',
                    'group' => $this->data['menu']['table'],
                    'status'=> 0,
                    'pid' => $result->id,
                ]);
            }

            if (in_array(4, $this->data['menu']['menu'])) {
                SysAuthRule::create([
                    'title' => '删除' . $name_show,
                    'name' => $url_prefix . '/delete',
                    'group' => $this->data['menu']['table'],
                    'status'=> 0,
                    'pid' => $result->id,
                ]);
            }

            if (in_array(5, $this->data['menu']['menu'])) {
                SysAuthRule::create([
                    'title' => '排序' . $name_show,
                    'name' => $url_prefix . '/listorder',
                    'group' => $this->data['menu']['table'],
                    'status'=> 0,
                    'pid' => $result->id,
                ]);
            }

            Db::commit();
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            Db::rollback();
        }

        return true;
    }
}