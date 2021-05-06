<?php


namespace app\controller;
use app\model\Site;
use app\model\State;
use app\model\Type;
use app\model\User;
use think\Db;
use think\facade\session;

class Api extends Base
{

    //登录页面
    public function login()
    {
        $username = $this->request->param('username');
        $password = $this->request->param('password');
        $result = Db::table('user')->where(['username'=>$username,'password'=>$password])->select();

        session('username',$username);
        if($result){

            return["code"=>"1"];
        }else
        {
            return['code'=>'0'];
        }
    }

    //退出登录
    public function logOut(){
        session(null);
        return $this->redirect('Admin/login');//跳转到登录页面
    }

    //国家列表
    public function stateTable()
    {
        $list = State::all();
        //总条数
        $count=count($list);
        //获取每页显示的条数
        $limit= $this->request->param('limit');
        //获取当前页数
        $page= $this->request->param('page');
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        // 查询出当前页数显示的数据
        $list = Db::table('state')->field(['state_id', 'state_area', 'state_name','ishot'])->limit("$tol","$limit")->order('state_id', 'desc')->select();
        //返回数据
        return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$list];
    }
//类型列表
    public function typeTable()
    {
        $list = Type::all();
        //总条数
        $count=count($list);
        //获取每页显示的条数
        $limit= $this->request->param('limit');
        //获取当前页数
        $page= $this->request->param('page');
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        // 查询出当前页数显示的数据
        $list = Db::table('type')->field(['type_id', 'type_name','ishot'])->limit("$tol","$limit")->order('type_id')->select();
        //返回数据
        return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$list];
    }
    //网站列表
    public function siteTable()
    {
        $list = Site::all();
        //总条数
        $count=count($list);
        //获取每页显示的条数
        $limit= $this->request->param('limit');
        //获取当前页数
        $page= $this->request->param('page');
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        // 查询出当前页数显示的数据
        $list = Db::table('site')->limit("$tol","$limit")->order('ID', 'desc')->select();
        //返回数据
        return ["code"=>"0","msg"=>"","count"=>$count,"data"=>$list];
    }
    //添加国家
    public function addState()
    {
        $postdata = $this->request->param();
        $area = $postdata['area'];
        $state = $postdata['state'];
        $isHot = $postdata['isHot'];
        $result = Db::table('state')
            ->where('state_name',$state)
            ->find();
        if ($result){
            return['msg'=>'这个国家系统已经录入！','status'=>200];
        }else{
            $res = State::create([
                'state_area'=>$area,
                'state_name'=>$state,
                'ishot'=>$isHot,
            ]);
            if ($res){
                return['msg'=>'添加新国家成功','status'=>100];
            }else{
                return['msg'=>'添加失败！','status'=>200];
            }
        }
    }
    //删除国家
    public function delState()
    {
        $state_id = $this->request->GET('state_id');
        Db::table('state')->where('state_id',$state_id)->delete();
        return['status'=>1,'msg'=>'删除成功','state_id'=>$state_id];
    }
    //编辑国家
    public function editState()
    {
        $postdata = $this->request->param();
        $stateId = $postdata['stateId'];
        $area = $postdata['area'];
        $state = $postdata['state'];
        $isHot = $postdata['isHot'];
        $data=[
            'state_name'=>$state,
            'state_area'=>$area,
            'ishot'=>$isHot,
        ];
        $where=[
            'state_id'=>$stateId
        ];
        $field=[
            'state_name','state_area','ishot'
        ];
        $result = State::update($data,$where,$field);
        if ($result){
            return['msg'=>'修改成功！','status'=>100];
        }else{
            return['msg'=>'修改失败！','status'=>200];

        }
    }

    //添加类型
    public function addType()
    {
        $postdata = $this->request->param();
        $type = $postdata['type'];
        $isHot = $postdata['isHot'];
        $result = Db::table('type')
            ->where('type_name',$type)
            ->find();
        if ($result){
            return['msg'=>'这个网站类型已经录入！','status'=>200];
        }else{
            $res = Type::create([
                'type_name'=>$type,
                'ishot'=>$isHot
            ]);
            if ($res){
                return['msg'=>'添加新类型成功','status'=>100];
            }else{
                return['msg'=>'添加失败！','status'=>200];
            }
        }
    }

    //删除类型
    public function delType()
    {
        $type_id = $this->request->GET('type_id');
        Db::table('type')->where('type_id',$type_id)->delete();
        return['status'=>1,'msg'=>'删除成功','type_id'=>$type_id];
    }

    //编辑类型
    public function editType()
    {
        $postdata = $this->request->param();
        $type = $postdata['type'];
        $typeId = $postdata['typeId'];
        $isHot = $postdata['isHot'];
        $data=[
            'type_name'=>$type,
            'ishot'=>$isHot,
        ];
        $where=[
            'type_id'=>$typeId
        ];
        $field=[
            'type_name','ishot'
        ];
        $result = Type::update($data,$where,$field);
        if ($result){
            return['msg'=>'修改成功！','status'=>100];
        }else{
            return['msg'=>'修改失败！','status'=>200];

        }
    }

    //选择大洲下拉选项框联动国家
    public function selectArea()
    {
        $post = $this->request->post('select');
        $state = Db::table('state')->distinct(true)->field('state_name')->where('state_area',$post)->select();
        return ($state);
    }

    //添加网站详情
    public function addSite()
    {
        $data = $this->request->param();
        $site_name = $data['siteName'];
        $adress = $data['adress'];
        $ishot = $data['ishot'];
        $isindex = $data['isindex'];
        $siteInfo = $data['siteInfo'];
        $siteSimpleInfo = $data['siteSimpleInfo'];
        $state = $data['state'];
        $type = $data['type'];
        $img = $data['img'];
        $imgHot = $data['imgHot'];
        $imgLogo = $data['imgLogo'];
        $res = Site::create([
            'sitename'=>$site_name,
            'adress'=>$adress,
            'ishot'=>$ishot,
            'is_index'=>$isindex,
            'info'=>$siteInfo,
            'simple_info'=>$siteSimpleInfo,
            'state_name'=>$state,
            'type_name'=>$type,
            'img'=>$img,
            'hot_img_adress'=>$imgHot,
            'img_logo'=>$imgLogo,
        ]);
        if ($res){
            return['msg'=>'添加网站成功','status'=>100];
        }else{
            return['msg'=>'添加失败！','status'=>200];
        }
    }
    //编辑网站详情
    public function editSite()
    {
        $data = $this->request->param();
        $site_name = $data['siteName'];
        $adress = $data['adress'];
        $ishot = $data['ishot'];
        $isindex = $data['isindex'];
        $siteInfo = $data['siteInfo'];
        $siteSimpleInfo = $data['siteSimpleInfo'];
        $state = $data['state'];
        $type = $data['type'];
        $img = $data['img'];
        $imgHot = $data['imgHot'];
        $imgLogo = $data['imgLogo'];
        $where=[
            'ID'=>$data['siteId']
        ];
        $field=[
            'sitename','adress','ishot','is_index','info','simple_info','state_name','type_name','img','hot_img_adress','img_logo'
        ];
        $data=[
            'sitename'=>$site_name,
            'adress'=>$adress,
            'ishot'=>$ishot,
            'is_index'=>$isindex,
            'info'=>$siteInfo,
            'simple_info'=>$siteSimpleInfo,
            'state_name'=>$state,
            'type_name'=>$type,
            'img'=>$img,
            'hot_img_adress'=>$imgHot,
            'img_logo'=>$imgLogo,
        ];
        $result = Site::update($data,$where,$field);
        if ($result){
            return['msg'=>'网站更新成功','status'=>100,'res'=>$result];
        }else{
            return['msg'=>'更新失败！','status'=>200,'res'=>$result];
        }
    }
    //删除网站详情
    public function delSite()
    {
        $site_id = $this->request->GET('site_id');
        Db::table('site')->where('ID',$site_id)->delete();
        return['status'=>1,'msg'=>'删除成功','site_id'=>$site_id];
    }

    //上传网站图标
    public function addImgLogo()
    {
        $icon = $this->request->file("imgLogo");
        $info = $icon->rule('uniqid')->move('images/site/','');

        if($info)
        {
            $imgName = $info->getSaveName();
            return  (['code'=>1,'msg'=>'上传成功','imgName'=>$imgName]);
        }
        else
        {
            return  (['code'=>0,'msg'=>$icon->getError()]);
        }
    }

    //上传网站图片
    public function addImg()
    {
        $icon = $this->request->file("img");
        $info = $icon->rule('uniqid')->move('images/site/','');

        if($info)
        {
            $imgName = $info->getSaveName();
            return  (['code'=>1,'msg'=>'上传成功','imgName'=>$imgName]);
        }
        else
        {
            return  (['code'=>0,'msg'=>$icon->getError()]);
        }
    }

    //上传热门网站小图标
    public function addImgHot()
    {
        $icon = $this->request->file("imgHot");
        $info = $icon->rule('uniqid')->move('images/hot-logo/','');

        if($info)
        {
            $imgName = $info->getSaveName();
            return  (['code'=>1,'msg'=>'上传成功','imgName'=>$imgName]);
        }
        else
        {
            return  (['code'=>0,'msg'=>$icon->getError()]);
        }
    }

    //编辑网站详情下拉列表
    public function editSiteSelect()
    {
        $post = $this->request->post('sitename');
        $state = Db::table('site')->field('state_name')->where('sitename',$post)->find();
        $area = Db::table('state')->field('state_area')->where('state_name',$state['state_name'])->find();
        return (['state'=>$state['state_name'],'area'=>$area['state_area']]);
    }

    //搜索框(上传网站页面)
    public function searchSite()
    {
        $post =$this->request->param('data');
        $info = Db::table('site')->where('sitename|simple_info','like',"%".$post."%")->select();
        $count=count($info);
        if ($info){
            return['msg'=>'查询成功','code'=>'0','data'=>$info,'count'=>$count];
        }else{
            return['msg'=>'没有这条数据！','code'=>'1','data'=>$info];
        }
    }

    //搜索框(上传类型页面)
    public function searchTypeText()
    {
        $post =$this->request->param('data');
        $info = Db::table('type')->where('type_name','like',"%".$post."%")->select();
        $count=count($info);
        if ($info){
            return['msg'=>'查询成功','code'=>'0','data'=>$info,'count'=>$count];
        }else{
            return['msg'=>'没有这条数据！','code'=>'1','data'=>$info];
        }
    }

    //搜索框(上传国家页面)
    public function searchStateText()
    {
        $post =$this->request->param('data');
        $info = Db::table('state')->where('state_area|state_name','like',"%".$post."%")->select();
        $count=count($info);
        if ($info){
            return['msg'=>'查询成功','code'=>'0','data'=>$info,'count'=>$count];
        }else{
            return['msg'=>'没有这条数据！','code'=>'1','data'=>$info];
        }
    }

    //筛选国家
    public function searchState()
    {
        $post =$this->request->param('data');
        //获取每页显示的条数
        $limit= $this->request->param('limit');
        //获取当前页数
        $page= $this->request->param('page');
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        //获取重条数
        $info = Db::table('site')->where('state_name',$post)->select();
        $count=count($info);
        //分页
        $info = Db::table('site')->limit("$tol","$limit")->where('state_name',$post)->select();
        if ($info){
            return['msg'=>'查询成功','code'=>'0','data'=>$info,'count'=>$count];
        }else{
            return['msg'=>'没有这条数据！','code'=>'1','data'=>$info];
        }
    }

    //筛选类型
    public function searchType()
    {
        $post =$this->request->param('data');
        //获取每页显示的条数
        $limit= $this->request->param('limit');
        //获取当前页数
        $page= $this->request->param('page');
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        //获取重条数
        $info = Db::table('site')->where('type_name',$post)->select();
        $count=count($info);
        //分页
        $info = Db::table('site')->limit("$tol","$limit")->where('type_name',$post)->select();
        if ($info){
            return['msg'=>'查询成功','code'=>'0','data'=>$info,'count'=>$count];
        }else{
            return['msg'=>'没有这条数据！','code'=>'1','data'=>$info];
        }
    }

    //筛选热门
    public function searchHot()
    {
        $post =$this->request->param('data');
        //获取每页显示的条数
        $limit= $this->request->param('limit');
        //获取当前页数
        $page= $this->request->param('page');
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        //获取重条数
        $info = Db::table('site')->where('ishot',$post)->select();
        $count=count($info);
        //分页
        $info = Db::table('site')->limit("$tol","$limit")->where('ishot',$post)->select();
        if ($info){
            return['msg'=>'查询成功','code'=>'0','data'=>$info,'count'=>$count];
        }else{
            return['msg'=>'没有这条数据！','code'=>'1','data'=>$info];
        }
    }

    //筛选是否主页显示
    public function searchIndex()
    {
        $post =$this->request->param('data');
        //获取每页显示的条数
        $limit= $this->request->param('limit');
        //获取当前页数
        $page= $this->request->param('page');
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        //获取重条数
        $info = Db::table('site')->where('is_index',$post)->select();
        $count=count($info);
        //分页
        $info = Db::table('site')->limit("$tol","$limit")->where('is_index',$post)->select();
        if ($info){
            return['msg'=>'查询成功','code'=>'0','data'=>$info,'count'=>$count];
        }else{
            return['msg'=>'没有这条数据！','code'=>'1','data'=>$info];
        }
    }

    //筛选热门国家(国家管理)
    public function searchStateHot()
    {
        $post =$this->request->param('data');
        //获取每页显示的条数
        $limit= $this->request->param('limit');
        //获取当前页数
        $page= $this->request->param('page');
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        //获取重条数
        $info = Db::table('state')->where('ishot',$post)->select();
        $count=count($info);
        //分页
        $info = Db::table('state')->limit("$tol","$limit")->where('ishot',$post)->select();
        if ($info){
            return['msg'=>'查询成功','code'=>'0','data'=>$info,'count'=>$count];
        }else{
            return['msg'=>'没有这条数据！','code'=>'1','data'=>$info];
        }
    }

    //筛选大洲(国家管理)
    public function searchAreaSelect()
    {
        $post =$this->request->param('data');
        //获取每页显示的条数
        $limit= $this->request->param('limit');
        //获取当前页数
        $page= $this->request->param('page');
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        //获取重条数
        $info = Db::table('state')->where('state_area',$post)->select();
        $count=count($info);
        //分页
        $info = Db::table('state')->limit("$tol","$limit")->where('state_area',$post)->select();
        if ($info){
            return['msg'=>'查询成功','code'=>'0','data'=>$info,'count'=>$count];
        }else{
            return['msg'=>'没有这条数据！','code'=>'1','data'=>$info];
        }
    }

    //筛选热门类别(类别管理)
    public function searchTypeHot()
    {
        $post =$this->request->param('data');
        //获取每页显示的条数
        $limit= $this->request->param('limit');
        //获取当前页数
        $page= $this->request->param('page');
        //计算出从那条开始查询
        $tol=($page-1)*$limit;
        //获取重条数
        $info = Db::table('type')->where('ishot',$post)->select();
        $count=count($info);
        //分页
        $info = Db::table('type')->limit("$tol","$limit")->where('ishot',$post)->select();
        if ($info){
            return['msg'=>'查询成功','code'=>'0','data'=>$info,'count'=>$count];
        }else{
            return['msg'=>'没有这条数据！','code'=>'1','data'=>$info];
        }
    }




}