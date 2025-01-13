<?php
namespace QRcode;
/*
 * PHP QR Code encoder
 *
 * Root library file, prepares environment and includes dependencies
 *
 * Based on libqrencode C library distributed under LGPL 2.1
 * Copyright (C) 2006, 2007, 2008, 2009 Kentaro Fukuchi <fukuchi@megaui.net>
 *
 * PHP QR Code is distributed under LGPL 3
 * Copyright (C) 2010 Dominik Dzienia <deltalab at poczta dot fm>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */
	
	$QR_BASEDIR = dirname(__FILE__).DIRECTORY_SEPARATOR;
	
	// Required libs
	
	include $QR_BASEDIR."qrconst.php";
	include $QR_BASEDIR."qrconfig.php";
	include $QR_BASEDIR."qrtools.php";
	include $QR_BASEDIR."qrspec.php";
	include $QR_BASEDIR."qrimage.php";
	include $QR_BASEDIR."qrvect.php";
	include $QR_BASEDIR."qrinput.php";
	include $QR_BASEDIR."qrbitstream.php";
	include $QR_BASEDIR."qrsplit.php";
	include $QR_BASEDIR."qrrscode.php";
	include $QR_BASEDIR."qrmask.php";
	include $QR_BASEDIR."qrencode.php";

	class generate {
	// class QRcode {
		public $filesave = false;
		public $size     = 15;
		public $border   = 1;
		// color
		public $back     = 0xFFFFFF;
		public $fore     = 0x000000;
		public $logo     = false;
		public $divisor  = 3;
		public $subdivs  = 3;
	
		public function __construct($text,$quality){
			$level = [
				'low'    => QR_ECLEVEL_L,
				'medium' => QR_ECLEVEL_M,
				'high'   => QR_ECLEVEL_H,
				'best'   => QR_ECLEVEL_Q
			];

			$this->filepath = time().'.png';
			$this->text     = $text;
			$this->level    = $level[$quality];
		}
	
		public function save($filename){
			$this->filesave = $filename;
		}
	
		public function logo($filepng,$div=0,$sub=0){
			$this->logo 	= $filepng;
			$this->divisor  = $div? $div : $this->divisor;
			$this->subdivs  = $sub? $sub : $this->subdivs;
		}
	
		public function size($size,$border=0,$forecl=false,$backcl=false){
			$this->size  	= $size;
			$this->border   = $border? $border : $this->border;
			$this->fore 	= $forecl? $forecl : $this->fore;
			$this->back 	= $backcl? $backcl : $this->back;
		}
	
		public function load(){
			$file_location = $this->logo ? $this->filepath : $this->filesave;
			generateQRcode::png($this->text, $file_location, $this->level, $this->size , $this->border, true, $this->back, $this->fore);
			
			if($this->logo){
	
				$source = @imagecreatefrompng( $file_location );  # QR-Code
				$logo 	= @imagecreatefrompng( $this->logo );    # Overlay
	
				$sw = intval(imagesx($source));
				$sh = intval(imagesy($source));
				$lw = intval(imagesx($logo));
				$lh = intval(imagesy($logo));
	
				/* Create a new image onto which we will copy images & assign transparency */
				$target = imagecreatetruecolor( $sw, $sh );
				imagesavealpha( $target , true );
	
				/* image size calculations */
				$clw 	= $sw / $this->divisor;      #   calculated width
				$scale 	= $lw / $clw;        #   calculated ratio
				$clh 	= $lh / $scale;        #   calculated height
	
				/* allocate a transparent colour for the new image */
				$transparent = imagecolorallocatealpha( $target, 0, 0, 0, 127 );
				imagefill($target,0, 0, $transparent);
	
				/* copy the QR-Code to the new image */
				imagecopy($target, $source, 0, 0, 0, 0, $sw, $sh);
	
				/* Determine position of overlay image using divisor */
				$px=$sw/$this->subdivs;
				$py=$sh/$this->subdivs;
	
				/* add the overlay */
				imagecopyresampled( $target, $logo, $px, $py, 0, 0, $clw, $clh, $lw, $lh );
	
				/* output or save image */
				if($this->filesave){
					imagepng($target,$this->filesave);
				}else{
					header("Content-Type: image/png");
					imagepng($target);
				}
	
				/* clean up */
				imagedestroy($target);
				imagedestroy($source);
				imagedestroy($logo);
			}
	
			if($file_location){
				unlink($file_location);
			}
		}
	} 

