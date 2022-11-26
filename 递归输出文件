<?php
//实例：递归显示phpMyAdmin目录中所有条目
/*
    分析过程：
    (1)函数递归：递归是通过函数调用自己来实现
    (2)操作顺序：打开目录——读取所有条目
    (3)递归条件：如果它是目录，则递归调用，即打开目录——读取目录条目
    (4)关闭目录
*/


// 定义读取文件的递归函数
function exploreAllFiles($docPath)
{
    // 打开文件夹
    $resource = opendir($docPath);

    // 输出一个ul列表对页面进行修饰
    echo "<ul>";

    // 循环输出所有文件列表
    while ($item = readdir($resource)) {
        // 如果item为.或..则跳过本次循环
        if ($item == "." || $item == "..") {
            continue;
        }
        // 正常输出项
        // echo "<li>" . $docPath . "/" . $item . "</li>";
        echo "<li>" . $item . "</li>";

        // 判断item是否为文件夹，如果是文件夹，则进行递归
        $secondLevelDoc = $docPath . "/" . $item;
        if (is_dir($secondLevelDoc)) {
            exploreAllFiles($secondLevelDoc);
        }
    }
    echo "</ul>";

    // 关闭资源读取
    closedir($docPath);
}

// 定义读取文件夹路径
$docPath = "./phpMyAdmin";

// 触发函数
exploreAllFiles($docPath);
