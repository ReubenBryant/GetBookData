<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Get Book Data</title>
</head>
<body>
<H1>Get Book Information</H1>
<?php
if(isset($_POST['submit'])) 
{ 
    $bookdata = getbookdata($_POST['ISBN']);
    $BookISBN = $bookdata->getISBN();
    $BookTitle = strtoupper($bookdata->getTitle());
	$BookAuthor = strtoupper($bookdata->getAuthor());
	$BookDescription = $bookdata->getDescription();
	echo "<img src='http://books.google.com/books?vid=$BookISBN&printsec=frontcover&img=1&zoom=1' width='100' height='150' align='left' style='padding:5px;'/>";
    echo "<b> $BookTitle </b><br>";
    echo "$BookAuthor <br>";
    echo "<small>$BookDescription</small>";
    echo "<P>";
    echo "<BR>";
	echo "<textarea rows='10' cols='50'>";
	echo "<img src='http://books.google.com/books?vid=$BookISBN&printsec=frontcover&img=1&zoom=1' width='100' height='150' align='left' style='padding:5px;'/>";
    echo "<b> $BookTitle </b><br>";
    echo "$BookAuthor <br>";
    echo "<small>$BookDescription</small>";
    echo "<P>";
    echo "</textarea>"; 
}
function getbookdata($isbn) {
		
		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_URL, "https://www.googleapis.com/books/v1/volumes?q=".$isbn);
		
		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		
		$result = curl_exec($curl);
		curl_close($curl);
		
		$book_array = (array) json_decode($result, true);
		$book_title = $book_array["items"][0]["volumeInfo"]["title"];
		$book_author = $book_array["items"][0]["volumeInfo"]["authors"][0];
		$book_cover_url = $book_array["items"][0]["volumeInfo"]["imageLinks"]["thumbnail"];
		$book_isbn = $book_array["items"][0]["volumeInfo"]["industryIdentifiers"][1]["identifier"]; // ISBN13
		$book_description = $book_array["items"][0]["volumeInfo"]["description"];
		$book_preview_url = $book_array["items"][0]["accessInfo"]["webReaderLink"];

		$book = new BookConstr($book_title, $book_isbn, $book_author, $book_cover_url, $book_description, $book_preview_url);

		return $book;
		
	}
		
	// The book constructor, simple object programming here
	class BookConstr {
		
		private $book_title;
		private $book_isbn;
		private $author;
		private $cover_url;
		private $book_description;
		private $book_preview_url;

		public function __construct($book_title, $book_isbn, $author, $cover_url, $book_description, $book_preview_url) {
			$this->author = $author;
			$this->book_isbn = $book_isbn;
			$this->cover_url = $cover_url;
			$this->book_title = $book_title;
			$this->book_description = $book_description;
			$this->book_preview_url = $book_preview_url;
			
		}

		public function getTitle() {
			return $this->book_title;
		}

		public function getISBN() {
			return $this->book_isbn;
		}

		public function getAuthor() {
			return $this->author;
		}

		public function getCoverURL() {
			return $this->cover_url;
		}
		
		public function getDescription() {
			return $this->book_description;
		}
		
		public function getBookPreviewURL() {
			return $this->book_preview_url;	
		}
		
	}

?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
   Enter ISBN:<input type="text" name="ISBN"><br>
   <input type="submit" name="submit" value="Submit Form"><br>
</form>
</body>
</html>