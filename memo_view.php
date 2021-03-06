<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '1');
require_once('modules/db.php'); // DB연결을 위한 같은 경로의 dbconn.php를 인클루드합니다.

$mb_id = isset($_SESSION['ss_mb_id']) ? $_SESSION['ss_mb_id'] : null;
$om_id =	isset($_SESSION['naver_mb_id']) ? $_SESSION['naver_mb_id'] : (isset($_SESSION['kakao_mb_id']) ? $_SESSION['kakao_mb_id'] : null);
$om_id = substr($om_id, 5);
$all = isset($mb_id) ? $mb_id : $om_id;
$kind = $_GET['kind'] ? $_GET['kind'] : 'recive';

if (!$mb_id && !$om_id) {
	echo "<script>alert('회원만 이용하실 수 있습니다.');window.close();</script>";
	exit;
}

$me_id = $_GET['me_id'];
$me_read_datetime	= date('Y-m-d H:i:s', time()); // 메모읽은일시

if ($kind == 'recive')
{
    $kind_str = "보낸";
    $kind_date = "받은";

    $sql = " UPDATE mb_om_memo
                SET me_recive_datetime = '$me_read_datetime'
                WHERE me_id = '$me_id'
                AND me_recive_mb_id = '$all'
                AND me_recive_datetime = '0000-00-00 00:00:00' ";
		// echo $sql;
    $result = mysqli_query($conn, $sql);
}
else if ($kind == 'send')
{
    $kind_str = "받는";
    $kind_date = "보낸";
}
else
{
	echo "<script>alert('변수 kind 값이 없습니다.');window.close();</script>";
	exit;
}

$sql = " SELECT * FROM mb_om_memo
            WHERE me_id = '$me_id'
            AND me_{$kind}_mb_id = '$all' ";
$result = mysqli_query($conn, $sql);
$memo = mysqli_fetch_assoc($result);

$sql = " SELECT a.*,b.mb_id, b.mb_email, c.om_id,c.om_nickname
            FROM mb_om_memo a
            LEFT JOIN member b ON (a.me_{$kind}_mb_id = b.mb_id)
            LEFT JOIN oauth_member c ON (a.me_{$kind}_mb_id = c.om_id)
            WHERE a.me_{$kind}_mb_id = '{$memo["me_send_mb_id"]}'";
// echo $sql;
$result = mysqli_query($conn, $sql);
$memos = mysqli_fetch_assoc($result);
// echo $memos['mb_id'];
// echo $memos['pr_id'];
?>

<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/css_memo_view.css">
	<link rel="stylesheet" href="css/css_noamlfont.css">
	<title>메트로켓</title>
</head>
<body id="memo">
	<!-- 쪽지보기 시작 { -->
	<div class="note">
		<div class="header">
			<div class="left_header">
				<img src="img/note.png">
		    <span class="title">쪽지 보내기</span>
			</div>
			<div class="right_header">
				<img src="img/cancle.png" class="" onclick="self.close()" style="width:2.3rem;height:2.3rem;cursor:pointer">
			</div>
		</div>
		<button class="btn1" onclick="location.href ='./memo.php?kind=recive'">받은쪽지</button>
		<button class="btn2" onclick="location.href ='./memo.php?kind=send'">보낸쪽지</button>
	</div>

	<div>


	<div class="content_box">

			<!-- 받는사람 나오고 버튼있는 박스 -->
			<div class="sendMemo_box">
				<!-- 받는사람 나오는 부분  -->
				<div class="recive_member">
					<div class="">
						<?php echo $kind_str ?>사람
						<?php echo $memo['me_send_mb_id'] ?>
					</div>
					<div class="">
						<?php echo $kind_date ?>시간
						<?php echo substr($memo['me_send_datetime'], 0, 16); ?>
					</div>
				</div>

				<!-- 답장하기버튼 -->
				<div class="conbtn">
						<?php if ($kind == 'recive') {  ?>
							<?php if($memo['me_send_mb_id'] === 'admin'){}
								else{?>
									<button id="submit_memo" type="button" onclick='location.href="./memo_form.php?me_recive_mb_id=<?php echo $memos['mb_id'] != null ? $memos['mb_id']  : 'sir'.$memos['om_id']  ?>&amp;me_id=<?php echo $memo['me_id'] ?>&amp;id=<?=$memos['pr_id']?>"'>답장하기</button>
								<?php } ?>
							<?php }  ?>
							<button id="returnList_btn" onclick="location.href ='./memo.php?kind=<?php echo $kind ?>'">목록보기</button>
				</div>
			</div>

			<div class="insertText_box">
				<textarea name="me_memo" rows="10" cols="50" readonly><?php echo $memo['me_text']; ?></textarea>
			</div>

	</div>


	</div>
	<!-- } 쪽지보기 끝 -->
</body>
<script type="text/javascript">
<?php
	$kind= $_REQUEST["kind"];
		if ($kind =="recive") {
?>
			document.querySelector('.btn1').classList.add("current");
			document.querySelector('.btn2').classList.remove("current");
<?php
		}else{
?>
			document.querySelector('.btn2').classList.add("current");
			document.querySelector('.btn1').classList.remove("current");
<?php
		}
		mysqli_close($conn); // 데이터베이스 접속 종료
?>

</script>
</html>
