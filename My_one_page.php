<?php
require_once("modules/db.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<link rel="stylesheet" href="css/css_select_station.css">
<link rel="stylesheet" href="css/css_my_one_page.css">
<link rel="stylesheet" href="css/css_noamlfont.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="//code.jquery.com/jquery-3.2.1.min.js"></script>
<link rel="stylesheet" href="css/css_metrocket_header.css">
<link rel="stylesheet" href="css/css_metrocket_footer.css">

<style>
.click_box{
  width:100%;
}
</style>
</head>
<body>
<div id="wrapPage">
  <!-- 상단 메뉴 부분 -->
  <?php
    if(empty($_SESSION['ss_mb_id']) && empty($_SESSION['naver_mb_id']) && empty($_SESSION['kakao_mb_id']) ){
      echo "<script>alert('로그인을 해주세요');</script>";
      echo "<script>location.replace('./index.php');</script>";
    }else{
  ?>
  <!-- 최상단 로고 및 상단메뉴 -->
  <?php
      if(!(empty($_SESSION['ss_mb_id']) || empty($_SESSION['naver_mb_id']) || empty($_SESSION['kakao_mb_id']))){
          echo "123";
      }else{
        if(isset($_SESSION['ss_mb_id'])){
          $mb_id = $_SESSION['ss_mb_id'];
          $sql = " select * from member where mb_id = TRIM('$mb_id') ";
          $result = mysqli_query($conn, $sql);
          $mb = mysqli_fetch_assoc($result);
        }elseif(isset($_SESSION['naver_mb_id'])){
          $om_id = $_SESSION['naver_mb_id'];
          $om_id = substr($om_id, 5);
          $sql = " select * from oauth_member where om_id = TRIM($om_id) ";
          $result = mysqli_query($conn, $sql);
          $om = mysqli_fetch_assoc($result);
        }elseif(isset($_SESSION['kakao_mb_id'])){
          $oms_id = $_SESSION['kakao_mb_id'];
          $oms_id = substr($oms_id, 5);
          $sql = " select * from oauth_member where om_id = TRIM($oms_id) ";
          $result = mysqli_query($conn, $sql);
          $om = mysqli_fetch_assoc($result);
        }
      }
  ?>
  <!-- 최상단 로고 및 상단메뉴 -->
    <?php require_once('metrocket_header.php') ?>
    <?php require_once('select_station.php'); ?>

  <!-- 모달팝업 (hidden여부로 팝업) -->
  <div class="my_one_page_modal hidden">
    <div class="bg">          <!-- 백그라운드 잡는 부분  -->
      <div class="fix_page">  <!-- 화면가운데 fix로 잡는 부분  -->

        <div class="modalBox">            <!-- 콘텐츠 들어가는부분 -->

          <div class="closeBtn_box"><img src="img/cancle.png" class="" onclick="updateImage_close()" style="width:2.3rem;height:2.3rem;cursor:pointer"></div>
          <h3>프로필 이미지 등록</h3>
          <p>나를 표현하는 프로필 이미지를 등록하세요.</p>
          <div class="changedProfile_image" ><img id="changedProfile_image" class="w3-circle" src="<?= $mb['mb_image'] ?($mb['mb_image'] == 'img/normal_profile.png' ? $mb['mb_image'] : 'files/'.$mb['mb_image']) : $om['om_image_url'] ?>" alt="">          </div>
          <div class="updateImage_Button_Line">
            <button type="button" id="uploadImg_btn" class="w3-button w3-round w3-light-gray" name="button">사진올리기</button>
            <button type="button" id="deleteImg_btn" class="w3-button w3-round w3-light-gray" name="button">삭제</button>
          </div>
          <span>닉네임</span>
          <form action="update_ProfileImg.php" method="post" enctype="multipart/form-data">
            <input type="file" id="real-input" name="files" class="image_inputType_file"  accept="image/jpeg,image/png,image/gif" style="display:none;" >
            <div class="inputNicname_Line">
              <input name="nickname" type="text" value="<?=$mb['mb_name'] ? $mb['mb_name'] : null?>" placeholder="<?=$mb['mb_name'] ? $mb['mb_name'] : $om['om_nickname']?>">
              <input type="hidden" name="member_num" value="<?=$mb['mb_num'] ? $mb['mb_num'] : null?>">
              <img src="img/close_10x10.png" alt="">
            </div>

            <div class="updateProfile_Button_Line">
              <input class="w3-button w3-round-xxlarge w3-blue" type="submit" name="" value="저장">
              <input class="w3-button w3-round-xxlarge w3-light-gray" type="button" onclick="updateImage_close()" value="취소">
            </div>

          </form>

        </div>

      </div>
    </div>
  </div>

	<div class="w3-content w3-container w3-margin-top" >

    <!-- 유저정보  차후 php 작업 필요 -->
    <div class="profile_box">
      <div class="prfileImg_box">
        <img class="w3-circle" src="<?=($mb['mb_image']  != 'img/normal_profile.png'  ?'files/'.$mb['mb_image'] :
          ($mb['mb_image']  == 'img/normal_profile.png' ? $mb['mb_image']  : $om['om_image_url']));?>" >
        <img src="img/camera.png" style="position:absolute;left:70%;top:70%;" alt="" class="open_updateImage_btn" data-check_om="<?= isset($om['om_id']) ? $om['om_id'] : null ?> ">
      </div>
        <div class="user_name"><?=$mb['mb_name'] ? $mb['mb_name'] : $om['om_nickname']?></div>
    </div>

    <!-- 메인 버튼 -->

    <div class="mainBtn_box">
       <button type="button" class="w3-button w3-round-large tapMenu_btn" name="main_button" onclick = "changeIframeUrl('member_update.php')" >회원 정보</button>
       <button type="button" class="w3-button w3-round-large tapMenu_btn" name="main_button" onclick = "changeIframeUrl('sangpum.php')" >판매 상품</button>
       <button type="button" class="w3-button w3-round-large tapMenu_btn" name="main_button" onclick = "changeIframeUrl('gansim_sangpum.php')" >관심 상품</button>
       <button type="button" class="w3-button w3-round-large tapMenu_btn" name="main_button" onclick = "changeIframeUrl('buy_sangpum.php')">구매 상품</button>
    </div>

    <form name="nan" method="post" target="mbs">
      <input type="hidden" name="mba" value="<?=$mb_id?>">
    </form>

    <div class="click_box">
							<iframe style="float:left;" frameborder="0"  id="main_frame" src="member_update.php" width="100%"></iframe>
		</div>

	</div>
  <script>

    function changeIframeUrl(url){
        document.getElementById("main_frame").src = url;
  		}

    var mainBtn = document.getElementsByClassName('tapMenu_btn');
    for (var i = 0; i < 4; i++) {

      mainBtn.item(i).addEventListener('click',(event)=>{
        for (var j = 0; j < 4; j++) {
            mainBtn.item(j).style.background='#e6e6e6';
        }
        event.target.style.background='#fff';
     });
    }
  </script>
  <!-- 하단 메뉴 부분 -->
  <?php require_once 'metrocket_footer.php';?>
</div>
</body>
<script type="text/javascript">
  $(document).ready(function(){

    function selectStation_close() {
       document.querySelector(".modal_2").classList.add("hidden");
    }
    document.querySelector('.closeBtn_2').addEventListener("click", selectStation_close);

  });


  var iframe = document.getElementById('main_frame')

  window.addEventListener('DOMContentLoaded', function () {
  iframe.addEventListener('load', autoHeight);
  })

  function autoHeight() {
  var frame = iframe
    var sub = frame.contentDocument ? frame.contentDocument : frame.contentWindow.document
    iframe.height = sub.body.scrollHeight
  }

  // 유저 이미지 및 닉네임 변경모달창 관련 함수
  function updateImage_open() {
    alert(document.querySelector(".open_updateImage_btn").dataset.check_om);
    if (document.querySelector(".open_updateImage_btn").dataset.check_om == null) {
       document.querySelector(".my_one_page_modal").classList.remove("hidden");
    }
  }
  function updateImage_close() {
    document.querySelector(".my_one_page_modal").classList.add("hidden");
  }
  document.querySelector(".open_updateImage_btn").addEventListener("click", updateImage_open);

  const realInput = document.getElementById('real-input');
  const uploadImg_btn = document.getElementById('uploadImg_btn');
  const deleteImg_btn = document.getElementById('deleteImg_btn');
  const changedProfile_image = document.getElementById('changedProfile_image');

  //사진 올리기 버튼과 이미지에 파일업로드 이벤트 연결
  uploadImg_btn.addEventListener('click',()=>{realInput.click();});
  changedProfile_image.addEventListener('click',()=>{realInput.click();});

  //삭제버튼 누르면 파일 정보를 삭제
  deleteImg_btn.addEventListener('click',()=>{
    realInput.value="";
    // realInput.files[0].name ="img/normal_profile.png";
    // alert(realInput.files[0].name);
    changedProfile_image.src="img/normal_profile.png";
  });

  //사진 업로드 함수
  realInput.addEventListener('change',function(){

      if(this.files[0].type!='image/jpeg' && this.files[0].type!='image/png') {
        alert('jpg 및 png 이미지를 업로드해주세요');
        realInput.value="";
      }else{
        var url =""
        url = URL.createObjectURL(this.files[0]);
        changedProfile_image.src =  url ;
        alert(changedProfile_image.src);
      }

  })
</script>
</html>
<?php } ?>
