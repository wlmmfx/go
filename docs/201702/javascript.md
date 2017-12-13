##  数组
###  基本语法
* 定义时赋值

  ```javascript
  var arr = [0,1,2];
  ```
* 先定义后赋值

  ```javascript
  var arr = [];
  arr[0] = 123;
  arr[1] = 3;
  ```
* 任何类型的数据，都可以放入数组

  ```javascript
  var arr = [
    {a: 1},
    [1, 2, 3],
    function() {return true;}
  ];

  arr[0] // Object {a: 1}
  arr[1] // [1, 2, 3]
  arr[2] // function (){return true;}
  ```
* `typeof`运算符会返回数组的类型是`object` 如：`typeof [1, 2, 3] // "object"`

* `Object.keys`方法返回数组的所有键名:

  ```javascript
  var arr = [3,4,5,6];
  Object.keys(arr);
  (4) ["0", "1", "2", "3"]
  ```
* JavaScript语言规定，**对象的键名一律为字符串**，所以，数组的键名其实也是字符串,之所以可以用数值读取，是因为非字符串的键名会被转为字符串。

### 数组的遍历

* `for...in`不仅会遍历数组所有的数字键，还会遍历非数字键。所以，不推荐使用for...in遍历数组。

* 数组的遍历可以考虑使用for循环或while循环

  ```javascript
  var a = [1, 2, 3];

  // for循环
  for(var i = 0; i < a.length; i++) {
    console.log(a[i]);
  }

  // while循环
  var i = 0;
  while (i < a.length) {
    console.log(a[i]);
    i++;
  }

  var l = a.length;
  while (l--) {
    console.log(a[l]);
  }
  ```
* 数组的forEach方法，也可以用来遍历数组

  ```javascript
  var colors = ['red', 'green', 'blue'];
  colors.forEach(function (color) {
    console.log(color);
  });
  ```