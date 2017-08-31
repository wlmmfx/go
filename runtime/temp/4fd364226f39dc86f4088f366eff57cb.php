<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:87:"D:\wamp64\www\thinkphp5-study-line\public/../application/frontend\view\index\index.html";i:1501425274;s:82:"D:\wamp64\www\thinkphp5-study-line\public/../application/frontend\view\layout.html";i:1501407855;s:89:"D:\wamp64\www\thinkphp5-study-line\public/../application/frontend\view\common\header.html";i:1503715325;s:89:"D:\wamp64\www\thinkphp5-study-line\public/../application/frontend\view\common\footer.html";i:1501412041;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <title><?php echo \think\Config::get('app_title'); ?></title>
    <meta name="description" content="overview &amp; stats"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="__COMMON__/bootstrap-3.3.5/css/bootstrap.css"/>
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <script type="text/javascript" src="__COMMON__/plugins/ueditor/third-party/SyntaxHighlighter/shCore.js"></script>
    <link rel="stylesheet" href="__COMMON__/plugins/ueditor/third-party/SyntaxHighlighter/shCoreDefault.css">
    <script type="text/javascript">
        SyntaxHighlighter.all();
    </script>
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 50px;
            color: #5a5a5a;
        }

        .footer {
            padding-bottom: 10px;
            background: #222;
            color: #9D9D9D;
        }

        .footer hr {
            border-top: 1px solid #000;
            border-bottom: 1px solid #333;
            margin: 10px 0;
        }

        .footer a {
            color: #9D9D9D;
        }

        .navbar-form {
            margin-right: -15px;
            margin-bottom: 8px;
            margin-left: -15px;
        }

        .tag-cloud a {
            padding: 2px 7px;
            display: inline-block;
            font-size: 12px;
            transition: all 0.2s ease;
            margin: 2px;
            background: #e5e5e5;
            color: #555;
            border-radius: 3px;
            text-decoration: none;
            border: 1px dashed #bbb;
        }

        .tag-cloud a span {
            vertical-align: super;
            font-size: 0.8em;
        }

        .tag-cloud li {
            line-height: 28px;
        }

        .panel ul.post-list li {
            line-height: 2.4em;
            color: #ccc;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }

        .media-list .media-body h2.media-heading {
            font-size: 14px;
        }

        .media-heading {
            margin-top: 0;
            margin-bottom: 5px;
        }

        .media-list .media .media-object {
            width: 48px;
            height: 48px;
            padding: 1px;
            border: #ddd solid 1px;
        }

        .box-title {
            color: #333;
            font-size: 18px;
            border-bottom: 2px solid #CBCBCB;
            padding-bottom: 5px;
        }

        .media-list .media {
            border-bottom: #eee solid 1px;
            line-height: 30px;
        }

        .page-title span {
            color: #999;
            font-size: 14px;
            margin-right: 5px;
        }

        .post-label > img {
            margin-left: auto;
            margin-right: auto;
            display: block;
            height: 150px;
            width: 100%;
        }

        .border-bottom {
            border-bottom: 1px solid #ccc;
        }

        .btn-group, .btn-group-vertical {
            position: relative;
            display: inline-block;
            vertical-align: middle;
            line-height: 26px;
        }

        .panel ul {
            list-style-type: none;
            margin-left: -30px;
        }

        .site-signup ul li {
            display: inline;
            list-style-type: none;
            height: 40px;
            text-align: center
        }
    </style>
</head>
<body>
<!-- 顶部导航 -->
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation" id="menu-nav">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">切换导航</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">
                <img src="__COMMON__/images/logo.png" alt="Yii Framework 中文社区" height="35">
            </a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="navbar-nav navbar-left nav">
                <li class="active"><a href="/">首页</a></li>
                <li><a href="#summary-container">下载</a></li>
                <li><a href="#summary-container">文档</a></li>
                <li><a href="#summary-container">教程</a></li>
                <li><a href="#summary-container">扩展</a></li>
                <li><a href="#summary-container">源码</a></li>
                <li><a href="#summary-container">回答</a></li>
                <li><a href="#summary-container">话题</a></li>
                <li><a href="#summary-container">视频</a></li>
                <li><a href="#summary-container">案例</a></li>
                <li><a href="#summary-container">排行</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">特点 <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#feature-tab" data-tab="tab-chrome">Chrome</a></li>
                        <li><a href="#feature-tab" data-tab="tab-firefox">Firefox</a></li>
                        <li><a href="#feature-tab" data-tab="tab-safari">Safari</a></li>
                        <li><a href="#feature-tab" data-tab="tab-opera">Opera</a></li>
                        <li><a href="#feature-tab" data-tab="tab-ie">IE</a></li>
                    </ul>
                </li>
                <li>
                    <form class="navbar-form navbar-left" role="search">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search">
                        </div>
                        <button type="submit" class="btn btn-default">提交</button>
                    </form>
                </li>
            </ul>
            <ul class="navbar-nav navbar-right nav">
                <?php if(\think\Session::get('frontend.username')): ?>
                <li><a href="<?php echo url('frontend/member/signin121'); ?>"><?php echo \think\Session::get('frontend.username'); ?></a></li>
                <?php else: ?>
                <li><a href="<?php echo url('frontend/member/signup'); ?>">注册</a></li>
                <li><a href="<?php echo url('frontend/member/signin'); ?>">登录</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>


<!-- 主要内容 -->
<div class="container">
    <!-- 简介 -->
    <div class="col-lg-9">
        <div id="myCarousel" class="carousel slide">
            <!-- 轮播（Carousel）指标 -->
            <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#myCarousel" data-slide-to="1"></li>
            </ol>
            <!-- 轮播（Carousel）项目 -->
            <div class="carousel-inner">
                <div class="item active">
                    <img src="__COMMON__/images/001.jpg" alt="First slide">
                </div>
                <div class="item">
                    <img src="__COMMON__/images/002.jpg" alt="Second slide">
                </div>

            </div>
            <!-- 轮播（Carousel）导航 -->
            <a class="carousel-control left" href="#myCarousel"
               data-slide="prev">&lsaquo;
            </a>
            <a class="carousel-control right" href="#myCarousel"
               data-slide="next">&rsaquo;
            </a>
        </div>
        <hr>
        <div class="panel">
            <div class="panel-title box-title">
                <span>最新文章</span>
                <span class="pull-right"><a href="/post/index-all.html" class="font-12">更多»</a></span>
            </div>
            <div class="new-list">
                <div class="panel-body border-bottom">
                    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <div class="row">
                        <div class="col-lg-4 label-img-size">
                            <a href="#" class="post-label">
                                <img src="http://up.yii-china.com/image/20160505/1462417912122071.png"
                                     alt="<?php echo $vo['title']; ?>">
                            </a>
                        </div>
                        <div class="col-lg-8 btn-group">
                            <h4><a href="<?php echo url('frontend/index/detail',['id'=>$vo['id']]); ?>l"><?php echo $vo['title']; ?></a>&nbsp;<span class="cat">[<?php echo $vo['c_name']; ?>]</span></h4>
                            <span class="post-tags">
                        <span class="glyphicon glyphicon-user"></span><a href="/member/index/1.html"><?php echo $vo['username']; ?></a>&nbsp;
                        <span class="glyphicon glyphicon-time"></span>&nbsp;<?php echo date("Y-m-d",$vo['create_time']); ?>&nbsp;
                        <span class="glyphicon glyphicon-eye-open"></span>&nbsp;<?php echo $vo['views']; ?>
                        <span class="glyphicon glyphicon-comment"></span><a href="/post/detail/226.html">&nbsp;18</a>
                    </span>
                            <p class="post-content">
                            <?php echo mb_substr($vo["content"],0,84); ?>
                            </p>
                            <a href="<?php echo url('frontend/index/detail',['id'=>$vo['id']]); ?>">
                                <button class="btn btn-warning no-radius btn-sm pull-right">阅读全文</button>
                            </a>
                        </div>
                    </div>
                    <hr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3">
        <div class="btn-group btn-group-justified">
            <a class="btn btn-success disabled" href="/registration"><i class="fa fa-calendar-check-o"></i>
                今日已签到<br>已连续177天</a> <a class="btn btn-primary" href="/registration">2017年07月30日<br>今日已有91人签到</a>
        </div>
        <p></p>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title"><i class="fa fa-file-code-o"></i> 标签库</h2>
            </div>
            <div class="panel-body">
                <div class="tag-cloud">
                    <?php if(is_array($tags) || $tags instanceof \think\Collection || $tags instanceof \think\Paginator): $i = 0; $__LIST__ = $tags;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <a href="<?php echo url('frontend/index/searchByTagId',['id'=>$vo['tag_id']]); ?>"  >
                        <?php echo $vo['name']; ?> <span class="badge"><?php echo $vo["art_num"]; ?></span></a>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
        </div>
        <!--最新教程-->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title"><i class="fa fa-file-word-o"></i> 最新教程</h2>
            </div>
            <div class="panel-body" style="padding-left: -19px;">
                <ul class="post-list">
                    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <li><i class="fa fa-angle-double-right"></i> <a href="<?php echo url('frontend/index/detail',['id'=>$vo['id']]); ?>"><?php echo $vo['title']; ?></a></li>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

</div>

<!--footer-->
<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-2">
                <h2><i class="fa fa-info-circle"></i> 关于 Yii</h2>
                <ul>
                    <li><a href="/about">Yii 的简介</a></li>
                    <li><a href="/news">Yii 的动态</a></li>
                    <li><a href="/features">Yii 的特性</a></li>
                    <li><a href="/performance">Yii 的性能</a></li>
                    <li><a href="/license">Yii 的许可</a></li>
                </ul>
            </div>
            <div class="col-lg-2">
                <h2><i class="fa fa-book"></i> 文档手册</h2>
                <ul>
                    <li><a href="/doc">中文文档</a></li>
                    <li><a href="/download">框架下载</a></li>
                    <li><a href="/tutorial">中文教程</a></li>
                    <li><a href="/extension">安装扩展</a></li>
                    <li><a href="/code">优秀源码</a></li>
                </ul>
            </div>
            <div class="col-lg-2">
                <h2><i class="fa fa-commenting"></i> 社区资源</h2>
                <ul>
                    <li><a href="/question">社区问答</a></li>
                    <li><a href="/topic">社区讨论</a></li>
                    <li><a href="/case">用户案例</a></li>
                    <li><a href="/video">视频教程</a></li>
                    <li><a href="/top">会员排行</a></li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h2><i class="fa fa-qq"></i> QQ交流群</h2>
                <ul class="list-unstyled">
                    <li>
                        ① <a target="_blank" href="http://shang.qq.com/wpa/qunwpa?idkey=19f92b4525df025f856917537c4a6d9bb8dd6a0fc1c660b408d65cc21fef6c22">4241653</a> <font class="secure">(未满)</font>　
                        ② <a target="_blank" href="http://shang.qq.com/wpa/qunwpa?idkey=38dee71f9bd97c37e131c0722e640fe7c12f459afc67ca34fb82d67dd1ab9b0c">4829703</a> <font class="secure">(未满)</font>
                    </li>
                    <li>
                        ③ <a target="_blank" href="http://shang.qq.com/wpa/qunwpa?idkey=215d55638b0eac69f25b68664d394579994b48b34789149855419c548a620d57">4958407</a> <font class="fast">(已满)</font>　
                        ④ <a target="_blank" href="http://shang.qq.com/wpa/qunwpa?idkey=7aa35873c42e820781a4822e7ba2c7352c3c85454ea9454009fe2c15a2797c5d">5476028</a> <font class="fast">(已满)</font>
                    </li>
                    <li>
                        ⑤ <a target="_blank" href="http://shang.qq.com/wpa/qunwpa?idkey=1a0c961d723cd24f98185b4a631f303efa05c2442f24022c3eb1ddb8b623a270">5478523</a> <font class="fast">(已满)</font>　
                        ⑥ <a target="_blank" href="http://shang.qq.com/wpa/qunwpa?idkey=f0ab6fcfcd0a431c53c0ef8e5538609a6920360c86b73dd401e7e88f1a2795b9">5604716</a> <font class="fast">(已满)</font>
                    </li>
                    <li>
                        ⑦ <a target="_blank" href="http://shang.qq.com/wpa/qunwpa?idkey=143aade31ff7a073a07bdc75d3c960b3f671a76f6f6de0c608c3702b6aac60a7">5629416</a> <font class="fast">(已满)</font>　
                        ⑧ <a target="_blank" href="http://shang.qq.com/wpa/qunwpa?idkey=57b5f15c3b1f35cd2721b45a6eb20fd63cc76a4776e5c1767b521f01c14dec9c">6419794</a> <font class="fast">(已满)</font>
                    </li>
                    <li>
                        ⑨ <a target="_blank" href="http://shang.qq.com/wpa/qunwpa?idkey=77e547190bdda1bac3d1fed071882b53585d63120f65ef656e7f4f0d3112cbdd">7415106</a> <font class="fast">(已满)</font>　
                        ⑩ <a target="_blank" href="http://shang.qq.com/wpa/qunwpa?idkey=d626c01d0074072d2e01219259aab99d10d8691711a2882478c1dbf8a9b5e23e">7594839</a> <font class="fast">(已满)</font>
                    </li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h2><i class="fa fa-share-alt"></i> 关注我们</h2>
                <dl>
                    <dt><i class="fa fa-weibo"></i> Yii 中文社区 官方微博</dt>
                    <dd><a href="http://weibo.com/yiichina">http://weibo.com/yiichina</a></dd>
                </dl>
                <dl>
                    <dt><i class="fa fa-github"></i> Yii China GitHub 仓库</dt>
                    <dd><a href="https://github.com/yiichina">https://github.com/yiichina</a></dd>
                </dl>
            </div>
        </div>
    </div>
    <hr>
    <div class="container">
        <div class="clearfix">
            <span class="pull-left">Copyright © 2009-2017 by <a href="http://www.yiichina.com">Yii China</a>. All Rights Reserved.</span>
            <span class="pull-right">
				技术支持 <a href="http://www.yiiframework.com/" rel="external">Yii 框架</a> 2.0.12.
				<a href="http://www.miibeian.gov.cn" target="_blank">京ICP备09104811号</a>
				<script src="https://s4.cnzz.com/z_stat.php?id=1256642902&amp;web_id=1256642902" language="JavaScript"></script><script src="https://c.cnzz.com/core.php?web_id=1256642902&amp;t=z" charset="utf-8" type="text/javascript"></script><a href="http://www.cnzz.com/stat/website.php?web_id=1256642902" target="_blank" title="站长统计">站长统计</a>
                <a href="/link">友情链接</a>			</span>
        </div>
    </div>
</footer>
</body>
</html>