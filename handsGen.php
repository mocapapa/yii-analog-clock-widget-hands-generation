n<?php
  //
  // 短針、長針、秒針の画像のジェネレータ
  // $Id$
  //
$scale = 12;

// 小盤上の定義 ----------------------------------------------------------
$sBanWidth = 142 * $scale; // display 上で142mm
$sBanHeight = 142 * $scale;
// 小盤の生成
$sBan = ImageCreateTrueColor($sBanWidth, $sBanHeight);
//ImageAntiAlias($sBan, true);
ImageAlphaBlending($sBan, true);

// 針の基本パラメータ。設計書を参照。
$w1 = 2.5 * $scale; // 長針幅
$w2 = 4.5 * $scale; // 短針幅
$w3 = 1.2 * $scale; // 秒針幅
$l1 = 51 * $scale; // 秒針長
$l5 = 46 * $scale; // 長針長
$l2 = 6 * $scale; // 長針、短針オフセット
$l3 = 32 * $scale; // 短針長
$l4 = 14 * $scale; // 秒針オフセット
$kx = 1.2 * $scale; // 影オフセットx
$ky = -1.2 * $scale; // 影オフセットy
$kbx = 2.0 * $scale; // 秒針影オフセットx
$kby = -2.0 * $scale; // 秒針影オフセットy

$kage = array($kx, $ky); // 影ベクトル
$kageb = array($kbx, $kby); // 秒針影ベクトル

// 小円のパラメータ(8角形で代用)
$r1 = 3 * $scale;

// 長針のデータ
$chouValue = array(-$w1/2, $l5,
		   $w1/2, $l5,
		   $w1/2, -$l2,
		   -$w1/2, -$l2);
// 短針のデータ
$tanValue = array(-$w2/2, $l3,
		   $w2/2, $l3,
		   $w2/2, -$l2,
		   -$w2/2, -$l2);
// 秒針のデータ
$byouValue = array(-$w3/2, $l1,
		   $w3/2, $l1,
		   $w3/2, -$l4,
		   -$w3/2, -$l4);
// 円のデータ
$sqrt = 1.41421356;
$enValue = array(0, $r1,
		 $r1/$sqrt, $r1/$sqrt,
		 $r1, 0,
		 $r1/$sqrt, -$r1/$sqrt,
		 0, -$r1,
		 -$r1/$sqrt, -$r1/$sqrt,
		 -$r1, 0,
		 -$r1/$sqrt, $r1/$sqrt
		 );
// 色の定義 ---------------------------------------------------------------
$black = ImageColorAllocate($sBan, 0x00, 0x00, 0x00);
$gray1 = ImageColorAllocate($sBan, 0x11, 0x11, 0x11);
$gray2 = ImageColorAllocate($sBan, 0x22, 0x22, 0x22);
$gray3 = ImageColorAllocate($sBan, 0x33, 0x33, 0x33);
$gray4 = ImageColorAllocate($sBan, 0x44, 0x44, 0x44);
$gray5 = ImageColorAllocate($sBan, 0x55, 0x55, 0x55);
$gray6 = ImageColorAllocate($sBan, 0x66, 0x66, 0x66);
$gray7 = ImageColorAllocate($sBan, 0x77, 0x77, 0x77);
$gray8 = ImageColorAllocate($sBan, 0x88, 0x88, 0x88);
$gray9 = ImageColorAllocate($sBan, 0x99, 0x99, 0x99);
$graya = ImageColorAllocate($sBan, 0xaa, 0xaa, 0xaa);
$grayb = ImageColorAllocate($sBan, 0xbb, 0xbb, 0xbb);
$white = ImageColorAllocate($sBan, 0xff, 0xff, 0xff);

$sBack = ImageColorAllocate($sBan, 0xf5, 0xf5, 0xf5);
ImageColorTransparent($sBan, $sBack);
// 針の色
$hariColor = $gray6;
$kageColor = $grayb;

// 大盤上の定義 ----------------------------------------------------------
$width = 104;
$height = 104;

$dBanWidth = $width * 60; // 横に60枚並ぶ
$dBanHeight = $height * 3; // 縦に3枚並ぶ
// 大盤の生成
$dBan = ImageCreateTrueColor($dBanWidth, $dBanHeight);
//ImageAntiAlias($dBan, true);
ImageAlphaBlending($dBan, true);

// 大盤の初期化、背景を透明色で塗りつぶす
$dBack = ImageColorAllocate($dBan, 0xf5, 0xf5, 0xf5);
ImageColorTransparent($dBan, $dBack);
ImageFilledRectangle($dBan, 0,0, $dBanWidth, $dBanHeight, $dBack);


// 実行 --------------------------------------------------------------------
// 横に60枚
for ($i = 0; $i < 60; $i++) {
  // 進捗率表示
  printf("%02d\%\n".chr(0x1b).'M', $i/60*100);

  // 角度
  $d = $i * 6;

  // 短針の描画
  // 小盤の初期化、背景を透明色で塗りつぶす
  ImageFilledRectangle($sBan, 0,0, $sBanWidth, $sBanHeight, $sBack);
  // 影の描画(逆回転した影ベクトルを用いて針をシフトし、正回転させて影を描画)
  ImagefilledPolygon($sBan, trans4(rot4(kshift4($tanValue, rot1($kage, -$d)), $d)), 4, $kageColor);
  // ImageFilter($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // 短針とセンターの描画(正回転をかけて針を描画)
  ImagefilledPolygon($sBan, trans4(rot4($tanValue, $d)), 4, $hariColor);
  ImagefilledPolygon($sBan, trans8($enValue), 8, $gray7);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // 大盤上に縮小コピー
  ImageCopyResampled($dBan, $sBan, $i*$width, 0, 0, 0, $width, $height, $sBanWidth, $sBanHeight);
  // デバッグ用に保存
  // ImagePng($sBan, sprintf("sban-t%02d.png", $i));


  // 長針の描画
  // 小盤の初期化、背景を透明色で塗りつぶす
  ImageFilledRectangle($sBan, 0,0, $sBanWidth, $sBanHeight, $sBack);
  // 影の描画(逆回転した影ベクトルを用いて針をシフトし、正回転させて影を描画)
  ImagefilledPolygon($sBan, trans4(rot4(kshift4($chouValue, rot1($kage, -$d)), $d)), 4, $kageColor);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // 長針とセンターの描画(正回転をかけて針を描画)
  ImagefilledPolygon($sBan, trans4(rot4($chouValue, $d)), 4, $hariColor);
  ImagefilledPolygon($sBan, trans8($enValue), 8, $gray7);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // 大盤上に縮小コピー
  ImageCopyResampled($dBan, $sBan, $i*$width, $height, 0, 0, $width, $height, $sBanWidth, $sBanHeight);
  // デバッグ用に保存
  // ImagePng($sBan, sprintf("sban-c%02d.png", $i));


  // 秒針の描画
  // 小盤の初期化、背景を透明色で塗りつぶす
  ImageFilledRectangle($sBan, 0,0, $sBanWidth, $sBanHeight, $sBack);
  // 影の描画(逆回転した影ベクトルを用いて針をシフトし、正回転させて影を描画)
  ImagefilledPolygon($sBan, trans4(rot4(kshift4($byouValue, rot1($kageb, -$d)), $d)), 4, $kageColor);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // 秒針とセンターの描画(正回転をかけて針を描画)
  ImagefilledPolygon($sBan, trans4(rot4($byouValue, $d)), 4, $hariColor);
  ImagefilledPolygon($sBan, trans8($enValue), 8, $gray7);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // ImageFilger($sBan, IMG_FILTER_GAUSSIAN_BLUR, 1);
  // 大盤上に縮小コピー
  ImageCopyResampled($dBan, $sBan, $i*$width, $height*2, 0, 0, $width, $height, $sBanWidth, $sBanHeight);
  // デバッグ用に保存
  // ImagePng($sBan, sprintf("sban-b%02d.png", $i));
}

ImageAlphaBlending($dBan, false);
ImageSaveAlpha($dBan, true);

ImagePng($dBan, 'analog-clock-hands.png');

exit;

// 関数定義 -------------------------------------------------------------------------------------------------
// 原点中心の座標系からビットマップ座標系への変換
function transX($x)
{
  global $sBanWidth;
  return $sBanWidth/2 + $x;
}
// 原点中心の座標系からビットマップ座標系への変換
function transY($y)
{
  global $sBanHeight;
  return $sBanHeight/2 - $y;
}

// 4点からなるポリゴンについて、原点中心の座標系からビットマップ座標系への変換
function trans4($a)
{
  list($x0, $y0, $x1, $y1, $x2, $y2, $x3, $y3) = $a;
  return array(transX($x0), transY($y0), transX($x1), transY($y1), transX($x2), transY($y2), transX($x3), transY($y3));
}

// 8点からなるポリゴンについて、原点中心の座標系からビットマップ座標系への変換
function trans8($a)
{
  list($x0, $y0, $x1, $y1, $x2, $y2, $x3, $y3, $x4, $y4, $x5, $y5, $x6, $y6, $x7, $y7) = $a;
  return array(transX($x0), transY($y0), transX($x1), transY($y1), transX($x2), transY($y2), transX($x3), transY($y3),
	       transX($x4), transY($y4), transX($x5), transY($y5), transX($x6), transY($y6), transX($x7), transY($y7));
}

// 4点からなるポリゴンについて、原点中心の座標系において、影のオフセットだけシフト
function kshift4($a, $k)
{
  list($kx, $ky) = $k;
  list($x0, $y0, $x1, $y1, $x2, $y2, $x3, $y3) = $a;
  $x0 += $kx; $y0 += $ky;
  $x1 += $kx; $y1 += $ky;
  $x2 += $kx; $y2 += $ky;
  $x3 += $kx; $y3 += $ky;
  return array($x0, $y0, $x1, $y1, $x2, $y2, $x3, $y3);
}

// 原点中心座標系において、1点の回転
function rot1($a, $d)
{
  $t = $d / 180 * M_PI;
  list($x0, $y0) = $a;
  return array($x0 * cos($t) + $y0 * sin($t), -($x0 * sin($t) - $y0 * cos($t)));
}

// 原点中心座標系において、4点からなるポリゴンの回転
function rot4($a, $d)
{
  $t = $d / 180 * M_PI;
  list($x0, $y0, $x1, $y1, $x2, $y2, $x3, $y3) = $a;
  return array($x0 * cos($t) + $y0 * sin($t), -($x0 * sin($t) - $y0 * cos($t)),
	       $x1 * cos($t) + $y1 * sin($t), -($x1 * sin($t) - $y1 * cos($t)),
	       $x2 * cos($t) + $y2 * sin($t), -($x2 * sin($t) - $y2 * cos($t)),
	       $x3 * cos($t) + $y3 * sin($t), -($x3 * sin($t) - $y3 * cos($t)));
}
