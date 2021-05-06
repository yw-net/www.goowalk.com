<?php
namespace app\controller;

use think\Db;

class Index extends Base
{
    //空操作（index模块下错误地址）
    public function _empty()
    {
        $this->redirect('index/index');
    }

    public function index()
    {
        $hotSite = Db::table('site')->field(['sitename', 'hot_img_adress'])->where('ishot',1)->select();
        $hotState = Db::table('state')->field(['state_name'])->where('ishot',1)->select();
        $hotType = Db::table('type')->field(['type_name'])->where('ishot',1)->select();
        $isIndexNew = Db::table('site')->field(['sitename','img_logo','simple_info'])->where(['is_index'=>1,'type_name'=>'新闻'])->select();
        $isIndexSearch= Db::table('site')->field(['sitename','img_logo','simple_info'])->where(['is_index'=>1,'type_name'=>'搜索'])->select();
        $isIndexYinshi= Db::table('site')->field(['sitename','img_logo','simple_info'])->where(['is_index'=>1,'type_name'=>'影视'])->select();
        $isIndexShejiao= Db::table('site')->field(['sitename','img_logo','simple_info'])->where(['is_index'=>1,'type_name'=>'社交'])->select();
        $isIndexYule= Db::table('site')->field(['sitename','img_logo','simple_info'])->where(['is_index'=>1,'type_name'=>'娱乐'])->select();
        $isIndexJunshi= Db::table('site')->field(['sitename','img_logo','simple_info'])->where(['is_index'=>1,'type_name'=>'军事'])->select();
        $isIndexLvyou= Db::table('site')->field(['sitename','img_logo','simple_info'])->where(['is_index'=>1,'type_name'=>'旅游资讯'])->select();
        $this->assign([
            'hotSite'=>$hotSite
            ,'hotState'=>$hotState
            ,'hotType'=>$hotType
            ,'isIndexNew'=>$isIndexNew
            ,'isIndexSearch'=>$isIndexSearch
            ,'isIndexYinshi'=>$isIndexYinshi
            ,'isIndexShejiao'=>$isIndexShejiao
            ,'isIndexYule'=>$isIndexYule
            ,'isIndexJunshi'=>$isIndexJunshi
            ,'isIndexLvyou'=>$isIndexLvyou
        ]);
        return $this->view->fetch();
    }

    public function search()
    {
        $get = $this->request->get('search');
        $info = Db::table('site')->where('sitename|simple_info|type_name|state_name','like',"%".$get."%")->select();
        $this->assign(['info'=>$info,'get'=>$get]);
        return $this->view->fetch();
    }

    public function site()
    {
        $siteName = $this->request->param('name');
        $siteInfo= Db::table('site')
            ->where('sitename',$siteName)->find();
        $siteType = Db::table('type')
            ->where('type_name',$siteInfo['type_name'])->find();
        $siteState = Db::table('state')
            ->where('state_name',$siteInfo['state_name'])->find();
        $this->assign([
            'siteInfo'=>$siteInfo
            ,'type'=>$siteType['type_name']
            ,'state'=>$siteState['state_name']
            ,'area'=>$siteState['state_area']
        ]);
        return $this->view->fetch();
    }
    public function continent(){
        $beimei = Db::table('state')->where('state_area','北美洲')->select();
        $nanmei = Db::table('state')->where('state_area','南美洲')->select();
        $dayang = Db::table('state')->where('state_area','大洋洲')->select();
        $yazhou = Db::table('state')->where('state_area','亚洲')->select();
        $feizhou = Db::table('state')->where('state_area','非洲')->select();
        $ouzhou = Db::table('state')->where('state_area','欧洲')->select();
        $this->assign(['beimei'=>$beimei,'nanmei'=>$nanmei,'dayang'=>$dayang,'yazhou'=>$yazhou,'feizhou'=>$feizhou,'ouzhou'=>$ouzhou]);
        return $this->view->fetch();
    }
    public function state()
    {
        $stateName = $this->request->param('name');
        $postType = $this->request->param('type');
        $state = Db::table('state')
            ->where('state_name',$stateName)->find();
        if ($postType!=null && $postType!='全部类别'){
            $siteState = Db::table('site')->where(['state_name'=>$stateName,'type_name'=>$postType])->select();
        }
        else{
            $siteState = Db::table('site')->where('state_name',$stateName)->select();
        }

        $type = Db::table('site')->Distinct(true)->field('type_name')->where('state_name',$stateName)->select();//选择框赋值
        $this->assign(['state'=>$state,'siteState'=>$siteState,'type'=>$type]);
        return $this->view->fetch();
    }
    public function type()
    {
        $typeName = $this->request->param('name');
        $postState = $this->request->param('state');
        $type = Db::table('type')
            ->where('type_name',$typeName)->find();
        if ($postState!=null && $postState!='全部国家'){
            $siteType = Db::table('site')->where(['state_name'=>$postState,'type_name'=>$typeName])->select();
        }
        else{
            $siteType = Db::table('site')->where('type_name',$typeName)->select();
        }
        $state = Db::table('site')->Distinct(true)->field('state_name')->where('type_name',$typeName)->select();//选择框赋值
        $this->assign(['type'=>$type,'siteType'=>$siteType,'state'=>$state]);
        return $this->view->fetch();
    }

    public function alltype()
    {
        $info = Db::table('type')->distinct(true)->select();
        $this->assign('info',$info);

        return $this->view->fetch();
    }


}
