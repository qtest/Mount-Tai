<?php
class SupportAction extends Action {
	public function index() {
		$this->question ();
	}
	
	public function question() {
		$this->display ();
	}
	
	public function information() {
		$this->display ();
	}
}