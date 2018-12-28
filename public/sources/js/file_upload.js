layui.use('upload', function () {
    var upload = layui.upload,
        jq = layui.jquery;
    upload.render({
        url: "{:url('tianchi/index/imageChangeTextContentPost')}"
        , elem: '#image'
        , ext: 'jpg|png|gif'
        , area: ['500', '500px']
        , before: function (input) {
            loading = layer.load(2, {
                shade: [0.2, '#000']
            });
        }
        , done: function (res) {
            layer.close(loading);
            jq('input[name=img]').val(res.path);
            img.src = "" + res.path;
            layer.msg(res.msg, { icon: 1, time: 1000 });
        }
    });
})