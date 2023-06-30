<?php include_once('../_common.php');
/**
 * 목적 : 카테고리 테이블 작성을 위한 json -> data 프로그램 
 * [참고]
 * 카테고리 크롤링 예시 파일 : ./json/category_example.json
 * 쿼리 실행문 주석처리 되어있음.
 * [기능]
 * 1.category insert
 * 2.content insert
 */

##################################1. category insert start ##################################
//
// $dir = './';

// $fileList = getCurrentFileList($dir);
// echo count($fileList) . '개 검색됨<br />';

// $tmpArray = [];
// foreach ($fileList as $f) {
//   $json_string = file_get_contents($f);
//   //echo $json_string . '<br />';
//   $array = json_decode($json_string, true);


//   //배열 처리
//   // 1. 카테고리 템플릿 테이블에 마스터 정보 밀어넣기.   
//   foreach ($array as $category) {
//     $tmpArray[$category['noticeCategoryId']] = $category['noticeCategoryName'];
//   }



//   //$newArray = array_unique($tmpArray, SORT_REGULAR);
// }
// ksort($tmpArray);
// print_r($tmpArray);

// foreach ($tmpArray as $key => $value) {
//   $query = " INSERT INTO template_notice_category SET
//     notice_category_id = $key,
//     notice_category_name = '$value'
//   ";

//   print_r('<br>'.$query);
//   //sql_query($query);
// }

##################################category insert end ##################################
##################################2. content insert start ##################################

$dir = './';

$fileList = getCurrentFileList($dir);
echo count($fileList) . '개 검색됨<br />';


$getCategoryKeyQuery =  "SELECT * FROM template_notice_category order by notice_category_id";

$rows = sql_array($getCategoryKeyQuery);
$categoryStrToKey = [];
foreach ($rows as $row) {
  $categoryStrToKey[$row['notice_category_name']] = $row['notice_category_id'];
}

$tmpArray = [];
foreach ($fileList as $f) {
  $json_string = file_get_contents($f);

  $array = json_decode($json_string, true);


  //배열 처리
  // 1. 카테고리 템플릿 테이블에 마스터 정보 밀어넣기.   
  foreach ($array as $category) {
    $list = $category['noticeCategoryTemplateDtoList']; // []
    $key = $categoryStrToKey[$category['noticeCategoryName']];

    if ($key == 0) {
      print_r($category);
      exit;
    }
    if (is_array($tmpArray[$key])) {
      continue;
    }

    foreach ($list as $index => $content) {
      $value = $content['noticeCategoryTemplateLocaleDto'];

      $tmpArray[$key][] = [
        'order' => $content['order'],
        'name' => $value['name'],
        'default_value' => $value['defaultValue']
      ];
    }
  }
}

foreach ($tmpArray as $index => $list) {

  foreach ($list as $row) {

    $addColumns = '';
    if ($row['default_value'] != '' || $row['default_value'] != '쿠팡고객센터 1577-7011') $addColumns = " ,notice_content_default_value = '{$row['default_value']}' ";
    $query = " INSERT INTO template_notice_content SET
      notice_category_id = $index,
      notice_content_name = '{$row['name']}',
      notice_content_order = '{$row['order']}'
      {$addColumns}
  ";
    //sql_query($query);
  }
}
