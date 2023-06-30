<?php include_once('../_common.php');

$f = './json/category.json';
$json_string = file_get_contents($f);
$array = json_decode($json_string, true);

print_r('벡업용. 닫습니다.'); exit;

// 각 카테고리와 하위 카테고리에 대한 INSERT 쿼리 작성 및 실행
foreach ($array as $parent) {
    $depth = 0;
    $parentCategory = $parent['category'];
    $parentQuery = "INSERT INTO category (category_name,depth) VALUES ('$parentCategory',$depth)";
    
    // print_r($parentQuery); exit;
    $id = sql_query($parentQuery,true);

    $parentId = $id;

    if (isset($parent['children']) && is_array($parent['children'])) {
        insertCategories($parent['children'], $parentId, $depth);
    }
}
//print_r('종료');

function insertCategories($categories, $parentId = null, $depth = 0) {
    foreach ($categories as $category) {
        $childDepth = $depth + 1;

        $categoryName = $category['category'];
        $categoryQuery = "INSERT INTO category (category_name, parent_idx, depth) VALUES ('$categoryName', '$parentId',$childDepth)";
        
        $id = sql_query($categoryQuery,true);

        $categoryId = $id;
        
        if (isset($category['children']) && is_array($category['children'])) {
            insertCategories($category['children'], $categoryId,$childDepth);
        }
    }
}
