##  混淆笔记记录
####  路由
+   参数接收问题
    +   1、定义路由：`Route::rule('liv/:id',['live/Index/detail',[],['id'=>'\d+']],'GET')`  
    +   2、使用`$_GET`接收打印，结果是一个空数组`array(0) {}`  
    +   3、正确的接受路由参数方式，`input("param.id")`，这样子就可以接受传递的参数id值了  
