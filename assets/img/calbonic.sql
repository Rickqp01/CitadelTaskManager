CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_name` varchar(390) NOT NULL,
  `task_desc` text NOT NULL,
  `assignment` varchar(390) NOT NULL DEFAULT 'Unassigned',
  `task_status` varchar(390) NOT NULL DEFAULT 'Unassigned',
  `task_date` date NOT NULL DEFAULT current_timestamp(),
  `due_date` date NOT NULL DEFAULT current_timestamp(),
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  `checkbox` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=COMPRESSED;

CREATE TABLE IF NOT EXISTS `users_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lname` varchar(190) DEFAULT NULL,
  `fname` varchar(190) DEFAULT NULL,
  `username` varchar(190) DEFAULT NULL,
  `email` varchar(190) DEFAULT NULL,
  `password` varchar(190) DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  `checkbox` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPRESSED;
COMMIT;
