<?

interface IDatabaseResult {
	public function affected();
	public function fetch_row();
	public function fetch_assoc();
}