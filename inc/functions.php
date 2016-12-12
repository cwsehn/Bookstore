<?php 

/*
	returns an array of media categories and genres for the "genres" field
	in the suggest form...
*/

function get_genre_array($category = null) {
	$category = strtolower($category);
	include("connection.php");

	try {
		$results = $dbMY->query("
		SELECT category, genre 
		FROM Genre_Categories 
		JOIN Genres
		ON Genres.genre_id = Genre_Categories.genre_id
		ORDER BY category, genre");
	
	} catch (Exception $e) {
		echo "Unable to retrieve results";
		exit;
	}
	// PDO::FETCH_ASSOC...fetches associative array allowing for "category" and "genre to be keys...." 
	$genreList = array();

	while ($row = $results->fetch(PDO::FETCH_ASSOC)) {

		$genreList[$row["category"]][] = $row["genre"];

	}

	return $genreList;
}


function get_catalog_count($category = null, $search = null) {
	$category = strtolower($category);
	include("connection.php");

	try {
		$sql = "SELECT COUNT(media_id) FROM Media";
		if (!empty($search)) {
			$result = $dbMY->prepare(
				$sql
				. " WHERE title LIKE ?");
			$result->bindValue(1, '%'.$search.'%', PDO::PARAM_STR);
		}
		else if (!empty($category)) {
			$result = $dbMY->prepare(
				$sql
				. " WHERE LOWER(category) = ?"
			);
			$result->bindParam(1, $category, PDO::PARAM_STR);
		} else {
			$result = $dbMY->prepare($sql);
		}
		$result->execute();		
	} catch (Exception $e) {
		echo "bad query";
	}

	$count = $result->fetchColumn(0);
	return $count;
}

function full_catalog_array($limit = null, $offset = 0) {
	include("connection.php");

	try {
		$sql = "SELECT media_id, title, img, category 
			FROM Media
			ORDER BY
			REPLACE (
				REPLACE(
					REPLACE(title, 'The ', ''),
						'An ',''),
						'A ', '')";
		if (is_integer($limit)) {
			$results = $dbMY->prepare($sql . " LIMIT ? OFFSET ?");
			$results->bindParam(1, $limit, PDO::PARAM_INT);
			$results->bindParam(2, $offset, PDO::PARAM_INT);
		} else {
			$results = $dbMY->prepare($sql);
		}
		$results->execute();
		//echo "Retrieved Results";
	} catch (Exception $e) {
		echo "Unable to retrieve results";
		exit;
	}
	//PDO::FETCH_ASSOC
	$catalog = $results->fetchAll();
	return $catalog;
	}

function category_catalog_array($category, $limit = null, $offset = 0) {
	include("connection.php");
	$category = strtolower($category);
	try {
		$sql = "SELECT media_id, title, img, category 
			FROM Media
			WHERE LOWER(category) = ?
			ORDER BY
			REPLACE (
				REPLACE(
					REPLACE(title, 'The ', ''),
						'An ',''),
						'A ', '')";
	if (is_integer($limit)) {
		$results = $dbMY->prepare($sql . " LIMIT ? OFFSET ?");
		$results->bindParam(1, $category, PDO::PARAM_STR);
	  	$results->bindParam(2, $limit, PDO::PARAM_INT);
	  	$results->bindParam(3, $offset, PDO::PARAM_INT);
	  } else {
	  	$results = $dbMY->prepare($sql);
	  	$results->bindParam(1, $category, PDO::PARAM_STR);
	  }
	  $results->execute();
		//echo "Retrieved Results";
	} catch (Exception $e) {
		echo "Unable to retrieve results";
		exit;
	}
	//PDO::FETCH_ASSOC
	$catalog = $results->fetchAll();
	return $catalog;
	}

function search_catalog_array($search, $limit = null, $offset = 0) {
	include("connection.php");

	try {
		$sql = "SELECT media_id, title, img, category 
			FROM Media
			WHERE title LIKE ?
			ORDER BY
			REPLACE (
				REPLACE(
					REPLACE(title, 'The ', ''),
						'An ',''),
						'A ', '')";
	if (is_integer($limit)) {
		$results = $dbMY->prepare($sql . " LIMIT ? OFFSET ?");
		$results->bindValue(1, '%'.$search.'%', PDO::PARAM_STR);
	  	$results->bindParam(2, $limit, PDO::PARAM_INT);
	  	$results->bindParam(3, $offset, PDO::PARAM_INT);
	  } else {
	  	$results = $dbMY->prepare($sql);
	  	$results->bindParam(1, $category, PDO::PARAM_STR);
	  }
	  $results->execute();
		//echo "Retrieved Results";
	} catch (Exception $e) {
		echo "Unable to retrieve results";
		exit;
	}
	//PDO::FETCH_ASSOC
	$catalog = $results->fetchAll();
	return $catalog;
	}

function random_catalog_array() {
	include("connection.php");

	try {
		$results = $dbMY->query(
			"SELECT media_id, title, img, category 
			FROM Media
			ORDER BY RAND()
			LIMIT 4"
		);
		//echo "Retrieved Results";
	} catch (Exception $e) {
		echo "Unable to retrieve results";
		exit;
	}
	//PDO::FETCH_ASSOC
	$catalog = $results->fetchAll();
	return $catalog;
	}


function single_item_array($id) {
	include("connection.php");

	try {
		$results = $dbMY->prepare(
			"SELECT Media.media_id, title, year, img, format, category, genre, publisher, isbn
			FROM Media 
			JOIN Genres ON Media.genre_id = Genres.genre_id
			LEFT OUTER JOIN Books ON Media.media_id = Books.media_id
			WHERE Media.media_id = ?");

		$results->bindParam(1, $id, PDO::PARAM_INT);
		$results->execute();
		//echo "Retrieved Results";
	} catch (Exception $e) {
		echo "Unable to retrievey results";
		exit;
	}
	//PDO::FETCH_ASSOC
	$item = $results->fetch();
	if (empty($item)) return $item;

	try {
		$results = $dbMY->prepare(
			"SELECT fullname, role
			FROM Media_People
			JOIN People ON Media_People.people_id = People.people_id
			WHERE Media_People.media_id = ?");
		$results->bindParam(1, $id, PDO::PARAM_INT);
		$results->execute();
		//echo "Retrieved Results";
	} catch (Exception $e) {
		echo "Unable to retrievey results";
		exit;
	}
	while($row = $results->fetch(PDO::FETCH_ASSOC)) {
			$item[$row["role"]][] = $row["fullname"];
		}
		
	return $item;
}
//var_dump(single_item_array(1));




function get_item_html($item) {
	$output = "<li><a href='details.php?id="
		 . $item["media_id"] ."'><img src='"
		 . $item["img"] . "' alt='"
		 . $item["title"] . "' />"
		 . "<p>View Details</p>"
		 . "</a></li>";
	return $output;
}

function array_category($catalog, $category) {
	
	$output = array();

	foreach ($catalog as $id => $item) {
		if ($category == null OR strtolower($category) == strtolower($item["category"])) {
			$sort = $item["title"];
			$sort = ltrim($sort, "The ");
			$sort = ltrim($sort, "A ");
			$sort = ltrim($sort, "An ");
			$output[$id] = $sort;
		}

	}
	//var_dump($output);
	asort($output);
	//var_dump($output);
	return array_keys($output);
}