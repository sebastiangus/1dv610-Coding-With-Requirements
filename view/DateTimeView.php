<?php

class DateTimeView {


	public function show() {
        //$timeString represents date as: Tuesday, the DDth of Month Year, The time is GG:ii
		$timeString = Date("l") . ", the " . Date("jS") . " of " . Date("F") . " " . Date("Y") . ", The time is " . Date("G:i");

		return '<p>' . $timeString . '</p>';
	}
}