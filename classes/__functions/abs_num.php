<?php
function abs_num($num){
	return abs(preg_replace('/[,\.]/',"",$num));
};