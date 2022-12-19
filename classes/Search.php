<?php
require_once $rootFolder . '/qmarkets/classes/Database.php';

class Search
{
	public static function user($input)
	{
		try {
			$result = Database::get_instance()->connection->prepare(self::user_query());
			$result->execute([
				'full_name_search1' => "$input%",
				'full_name_search2' => "% $input%",
				'mail_search1' => "$input%@%",
				'mail_search2' => "%.$input%@%",
				'username_search1' => "$input%",
				'username_search2' => "%\_$input%",
			]);


			return $result->fetchAll(PDO::FETCH_OBJ);
		} catch (PDOException $e) {
			return $e;
		}
	}

	private static function user_query()
	{
		return "
			SELECT profiles.id, value AS 'full name', mail, name FROM users
			JOIN profiles
			ON users.uid = profiles.uid
		
			WHERE
			(value LIKE :full_name_search1 OR value LIKE :full_name_search2)
			OR (mail LIKE :mail_search1 OR mail LIKE :mail_search2)
			OR (name LIKE :username_search1 OR name LIKE :username_search2)
		
			order by CASE
							WHEN value LIKE :full_name_search1 OR value LIKE :full_name_search2 THEN 0
							ELSE 1
					END,
					CASE
							WHEN mail LIKE :mail_search1 OR mail LIKE :mail_search2 THEN 0
							ELSE 1
					END,
					CASE
							WHEN name LIKE :username_search1 OR name LIKE :username_search2 THEN 0
							ELSE 1
					END
		
			LIMIT 5
			";
	}
}
