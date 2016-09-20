<?php

class DateTimeView {


	public function show() {
		$timeString = Date("l") . ", the " . Date("j") . "th of " . Date("F") . " " . Date("Y") . ", The time is " . Date("G:i");

		return '<p>' . $timeString . '</p>';
	}
}