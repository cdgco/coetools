SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for table `tool_dir`
--

CREATE TABLE `tool_dir` (
  `id` bigint(20) NOT NULL,
  `name` varchar(128) NOT NULL,
  `description` varchar(2048) NOT NULL,
  `category` varchar(128) NOT NULL,
  `link` varchar(2048) NOT NULL,
  `staffOnly` varchar(87) NOT NULL,
  `display` int(1) NOT NULL,
  `tab` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `user_layouts`
--

CREATE TABLE `user_layouts` (
  `user` varchar(24) NOT NULL,
  `layout` varchar(2048) NOT NULL,
  `nightmode` int(1) NOT NULL,
  `hidden` varchar(2048) NOT NULL,
  `favorites` varchar(2048) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `user_recentbackup`
--

CREATE TABLE `user_recentbackup` (
  `user` varchar(64) NOT NULL,
  `recents` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `user_recents`
--

CREATE TABLE `user_recents` (
  `user` varchar(64) NOT NULL,
  `recents` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for table `tool_dir`
--
ALTER TABLE `tool_dir`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `id_2` (`id`);

--
-- Indexes for table `user_layouts`
--
ALTER TABLE `user_layouts`
  ADD PRIMARY KEY (`user`),
  ADD UNIQUE KEY `user` (`user`);

--
-- Indexes for table `user_recentbackup`
--
ALTER TABLE `user_recentbackup`
  ADD PRIMARY KEY (`user`);

--
-- Indexes for table `user_recents`
--
ALTER TABLE `user_recents`
  ADD PRIMARY KEY (`user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
