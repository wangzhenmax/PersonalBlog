// 当选择图片时触发
$(".preview").change(function(){
    // 获取选择的图片
    var strs = [];
    for (let i = 0; i < this.files.length; i++) {
        strs[i] = getObjectUrl(this.files[i]);
    } 
    // 转成字符串
    // 先删除上一个
    $('.img_preview').remove();
    // 在框的前面放一个图片
    var str = "";
    for (let i = 0; i < strs.length; i++) {
        str += "<div style='display:inline-block;' class='img_preview'><img  src='"+strs[i]+"' style='width:180px;height:120px' ></div>";
    }
    $(this).before(str);
});

// 把图片转成一个字符串
function getObjectUrl(file) {
    var url = null;
    if (window.createObjectURL != undefined) {
        url = window.createObjectURL(file)
    } else if (window.URL != undefined) {
        url = window.URL.createObjectURL(file)
    } else if (window.webkitURL != undefined) {
        url = window.webkitURL.createObjectURL(file)
    }
    return url
}