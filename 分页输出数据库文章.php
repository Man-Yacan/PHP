<?php

/**
 * 实现功能：使用PHP链接数据库，输出文章分页列表
 * 演示页面：https://demo.manyacan.com/article-list.php
 */

// 引入链接数据库的文件：demo.php
require_once("./demo.php"); //数据库已链接

/**
 * 定义分页查询变量
 * 定义查询计数器，如果地址栏有参数则调用地址栏参数，如果无就默认打开首页
 * 调用地址：./article-list-advance.php/?page=2
 */
$countNum = isset($_GET['page']) ? $_GET['page'] : 1;
$showSize = 10; // 数据库一次查询个数
$startRow = ($countNum - 1) * $showSize; // 数据库查询起始行号

/**
 * 编写并执行查询sql语句,sql语句需要两条
 * 第一条用来查询数据库总行数，用来计算分页的页数
 * 第二条用来查询当前页面显示的文章
 */
$sqlQuery = "SELECT * FROM typecho_contents";
// $sqlQuery = "SELECT * FROM typecho_contents ORDER BY cid LIMIT 0, 10";

$findResult = mysqli_query($db_link, $sqlQuery); // 查询结果集赋值到变量中

// 获取文章总数（即结果集行数），进而获取分页总数
$numberOfRows = mysqli_num_rows($findResult);
$pagesNum = ceil($numberOfRows / $showSize);
// echo "共有{$numberOfRows}条数据，按{$showSize}条数据/页，一共分了{$pagesNum}页，当前页为：{$countNum}";

// 编写第二条sql语句，数据库查询当前页面需要的数据
$sqlQuery .= " ORDER BY cid DESC LIMIT {$startRow}, {$showSize}";

// 获取第二次查询的结果集，即用于当前页面显示的文章
$findResult = mysqli_query($db_link, $sqlQuery);

// 以关联数组形式获取结果集中所有行
$allRows = mysqli_fetch_all($findResult, MYSQLI_ASSOC);

// 关闭数据库链接
mysqli_close($db_link);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>文章列表页面</title>
    <style>
        /* 分页按钮样式 */
        .pagination a {
            margin: auto 5px;
            display: inline-block;
            width: 20px;
            height: 20px;
            color: skyblue;
            text-decoration: none;
        }
    </style>
    <script>
        // 自定义一个删除提示函数
        function confirmDel(cid) {
            if (window.confirm("确认删除cid为：" + cid + " 的文章?")) {
                window.location.href = "./delete-article.php?cid=" + cid; // 用户确认删除时，浏览器窗口重定向
            }
        }
    </script>
</head>

<body>
    <div style="margin: 10px auto;text-align: center;">
        <h2>文章信息管理</h2>
        <a href="./add-article.php">添加文章</a>
        目前共有<font color=red><?php echo $numberOfRows ?></font>篇文章
    </div>
    <table width="1000" border="1" align="center" rules="all" cellpadding="5">
        <!-- 分页按钮html显示 -->
        <tr class="pagination">

            <td colspan="8" align="center" height="50">
                <?php
                // 对分页按钮进行处理
                $startPageNum = $countNum - 5;
                $EndPageNum = $countNum + 5;

                // 两种特殊情况
                if ($countNum <= 5) {
                    $startPageNum = 1;
                    $EndPageNum = $showSize;
                }

                if ($countNum > $pagesNum - 5) {
                    $EndPageNum = $pagesNum;
                    $startPageNum = $pagesNum - $showSize;
                }

                // 当分页数小于十时全部输出
                if ($pagesNum < 10) {
                    $startPageNum = 1;
                    $EndPageNum = $pagesNum;
                }

                // 循环输出分页按钮
                for ($i = $startPageNum; $i <= $EndPageNum; $i++) {
                    if ($i == $countNum) {
                        echo "<a href='?page={$i}' style='border: 2px solid red'>{$i}</a>";
                    } else {
                        echo "<a href='?page={$i}' style='border: 2px solid #ccc'>{$i}</a>";
                    }
                }
                ?>
            </td>
        </tr>

        <tr bgcolor="#ccc">
            <th>cid</th>
            <th>title</th>
            <th>text</th>
            <th>type</th>
            <th>commentsNum</th>
            <th>viewsNum</th>
            <th>likes</th>
            <th>操作选项</th>
        </tr>

        <!-- 循环结果集得到的二维数组 -->
        <?php
        foreach ($allRows as $row) {
        ?>
            <tr align="center">
                <td><?php echo $row['cid']; ?></td>
                <td><a href="https://blog.manyacan.com/archives/<?php echo $row['cid']; ?>/" target="_blank"><?php echo $row['title']; ?></a></td>
                <td><?php echo htmlentities(substr($row['text'], 0, 100), ENT_QUOTES, "UTF-8"); ?></td>
                <td><?php echo $row['type']; ?></td>
                <td><?php echo $row['commentsNum']; ?></td>
                <td><?php echo $row['viewsNum']; ?></td>
                <td><?php echo $row['likes']; ?></td>
                <td><a href="#">修改</a> | <a href="#" onclick="confirmDel(<?php echo $row['cid']; ?>)">删除</a></td>
            </tr>
        <?php } ?>

        <!-- 分页按钮html显示 -->
        <tr class="pagination">
            <td colspan="8" align="center" height="50">
                <?php
                for ($i = $startPageNum; $i <= $EndPageNum; $i++) {
                    if ($i == $countNum) {
                        echo "<a href='?page={$i}' style='border: 2px solid red'>{$i}</a>";
                    } else {
                        echo "<a href='?page={$i}' style='border: 2px solid #ccc'>{$i}</a>";
                    }
                }
                ?>
            </td>
        </tr>
    </table>
</body>

</html>
