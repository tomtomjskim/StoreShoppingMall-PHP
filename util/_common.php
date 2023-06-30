<?php @include_once('../../common.php');


function getCurrentFileList($dir, $format = [])
{

    /**
     * dir 파일 리스트
     */
    $validFormats = count($format) > 0 ? $format  : ['json'];

    $handle = opendir($dir); // 디렉토리의 핸들을 얻어옴
    // 지정한 디렉토리에 있는 디렉토리와 파일들의 이름을 배열로 읽어들임
    $R = array(); // 결과 담을 변수 생성
    while ($filename = readdir($handle)) {
        if ($filename == '.' || $filename == '..') continue;
        $filepath = $dir . '/' . $filename;
        if (is_file($filepath)) { // 파일인 경우에만
            $getExt = pathinfo($filename, PATHINFO_EXTENSION); // 파일 확장자 구하기
            if (in_array($getExt, $validFormats)) {
                array_push($R, basename($filename, '.jpg')); // 파일이름만 선택하여 배열에 넣는다.
            }
        }
    }
    closedir($handle);
    sort($R); // 가나다순으로 정렬하기 위해
    return $R;
}
