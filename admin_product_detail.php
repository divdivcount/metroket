<?php
  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  require_once("modules/db.php");
  require_once("modules/notification.php");
  $dao = new Product;
  $pr_id = Get('id', null);
  echo $pr_id;

  if(empty($_SESSION['ss_mb_id'])){
    echo "<script>alert('로그인을 해주세요');</script>";
    exit;
  }
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="css/bxslider-4-4.2.12/src/css/jquery.bxslider.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>
  </head>
  <body>
    <?php $imgdao = $dao->admin_product_list_detail(isset($mb) ? $mb["mb_num"] : 'null', isset($om) ? $om["om_id"] : 'null',$pr_id); ?>
    <!-- 슬라이드 이미지 -->
    <?php foreach ($imgdao as $row) : ?>

    <?php
      $pr_imgs = $row["pr_img"];
      $pr_img = explode(",", $pr_imgs);
      // var_dump($pr_img);
      // print_r( $row);
    ?>
    <div id="slideImg_box">
      <!-- 나중에 php로 동적으로 이미지나오게 작업예정 -->

      <div class="bxslider">
        <?php for($img = 0; $img < count($pr_img); $img++) { ?><div><img src="files\<?= $pr_img[$img] ?>" alt="" ></div><?php } ?>
      </div>

    </div>




        <!-- 신고수 -->
        <?=$row["rep_count"]?><br>
        <!--호선  -->
        <?= $row["profile_station"] ?><br>
        <!--제품 제목  -->
        <?= $row["pr_title"] ?><br>
        <!-- 카테고리 내용 -->
        <?= $row["ca_name"] ?><br>
        <!-- 상품 설명  -->
        <?= $row["pr_explanation"] ?><br>
        <form action="admin_member_detail.php" method="post">
          <input type="hidden" name="om_id" value="<?=isset($row["om_id"]) ? $row["om_id"] : null ?>">
          <input type="hidden" name="mb_id" value="<?=isset($row["mb_id"]) ? $row["mb_id"] : null ?>">
          <input type="submit" value="회원보기" />
        </form>

        <form method="post">
          <input type="hidden" name= "gap" value="<?php if($row["pr_block"] == 1){echo 2;}else{echo 1;} ?>">
          <input type="submit" name="product_block" id="product_block" value="가리기" />
        </form>
        <form method="post">
          <input type="submit" name="product_del" id="product_del" value="삭제하기" />
        </form>
    <?php endforeach ?>
    <?php
    //제품가리기
      function product_block(){
        $dao = new Product;
        $gap = Post("gap",0);
        $pr_id = Get('id', null);
        $dao->Product_block_update($pr_id, $gap);
      }
      if(array_key_exists('product_block',$_POST))
      {
        $gap = Post("gap",0);
        product_block();
          if($gap == 2){
            userGoto("상품을 숨김처리 하셨습니다", "admin_product_list.php");
          }else{
            userGoto("상품을 보이게 하셨습니다", "admin_product_list.php");
          }
      }
      //제품삭제
      function product_del(){
        $dao = new Product;
        $pr_id = Get('id', null);
        echo $pr_id;
        $dao->admin_product_del($pr_id);
      }
      if(array_key_exists('product_del',$_POST))
      {
        product_del();
        userGoto("상품을 삭제 하셨습니다", "admin_product_list.php");
      }
    ?>
    <script type="text/javascript">
      $(document).ready(function(){
        $('.bxslider').bxSlider( {
            mode: 'horizontal',// 가로 방향 수평 슬라이드
            speed: 500,        // 이동 속도를 설정
            pager: true,      // 현재 위치 페이징 표시 여부 설정
            moveSlides: 1,     // 슬라이드 이동시 개수
            auto: true,        // 자동 실행 여부
            autoHover: false,   // 마우스 호버시 정지 여부
            controls: true    // 이전 다음 버튼 노출 여부
        });
    });
    </script>
  </body>
</html>