<?php
header('content-type:text/html;charset=utf-8');

$fileName = $_FILES['myFile'];
$max_size = 999999;//设置上传大小限制
//
//$allowExt = array('jpg', 'jpeg', 'png', 'gif', 'wbmp');//设置上传文件类型

function upload($fileName, $max_size = 99999, $uploadPath = 'uploads',
                $allowExt = array('jpg', 'jpeg', 'png', 'gif', 'wbmp'), $flag = true)
{
    //检查文件上传是否有误
    if ($fileName['error'] !== 0) {
        switch ($fileName['error']) {
            case 1:
                $mas = '上传文件超过了php配置中upload_max_filesize的值';
                break;
            case 2:
                $mas = '超出了表单max_file_size限制的大小';
                break;
            case 3:
                $mas = '文件部分被上传';
                break;
            case 4:
                $mas = '没有选择文件上传';
                break;
            case 6:
                $mas = '没有找到临时目录';
                break;
            case 7:
                $mas = '文件写入失败';

        }
        exit($mas);
    }
    //检查上传文件大小
    if ($fileName['size'] > $max_size) {
        exit('上传文件过大');
    }

    //设置检查是否是真是图片
    if ($flag) {
        if (!getimagesize($fileName['tmp_name'])) {
            exit('不是真实的图片');
        }
    }
    $ext = pathinfo($fileName['name'], PATHINFO_EXTENSION);//获取上传文件类型

    //检查上传文件类型
    if (!in_array($ext, $allowExt)) {
        exit('非法文件');
    }
    //检查文件是否是通过post的方式上传的
    if (!is_uploaded_file($fileName['tmp_name'])) {
        exit('文件不是通过HTTP POST 方式上传来的');
    }

    //创建目录
    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0777, true);
        chmod($uploadPath, 0777);
    }
    $nuiName = md5(uniqid(microtime(true), true)) . '.' . $ext;
    $destination = $uploadPath . '/' . $nuiName;
    if (!@move_uploaded_file($fileName['tmp_name'], $destination)) {
        exit('文件上传失败');
    }
    return array(
        $destination, '上传成功'
    );
}
