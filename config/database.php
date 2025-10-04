<?php
class Database
{
	public $conn;
	function __construct()
	{
		$this->conn = mysqli_connect("localhost", "root", "", "tcc2024");
		if (!$this->conn) {
			die("Error in connection....");
		}
	}


	public function getConnection()
	{
		return $this->conn;
	}


	function add($sql, $link = "")
	{
		$results = mysqli_query($this->conn, $sql);

		if (!$results) {
			die("SQL Error: " . mysqli_error($this->conn));
		}

		if (!empty($link)) {
			echo "<script>alert('Successfully added...');</script>";

			if (isset($_SESSION['curr_id'])) {
				header("refresh:0; url=curriculum-create.php?id=" . intval($_SESSION['curr_id']));
			} else {
				header("refresh:0; url=$link");
			}
			exit;
		}
	}
	function save($sql)
	{
		$results = mysqli_query($this->conn, $sql);
		return $results;
	}
	
	function view($sql)
	{
		$results = mysqli_query($this->conn, $sql);
		if (!$results) {
			die("Error in SQL");
		} else {
			if (mysqli_num_rows($results) > 0) {
				while ($row = mysqli_fetch_array($results)) {
					$data[] = $row;
				}
			} else {
				return false;
			}
		}

		return $data;
	}
	function getdata($sql)
	{
		$results = mysqli_query($this->conn, $sql);

		if (!$results) {
			die("SQL Error: " . mysqli_error($this->conn) . " in query: " . $sql);
		}

		return mysqli_fetch_array($results, MYSQLI_ASSOC);
	}



	function login($sql)
	{
		$results = mysqli_query($this->conn, $sql);
		if (mysqli_num_rows($results) > 0) {
			$row = mysqli_fetch_array($results);
			return $row;
		} else {
			return false;
		}
	}
	function update(string $sql)
	{
		$results = mysqli_query($this->conn, $sql);
		if (!$results) {
			die("Error in SQL!");
		}
		return $results;
	}
	function delete($sql, $link)
	{
		$results = mysqli_query($this->conn, $sql);
		if (!$results) {
			die("Error in SQL!");
		} else {
?>
			<script>
				alert("Deleted data...");
			</script>
		<?php
			header("refresh: 0; url=" . $link);
		}
	}
	function count($sql)
	{
		$results = mysqli_query($this->conn, $sql);
		$count = mysqli_num_rows($results);
		return $count;
	}
	function submit($sql, $link)
	{
		$results = mysqli_query($this->conn, $sql);
		if (!$results && empty($link) == false) {
			die("Error in SQL!");
		} else if (empty($link)) {
			echo "";
		} else {
		?>
			<script>
				alert("Successful!");
			</script>
		<?php
			header("refresh:0; url=../students.php?id=" . $_SESSION['curr_id']);
		}
	}
	function enroll($sql, $link)
	{
		$results = mysqli_query($this->conn, $sql);
		if (!$results && empty($link) == false) {
			die("Error in SQL!");
		} else if (empty($link)) {
			echo "";
		} else {
		?>
			<script>
				alert("Successfully Enrolled Student!");
			</script>
<?php
			header("refresh:0; url=../enrolled_students.php?id=" . $_SESSION['curr_id']);
		}
	}
	function getProgramId($p_code)
	{
		$sql = "SELECT program_id FROM programs WHERE p_code='$p_code' LIMIT 1";
		$result = mysqli_query($this->conn, $sql);
		if ($row = mysqli_fetch_assoc($result)) {
			return $row['program_id'];
		}
		return null;
	}

	function getCurriculumId($cur_year)
	{
		$sql = "SELECT cur_id FROM curriculum WHERE cur_year='$cur_year' LIMIT 1";
		$result = mysqli_query($this->conn, $sql);
		if ($row = mysqli_fetch_assoc($result)) {
			return $row['cur_id'];
		}
		return null;
	}
	public function escape(string $value): string
	{
		return mysqli_real_escape_string($this->conn, $value);
	}

	public function view_assoc(string $sql)
	{
		$results = mysqli_query($this->conn, $sql);
		if (!$results) return die("SQL Error: " . mysqli_error($this->conn) . " in query: " . $sql);
		$data = [];
		if (mysqli_num_rows($results) > 0) {
			while ($row = mysqli_fetch_assoc($results)) {
				$data[] = $row;
			}
		}
		return !empty($data) ? $data : false;
	}
}

$database = new Database();
?>